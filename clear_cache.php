<?php
/**
 * Cache Clear Script for cPanel
 * Upload this to your web root and access via browser: yourdomain.com/clear_cache.php
 * DELETE THIS FILE after use for security!
 */

// Prevent unauthorized access - change this password!
$password = 'ClearCache2025';

if (!isset($_GET['password']) || $_GET['password'] !== $password) {
    die('Unauthorized access');
}

echo "<h1>Clearing Laravel Cache...</h1>";

// Change to your Laravel root directory
chdir(__DIR__);

// Function to delete files in a directory
function deleteFiles($dir, $pattern = '*') {
    $files = glob($dir . '/' . $pattern);
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $count++;
        }
    }
    return $count;
}

// Function to recursively delete directory contents
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return 0;
    }
    
    $count = 0;
    $files = array_diff(scandir($dir), array('.', '..', '.gitignore'));
    
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            $count += deleteDirectory($path);
            @rmdir($path);
        } else {
            unlink($path);
            $count++;
        }
    }
    
    return $count;
}

echo "<h2>Results:</h2>";
echo "<ul>";

// Clear view cache
$viewCount = deleteDirectory('storage/framework/views');
echo "<li>✓ Cleared view cache: {$viewCount} files deleted</li>";

// Clear config cache
if (file_exists('bootstrap/cache/config.php')) {
    unlink('bootstrap/cache/config.php');
    echo "<li>✓ Cleared config cache</li>";
} else {
    echo "<li>- Config cache already clear</li>";
}

// Clear route cache
if (file_exists('bootstrap/cache/routes-v7.php')) {
    unlink('bootstrap/cache/routes-v7.php');
    echo "<li>✓ Cleared route cache</li>";
} else {
    echo "<li>- Route cache already clear</li>";
}

// Clear services cache
if (file_exists('bootstrap/cache/services.php')) {
    unlink('bootstrap/cache/services.php');
    echo "<li>✓ Cleared services cache</li>";
} else {
    echo "<li>- Services cache already clear</li>";
}

// Clear compiled cache
$compiledCount = deleteFiles('storage/framework/cache/data', '*');
echo "<li>✓ Cleared compiled cache: {$compiledCount} files deleted</li>";

// Clear session files
$sessionCount = deleteFiles('storage/framework/sessions', '*');
echo "<li>✓ Cleared sessions: {$sessionCount} files deleted</li>";

echo "</ul>";

echo "<h2 style='color: green;'>✓ Cache cleared successfully!</h2>";
echo "<p><strong>IMPORTANT:</strong> Delete this file (clear_cache.php) immediately for security!</p>";
echo "<p>Now refresh your application and check if the Teller menu appears.</p>";
?>
