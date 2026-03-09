param(
    [string]$BaseUrl = "http://localhost:18080",
    [int]$MasterUserId = 2,
    [int]$RequestId = 2
)

$cookieFile = Join-Path $PSScriptRoot "race_cookies.txt"
$loginHtml = Join-Path $PSScriptRoot "race_login.html"

if (Test-Path $cookieFile) { Remove-Item $cookieFile -Force }
if (Test-Path $loginHtml) { Remove-Item $loginHtml -Force }

curl.exe -s -c "$cookieFile" "$BaseUrl/login" -o "$loginHtml" | Out-Null
$html = Get-Content $loginHtml -Raw
$tokenMatch = [regex]::Match($html, 'name="_token" value="([^"]+)"')
if (-not $tokenMatch.Success) {
    throw "CSRF token not found on /login page"
}
$token = $tokenMatch.Groups[1].Value

curl.exe -s -b "$cookieFile" -c "$cookieFile" -X POST "$BaseUrl/login" `
    -H "Content-Type: application/x-www-form-urlencoded" `
    --data "_token=$token&user_id=$MasterUserId" | Out-Null

$takeUrl = "$BaseUrl/master/requests/$RequestId/take"
$body = "_token=$token"

$job1 = Start-Job -ScriptBlock {
    param($url, $cookie, $requestBody)
    curl.exe -s -o NUL -w "%{http_code}" -b $cookie -X POST $url -H "Accept: application/json" -H "Content-Type: application/x-www-form-urlencoded" --data $requestBody
} -ArgumentList $takeUrl, $cookieFile, $body

$job2 = Start-Job -ScriptBlock {
    param($url, $cookie, $requestBody)
    curl.exe -s -o NUL -w "%{http_code}" -b $cookie -X POST $url -H "Accept: application/json" -H "Content-Type: application/x-www-form-urlencoded" --data $requestBody
} -ArgumentList $takeUrl, $cookieFile, $body

$status1 = Receive-Job -Job $job1 -Wait
$status2 = Receive-Job -Job $job2 -Wait

Remove-Job $job1, $job2 -Force

"Request #1 HTTP status: $status1"
"Request #2 HTTP status: $status2"
"Expected: one 200 and one 409"
