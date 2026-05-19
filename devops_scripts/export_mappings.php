<?php
// export_mappings.php
$dbHost = '127.0.0.1';
$dbName = 'newstew1_main';
$dbUser = 'newstew1_newsthet';
$dbPass = '3RdX?tPig*^$';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = ['categories', 'tags', 'post_categories', 'post_tags', 'medias'];
    $sqlContent = "-- Mapping and Media Export\n\n";

    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT * FROM $table");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) > 0) {
            $sqlContent .= "-- Data for $table\n";
            foreach ($rows as $row) {
                $columns = "`" . implode("`, `", array_keys($row)) . "`";
                $values = array_map(function($val) use ($pdo) {
                    if ($val === null) return 'NULL';
                    return $pdo->quote($val);
                }, array_values($row));
                $sqlContent .= "REPLACE INTO `$table` ($columns) VALUES (" . implode(", ", $values) . ");\n";
            }
            $sqlContent .= "\n";
        }
    }

    file_put_contents('mapping_export.sql', $sqlContent);

    // Also prepare the media zip
    $zipFile = 'full_uploads.zip';
    $sourceDir = __DIR__ . '/public/uploads';

    if (is_dir($sourceDir)) {
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($sourceDir),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($sourceDir) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            echo "SUCCESS|mapping_export.sql|" . filesize('mapping_export.sql') . "|full_uploads.zip|" . filesize($zipFile);
        } else {
            echo "ERROR|ZIP_FAILED";
        }
    } else {
        echo "ERROR|UPLOADS_NOT_FOUND";
    }

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>
