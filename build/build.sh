#!/bin/bash
# build.sh - Enhanced Build Script with Flatpak Wine

set -e

# Source helper functions
source "$(dirname "$0")/wine_helpers.sh"

# Configuration
PROJECT_NAME="PatientManagementSystem"
PROJECT_VERSION="2.0.0"
BUILD_DIR="$(pwd)/build"
SRC_DIR="$(pwd)/src"
DIST_DIR="$(pwd)/dist"
WINE_PREFIX="${WINE_PREFIX:-$HOME/.var/app/org.winehq.Wine/data/wine}"
ENCRYPTION_KEY=$(openssl rand -hex 32)
WINE_INSTALLER_DIR="$WINE_PREFIX/drive_c/temp/output"  # Wine's output directory
INSTALLER_NAME="Patient Management System_Setup_v${PROJECT_VERSION}.exe"
LINUX_INSTALLER_PATH="$DIST_DIR/$INSTALLER_NAME"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

check_dependency() {
    if ! command -v "$1" >/dev/null; then
        log_error "Required command not found: $1"
        exit 1
    fi
}

# Cleanup function
cleanup() {
    log_info "Cleaning up temporary files..."
    rm -rf /tmp/xampp-*.zip
    rm -rf /tmp/laravel_build_*
    if [ -d "$WINE_PREFIX/drive_c/temp" ]; then
        rm -rf "$WINE_PREFIX/drive_c/temp"
    fi
}

echo "========================================"
echo "Patient Management System Builder v2.0"
echo "========================================"

log_info "Build started at $(date)"

# Verify dependencies
log_info "Checking dependencies..."
check_dependency composer
check_dependency npm
check_dependency wget
check_dependency unzip
check_dependency flatpak
check_dependency openssl

# Verify Flatpak Wine installation
if ! flatpak list | grep -q "org.winehq.Wine"; then
    log_error "Flatpak Wine is not installed!"
    log_info "Please run: flatpak install flathub org.winehq.Wine"
    exit 1
fi

# Verify Inno Setup installation
INNO_SETUP_PATH="$(get_wine_program_files)/Inno Setup 6/ISCC.exe"
if [ ! -f "$WINE_PREFIX/drive_c/Program Files (x86)/Inno Setup 6/ISCC.exe" ]; then
    log_error "Inno Setup not found in Wine prefix!"
    log_info "Please run the setup script: ./flatpak_wine_setup.sh"
    exit 1
fi

# Clean previous builds
log_info "Cleaning previous builds..."
rm -rf "$DIST_DIR"
mkdir -p "$DIST_DIR"
mkdir -p "$BUILD_DIR/temp"

# Step 1: Prepare Laravel application
log_info "Step 1: Preparing Laravel application..."
if [ ! -d "$SRC_DIR/laravel-app" ]; then
    log_error "Laravel application not found at $SRC_DIR/laravel-app"
    exit 1
fi

cd "$SRC_DIR/laravel-app"

# Install dependencies
log_info "Installing Composer dependencies..."
if ! composer install; then
    log_error "Composer install failed!"
    exit 1
fi

# Install and build frontend assets
if [ -f "package.json" ]; then
    log_info "Installing NPM dependencies..."
    npm ci --silent
    
    log_info "Building frontend assets..."
    npm run build --silent
fi

# Clear and cache Laravel configurations
log_info "Optimizing Laravel configuration..."
php artisan config:clear --quiet
php artisan cache:clear --quiet
php artisan view:clear --quiet
php artisan route:clear --quiet

php artisan config:cache --quiet
php artisan route:cache --quiet
php artisan view:cache --quiet

# Step 2: Download and prepare XAMPP
log_info "Step 2: Preparing XAMPP portable..."
if [ ! -d "$SRC_DIR/xampp-portable" ]; then
    log_info "Downloading XAMPP portable..."
    cd "$BUILD_DIR/temp"
    
    wget -q --show-progress -O xampp-portable.zip \
        "https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.4/xampp-portable-windows-x64-8.2.4-0-VS16.zip/download"
    
    if [ $? -ne 0 ]; then
        log_error "Failed to download XAMPP!"
        exit 1
    fi
    
    log_info "Extracting XAMPP..."
    mkdir -p "$SRC_DIR/xampp-portable"
    unzip -q xampp-portable.zip -d "$SRC_DIR/xampp-portable/"
    
    # Remove unnecessary components
    log_info "Removing unnecessary XAMPP components..."
    cd "$SRC_DIR/xampp-portable"
    rm -rf FileZillaFTP/ MercuryMail/ Tomcat/ sendmail/ webalizer/ anonymous/
    rm -rf install/ setup_xampp.bat xampp-control.exe xampp_start.exe xampp_stop.exe
    
    # Fix permissions on perl directory
    find "$SRC_DIR/xampp-portable/perl" -type d -exec chmod 755 {} \;
    find "$SRC_DIR/xampp-portable/perl" -type f -exec chmod 644 {} \;
