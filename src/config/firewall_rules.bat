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
