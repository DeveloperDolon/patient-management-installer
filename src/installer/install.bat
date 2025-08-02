@echo off
REM install.bat - Advanced Installation Script

setlocal enabledelayedexpansion

echo ========================================
echo Patient Management System Installer
echo Version 2.0 - Enterprise Edition
echo ========================================

REM Check for administrator privileges
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo ERROR: Administrator privileges required!
    echo Please right-click and select "Run as administrator"
    pause
    exit /b 1
)

REM Set installation variables
set "INSTALL_DIR=%ProgramFiles%\PatientManagementSystem"
set "DATA_DIR=%ProgramData%\PatientManagementSystem"
set "LOG_FILE=%INSTALL_DIR%\logs\install.log"

REM Create directories
echo Creating installation directories...
mkdir "%INSTALL_DIR%" 2>nul
mkdir "%DATA_DIR%" 2>nul
mkdir "%INSTALL_DIR%\logs" 2>nul

echo %date% %time% - Installation started > "%LOG_FILE%"

REM Check system requirements
echo Checking system requirements...
call :check_requirements
if %errorlevel% neq 0 (
    echo System requirements not met!
    pause
    exit /b 1
)

REM Stop any existing services
echo Stopping existing services...
taskkill /F /IM httpd.exe 2>nul
taskkill /F /IM mysqld.exe 2>nul
timeout /t 3 >nul

REM Copy application files
echo Copying application files...
echo %date% %time% - Copying files >> "%LOG_FILE%"

xcopy /E /I /Y /Q "xampp-portable" "%INSTALL_DIR%\xampp" >> "%LOG_FILE%" 2>&1
if %errorlevel% neq 0 goto :copy_error

xcopy /E /I /Y /Q "laravel-app" "%INSTALL_DIR%\app" >> "%LOG_FILE%" 2>&1
if %errorlevel% neq 0 goto :copy_error

xcopy /E /I /Y /Q "installer" "%INSTALL_DIR%\installer" >> "%LOG_FILE%" 2>&1
if %errorlevel% neq 0 goto :copy_error

xcopy /E /I /Y /Q "security" "%INSTALL_DIR%\security" >> "%LOG_FILE%" 2>&1
if %errorlevel% neq 0 goto :copy_error

REM Configure XAMPP
echo Configuring XAMPP...
call "%INSTALL_DIR%\installer\configure_xampp.bat"
if %errorlevel% neq 0 goto :config_error

REM Setup database
echo Setting up database...
call "%INSTALL_DIR%\installer\setup_database.bat"
if %errorlevel% neq 0 goto :db_error

REM Generate hardware-bound license
echo Generating license...
cd /d "%INSTALL_DIR%\app"
php -f security\generate_license.php
if %errorlevel% neq 0 goto :license_error

REM Configure Windows services (optional)
echo Configuring system integration...
call "%INSTALL_DIR%\installer\setup_services.bat"

REM Create shortcuts
echo Creating shortcuts...
call "%INSTALL_DIR%\installer\create_shortcuts.bat"

REM Set file permissions
echo Setting security permissions...
icacls "%INSTALL_DIR%" /grant:r Users:(RX) /T /Q
icacls "%DATA_DIR%" /grant:r Users:(M) /T /Q

REM Register uninstaller
echo Registering uninstaller...
call "%INSTALL_DIR%\installer\register_uninstaller.bat"

REM Final verification
echo Verifying installation...
if not exist "%INSTALL_DIR%\app\public\index.php" goto :verify_error
if not exist "%INSTALL_DIR%\xampp\apache\bin\httpd.exe" goto :verify_error
if not exist "%INSTALL_DIR%\security\license.dat" goto :verify_error

echo %date% %time% - Installation completed successfully >> "%LOG_FILE%"

echo.
echo ========================================
echo Installation completed successfully!
echo ========================================
echo.
echo The Patient Management System has been installed to:
echo %INSTALL_DIR%
echo.
echo You can start the application using the desktop shortcut
echo or from the Start menu.
echo.

REM Prompt to start application
set /p "start_now=Would you like to start the application now? (Y/N): "
if /i "!start_now!"=="Y" (
    echo Starting Patient Management System...
    call "%INSTALL_DIR%\installer\start_app.bat"
)

echo.
echo Installation log saved to: %LOG_FILE%
pause
exit /b 0

REM Error handling
:check_requirements
echo Checking Windows version...
for /f "tokens=4-5 delims=. " %%i in ('ver') do set VERSION=%%i.%%j
if "%version%" == "10.0" goto :req_ok
if "%version%" == "6.3" goto :req_ok
if "%version%" == "6.1" goto :req_ok
echo Unsupported Windows version: %version%
exit /b 1

:req_ok
echo Checking available disk space...
for /f "tokens=3" %%a in ('dir /-c "%SystemDrive%\" ^| find "bytes free"') do set FREE_SPACE=%%a
if %FREE_SPACE% LSS 2000000000 (
    echo Insufficient disk space. At least 2GB required.
    exit /b 1
)
exit /b 0

:copy_error
echo ERROR: Failed to copy application files!
echo Check the installation log for details: %LOG_FILE%
pause
exit /b 1

:config_error
echo ERROR: Failed to configure XAMPP!
echo Check the installation log for details: %LOG_FILE%
pause
exit /b 1

:db_error
echo ERROR: Database setup failed!
echo Check the installation log for details: %LOG_FILE%
pause
exit /b 1

:license_error
echo ERROR: License generation failed!
echo This may indicate hardware compatibility issues.
echo Check the installation log for details: %LOG_FILE%
pause
exit /b 1

:verify_error
echo ERROR: Installation verification failed!
echo Some files may be missing or corrupted.
echo Check the installation log for details: %LOG_FILE%
pause
exit /b 1
