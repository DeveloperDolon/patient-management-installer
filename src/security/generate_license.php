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
