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
