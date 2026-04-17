# 🚨 CRITICAL PROTOCOLS (MUST READ FIRST)
- **STABILITY (500 ERRORS)**: If the admin panel shows a 500 error, run `chown -R apache:apache /var/www/ntt` on the VPS immediately. The web server runs as `apache`. Never use `root` or `nginx` for file ownership.
- **DEPLOYMENT PROTOCOL**: Always use `./upgrade_backend_vps.sh`. Ensure it correctly enforces `apache:apache` (not `nginx`).
- **ATTRIBUTION PROTOCOL**: Always maintain **Dual Attribution**. `reporter_name` (Frontend) and `user_id` (Backend) are **DECOUPLED**. `user_id` must always track the actual authenticated user account for accountability, while `reporter_name` handles the public-facing name.
- **SCHEMA PROTOCOL**: If saving a post fails with 500, verify the `subtitle` column exists in the `posts` table using `verify_subtitle_field.php`.
- **CUSTOM UI SAFETY**: In custom Filament Blade pages (like **Monitor**), never use `<x-filament-actions::action />`. Always use `wire:click="mountAction('name')"` to prevent "Undefined variable $dynamicComponent" crashes.
- **DEPLOYMENT**: Always use `./upgrade_backend_vps.sh` for deployments; it contains the mandatory permission enforcement logic.
- **FILAMENT GRID LAYOUT SAFETY**: Never chain `->padding()` on a `Stack` Layout component in v3 Grids. Doing so triggers a fatal `BadMethodCallException` (500 Error). Use `->extraAttributes(['class' => 'p-4 flex flex-col h-full'])` instead.
- **FILAMENT UPLOAD DEHYDRATION**: Never attach `->dehydrated(false)` to a `FileUpload` component if you rely on the filepath in `mutateFormDataBeforeSave` or `mutateFormDataBeforeCreate`. Filament completely drops dehydrated fields from the payload map before those hooks execute. If it's not a DB column, manually `unset()` the key inside the hook instead.
- **FRONTEND MEDIA ASSETS**: Any `ImageColumn` or preview resolving uploaded media directly to the public web root must strictly use `->disk('webapp_public')`. Using the default `disk('public')` forces a `/storage/` URL injection which fundamentally breaks all native `/uploads/...` image previews across this infrastructure.
- **MEDIA CREATION SCHEMA**: When manually generating `Media` records via Eloquent (e.g. inside `mutateFormDataBeforeSave`), you MUST explicitly inject the full required field set: `type` ('image'), `path` (the disk path), `url` (the public path), `name` (the file basename), `extension`, and `mimetype`. The `medias` table STRICTLY requires these definitions and lacks default values; omitting any triggers a fatal SQL 1364 Exception (500 Error).

## 📌 Project Architecture (Updated April 11, 2026)
- **Domain**: `newsthetruth.com` (Managed via **GoDaddy**).
- **Frontend (UI)**: Next.js 15 (React 19) hosted on **Vercel** (`newsthetruth.com`).
- **Backend (API & Admin)**: Laravel 11 (PHP 8.2) hosted on VPS with its own subdomain: **`https://backend.newsthetruth.com`**.
- **Security**: Raw IP `117.252.16.132` is masked. SSL is active on the backend.

## 🛠 Verified Environment & Tools
- **PHP 8.2**: `gd` extension active.
- **SSH Key**: `C:\Users\HPi9\.ssh\id_ed25519_ntt`
- **VPS Host**: `backend.newsthetruth.com` (117.252.16.132)
- **OFFICIAL BACKUP**: A full snapshot is stored on the VPS at `/root/backups/NTT_BACKUP_2026.tar.gz` (Created April 13, 2026).

## 🚀 Deployment Workflow
1. **Frontend**: Pushing to `main` branch of `newsthetruthindia/NTT-FINAL-` triggers Vercel.
2. **Backend**: 
   - Commit & Push from `d:\NTT_LOCAL_SERVER` to `newsthetruthindia/newsthetruth` (main).
   - SSH into VPS and run `upgrade_backend_vps.sh` in `/var/www/ntt`.
   - **IMPORTANT**: The application user is `apache:apache`. All file operations must preserve this ownership to prevent 500 errors.
3. **Image Logic**: Vercel Image Optimization is **DISABLED**. Images are optimized on the VPS (Intervention v3) during upload (80% Quality, 1200px Max Width).

## 🤖 AI Interaction Guidelines
- **Role**: Senior Full-Stack Developer & UX Specialist.
- **Goal**: Maintain a "State of the Art" aesthetic with high-end performance and editorial clarity.
- **Access**: Always use `https://backend.newsthetruth.com/admin` for the admin panel.
- **Permissions**: Never use `nginx:nginx` for ownership; the PHP user is strictly `apache`. Always run `chown -R apache:apache /var/www/ntt` after updates.
- **Custom UI**: In custom Blade pages, use `wire:click="mountAction('name')"` rather than `<x-filament-actions::action />` to avoid scope/context errors like `Undefined variable $dynamicComponent`.

## 🏆 Project Milestones

### April 15, 2026: Authentication Security & Onboarding Overhaul
Restored the broken "forgot password" system by migrating to Gmail SMTP (`smtp.gmail.com`, port 465, SSL). Upgraded `ApiAuthController` to a secure implementation utilizing hashed reset tokens and user enumeration protection. Launched a new administrative onboarding flow in the Filament Staff Resource: added "Verify Mail" (resends verification links) and "Send Auth" (onboards users to 2FA) buttons. Implemented a dedicated `/verify-email` frontend route to handle secure verification links.

### April 13, 2026: Subtitle & Attribution Stability Fixes. Corrected permission protocol (`nginx` -> `apache`) and fixed article attribution glitch by patching `Post` model and adding sync logic to `PostResource`. Resolved fatal 500 exceptions in Filament Grids caused by invalid `Stack::padding()` chaining. Restored broken Media Upload flow by exposing `dehydrated` lifecycle paths, enforcing `webapp_public` disk mapping, and satisfying strict SQL schema constraints (providing full Media field set: type, path, url, name, extension, mimetype) to prevent 1364 SQL errors.

### April 11, 2026: News Command Center & Infrastructure Hardening
- **News Monitor Launch**: Implemented an edge-to-edge, 12-screen CCTV-style monitoring matrix with zero-clutter HUD and scrolling headlines.
- **Permission Protocol**: Permanently resolved the recurring 500 Error by identifying and correcting the Apache-Nginx user conflict in the deployment pipeline.
- **NTT Desk Restoration**: Fully restored the official news desk reporter profile and attribution logic.

### April 9, 2026: The NTT Modernization Completion
- **Subdomain Launch**: Launched `backend.newsthetruth.com` to professionally mask the VPS IP. SSL secured via Certbot.
- **Archive Overhaul**: Launched high-impact Search Banner and visual Reporter Grid.
- **Fail-Safe Optimization**: Implemented a robust, non-blocking image pipeline on the VPS. Bypassed Vercel usage limits permanently.
- **IP Transition**: Fully retired all raw IP references in the frontend codebase.

### March 31, 2026: NTT Rebranding & Storage Proxy
- **NTT Rebranding**: Site transitioned from 'News The Truth' to 'NTT'. Updated headers, metadata, and JSON-LD.
- **Storage Proxy**: Hardened the proxy with a robust VPS-to-Legacy fallback system.

*Last Updated: April 15, 2026 15:45 IST*
