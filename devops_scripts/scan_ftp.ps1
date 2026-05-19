$url = "ftp://newsthetruth.com/public_html/"
$request = [System.Net.FtpWebRequest]::Create($url)
$request.Credentials = New-Object System.Net.NetworkCredential('newstew1', 'G7r@9T!mQ*')
$request.Method = [System.Net.WebRequestMethods+Ftp]::ListDirectory
$response = $request.GetResponse()
$reader = New-Object IO.StreamReader $response.GetResponseStream()
echo $reader.ReadToEnd()
$reader.Close()
$response.Close()