fi

# Step 3: Configure XAMPP
log_info "Step 3: Configuring XAMPP..."
XAMPP_CONFIG="$SRC_DIR/xampp-portable"

# Update Apache configuration
sed -i 's/Listen 80/Listen 8080/g' "$XAMPP_CONFIG/apache/conf/httpd.conf"
sed -i 's/ServerName localhost:80/ServerName localhost:8080/g' "$XAMPP_CONFIG/apache/conf/httpd.conf"

# Add virtual host for Laravel
cat >> "$XAMPP_CONFIG/apache/conf/httpd.conf" << 'EOF'

# Patient Management System Virtual Host
<VirtualHost *:8080>
    DocumentRoot "../app/public"
    ServerName localhost
    <Directory "../app/public">
        AllowOverride All
        Require all granted
        DirectoryIndex index.php
    </Directory>
</VirtualHost>
EOF

# Configure MySQL for portable use
sed -i 's/port=3306/port=3306/g' "$XAMPP_CONFIG/mysql/bin/my.ini"

# Step 5: Generate security components
log_info "Step 5: Generating security components..."

# Create license generator
mkdir -p "$SRC_DIR/security"
cat > "$SRC_DIR/security/generate_license.php" << 'EOF'
<?php
require_once 'hwid_validator.php';

try {
    $validator = new AdvancedHWIDValidator();
    $license = $validator->createLicense(365, ['full_access' => true]);
    
    echo "License generated successfully!\n";
    echo "Hardware ID: " . $license['hwid'] . "\n";
    echo "Expiry: " . date('Y-m-d H:i:s', $license['expiry']) . "\n";
    
    // Generate machine report
    file_put_contents('machine_report.json', $validator->generateMachineReport());
    
    exit(0);
} catch (Exception $e) {
    echo "Error generating license: " . $e->getMessage() . "\n";
    exit(1);
}
EOF

# Step 6: Create installer configuration files
log_info "Step 6: Creating installer configuration..."

# Create firewall configuration script
mkdir -p "$SRC_DIR/config"
cat > "$SRC_DIR/config/firewall_rules.bat" << 'EOF'
@echo off
if "%1"=="add" (
    netsh advfirewall firewall add rule name="Patient Management System - Apache" dir=in action=allow protocol=TCP localport=8080
    netsh advfirewall firewall add rule name="Patient Management System - MySQL" dir=in action=allow protocol=TCP localport=3306
    echo Firewall rules added successfully.
) else if "%1"=="remove" (
    netsh advfirewall firewall delete rule name="Patient Management System - Apache"
    netsh advfirewall firewall delete rule name="Patient Management System - MySQL"
    echo Firewall rules removed successfully.
) else (
    echo Usage: firewall_rules.bat [add^|remove]
)
EOF

# Create post-installation script
mkdir -p "$SRC_DIR/installer"
cat > "$SRC_DIR/installer/post_install.bat" << 'EOF'
@echo off
set "APP_DIR=%~dp0.."
set "LOG_FILE=%APP_DIR%\logs\post_install.log"

echo %date% %time% - Post-installation started > "%LOG_FILE%"

REM Create necessary directories
mkdir "%APP_DIR%\logs" 2>nul
mkdir "%APP_DIR%\temp" 2>nul
mkdir "%APP_DIR%\xampp\logs" 2>nul
mkdir "%APP_DIR%\xampp\tmp" 2>nul

REM Set proper permissions
icacls "%APP_DIR%\app\storage" /grant Everyone:(OI)(CI)F /T /Q >> "%LOG_FILE%" 2>&1
icacls "%APP_DIR%\app\bootstrap\cache" /grant Everyone:(OI)(CI)F /T /Q >> "%LOG_FILE%" 2>&1

REM Initialize database
cd /d "%APP_DIR%\app"
php artisan key:generate --force >> "%LOG_FILE%" 2>&1

