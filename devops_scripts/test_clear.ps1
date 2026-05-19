try {
  $r = Invoke-WebRequest -Uri "https://newsthetruth.com/cache_clear.php" -UseBasicParsing
  Write-Host "STATUS: " $r.StatusCode
  Write-Host "R: " $r.Content
} catch {
  Write-Host "ERR: " $_.Exception.Response.StatusCode.value__
  try {
      $stream = $_.Exception.Response.GetResponseStream()
      $reader = New-Object System.IO.StreamReader($stream)
      Write-Host "B: " $reader.ReadToEnd()
  } catch {}
}
