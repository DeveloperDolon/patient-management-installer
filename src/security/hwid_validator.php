<?php
// src/security/hwid_validator.php

class AdvancedHWIDValidator {
    private $licenseFile;
    private $keyFile;
    private $hardwareComponents;
    
    public function __construct($licenseFile = 'license.dat', $keyFile = 'machine.key') {
        $this->licenseFile = $licenseFile;
        $this->keyFile = $keyFile;
        $this->hardwareComponents = $this->detectHardwareComponents();
    }
    
    private function detectHardwareComponents() {
        $components = [];
        
        // CPU Information
        $cpu = $this->getWMICValue('cpu', 'ProcessorId');
        if ($cpu) $components['cpu'] = $cpu;
        
        // Motherboard Serial
        $motherboard = $this->getWMICValue('baseboard', 'SerialNumber');
        if ($motherboard) $components['motherboard'] = $motherboard;
        
        // BIOS Information
        $bios = $this->getWMICValue('bios', 'SerialNumber');
        if ($bios) $components['bios'] = $bios;
        
        // Primary Hard Drive
        $hdd = $this->getWMICValue('diskdrive where index=0', 'SerialNumber');
        if ($hdd) $components['hdd'] = $hdd;
        
        // Network Adapter MAC (first physical adapter)
        $mac = $this->getWMICValue('path win32_networkadapter where physicaladapter=true', 'MACAddress');
        if ($mac) $components['mac'] = $mac;
        
        // System UUID
        $uuid = $this->getWMICValue('csproduct', 'UUID');
        if ($uuid) $components['uuid'] = $uuid;
        
        return $components;
    }
    
    private function getWMICValue($class, $property) {
        $command = sprintf('wmic %s get %s /value 2>nul', $class, $property);
        $output = shell_exec($command);
        
        if ($output && preg_match("/{$property}=(.+)/", $output, $matches)) {
            return trim($matches[1]);
        }
        
        return null;
    }
    
    public function generateStrongHWID() {
        $hardwareString = '';
        
        // Combine multiple hardware components for stronger fingerprint
        foreach ($this->hardwareComponents as $component => $value) {
            if (!empty($value) && $value !== 'To be filled by O.E.M.') {
                $hardwareString .= $component . ':' . $value . '|';
            }
        }
        
        // Add system-specific information
        $hardwareString .= 'os:' . php_uname('s') . '|';
        $hardwareString .= 'arch:' . php_uname('m') . '|';
        
        // Create a strong hash
        return hash('sha256', $hardwareString);
    }
    
public function validateLicense() {
    echo "[DEBUG] Starting license validation...\n";

    if (!file_exists($this->licenseFile)) {
        echo "[DEBUG] License file not found: {$this->licenseFile}\n";
        $this->logSecurityEvent('License file not found');
        return false;
    }

    try {
        $licenseContent = file_get_contents($this->licenseFile);
        echo "[DEBUG] License content: $licenseContent\n";

        $licenseData = json_decode($licenseContent, true);

        if (!$licenseData || !isset($licenseData['hwid'], $licenseData['expiry'], $licenseData['signature'])) {
            echo "[DEBUG] Invalid license format or missing fields\n";
            $this->logSecurityEvent('Invalid license format');
            return false;
        }

        echo "[DEBUG] License data loaded. Verifying signature...\n";

        if (!$this->verifyLicenseSignature($licenseData)) {
            echo "[DEBUG] Signature verification failed\n";
            $this->logSecurityEvent('License signature verification failed');
            return false;
        }

        echo "[DEBUG] Signature OK. Checking expiry...\n";

        if ($licenseData['expiry'] < time()) {
            echo "[DEBUG] License expired\n";
            $this->logSecurityEvent('License expired');
            return false;
        }

        echo "[DEBUG] Expiry OK. Checking HWID...\n";

        $currentHWID = $this->generateStrongHWID();
        if (!hash_equals($licenseData['hwid'], $currentHWID)) {
            echo "[DEBUG] HWID mismatch: expected {$licenseData['hwid']}, got $currentHWID\n";
            if (!$this->isMinorHardwareChange($licenseData['hwid'], $currentHWID)) {
                $this->logSecurityEvent('Hardware fingerprint mismatch');
                return false;
            } else {
                echo "[DEBUG] Minor HW change accepted.\n";
            }
        }

        echo "[DEBUG] HWID OK. Updating validation time...\n";
        $this->updateValidationTime();

        echo "[DEBUG] License validated successfully.\n";
        return true;

    } catch (Exception $e) {
        echo "[DEBUG] Exception: " . $e->getMessage() . "\n";
        $this->logSecurityEvent('License validation error: ' . $e->getMessage());
        return false;
    }
}

    
    private function isMinorHardwareChange($oldHWID, $newHWID) {
        // Allow for minor changes by checking if core components remain the same
        $coreComponents = ['cpu', 'motherboard', 'bios'];
        $oldComponents = $this->parseHWIDComponents($oldHWID);
        $newComponents = $this->hardwareComponents;
        
        $coreMatches = 0;
        foreach ($coreComponents as $component) {
            if (isset($oldComponents[$component]) && 
                isset($newComponents[$component]) &&
                $oldComponents[$component] === $newComponents[$component]) {
                $coreMatches++;
            }
        }
        
        // Allow license if at least 2 out of 3 core components match
        return $coreMatches >= 2;
    }
    
