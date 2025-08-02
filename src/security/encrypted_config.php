<?php
// security/encrypted_config.php

class EncryptedConfig {
    private static $key = 'your-secret-key';
    private static $iv = '1234567890123456';
    
    public static function loadEnvironment() {
        $encryptedFile = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . '.env.encrypted';
        
        if (!file_exists($encryptedFile)) {
            throw new Exception('Encrypted environment file not found');
        }
        
        $encrypted = file_get_contents($encryptedFile);
        $decrypted = openssl_decrypt(base64_decode($encrypted), 'AES-256-CBC', self::$key, 0, self::$iv);
        
        if ($decrypted === false) {
            throw new Exception('Failed to decrypt environment file');
        }
        
        // Parse and set environment variables
        $lines = explode("\n", $decrypted);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && !empty(trim($line))) {
                list($key, $value) = explode('=', $line, 2);
                putenv(trim($key) . '=' . trim($value));
            }
        }
    }
}

// Load encrypted environment on bootstrap
EncryptedConfig::loadEnvironment();
?>