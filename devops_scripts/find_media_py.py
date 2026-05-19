import os

file_path = r"d:\NTT_WEBSITE\backups\database_backup.sql"
target = "(5708,"
buffer_size = 1024 * 1024 # 1MB

with open(file_path, "rb") as f:
    overlap = b""
    while True:
        chunk = f.read(buffer_size)
        if not chunk:
            break
        
        search_chunk = overlap + chunk
        if target.encode() in search_chunk:
            pos = search_chunk.find(target.encode())
            # Extract some context around it
            start = max(0, pos - 500)
            end = min(len(search_chunk), pos + 1000)
            print(search_chunk[start:end].decode('utf-8', errors='ignore'))
            break
        
        overlap = chunk[-len(target):]
