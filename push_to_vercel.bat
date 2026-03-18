@echo off
echo ==========================================
echo   NTT VERCEL BUILD OPTIMIZER (FORCE)
echo ==========================================
cd /d d:\NTT_LOCAL_SERVER

echo 1. Removing old lock file (forcing dependency sync)...
del composer.lock

echo 2. Adding optimized files...
git add .
git add composer.lock

echo 3. Committing changes...
git commit -m "fix: remove composer.lock to force Vercel to install new requirements"

echo 4. Pushing to GitHub (This triggers Vercel)...
git push

echo ==========================================
echo   DONE! Check your Vercel Dashboard now.
echo   (This build will take ~3 mins to finish)
echo ==========================================
pause
