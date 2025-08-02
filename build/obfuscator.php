<?php
// build/obfuscator.php

class AdvancedLaravelObfuscator {
    private $sourceDir;
    private $outputDir;
    private $encryptionKey;
    private $excludeDirs = ['vendor', 'node_modules', 'storage', 'bootstrap/cache', 'tests'];
    private $excludeFiles = ['.env', '.env.example', 'composer.json', 'composer.lock', 'package.json'];
    
    public function __construct($sourceDir, $outputDir, $encryptionKey = null) {
        $this->sourceDir = rtrim($sourceDir, '/');
        $this->outputDir = rtrim($outputDir, '/');
        $this->encryptionKey = $encryptionKey ?: $this->generateEncryptionKey();
    }
    
    public function obfuscate() {
        echo "Starting advanced obfuscation process...\n";
        
        $this->copyStructure();
        $this->obfuscatePhpFiles();
        $this->encryptCriticalFiles();
        $this->createBootstrapLoader();
        $this->protectComposerFiles();
        
        echo "Obfuscation completed successfully!\n";
    }
    
    private function copyStructure() {
        echo "Copying directory structure...\n";
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->sourceDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            $relativePath = substr($item->getPathname(), strlen($this->sourceDir));
            $destinationPath = $this->outputDir . $relativePath;
            
            if ($this->shouldExclude($item->getPathname())) {
                continue;
            }
            
            if ($item->isDir()) {
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
            } else {
                $this->copyFile($item->getPathname(), $destinationPath);
            }
        }
    }
    
    private function obfuscatePhpFiles() {
        echo "Obfuscating PHP files...\n";
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->outputDir)
        );
        
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php' && 
                !$this->shouldExclude($file->getPathname()) &&
                !$this->isVendorFile($file->getPathname())) {
                
                $this->obfuscatePhpFile($file->getPathname());
            }
        }
    }
    
    private function obfuscatePhpFile($filePath) {
        $content = file_get_contents($filePath);
        
        // Advanced obfuscation techniques
        $obfuscated = $this->applyObfuscationTechniques($content);
        
        // Encrypt sensitive files
        if ($this->isSensitiveFile($filePath)) {
            $obfuscated = $this->encryptContent($obfuscated);
        }
        
        file_put_contents($filePath, $obfuscated);
    }
    
    private function applyObfuscationTechniques($code) {
        // Remove comments but preserve important ones
        $code = preg_replace('/\/\*(?!.*@).*?\*\//s', '', $code);
        $code = preg_replace('/\/\/(?!.*TODO|.*FIXME|.*@).*$/m', '', $code);
        
        // Minify whitespace while preserving functionality
        $code = preg_replace('/\s+/', ' ', $code);
        $code = str_replace([' {', '{ ', ' }', '} ', ' (', '( ', ' )', ') ', ' ;', '; '], 
                          ['{', '{', '}', '}', '(', '(', ')', ')', ';', ';'], $code);
        
        // Variable name obfuscation for non-public methods
        $code = $this->obfuscateVariableNames($code);
        
        // String obfuscation for sensitive data
        $code = $this->obfuscateStrings($code);
        
        return $code;
    }
    
    private function obfuscateVariableNames($code) {
        // Create mapping of variable names to obfuscated names
        preg_match_all('/\$([a-zA-Z_][a-zA-Z0-9_]*)/', $code, $matches);
        $variables = array_unique($matches[1]);
        
        $obfuscatedVars = [];
        foreach ($variables as $var) {
            // Skip super globals and common Laravel variables
            if (!in_array($var, ['_GET', '_POST', '_SESSION', '_COOKIE', '_SERVER', '_ENV', 
                               'this', 'request', 'response', 'app', 'config'])) {
                $obfuscatedVars[$var] = 'v' . substr(md5($var . $this->encryptionKey), 0, 8);
            }
        }
        
        // Replace variables
        foreach ($obfuscatedVars as $original => $obfuscated) {
            $code = preg_replace('/\$' . preg_quote($original) . '\b/', '$' . $obfuscated, $code);
        }
        
        return $code;
    }
    
    private function obfuscateStrings($code) {
        // Obfuscate string literals (but not routes, views, or config keys)
        $code = preg_replace_callback(
            '/"([^"\\\\]*(\\\\.[^"\\\\]*)*)"/',
            function($matches) {
                $string = $matches[1];
                // Skip certain strings
                if (strlen($string) < 3 || 
                    preg_match('/^(route\.|view\.|config\.|env\.|trans\.)/', $string) ||
                    preg_match('/\.(blade\.php|css|js|png|jpg|gif)$/', $string)) {
                    return $matches[0];
                }
                return '"' . base64_encode($string) . '"';
            },
            $code
        );
        
        return $code;
    }
    
    private function encryptCriticalFiles() {
        echo "Encrypting critical configuration files...\n";
        
        $criticalFiles = [
            '.env',
            'config/app.php',
            'config/database.php',
            'app/Http/Kernel.php'
        ];
        
        foreach ($criticalFiles as $file) {
            $fullPath = $this->outputDir . '/' . $file;
            if (file_exists($fullPath)) {
                $content = file_get_contents($fullPath);
                $encrypted = $this->encryptContent($content);
                file_put_contents($fullPath . '.enc', $encrypted);
                unlink($fullPath);
            }
        }
    }
    
    private function createBootstrapLoader() {
        echo "Creating encrypted bootstrap loader...\n";
        
        $loaderCode = '<?php
class SecureBootstrap {
    private static $key = "' . $this->encryptionKey . '";
    
    public static function load() {
        $encryptedFiles = [
            "config/app.php.enc",
            "config/database.php.enc",
            ".env.enc"
        ];
        
        foreach ($encryptedFiles as $file) {
            if (file_exists($file)) {
                $decrypted = self::decrypt(file_get_contents($file));
                $originalFile = str_replace(".enc", "", $file);
                file_put_contents($originalFile, $decrypted);
            }
        }
    }
    
    private static function decrypt($data) {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, "AES-256-CBC", self::$key, 0, $iv);
    }
}

