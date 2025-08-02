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
