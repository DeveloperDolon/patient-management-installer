@echo off
REM ========================================
REM Patient Management System - Startup Script
REM Updated for Wine/Linux compatibility
REM ========================================

setlocal enabledelayedexpansion

:: Configure paths
set "APP_DIR=%~dp0.."
set "XAMPP_DIR=%APP_DIR%\xampp"
set "LARAVEL_DIR=%APP_DIR%\app"
set "LOG_FILE=%APP_DIR%\logs\runtime.log"
set "PID_FILE=%APP_DIR%\logs\services.pid"

:: Create logs directory if missing
if not exist "%APP_DIR%\logs" mkdir "%APP_DIR%\logs"

:: Initialize log
echo [%date% %time%] Starting Patient Management System >> "%LOG_FILE%"
echo ========================================
echo  Patient Management System
echo  Starting Services...
echo ========================================

:: ----------------------------------------
:: License Validation
:: ----------------------------------------
echo Checking license...
echo DEBUG: Running license check from: %LARAVEL_DIR%\security\license_check.php >> "%LOG_FILE%"

cd /d "%LARAVEL_DIR%"
php -f security\license_check.php >> "%LOG_FILE%" 2>&1
if %errorlevel% neq 0 (
    echo.
    echo ========================================
    echo  LICENSE VALIDATION FAILED
    echo ========================================
    echo  Error details:
    type "%LOG_FILE%" | findstr /i "error fail"
    echo.
    echo  Support: support@yourcompany.com
    pause
    exit /b 1
)
echo License valid. Proceeding... >> "%LOG_FILE%"

:: ----------------------------------------
:: Port Conflict Check (Wine-friendly)
:: ----------------------------------------
echo Checking ports...
set "APP_PORT=8080"

:: Use netstat if available, else fallback to Wine
netstat -an 2>nul | find "LISTENING" | find ":8080" >nul
if %errorlevel% equ 0 (
    set "APP_PORT=8081"
    echo WARNING: Port 8080 in use. Switching to 8081 >> "%LOG_FILE%"
)

:: ----------------------------------------
:: Start Apache (Wine-compatible)
:: ----------------------------------------
echo Starting Apache...
start /B "" "%XAMPP_DIR%\apache\bin\httpd.exe"
timeout /t 3 >nul

tasklist /fi "imagename eq httpd.exe" | find "httpd.exe" >nul
if %errorlevel% neq 0 (
    echo ERROR: Apache failed to start! Check %XAMPP_DIR%\apache\logs\error.log >> "%LOG_FILE%"
    goto :cleanup
)
echo Apache started on port !APP_PORT! >> "%LOG_FILE%"

:: ----------------------------------------
:: Start MySQL (Skip if Wine)
:: ----------------------------------------
echo Starting MySQL...
if "%WINE%"=="1" (
    echo Skipping MySQL on Wine (use native MySQL) >> "%LOG_FILE%"
) else (
    start /B "" "%XAMPP_DIR%\mysql\bin\mysqld.exe" --defaults-file="%XAMPP_DIR%\mysql\bin\my.ini"
    timeout /t 5 >nul
    tasklist /fi "imagename eq mysqld.exe" | find "mysqld.exe" >nul
    if %errorlevel% neq 0 (
        echo ERROR: MySQL failed to start! Check %XAMPP_DIR%\mysql\data\*.err >> "%LOG_FILE%"
        goto :cleanup
    )
)

:: ----------------------------------------
:: Laravel Optimization
:: ----------------------------------------
echo Optimizing Laravel...
cd /d "%LARAVEL_DIR%"
php artisan config:clear >> "%LOG_FILE%" 2>&1
php artisan cache:clear >> "%LOG_FILE%" 2>&1

:: ----------------------------------------
:: Launch Browser (Wine workaround)
:: ----------------------------------------
echo Opening browser...
if "%WINE%"=="1" (
    wine start http://localhost:!APP_PORT! >> "%LOG_FILE%" 2>&1
) else (
    start http://localhost:!APP_PORT!
)

:: ----------------------------------------
:: Success Message
:: ----------------------------------------
echo.
echo ========================================
echo  SYSTEM RUNNING
echo ========================================
echo  URL: http://localhost:!APP_PORT!
echo  Logs: %LOG_FILE%
echo.
echo  Press CTRL+C to stop
echo ========================================

:: Keep the window open
pause >nul

:cleanup
echo Cleaning up... >> "%LOG_FILE%"
taskkill /f /im httpd.exe 2>nul
taskkill /f /im mysqld.exe 2>nul
exit /b 0
