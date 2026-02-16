# PowerShell version
$cookieJar = $null
$baseUrl = "http://127.0.0.1:8000"

Write-Host "=== Testing Login Flow with Database Sessions ===" -ForegroundColor Green
Write-Host ""

# Get the landing page
Write-Host "1. Getting landing page..." -ForegroundColor Cyan
$response = Invoke-WebRequest -Uri "$baseUrl/" -SessionVariableName "session"

# Extract CSRF token
$csrfMatch = [regex]::Match($response.Content, 'name="_token"\s+value="([^"]+)"')
$csrfToken = $csrfMatch.Groups[1].Value

if (-not $csrfToken) {
    Write-Host "ERROR: Could not extract CSRF token" -ForegroundColor Red
    exit 1
}

Write-Host "Got CSRF token: $($csrfToken.Substring(0, 10))..." -ForegroundColor Green
Write-Host ""

# Login
Write-Host "2. Posting login form..." -ForegroundColor Cyan
$loginResponse = Invoke-WebRequest -Uri "$baseUrl/login" -Method POST `
    -Body @{
        credential = "u0001"
        password = "password"
        _token = $csrfToken
    } `
    -WebSession $session `
    -MaximumRedirection 0 -ErrorAction SilentlyContinue

Write-Host "Login response status: $($loginResponse.StatusCode)" -ForegroundColor Yellow
Write-Host ""

# Check test-page
Write-Host "3. Checking /test-page..." -ForegroundColor Cyan
$testResponse = Invoke-WebRequest -Uri "$baseUrl/test-page" -WebSession $session
Write-Host $testResponse.Content | Select-String "Authenticated|Not Authenticated" -ForegroundColor
Write-Host ""

# Check debug/auth
Write-Host "4. Checking /debug/auth..." -ForegroundColor Cyan
$authResponse = Invoke-WebRequest -Uri "$baseUrl/debug/auth" -WebSession $session
Write-Host $authResponse.Content -ForegroundColor Yellow
Write-Host ""

# Check logs
Write-Host "5. Checking recent logs..." -ForegroundColor Cyan
$logsResponse = Invoke-WebRequest -Uri "$baseUrl/debug/logs" -WebSession $session
$logsLines = ($logsResponse.Content -split "`n" | Select-Object -Last 50)
Write-Host ($logsLines -join "`n") -ForegroundColor Gray

Write-Host ""
Write-Host "=== Test Complete ===" -ForegroundColor Green
