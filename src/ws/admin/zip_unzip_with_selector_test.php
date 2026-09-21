<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zip Archive Performance Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        .button { background: #007cba; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; margin: 10px; font-size: 16px; }
        .button:hover { background: #005a87; }
        .input-group { margin: 15px 0; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .results { background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .status { margin: 10px 0; padding: 10px; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #cce7ff; color: #004085; border: 1px solid #99d3ff; }
        .form-section { background: #f9f9f9; padding: 20px; margin: 20px 0; border-radius: 4px; border: 1px solid #e0e0e0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Zip Archive Performance Test</h1>
        <p>Enter the paths for your zip file and folder to test zip/unzip operations with custom directories.</p>
        
        <form method="post">
            <div class="form-section">
                <h3>File Paths</h3>
                <div class="input-group">
                    <label for="zip_path">Zip File Path:</label>
                    <input type="text" id="zip_path" name="zip_path" 
                           value="<?php echo htmlspecialchars($_POST['zip_path'] ?? 'test_archive.zip'); ?>" 
                           placeholder="e.g., /path/to/archive.zip or archive.zip">
                </div>
                
                <div class="input-group">
                    <label for="folder_path">Folder Path (for zipping/extracting):</label>
                    <input type="text" id="folder_path" name="folder_path" 
                           value="<?php echo htmlspecialchars($_POST['folder_path'] ?? 'test_folder/'); ?>" 
                           placeholder="e.g., /path/to/folder/ or folder_name/">
                </div>
            </div>
            
            <div class="form-section">
                <h3>Actions</h3>
                <button type="submit" name="generate_files" class="button">Generate Test Files (30 files in folder)</button>
                <button type="submit" name="create_zip" class="button">Create Zip from Folder</button>
                <button type="submit" name="unzip_test" class="button">Extract Zip to Folder</button>
                <button type="submit" name="cleanup" class="button">Cleanup Files</button>
            </div>
        </form>

        <?php
        // Get paths from form
        $zip_filename = $_POST['zip_path'] ?? 'test_archive.zip';
        $folder_path = $_POST['folder_path'] ?? 'test_folder/';

        // Ensure folder path ends with slash
        if (!empty($folder_path) && substr($folder_path, -1) !== '/') {
            $folder_path .= '/';
        }

        function formatBytes($size, $precision = 2) {
            $units = array('B', 'KB', 'MB', 'GB', 'TB');
            for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
                $size /= 1024;
            }
            return round($size, $precision) . ' ' . $units[$i];
        }

        function generateRandomData($size_mb) {
            $size_bytes = $size_mb * 1024 * 1024;
            $chunk_size = 8192;
            $data = '';
            
            while (strlen($data) < $size_bytes) {
                $remaining = $size_bytes - strlen($data);
                $current_chunk = min($chunk_size, $remaining);
                $data .= str_repeat(chr(rand(32, 126)), $current_chunk);
            }
            
            return $data;
        }

        function getAllFiles($dir) {
            $files = array();
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $files[] = $file->getPathname();
                }
            }
            
            return $files;
        }

        // Generate test files
        if (isset($_POST['generate_files'])) {
            echo "<div class='results'>";
            echo "<h3>Generating Test Files in: $folder_path</h3>";
            
            $start_time = microtime(true);
            
            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0755, true);
                echo "<div class='info'>Created directory: $folder_path</div>";
            }
            
            // Clean existing files
            $files = glob($folder_path . '*');
            foreach ($files as $file) {
                if (is_file($file)) unlink($file);
            }
            
            $total_files = 30;
            $files_created = 0;
            
            for ($i = 1; $i <= $total_files; $i++) {
                $file_size_mb = rand(3, 10);
                $filename = $folder_path . "testfile_" . sprintf("%04d", $i) . "_" . $file_size_mb . "mb.txt";
                
                $data = generateRandomData($file_size_mb);
                
                if (file_put_contents($filename, $data)) {
                    $files_created++;
                }
                
                if ($i % 100 == 0) {
                    echo "<div class='info'>Created $i files...</div>";
                    flush();
                    ob_flush();
                }
            }
            
            $end_time = microtime(true);
            $generation_time = $end_time - $start_time;
            
            echo "<div class='success'>Successfully created $files_created files in " . number_format($generation_time, 2) . " seconds</div>";
            echo "</div>";
        }

        // Create zip from folder
        if (isset($_POST['create_zip'])) {
            echo "<div class='results'>";
            echo "<h3>Creating Zip Archive: $zip_filename from Folder: $folder_path</h3>";
            
            if (!is_dir($folder_path)) {
                echo "<div class='error'>Folder '$folder_path' not found. Please check the path or generate test files first.</div>";
            } else {
                $start_time = microtime(true);
                
                $zip = new ZipArchive();
                if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
$files = getAllFiles($folder_path);
$files_added = 0;
$total_size = 0;


                foreach ($files as $file) {
                    if (is_file($file)) {
                        $relative_path = str_replace($folder_path, '', $file);
                        $zip->addFile($file, $relative_path);
                        $total_size += filesize($file);
                        $files_added++;
                        
                        if ($files_added % 100 == 0) {
                            echo "<div class='info'>Added $files_added files to archive...</div>";
                            flush();
                            ob_flush();
                        }
                    }
                }
                
                $zip->close();
                
                $end_time = microtime(true);
                $zip_time = $end_time - $start_time;
                
                $zip_size = filesize($zip_filename);
                $compression_ratio = ($total_size - $zip_size) / $total_size * 100;
                
                echo "<div class='success'>Zip archive created with $files_added files in " . number_format($zip_time, 2) . " seconds</div>";
                echo "<div class='info'>Original size: " . formatBytes($total_size) . "</div>";
                echo "<div class='info'>Archive size: " . formatBytes($zip_size) . "</div>";
                echo "<div class='info'>Compression ratio: " . number_format($compression_ratio, 1) . "%</div>";
                echo "<div class='info'>Average speed: " . formatBytes($total_size / $zip_time) . "/sec</div>";
            } else {
                echo "<div class='error'>Failed to create zip archive at '$zip_filename'</div>";
            }
        }
        
        echo "</div>";
    }

    // Unzip test
    if (isset($_POST['unzip_test'])) {
        echo "<div class='results'>";
        echo "<h3>Extracting Zip: $zip_filename to Folder: $folder_path</h3>";
        
        if (!file_exists($zip_filename)) {
            echo "<div class='error'>Zip file '$zip_filename' not found. Please check the path.</div>";
        } else {
            $start_time = microtime(true);
            
            // Create extraction directory if it doesn't exist
            if (!is_dir($folder_path)) {
                mkdir($folder_path, 0755, true);
                echo "<div class='info'>Created directory: $folder_path</div>";
            } else {
                // Clean extraction directory
                $files = glob($folder_path . '*');
                foreach ($files as $file) {
                    if (is_file($file)) unlink($file);
                }
                echo "<div class='info'>Cleaned existing files in directory</div>";
            }
            
            $zip = new ZipArchive();
            if ($zip->open($zip_filename) === TRUE) {
                $num_files = $zip->numFiles;
                echo "<div class='info'>Found $num_files files in archive</div>";
                
                $extracted = $zip->extractTo($folder_path);
                $zip->close();
                
                $end_time = microtime(true);
                $extraction_time = $end_time - $start_time;
                
                if ($extracted) {
                    $extracted_files = getAllFiles($folder_path);
                    $total_size = 0;
                    foreach ($extracted_files as $file) {
                        if (is_file($file)) {
                            $total_size += filesize($file);
                        }
                    }
                    
                    echo "<div class='success'>Successfully extracted " . count($extracted_files) . " files</div>";
                    echo "<div class='info'>Extraction time: " . number_format($extraction_time, 2) . " seconds</div>";
                    echo "<div class='info'>Total extracted size: " . formatBytes($total_size) . "</div>";
                    echo "<div class='info'>Average speed: " . formatBytes($total_size / $extraction_time) . "/sec</div>";
                } else {
                    echo "<div class='error'>Failed to extract files</div>";
                }
            } else {
                echo "<div class='error'>Failed to open zip file '$zip_filename'</div>";
            }
        }
        
        echo "</div>";
    }

    // Cleanup
    if (isset($_POST['cleanup'])) {
        echo "<div class='results'>";
        echo "<h3>Cleanup</h3>";
        
        $cleaned = 0;
        
        // Clean folder
        if (is_dir($folder_path)) {
            $files = getAllFiles($folder_path);
            foreach ($files as $file) {
                if (is_file($file) && unlink($file)) $cleaned++;
            }
            
            // Try to remove the directory (will only work if empty)
            if (@rmdir($folder_path)) {
                echo "<div class='success'>Removed folder: $folder_path</div>";
            } else {
                echo "<div class='info'>Cleaned files in folder: $folder_path (folder kept)</div>";
            }
        }
        
        // Remove zip file
        if (file_exists($zip_filename) && unlink($zip_filename)) {
            echo "<div class='success'>Removed zip file: $zip_filename</div>";
            $cleaned++;
        }
        
        echo "<div class='info'>Total files cleaned: $cleaned</div>";
        echo "</div>";
    }

    // Display current settings
    echo "<div class='results'>";
    echo "<h3>Current Settings</h3>";
    echo "<p><strong>Zip File Path:</strong> " . htmlspecialchars($zip_filename) . "</p>";
    echo "<p><strong>Folder Path:</strong> " . htmlspecialchars($folder_path) . "</p>";
    echo "<p><strong>Zip File Exists:</strong> " . (file_exists($zip_filename) ? 'Yes (' . formatBytes(filesize($zip_filename)) . ')' : 'No') . "</p>";
    echo "<p><strong>Folder Exists:</strong> " . (is_dir($folder_path) ? 'Yes' : 'No') . "</p>";
    
    if (is_dir($folder_path)) {
        $files_in_folder = getAllFiles($folder_path);
        $folder_size = 0;
        foreach ($files_in_folder as $file) {
            if (is_file($file)) {
                $folder_size += filesize($file);
            }
        }
        echo "<p><strong>Files in Folder:</strong> " . count($files_in_folder) . "</p>";
        echo "<p><strong>Folder Size:</strong> " . formatBytes($folder_size) . "</p>";
    }
    echo "</div>";
    ?>

    <div class="results">
        <h3>System Information</h3>
        <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
        <p><strong>Memory Limit:</strong> <?php echo ini_get('memory_limit'); ?></p>
        <p><strong>Max Execution Time:</strong> <?php echo ini_get('max_execution_time'); ?> seconds</p>
        <p><strong>ZipArchive Available:</strong> <?php echo class_exists('ZipArchive') ? 'Yes' : 'No'; ?></p>
        <p><strong>Current Memory Usage:</strong> <?php echo formatBytes(memory_get_usage()); ?></p>
        <p><strong>Peak Memory Usage:</strong> <?php echo formatBytes(memory_get_peak_usage()); ?></p>
    </div>
</div>

</body>
</html>