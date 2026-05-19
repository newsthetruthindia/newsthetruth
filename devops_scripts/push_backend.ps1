$backend = "d:\NTT_LOCAL_SERVER"

Set-Location $backend
Write-Host "Working in: $(Get-Location)" -ForegroundColor Yellow

Write-Host "Staging all backend changes..." -ForegroundColor Green
git add -A

$status = git status --short
if ($status) {
    Write-Host "Committing..." -ForegroundColor Green
    git commit -m "FIX: Sponsor Ads logic - return all ads per type, eager load media relations"
    Write-Host "Pushing to main..." -ForegroundColor Green
    git push origin HEAD:main
    Write-Host "Backend deployed!" -ForegroundColor Cyan
} else {
    Write-Host "Nothing to commit - checking remote..." -ForegroundColor Yellow
    git push origin HEAD:main
    Write-Host "Done!" -ForegroundColor Cyan
}
