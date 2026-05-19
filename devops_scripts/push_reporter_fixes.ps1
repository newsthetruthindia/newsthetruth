$backend = "d:\NTT_LOCAL_SERVER"
cd $backend

Write-Host "Staging Reporter Restoration and Sync Upgrades..." -ForegroundColor Green
git add .

Write-Host "Committing..." -ForegroundColor Green
git commit -m "Reporter Restoration: Dynamic Attribution Engine, Provisioning scripts, and Mass Cleanup v5"

Write-Host "Pushing to GitHub..." -ForegroundColor Green
git push origin HEAD:main

Write-Host "Done!" -ForegroundColor Green
