<?php
$zipFile = "march_media.zip";
$sourceDir = __DIR__ . "/public/uploads";
$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDir), RecursiveIteratorIterator::LEAVES_ONLY);
    foreach ($files as $name => $file) {
        if (!$file->isDir() && $file->getMTime() >= strtotime("2026-03-12 00:00:00")) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($sourceDir) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    echo "MEDIA_DONE|" . filesize($zipFile);
} else { echo "MEDIA_ERROR"; }
?>
