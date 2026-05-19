<?php
foreach (getallheaders() as $name => $value) {
    echo "$name: $value\n";
}
echo "\nSERVER:\n";
foreach ($_SERVER as $name => $value) {
    if (strpos($name, 'HTTP_') === 0) {
        echo "$name: $value\n";
    }
}