echo %date% %time% - Post-installation completed >> "%LOG_FILE%"
EOF

# Create stop application script
cat > "$SRC_DIR/installer/stop_app.bat" << 'EOF'
@echo off
echo Stopping Patient Management System...

REM Stop services gracefully
taskkill /IM httpd.exe /F 2>nul
taskkill /IM mysqld.exe /F 2>nul

REM Wait for processes to terminate
timeout /t 3 >nul

REM Verify services are stopped
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo Warning: Apache may still be running
) else (
    echo Apache stopped successfully
)

tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo Warning: MySQL may still be running
) else (
    echo MySQL stopped successfully
)

echo Patient Management System stopped.
pause
EOF

# Step 7: Prepare Wine environment and build installer
log_info "Step 7: Building Windows installer with Flatpak Wine..."

prepare_wine_environment() {
    log_info "Setting up Wine environment..."
    
    # Clean existing temp directory
    if [ -d "$WINE_PREFIX/drive_c/temp" ]; then
        log_info "Cleaning existing temp directory..."
        rm -rf "$WINE_PREFIX/drive_c/temp"
    fi
    
    # Create directory structure
    mkdir -p "$WINE_PREFIX/drive_c/temp/src"
    mkdir -p "$WINE_PREFIX/drive_c/temp/assets"
    mkdir -p "$WINE_PREFIX/drive_c/temp/output"
    
    # Copy all necessary files
    log_info "Copying application files..."
    rsync -a --exclude='*.pm' --exclude='perl/vendor/lib/' "$SRC_DIR/" "$WINE_PREFIX/drive_c/temp/src/"
    
    # Ensure assets directory exists
    if [ -d "$SRC_DIR/assets" ]; then
        cp -r "$SRC_DIR/assets" "$WINE_PREFIX/drive_c/temp/"
    else
        mkdir -p "$WINE_PREFIX/drive_c/temp/assets"
        # Create placeholder icon if missing
        if [ ! -f "$WINE_PREFIX/drive_c/temp/assets/icon.ico" ]; then
            log_warning "Creating placeholder icon.ico"
            convert -size 256x256 xc:white "$WINE_PREFIX/drive_c/temp/assets/icon.ico" || \
            touch "$WINE_PREFIX/drive_c/temp/assets/icon.ico"
        fi
    fi
    
    # Copy other required files
    cp "$BUILD_DIR/inno_setup_script.iss" "$WINE_PREFIX/drive_c/temp/"
    cp "$SRC_DIR/license.txt" "$WINE_PREFIX/drive_c/temp/" 2>/dev/null || \
       touch "$WINE_PREFIX/drive_c/temp/license.txt"
}

prepare_wine_environment

# Verify critical files were copied
if [ ! -f "$WINE_PREFIX/drive_c/temp/assets/icon.ico" ]; then
    log_error "Icon file not found in Wine environment"
    exit 1
fi

if [ ! -f "$WINE_PREFIX/drive_c/temp/inno_setup_script.iss" ]; then
    log_error "ISS file not found in Wine environment"
    exit 1
fi

# Update paths in the ISS file
log_info "Configuring installer script..."
sed -i "s|SetupIconFile=.*|SetupIconFile=C:\\\\temp\\\\assets\\\\icon.ico|g" "$WINE_PREFIX/drive_c/temp/inno_setup_script.iss"
sed -i "s|LicenseFile=.*|LicenseFile=C:\\\\temp\\\\license.txt|g" "$WINE_PREFIX/drive_c/temp/inno_setup_script.iss"

# Compile the installer
log_info "Running Inno Setup compiler..."
if ! run_wine "C:\\Program Files (x86)\\Inno Setup 6\\ISCC.exe" "C:\\temp\\inno_setup_script.iss"; then
    log_error "Inno Setup compilation failed!"
    exit 1
fi

# Wait for installer to be fully written
sleep 5

# Locate the installer
find_installer() {
    # Check the expected path first
    if [ -f "$WINE_INSTALLER_DIR/$INSTALLER_NAME" ]; then
        echo "$WINE_INSTALLER_DIR/$INSTALLER_NAME"
        return 0
    fi
    
    # Search alternative locations
    local found_path=$(find "$WINE_PREFIX/drive_c" -name "$INSTALLER_NAME" | head -1)
    if [ -n "$found_path" ]; then
        echo "$found_path"
        return 0
    fi
    
    return 1
}

