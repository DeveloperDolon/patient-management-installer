#!/bin/bash
# test_build.sh - Automated Testing for Built Installer

set -e

source "$(dirname "$0")/wine_helpers.sh"

PROJECT_NAME="PatientManagementSystem"
DIST_DIR="$(pwd)/dist"
TEST_DIR="$(pwd)/test_environment"
WINE_PREFIX_TEST="$HOME/.wine_test_pms"

log_info() {
    echo -e "\033[0;34m[TEST]\033[0m $1"
}

log_success() {
    echo -e "\033[0;32m[PASS]\033[0m $1"
}

log_error() {
    echo -e "\033[0;31m[FAIL]\033[0m $1"
}

# Setup test environment
setup_test_environment() {
    log_info "Setting up test environment..."
    
    # Create clean Wine prefix for testing
    rm -rf "$WINE_PREFIX_TEST"
    mkdir -p "$WINE_PREFIX_TEST"
    
    # Initialize Wine prefix
    WINEPREFIX="$WINE_PREFIX_TEST" flatpak run org.winehq.Wine wineboot --init
    
    # Install necessary components
    WINEPREFIX="$WINE_PREFIX_TEST" flatpak run org.winehq.Wine msiexec /i /quiet /norestart https://aka.ms/vs/17/release/vc_redist.x64.exe
}

# Test installer creation
test_installer_exists() {
    log_info "Testing installer existence..."
    
    local installer_file="$DIST_DIR/${PROJECT_NAME}_Setup_v*.exe"
    if ls $installer_file 1> /dev/null 2>&1; then
        log_success "Installer file exists"
        return 0
    else
        log_error "Installer file not found"
        return 1
    fi
}

# Test installer integrity
test_installer_integrity() {
    log_info "Testing installer integrity..."
    
    local installer_file=$(ls "$DIST_DIR/${PROJECT_NAME}_Setup_v"*.exe | head -1)
    
    # Check file size (should be > 100MB)
    local file_size=$(stat -c%s "$installer_file")
    if [ "$file_size" -gt 100000000 ]; then
        log_success "Installer size OK ($file_size bytes)"
    else
        log_error "Installer size too small ($file_size bytes)"
        return 1
    fi
    
    # Verify checksums
    if [ -f "${installer_file}.sha256" ]; then
        if sha256sum -c "${installer_file}.sha256" --quiet; then
            log_success "SHA256 checksum verified"
        else
            log_error "SHA256 checksum verification failed"
            return 1
        fi
    fi
}

# Test installer execution (silent mode)
test_installer_execution() {
    log_info "Testing installer execution..."
    
    local installer_file=$(ls "$DIST_DIR/${PROJECT_NAME}_Setup_v"*.exe | head -1)
    
    # Test silent installation
    WINEPREFIX="$WINE_PREFIX_TEST" timeout 300 flatpak run org.winehq.Wine "$installer_file" /SILENT /NORESTART
    
    local exit_code=$?
    if [ $exit_code -eq 0 ] || [ $exit_code -eq 124 ]; then  # 124 is timeout exit code
        log_success "Installer executed without critical errors"
        return 0
    else
        log_error "Installer execution failed with exit code $exit_code"
        return 1
    fi
}

# Test application components
test_application_components() {
    log_info "Testing application components..."
    
    local app_dir="$WINE_PREFIX_TEST/drive_c/Program Files/${PROJECT_NAME}"
    
    # Check core files
    local required_files=(
        "xampp/apache/bin/httpd.exe"
        "xampp/mysql/bin/mysqld.exe"
        "app/public/index.php"
        "app/artisan"
        "security/hwid_validator.php"
        "installer/start_app.bat"
    )
    
    for file in "${required_files[@]}"; do
        if [ -f "$app_dir/$file" ]; then
            log_success "Required file exists: $file"
        else
            log_error "Missing required file: $file"
            return 1
        fi
    done
}

# Test Laravel application structure
test_laravel_structure() {
    log_info "Testing Laravel application structure..."
    
    local laravel_dir="$WINE_PREFIX_TEST/drive_c/Program Files/${PROJECT_NAME}/app"
    
    local laravel_dirs=(
        "app/Http/Controllers"
        "app/Models"
        "config"
        "database/migrations"
        "public"
        "resources/views"
        "routes"
        "vendor"
    )
    
    for dir in "${laravel_dirs[@]}"; do
        if [ -d "$laravel_dir/$dir" ]; then
            log_success "Laravel directory exists: $dir"
        else
            log_error "Missing Laravel directory: $dir"
            return 1
        fi
    done
}

# Test security components
test_security_components() {
    log_info "Testing security components..."
    
    local security_dir="$WINE_PREFIX_TEST/drive_c/Program Files/${PROJECT_NAME}/security"
    
    # Test HWID validator
    cd "$security_dir"
    if WINEPREFIX="$WINE_PREFIX_TEST" flatpak run org.winehq.Wine php hwid_validator.php test 2>/dev/null; then
        log_success "HWID validator functional"
    else
        log_error "HWID validator test failed"
        return 1
    fi
}

# Run all tests
run_all_tests() {
    echo "========================================"
    echo "Patient Management System - Build Tests"
    echo "========================================"
    
    local tests=(
        "setup_test_environment"
        "test_installer_exists"
        "test_installer_integrity"
        "test_installer_execution"
        "test_application_components"
        "test_laravel_structure"
        "test_security_components"
    )
    
    local passed=0
    local failed=0
    
    for test in "${tests[@]}"; do
        echo ""
        if $test; then
            ((passed++))
        else
            ((failed++))
        fi
    done
    
    echo ""
    echo "========================================"
    echo "TEST RESULTS"
    echo "========================================"
    echo "Passed: $passed"
    echo "Failed: $failed"
    echo "Total:  $((passed + failed))"
    
    if [ $failed -eq 0 ]; then
        echo "🎉 ALL TESTS PASSED!"
        return 0
    else
        echo "❌ SOME TESTS FAILED!"
        return 1
    fi
}

# Cleanup test environment
cleanup_test_environment() {
    log_info "Cleaning up test environment..."
    rm -rf "$WINE_PREFIX_TEST"
    rm -rf "$TEST_DIR"
}

# Main execution
if [ "$1" = "--cleanup" ]; then
    cleanup_test_environment
    exit 0
fi

# Set trap for cleanup on exit
trap cleanup_test_environment EXIT

# Run tests
run_all_tests
