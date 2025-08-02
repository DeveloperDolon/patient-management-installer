<?php
// security/update_checker.php

class OfflineUpdateChecker {
    private $updatePath;
    private $currentVersion;
    
    public function __construct($updatePath = '../updates', $version = '1.0.0') {
        $this->updatePath = $updatePath;
        $this->currentVersion = $version;
    }
    
    public function checkForUpdates() {
        if (!is_dir($this->updatePath)) {
            return false;
        }
        
        $updateFiles = glob($this->updatePath . '/update_*.zip');
        if (empty($updateFiles)) {
            return false;
        }
        
        // Sort by version and get latest
        usort($updateFiles, function($a, $b) {
            return version_compare($this->extractVersion($b), $this->extractVersion($a));
        });
        
        $latestUpdate = $updateFiles[0];
        $updateVersion = $this->extractVersion($latestUpdate);
        
        return version_compare($updateVersion, $this->currentVersion, '>') ? $latestUpdate : false;
    }
    
    public function applyUpdate($updateFile) {
        // Extract and apply update
        $zip = new ZipArchive;
        if ($zip->open($updateFile) === TRUE) {
            $zip->extractTo('../');
            $zip->close();
            return true;
        }
        return false;
    }
    
    private function extractVersion($filename) {
        preg_match('/update_(\d+\.\d+\.\d+)\.zip/', basename($filename), $matches);
        return isset($matches[1]) ? $matches[1] : '0.0.0';
    }
}
?>