SecureBootstrap::load();
require_once "vendor/autoload.php";
?>';
        
        file_put_contents($this->outputDir . '/bootstrap/secure_autoload.php', $loaderCode);
    }
    
    private function encryptContent($content) {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($content, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
        return base64_encode($iv . $encrypted);
    }
    
    private function generateEncryptionKey() {
        return hash('sha256', uniqid(mt_rand(), true));
    }
    
    private function shouldExclude($path) {
        foreach ($this->excludeDirs as $excludeDir) {
            if (strpos($path, '/' . $excludeDir . '/') !== false) {
                return true;
            }
        }
        
        foreach ($this->excludeFiles as $excludeFile) {
            if (basename($path) === $excludeFile) {
                return true;
            }
        }
        
        return false;
    }
    
    private function isSensitiveFile($path) {
        $sensitivePatterns = [
            '/config\//',
            '/app\/Http\/Controllers\//',
            '/app\/Models\//',
            '/database\/migrations\//'
        ];
        
        foreach ($sensitivePatterns as $pattern) {
            if (preg_match($pattern, $path)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function isVendorFile($path) {
        return strpos($path, '/vendor/') !== false;
    }
    
    private function copyFile($source, $destination) {
        $destinationDir = dirname($destination);
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }
        copy($source, $destination);
    }
    
    private function protectComposerFiles() {
        // Create a simplified composer.json that doesn't reveal package details
        $protectedComposer = [
            "name" => "protected/application",
            "type" => "project",
            "require" => [
                "php" => "^8.1"
            ],
            "autoload" => [
                "psr-4" => [
                    "App\\" => "app/"
                ]
            ]
        ];
        
        file_put_contents($this->outputDir . '/composer.json', json_encode($protectedComposer, JSON_PRETTY_PRINT));
    }
}

// Usage
if ($argc < 3) {
    echo "Usage: php obfuscator.php <source_dir> <output_dir> [encryption_key]\n";
    exit(1);
}

$sourceDir = $argv[1];
$outputDir = $argv[2];
$encryptionKey = isset($argv[3]) ? $argv[3] : null;

$obfuscator = new AdvancedLaravelObfuscator($sourceDir, $outputDir, $encryptionKey);
$obfuscator->obfuscate();
?>