INSTALLER_SOURCE=$(find_installer)
if [ -z "$INSTALLER_SOURCE" ]; then
    log_error "Installer not found in Wine environment"
    log_info "Searching in: $WINE_INSTALLER_DIR"
    ls -la "$WINE_INSTALLER_DIR" || true
    exit 1
fi

# Copy to Linux dist directory
log_info "Copying installer from Wine to Linux directory..."
mkdir -p "$DIST_DIR"
if ! cp "$INSTALLER_SOURCE" "$LINUX_INSTALLER_PATH"; then
    log_error "Failed to copy installer to: $LINUX_INSTALLER_PATH"
    exit 1
fi

log_success "Installer successfully created at: $LINUX_INSTALLER_PATH"

# Generate checksums and metadata
log_info "Step 8: Generating checksums and metadata..."
cd "$DIST_DIR" || { log_error "Failed to enter dist directory"; exit 1; }

# Generate checksums (with fallbacks)
if command -v sha256sum >/dev/null; then
    sha256sum "$INSTALLER_NAME" > "${INSTALLER_NAME}.sha256" || log_warning "Failed to generate SHA256 checksum"
elif command -v shasum >/dev/null; then
    shasum -a 256 "$INSTALLER_NAME" > "${INSTALLER_NAME}.sha256" || log_warning "Failed to generate SHA256 checksum"
fi

if command -v md5sum >/dev/null; then
    md5sum "$INSTALLER_NAME" > "${INSTALLER_NAME}.md5" || log_warning "Failed to generate MD5 checksum"
elif command -v md5 >/dev/null; then
    md5 -r "$INSTALLER_NAME" > "${INSTALLER_NAME}.md5" || log_warning "Failed to generate MD5 checksum"
fi

# Create build info file
INSTALLER_SIZE=$(stat -c%s "$INSTALLER_NAME" 2>/dev/null || stat -f%z "$INSTALLER_NAME" 2>/dev/null || echo 0)

cat > "${PROJECT_NAME}_BuildInfo.json" << EOF
{
  "project_name": "${PROJECT_NAME}",
  "version": "${PROJECT_VERSION}",
  "build_date": "$(date -u +"%Y-%m-%dT%H:%M:%SZ")",
  "build_host": "$(hostname)",
  "build_user": "$(whoami)",
  "installer_file": "${INSTALLER_NAME}",
  "file_size": ${INSTALLER_SIZE},
  "sha256": "$(cat "${INSTALLER_NAME}.sha256" 2>/dev/null | cut -d' ' -f1 || echo '')",
  "md5": "$(cat "${INSTALLER_NAME}.md5" 2>/dev/null | cut -d' ' -f1 || echo '')"
}
EOF

# Step 9: Create deployment package
log_info "Step 9: Creating deployment package..."

# Create deployment instructions
cat > "${PROJECT_NAME}_Deployment_Instructions.md" << 'EOF'
# Patient Management System - Deployment Instructions

## System Requirements
- Windows 7 SP1 or later (64-bit)
- 2 GB available disk space
- 4 GB RAM (minimum)
- Administrator privileges for installation

## Installation Steps

1. **Download** the installer executable
2. **Right-click** the installer and select "Run as administrator"
3. **Follow** the installation wizard
4. **Allow** firewall exceptions when prompted
5. **Wait** for the installation to complete
6. **Launch** the application from the desktop shortcut

## First Time Setup

1. The application will automatically generate a hardware-bound license
2. If license generation fails, contact support with the machine report
3. Default login credentials will be provided during first run
4. Configure your patient data import settings

## Troubleshooting

### Common Issues

**Port 8080 already in use:**
- The installer will automatically try port 8081
- Check for other web servers running

**MySQL won't start:**
- Ensure no other MySQL services are running
- Check Windows Services for conflicts

**License validation fails:**
- Ensure system date/time is correct
- Contact support if hardware has changed significantly

### Log Files
- Installation: `C:\Program Files\PatientManagementSystem\logs\install.log`
- Runtime: `C:\Program Files\PatientManagementSystem\logs\runtime.log`
- Security: `C:\Program Files\PatientManagementSystem\logs\security.log`

## Support
For technical support, please contact:
- Email: support@yourcompany.com
- Include machine report from: `C:\Program Files\PatientManagementSystem\security\machine_report.json`
EOF

# Step 10: Create license generation tools
log_info "Step 10: Creating license management tools..."

