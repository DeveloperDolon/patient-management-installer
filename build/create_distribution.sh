#!/bin/bash
# create_distribution.sh - Create Final Distribution Package

set -e

PROJECT_NAME="PatientManagementSystem"
VERSION="2.0.0"
DIST_DIR="$(pwd)/dist"
PACKAGE_DIR="$DIST_DIR/distribution_package"

log_info() {
    echo -e "\033[0;34m[INFO]\033[0m $1"
}

log_success() {
    echo -e "\033[0;32m[SUCCESS]\033[0m $1"
}

# Create distribution package
create_distribution_package() {
    log_info "Creating distribution package..."
    
    # Create package directory structure
    mkdir -p "$PACKAGE_DIR"/{installer,documentation,tools,support}
    
    # Copy installer
    cp "$DIST_DIR"/${PROJECT_NAME}_Setup_v*.exe "$PACKAGE_DIR/installer/"
    cp "$DIST_DIR"/${PROJECT_NAME}_Setup_v*.exe.sha256 "$PACKAGE_DIR/installer/"
    cp "$DIST_DIR"/${PROJECT_NAME}_Setup_v*.exe.md5 "$PACKAGE_DIR/installer/"
    
    # Copy documentation
    cp "$DIST_DIR"/${PROJECT_NAME}_Deployment_Instructions.md "$PACKAGE_DIR/documentation/"
    cp "$DIST_DIR"/${PROJECT_NAME}_BuildInfo.json "$PACKAGE_DIR/documentation/"
    
    # Copy tools
    cp "$DIST_DIR/LicenseGenerator.php" "$PACKAGE_DIR/tools/"
    
    # Create README
    cat > "$PACKAGE_DIR/README.md" << EOF
# ${PROJECT_NAME} v${VERSION} - Distribution Package

This package contains everything needed to deploy the Patient Management System.

## Contents

### installer/
- **${PROJECT_NAME}_Setup_v${VERSION}.exe** - Main installer executable
- **${PROJECT_NAME}_Setup_v${VERSION}.exe.sha256** - SHA256 checksum
- **${PROJECT_NAME}_Setup_v${VERSION}.exe.md5** - MD5 checksum

### documentation/
- **${PROJECT_NAME}_Deployment_Instructions.md** - Complete deployment guide
- **${PROJECT_NAME}_BuildInfo.json** - Build information and metadata

### tools/
- **LicenseGenerator.php** - License generation tool for support team

### support/
- Support files and troubleshooting guides

## Quick Start

1. Download the installer from the \`installer/\` directory
2. Run as administrator on the target Windows machine
3. Follow the installation wizard
4. Refer to deployment instructions for detailed setup

## Support

For technical assistance, please refer to the documentation directory
or contact our support team with the machine report file.

---
Built on: $(date)
Build Host: $(hostname)
EOF

    # Create support files
    cat > "$PACKAGE_DIR/support/system_requirements.md" << 'EOF'
# System Requirements

## Minimum Requirements
- **Operating System:** Windows 7 SP1 (64-bit) or later
- **Processor:** Intel Core 2 Duo 2.4 GHz or equivalent
- **Memory:** 4 GB RAM
- **Storage:** 2 GB available disk space
- **Network:** Internet connection for initial activation (optional)

## Recommended Requirements
- **Operating System:** Windows 10 (64-bit) or later
- **Processor:** Intel Core i5 3.0 GHz or equivalent
- **Memory:** 8 GB RAM
- **Storage:** 4 GB available disk space (SSD preferred)
- **Network:** Broadband internet connection

## Software Dependencies
- Microsoft Visual C++ 2019 Redistributable (x64) - Included in installer
- .NET Framework 4.8 - Included in installer

## Network Requirements
- **Ports:** 8080 (HTTP), 3306 (MySQL) - Automatically configured
- **Firewall:** Windows Firewall exceptions created automatically
- **Antivirus:** Application folder should be whitelisted

## Browser Compatibility
- Google Chrome (recommended)
- Mozilla Firefox
- Microsoft Edge
- Internet Explorer 11+
EOF

    cat > "$PACKAGE_DIR/support/troubleshooting.md" << 'EOF'
# Troubleshooting Guide

## Installation Issues

### Error: "Installation requires administrator privileges"
**Solution:** Right-click the installer and select "Run as administrator"

### Error: "Insufficient disk space"
**Solution:** Free up at least 2 GB of disk space on the system drive

### Error: "Port 8080 is already in use"
**Solution:** 
1. Close any other web servers or applications using port 8080
2. The installer will automatically try port 8081 as alternative
3. Check Windows Services for conflicting services

## Runtime Issues

### Application won't start
**Possible causes:**
1. **Antivirus blocking:** Add application folder to antivirus whitelist
2. **Firewall blocking:** Allow through Windows Firewall (automatic during install)
3. **Missing dependencies:** Reinstall Visual C++ Redistributable

### License validation fails
**Solutions:**
1. Ensure system date and time are correct
2. Check that hardware hasn't changed significantly
3. Contact support with machine report file

### Database connection errors
**Solutions:**
1. Restart the application
2. Check if MySQL service is running in Task Manager
3. Verify no other MySQL instances are running
4. Check MySQL error logs in `xampp\mysql\data\`

### Performance issues
**Solutions:**
1. Close unnecessary applications
2. Increase available RAM
3. Move installation to SSD if possible
4. Check for Windows updates

## Log File Locations
- **Installation:** `C:\Program Files\PatientManagementSystem\logs\install.log`
- **Runtime:** `C:\Program Files\PatientManagementSystem\logs\runtime.log`
- **Security:** `C:\Program Files\PatientManagementSystem\logs\security.log`
- **Apache:** `C:\Program Files\PatientManagementSystem\xampp\apache\logs\error.log`
- **MySQL:** `C:\Program Files\PatientManagementSystem\xampp\mysql\data\*.err`

## Getting Help
1. Check log files for specific error messages
2. Collect machine report: `C:\Program Files\PatientManagementSystem\security\machine_report.json`
3. Contact support with:
   - Error description
   - Log files
   - Machine report
   - System information (Windows version, hardware specs)
EOF

    # Create final archive
    cd "$DIST_DIR"
    tar -czf "${PROJECT_NAME}_v${VERSION}_Complete_Distribution.tar.gz" distribution_package/
    
    log_success "Distribution package created successfully!"
    echo "📦 Package location: $PACKAGE_DIR"
    echo "📁 Archive: ${PROJECT_NAME}_v${VERSION}_Complete_Distribution.tar.gz"
}

# Execute
create_distribution_package
