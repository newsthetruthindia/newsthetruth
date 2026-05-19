try {
  $r = Invoke-WebRequest -Uri "https://newsthetruth.com/api/auth/login" -Method POST -Body '{"email":"test","password":"test"}' -ContentType "application/json" -UseBasicParsing
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

try {
  $o = Invoke-WebRequest -Uri "https://newsthetruth.com/api/auth/login" -Method OPTIONS -UseBasicParsing
  Write-Host "OPTIONS HEADERS: " ($o.Headers | Out-String)
} catch {
  Write-Host "OPT ERR: " $_.Exception.Message
}
