<?php
/**
 * Share Tool - Single File Upload & Export
 * Features: Glassmorphism UI, Upload, List, Download, Export all as Zip.
 */

// --- CONFIGURATION ---
$upload_dir = __DIR__ . '/uploads_share/';
$app_title = "NTT Share Hub";
$max_file_size = 50 * 1024 * 1024; // 50MB

// Create upload directory if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// --- LOGIC ---
$message = '';
$message_type = ''; // 'success' or 'error'

// Handle Upload
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        if ($file['size'] <= $max_file_size) {
            $target_file = $upload_dir . basename($file['name']);
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                $message = "File uploaded successfully!";
                $message_type = "success";
            } else {
                $message = "Failed to move uploaded file.";
                $message_type = "error";
            }
        } else {
            $message = "File is too large (max 50MB).";
            $message_type = "error";
        }
    } else {
        $message = "Upload error: " . $file['error'];
        $message_type = "error";
    }
}

// Handle Export All as Zip
if (isset($_GET['export']) && $_GET['export'] === 'zip') {
    $zip_filename = 'share_export_' . date('Ymd_His') . '.zip';
    $zip = new ZipArchive();
    if ($zip->open($zip_filename, ZipArchive::CREATE) === TRUE) {
        $files = array_diff(scandir($upload_dir), array('.', '..'));
        foreach ($files as $f) {
            $zip->addFile($upload_dir . $f, $f);
        }
        $zip->close();

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zip_filename);
        header('Content-Length: ' . filesize($zip_filename));
        readfile($zip_filename);
        unlink($zip_filename); // Remove zip after download
        exit;
    }
}

// List Files
$files = array_diff(scandir($upload_dir), array('.', '..'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $app_title; ?></title>
    <style>
        :root {
            --primary: #ec4445;
            --primary-hover: #d33a3b;
            --bg: #152036;
            --card-bg: #1b2a47;
            --text: #ffffff;
            --text-dim: #94a3b8;
            --glass-border: rgba(255, 255, 255, 0.05);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .header-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-logo img {
            max-width: 180px;
            height: auto;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--text);
            text-align: center;
            font-weight: 700;
        }

        p.subtitle {
            color: var(--text-dim);
            text-align: center;
            margin-bottom: 2rem;
        }

        .upload-zone {
            border: 2px dashed var(--glass-border);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 2rem;
        }

        .upload-zone:hover {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.03);
        }

        .upload-zone input {
            display: none;
        }

        .btn {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: center;
        }

        .btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            margin-top: 10px;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .file-list {
            margin-top: 2rem;
            list-style: none;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            margin-bottom: 8px;
            border: 1px solid transparent;
            transition: 0.2s;
        }

        .file-item:hover {
            border-color: var(--glass-border);
            background: rgba(255, 255, 255, 0.08);
        }

        .file-name {
            font-size: 0.9rem;
            color: var(--text);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-right: 10px;
        }

        .file-actions a {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .alert {
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: center;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .empty-state {
            text-align: center;
            color: var(--text-dim);
            font-size: 0.9rem;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header-logo">
            <img src="public/img/logo/logosn.png" alt="NTT Logo" onerror="this.style.display='none'">
        </div>
        <h1><?php echo $app_title; ?></h1>
        <p class="subtitle">Quickly upload or export project files</p>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" id="uploadForm">
            <label class="upload-zone" id="dropZone">
                <svg style="width: 48px; height: 48px; color: var(--text-dim); margin-bottom: 12px;" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                    </path>
                </svg>
                <p style="margin-bottom: 4px;">Click to upload or drag & drop</p>
                <p style="font-size: 0.8rem; color: var(--text-dim);">Max file size: 50MB</p>
                <input type="file" name="file" onchange="document.getElementById('uploadForm').submit()">
            </label>
        </form>

        <div class="file-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h3 style="font-size: 1rem;">Files</h3>
                <?php if (count($files) > 0): ?>
                    <a href="?export=zip" class="file-name" style="color: var(--primary); text-decoration: none;">Zip Export
                        All</a>
                <?php endif; ?>
            </div>

            <ul class="file-list">
                <?php if (count($files) > 0): ?>
                    <?php foreach ($files as $f): ?>
                        <li class="file-item">
                            <span class="file-name"><?php echo $f; ?></span>
                            <div class="file-actions">
                                <a href="uploads_share/<?php echo urlencode($f); ?>" download>Download</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="empty-state">No files uploaded yet.</li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Optional: Add a button to go back to home or dashboard -->
        <a href="/" class="btn btn-secondary">Back to Home</a>
    </div>

    <script>
        const dropZone = document.getElementById('dropZone');
        const input = dropZone.querySelector('input');

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'var(--primary)';
            dropZone.style.background = 'rgba(255, 255, 255, 0.05)';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = 'var(--glass-border)';
            dropZone.style.background = 'transparent';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            input.files = e.dataTransfer.files;
            document.getElementById('uploadForm').submit();
        });
    </script>

</body>

</html>