@echo off
REM setup_database.bat

set XAMPP_DIR=%~dp0..\xampp
set LARAVEL_DIR=%~dp0..\app

echo Setting up database...

REM Start MySQL temporarily for setup
start /B "MySQL Setup" "%XAMPP_DIR%\mysql\bin\mysqld.exe" --defaults-file="%XAMPP_DIR%\mysql\bin\my.ini"
timeout /t 10 >nul

REM Create database
"%XAMPP_DIR%\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS patient_management;"

REM Import database schema
cd /d "%LARAVEL_DIR%"
php artisan migrate --force
php artisan db:seed --force

REM Stop MySQL
taskkill /F /IM mysqld.exe 2>nul

echo Database setup completed.