    private function parseHWIDComponents($hwid) {
        // This would need to be implemented based on how you store component data
        // For now, return empty array
        return [];
    }
    
    private function verifyLicenseSignature($licenseData) {
        // Implement RSA signature verification
        $publicKey = $this->getPublicKey();
        if (!$publicKey) {
            return false;
        }
        
        $dataToVerify = json_encode([
            'hwid' => $licenseData['hwid'],
            'expiry' => $licenseData['expiry'],
            'features' => $licenseData['features'] ?? []
        ]);
        
        return openssl_verify(
            $dataToVerify,
            base64_decode($licenseData['signature']),
            $publicKey,
            OPENSSL_ALGO_SHA256
        ) === 1;
    }
    
    private function getPublicKey() {
        $publicKeyPem = <<<EOD
	-----BEGIN PUBLIC KEY-----
	MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqCL5uHHT5+OeXCw51p8V
	n6KMDiF9CBIz+d6VykQRKxjLHGsbtgK1UAByUaWsHV//Qu3mpylPjGX7ut1K5U6h
	RZf2pgbl5JCi+VojqV3yNs8abDjZxiTezkMnCynhf5JsK8QmAYAxuNmQ1+YJMcw0
	f8Ggewl1Qx2faGs/GtMWRftnaPK/vJxRfUvAO15eTlx5zSl5buIuu0e4niA71j1w
	NkXEPheoAxu6PXZryzpQ8/GEXQGPRfVKvUMA8ZIdR7tPjBjWHxWNfP/UU/c7P2Eu
	DD+M5sqEBG3ZbmiNZUPA/2pa9KpHC7XMJCi4d6m7bndeCcdw3QZUCMZlk3RzRXD+
	MwIDAQAB
	-----END PUBLIC KEY-----
	EOD;
        
        return openssl_pkey_get_public($publicKeyPem);
    }
    
    public function createLicense($expiryDays = 365, $features = []) {
        $hwid = $this->generateStrongHWID();
        $expiry = time() + ($expiryDays * 24 * 60 * 60);
        
        $licenseData = [
            'hwid' => $hwid,
            'expiry' => $expiry,
            'features' => $features,
            'generated' => date('Y-m-d H:i:s'),
            'hardware_info' => $this->hardwareComponents
        ];
        
        // Sign the license
        $licenseData['signature'] = $this->signLicense($licenseData);
        
        file_put_contents($this->licenseFile, json_encode($licenseData, JSON_PRETTY_PRINT));
        
        return $licenseData;
    }
    