# Create license generator for support team
cat > "$DIST_DIR/LicenseGenerator.php" << 'EOF'
#!/usr/bin/env php
<?php
/**
 * License Generator for Patient Management System
 * Usage: php LicenseGenerator.php <machine_report.json> [days]
 */

if ($argc < 2) {
    echo "Usage: php LicenseGenerator.php <machine_report.json> [expiry_days]\n";
    echo "Example: php LicenseGenerator.php machine_report.json 365\n";
    exit(1);
}

$reportFile = $argv[1];
$expiryDays = isset($argv[2]) ? intval($argv[2]) : 365;

if (!file_exists($reportFile)) {
    echo "Error: Machine report file not found: $reportFile\n";
    exit(1);
}

$machineReport = json_decode(file_get_contents($reportFile), true);
if (!$machineReport || !isset($machineReport['hwid'])) {
    echo "Error: Invalid machine report format\n";
    exit(1);
}

// Generate license
$licenseData = [
    'hwid' => $machineReport['hwid'],
    'expiry' => time() + ($expiryDays * 24 * 60 * 60),
    'features' => ['full_access' => true],
    'generated' => date('Y-m-d H:i:s'),
    'generated_by' => 'LicenseGenerator',
    'machine_info' => $machineReport['hardware'] ?? []
];

// In production, add proper RSA signature here
$licenseData['signature'] = base64_encode(hash_hmac('sha256', json_encode($licenseData), 'your-secret-key', true));

$licenseFile = 'license_' . substr($machineReport['hwid'], 0, 8) . '.dat';
file_put_contents($licenseFile, json_encode($licenseData, JSON_PRETTY_PRINT));

echo "License generated successfully!\n";
echo "File: $licenseFile\n";
echo "HWID: " . $machineReport['hwid'] . "\n";
echo "Expires: " . date('Y-m-d H:i:s', $licenseData['expiry']) . "\n";
echo "\nInstructions:\n";
echo "1. Send this license file to the customer\n";
echo "2. Customer should replace the existing license.dat file\n";
echo "3. Restart the Patient Management System\n";
EOF

chmod +x "$DIST_DIR/LicenseGenerator.php"

# Step 11: Final verification and packaging
log_info "Step 11: Final verification..."

# Verify installer integrity
if ! command -v unzip &> /dev/null; then
    log_warning "unzip not available, skipping installer verification"
else
    # Test if the installer is a valid ZIP (Inno Setup creates self-extracting archives)
    if unzip -t "$DIST_DIR/$INSTALLER_NAME" >/dev/null 2>&1; then
        log_success "Installer integrity verified"
    else
        log_warning "Could not verify installer integrity (this may be normal for Inno Setup executables)"
    fi
fi

# Create final distribution archive
log_info "Creating distribution archive..."
DIST_ARCHIVE="${PROJECT_NAME}_v${PROJECT_VERSION}_Distribution.tar.gz"

tar -czf "$DIST_ARCHIVE" \
    "$INSTALLER_NAME" \
    "${INSTALLER_NAME}.sha256" \
    "${INSTALLER_NAME}.md5" \
    "${PROJECT_NAME}_BuildInfo.json" \
    "${PROJECT_NAME}_Deployment_Instructions.md" \
    "LicenseGenerator.php"

log_success "Distribution archive created: $DIST_ARCHIVE"

# Display build summary
echo ""
echo "========================================"
echo "BUILD COMPLETED SUCCESSFULLY!"  
echo "========================================"
echo ""
echo "📦 Installer: $INSTALLER_NAME"
echo "📋 Size: $(du -h "$DIST_DIR/$INSTALLER_NAME" | cut -f1)"
echo "🔐 SHA256: $(cat "$DIST_DIR/${INSTALLER_NAME}.sha256" | cut -d' ' -f1)"
echo "📁 Distribution: $DIST_ARCHIVE"
echo "🔑 Encryption Key: $ENCRYPTION_KEY"
echo ""
echo "🚀 Ready for deployment!"
echo ""

# Optional: Test installer in Wine (if requested)
if [ "$1" = "--test" ]; then
    log_info "Testing installer in Wine..."
    if run_wine "$DIST_DIR/$INSTALLER_NAME" "/SILENT"; then
        log_success "Installer test completed"
    else
        log_warning "Installer test failed (this may be expected in headless environment)"
    fi
fi

# Manual cleanup at the very end
cleanup

log_success "Build process completed at $(date)"
