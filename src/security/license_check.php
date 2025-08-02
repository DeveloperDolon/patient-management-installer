<?php
// security/license_check.php

require_once __DIR__ . '/hwid_validator.php';

$validator = new AdvancedHWIDValidator();

if (!$validator->validateLicense()) {
    echo "License validation failed!\n";
    echo "Please contact support for assistance.\n";
    exit(1);
}

echo "License validated successfully.\n";
exit(0);
?>