    private function signLicense($licenseData) {
        $privateKey = $this->getPrivateKey();
        if (!$privateKey) {
            throw new Exception('Private key not available for signing');
        }
        
        $dataToSign = json_encode([
            'hwid' => $licenseData['hwid'],
            'expiry' => $licenseData['expiry'],
            'features' => $licenseData['features']
        ]);
        
        $signature = '';
        openssl_sign($dataToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        
        return base64_encode($signature);
    }
    
	private function getPrivateKey() {
	    $privateKeyPem = <<<EOD
		-----BEGIN PRIVATE KEY-----
		MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCoIvm4cdPn455c
		LDnWnxWfoowOIX0IEjP53pXKRBErGMscaxu2ArVQAHJRpawdX/9C7eanKU+MZfu6
		3UrlTqFFl/amBuXkkKL5WiOpXfI2zxpsONnGJN7OQycLKeF/kmwrxCYBgDG42ZDX
		5gkxzDR/waB7CXVDHZ9oaz8a0xZF+2do8r+8nFF9S8A7Xl5OXHnNKXlu4i67R7ie
		IDvWPXA2RcQ+F6gDG7o9dmvLOlDz8YRdAY9F9Uq9QwDxkh1Hu0+MGNYfFY18/9RT
		9zs/YS4MP4zmyoQEbdluaI1lQ8D/alr0qkcLtcwkKLh3qbtud14Jx3DdBlQIxmWT
		dHNFcP4zAgMBAAECggEADViJXdybJ2Ln5QUVqjWCb+NlHpIF7KgVPBYw6Zcxjaww
		IxdVYcMhRTJj4Js36DUAe/2KO2aKC+jkbFa4ZS8ymeWQDGmrwdLBPO2suvrcCQGz
		ZTe2AYZXsSOfa8FtRsUGlQrCwYe9aqDKYHi/7hX5bNKfvGRpzGQDjTRGFAkBUZic
		BVYtyOHFQzlJ65220vlHmKBT1RU3wcCuDmBT5xx4c47Aqtno7p+59FH5x4pajrey
		FYqHUAuQsvdlcstdsKPNvHcoXm+fcO39gfh2pl4WVkrnNiO/RWfZFLsuVxEfe1f9
		ETSK6Qo2pJSY5AndEypWTaKLIrD4n1QOI7i77cGA4QKBgQDbqH+n6skROXo/R6Ah
		YDBALPf6okaxV04TV0qJWtrgXUGGAmW6YEbqoklKn1P132lyNlyt3rUReEOjDCPO
		4WKYnADV0upwevnenssbimo9RSizo3R7Y5VAbd98sztpa0aFiU/UicRBr7J7QK1K
		vofMaKVMwIfiw3Y1wE6EHxnfqwKBgQDD9E8c+9++ZSgonx3iWMKv1lSC9kmPWrOt
		AAyal7ZwvpRsN281Ke9CK0Wh1cXF0TKAUxn2cBDYZPsEBqhzaCShRXtFBGr/D1al
		TCfccU+vHENcT+MalUaDUgRvkEy4NS/raFJFvs/+dRb9s0IRwZUkxjKKNt3AF+gU
		q7xXwAHzmQKBgA0+6/PERLu1K5oFR7K7ii1UiN+kSX8INL0V1njR8cD13q2mF1xV
		0fD0OFc3pGh/QGySuqQBegnDptB+OuqOzNNHFQXP4jNGNysliDnw7tcjBIFQqgWG
		y8lG1uM+K6b/wRDsZtP6Ost7PNgR3mFTe/znkpA+S/NwtgAnyJRPC9BhAoGAa/02
		oD16p72trP1LhdLur+LJPkcmcNdGj7+oMoYLaATPXPgL1Fug3HFmdXKPBJ5uW2Pw
		uew2jFrSLvzQVpF9JldZQMbXhGrakEvw576WcGV+BeCMwrijXHmVsjnu3dGJ8AzU
		0lT+4Td8DSJ99bUeUV7cuWjVOiiyoLmoGR/as3kCgYEAlcyNDlYzOl4YNTy/1MJG
		tw8efjN1yQ4YFeZlSypwoAJmPViVznsXeNTW/1x6OtMndbGZFxrWWKv6X/1lO/5F
		N6RrUJFu/8obq/Dxss4h9gPqs9fawruK3859mlNrxLin5a48Ko998p5Gx79+E37l
		GJPITm1lnyUTC00ANgXrWq4=
		-----END PRIVATE KEY-----
		EOD;

	    return openssl_pkey_get_private($privateKeyPem);
	}



    
    private function updateValidationTime() {
        $machineData = [
            'last_validation' => time(),
            'validation_count' => $this->getValidationCount() + 1
        ];
        
        file_put_contents($this->keyFile, json_encode($machineData));
    }
    
    private function getValidationCount() {
        if (file_exists($this->keyFile)) {
            $data = json_decode(file_get_contents($this->keyFile), true);
            return isset($data['validation_count']) ? $data['validation_count'] : 0;
        }
        return 0;
    }
    
    private function logSecurityEvent($event) {
        $logFile = 'security.log';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] SECURITY: {$event}\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    public function getHardwareInfo() {
        return $this->hardwareComponents;
    }
    
    public function generateMachineReport() {
        $report = [
            'timestamp' => date('Y-m-d H:i:s'),
            'hwid' => $this->generateStrongHWID(),
            'hardware' => $this->hardwareComponents,
            'system' => [
                'os' => php_uname('s'),
                'release' => php_uname('r'),
                'version' => php_uname('v'),
                'machine' => php_uname('m')
            ]
        ];
        
        return json_encode($report, JSON_PRETTY_PRINT);
    }
}
?>
