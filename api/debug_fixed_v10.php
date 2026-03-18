<?php
echo "<h1>VERIFICATION V10 - LIVE</h1>";
echo "UNIX TIME: " . time() . "<br>";
echo "WRITABLE /tmp: " . (is_writable('/tmp') ? "YES" : "NO") . "<br>";
echo "PHP VERSION: " . phpversion() . "<br>";
?>
