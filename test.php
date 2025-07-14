<?php
// Check if mysqli extension is loaded
if (extension_loaded('mysqli')) {
    echo "MySQLi extension is loaded ✓<br>";
} else {
    echo "MySQLi extension is NOT loaded ✗<br>";
}

// Check if PDO MySQL is loaded
if (extension_loaded('pdo_mysql')) {
    echo "PDO MySQL extension is loaded ✓<br>";
} else {
    echo "PDO MySQL extension is NOT loaded ✗<br>";
}

// Show all loaded extensions
echo "<h3>All loaded extensions:</h3>";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    echo $ext . "<br>";
}

// Show PHP version
echo "<h3>PHP Version:</h3>";
echo phpversion();
?>