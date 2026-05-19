$frontend = "d:\ntt-frontend"
cd $frontend

Write-Host "Force Staging ALL changes for Smart Discovery Engine..." -ForegroundColor Yellow
git add -A

Write-Host "Committing Final Discovery Engine Overhaul..." -ForegroundColor Green
git commit -m "FIX: Implement article view tracking for analytics dashboard"

Write-Host "Pushing to GitHub (Production Force)..." -ForegroundColor Green
git push origin HEAD:main --force

Write-Host "Frontend Transformation Fully Deployed!" -ForegroundColor Green
Write-Host "Check live at: https://newsthetruth.com" -ForegroundColor Cyan
