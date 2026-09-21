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
        .results { background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .status { margin: 10px 0; padding: 10px; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #cce7ff; color: #004085; border: 1px solid #99d3ff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Zip Archive Performance Test</h1>
        <p>This tool will test zip/unzip operations with large files. Make sure you have a zip file named 'test_archive.zip' in the same directory, or use the generate button to create test files first.</p>
        
        <form method="post">
            <button type="submit" name="generate_files" class="button">Generate Test Files (30 files)</button>
            <button type="submit" name="unzip_test" class="button">Unzip Test</button>
            <button type="submit" name="zip_test" class="button">Zip Test</button>
            <button type="submit" name="cleanup" class="button">Cleanup Files</button>
        </form>

        <?php
        // Configuration
        $zip_filename = 'test_archive.zip';
        $extract_dir = 'extracted_files/';
        $test_files_dir = 'test_files/';

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

        // Generate test files
        if (isset($_POST['generate_files'])) {
            echo "<div class='results'>";
            echo "<h3>Generating Test Files...</h3>";
            
            $start_time = microtime(true);
            
            if (!is_dir($test_files_dir)) {
                mkdir($test_files_dir, 0755, true);
            }
            
            // Clean existing files
            $files = glob($test_files_dir . '*');
            foreach ($files as $file) {
                if (is_file($file)) unlink($file);
            }
            
            $total_files = 30;
            $files_created = 0;
            
            for ($i = 1; $i <= $total_files; $i++) {
                $file_size_mb = rand(3, 10);
                $filename = $test_files_dir . "testfile_" . sprintf("%04d", $i) . "_" . $file_size_mb . "mb.txt";
                
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
            
            // Create zip file
            echo "<h3>Creating Zip Archive...</h3>";
            $zip_start = microtime(true);
            
            $zip = new ZipArchive();
            if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                $files = glob($test_files_dir . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        $zip->addFile($file, basename($file));
                    }
                }
                $zip->close();
                
                $zip_end = microtime(true);
                $zip_time = $zip_end - $zip_start;
                
                $zip_size = filesize($zip_filename);
                
                echo "<div class='success'>Zip archive created in " . number_format($zip_time, 2) . " seconds</div>";
                echo "<div class='info'>Archive size: " . formatBytes($zip_size) . "</div>";
            } else {
                echo "<div class='error'>Failed to create zip archive</div>";
            }
            
            echo "</div>";
        }

        // Unzip test
        if (isset($_POST['unzip_test'])) {
            echo "<div class='results'>";
            echo "<h3>Unzip Performance Test</h3>";
            
            if (!file_exists($zip_filename)) {
                echo "<div class='error'>Zip file '$zip_filename' not found. Please generate test files first.</div>";
            } else {
                $start_time = microtime(true);
                
                // Clean extraction directory
                if (is_dir($extract_dir)) {
                    $files = glob($extract_dir . '*');
                    foreach ($files as $file) {
                        if (is_file($file)) unlink($file);
                    }
                } else {
                    mkdir($extract_dir, 0755, true);
                }
                
                $zip = new ZipArchive();
                if ($zip->open($zip_filename) === TRUE) {
                    $num_files = $zip->numFiles;
                    echo "<div class='info'>Found $num_files files in archive</div>";
                    
                    $extracted = $zip->extractTo($extract_dir);
                    $zip->close();
                    
                    $end_time = microtime(true);
                    $extraction_time = $end_time - $start_time;
                    
                    if ($extracted) {
                        $extracted_files = glob($extract_dir . '*');
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
echo "<div class='error'>Failed to open zip file</div>";
}
}


        echo "</div>";
    }

    // Zip test
    if (isset($_POST['zip_test'])) {
        echo "<div class='results'>";
        echo "<h3>Zip Performance Test</h3>";
        
        if (!is_dir($extract_dir)) {
            echo "<div class='error'>No extracted files found. Please run unzip test first.</div>";
        } else {
            $files_to_zip = glob($extract_dir . '*');
            
            if (empty($files_to_zip)) {
                echo "<div class='error'>No files found in extraction directory. Please run unzip test first.</div>";
            } else {
                $start_time = microtime(true);
                
                $new_zip_filename = 'recompressed_archive.zip';
                
                $zip = new ZipArchive();
                if ($zip->open($new_zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                    $total_size = 0;
                    $files_added = 0;
                    
                    foreach ($files_to_zip as $file) {
                        if (is_file($file)) {
                            $zip->addFile($file, basename($file));
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
                    $compression_time = $end_time - $start_time;
                    
                    $compressed_size = filesize($new_zip_filename);
                    $compression_ratio = ($total_size - $compressed_size) / $total_size * 100;
                    
                    echo "<div class='success'>Successfully compressed $files_added files</div>";
                    echo "<div class='info'>Compression time: " . number_format($compression_time, 2) . " seconds</div>";
                    echo "<div class='info'>Original size: " . formatBytes($total_size) . "</div>";
                    echo "<div class='info'>Compressed size: " . formatBytes($compressed_size) . "</div>";
                    echo "<div class='info'>Compression ratio: " . number_format($compression_ratio, 1) . "%</div>";
                    echo "<div class='info'>Average speed: " . formatBytes($total_size / $compression_time) . "/sec</div>";
                } else {
                    echo "<div class='error'>Failed to create new zip archive</div>";
                }
            }
        }
        
        echo "</div>";
    }

    // Cleanup
    if (isset($_POST['cleanup'])) {
        echo "<div class='results'>";
        echo "<h3>Cleanup</h3>";
        
        $cleaned = 0;
        
        // Clean test files
        if (is_dir($test_files_dir)) {
            $files = glob($test_files_dir . '*');
            foreach ($files as $file) {
                if (is_file($file) && unlink($file)) $cleaned++;
            }
            if (rmdir($test_files_dir)) {
                echo "<div class='success'>Removed test files directory</div>";
            }
        }
        
        // Clean extracted files
        if (is_dir($extract_dir)) {
            $files = glob($extract_dir . '*');
            foreach ($files as $file) {
                if (is_file($file) && unlink($file)) $cleaned++;
            }
            if (rmdir($extract_dir)) {
                echo "<div class='success'>Removed extracted files directory</div>";
            }
        }
        
        // Remove zip files
        $zip_files = ['test_archive.zip', 'recompressed_archive.zip'];
        foreach ($zip_files as $zip_file) {
            if (file_exists($zip_file) && unlink($zip_file)) {
                echo "<div class='success'>Removed $zip_file</div>";
                $cleaned++;
            }
        }
        
        echo "<div class='info'>Total files cleaned: $cleaned</div>";
        echo "</div>";
    }
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