$c = Get-Content -Path d:\NTT_WEBSITE\sync_utf8.txt
$imageList = @()
foreach ($line in $c) {
    if ($line -match "'(public/uploads/.*?[.][a-zA-Z0-9]{3,4})'") {
        $imageList += $matches[1]
    }
}
$imageList | Sort-Object -Unique | Out-File -FilePath d:\NTT_WEBSITE\image_list.txt -Encoding utf8
