# 🚨 CRITICAL PROTOCOLS (MUST READ FIRST)
- **STABILITY (500 ERRORS)**: If the admin panel shows a 500 error, run `chown -R apache:apache /var/www/ntt` on the VPS immediately. The web server runs as `apache`. Never use `root` or `nginx` for file ownership. Always wrap `User::role()` calls in try-catch blocks and use `Option::...->first()?->value ?? '0'` for safe setting checks.
- **DEPLOYMENT PROTOCOL**: Always use `./upgrade_backend_vps.sh`. Ensure it correctly enforces `apache:apache` (not `nginx`).
- **ATTRIBUTION PROTOCOL**: Always maintain **Tri-Attribution**. `reporter_name` / `user_id` (Frontend Reporter Display) and `published_by` (Backend Admin/Editor Accountability) are **DECOUPLED**. `user_id` is linked to reporter matching for frontend API display. `published_by` safely tracks the actual authenticated admin account (`auth()->id()`) who created/saved the post. In `PostResource.php`, "PUBLISHED BY" column displays `publishedByUser.firstname`.
- **FILAMENT WIDGET STYLING & GRID HEIGHT**: Never use uncompiled custom Tailwind hex classes (like `from-[#e63946]`) in Filament v3 admin widgets because Filament uses pre-compiled CSS. Use inline CSS (`style="..."`). In Filament v3 Dashboard CSS Grid, sibling items automatically stretch to track height; wrap widget root in `<section class="fi-wi-widget" style="display: flex; height: 100%; width: 100%;">` to guarantee exact height alignment with default widgets like `AccountWidget`.
- **SCHEMA PROTOCOL**: If saving a post fails with 500, verify the `subtitle` column exists in the `posts` table using `verify_subtitle_field.php`.
- **CUSTOM UI SAFETY**: In custom Filament Blade pages (like **Monitor**), never use `<x-filament-actions::action />`. Always use `wire:click="mountAction('name')"` to prevent "Undefined variable $dynamicComponent" crashes.
- **DEPLOYMENT**: Always use `./upgrade_backend_vps.sh` for deployments; it contains the mandatory permission enforcement logic.
- **FILAMENT GRID LAYOUT SAFETY**: Never chain `->padding()` on a `Stack` Layout component in v3 Grids. Doing so triggers a fatal `BadMethodCallException` (500 Error). Use `->extraAttributes(['class' => 'p-4 flex flex-col h-full'])` instead.
- **FILAMENT UPLOAD DEHYDRATION**: Never attach `->dehydrated(false)` to a `FileUpload` component if you rely on the filepath in `mutateFormDataBeforeSave` or `mutateFormDataBeforeCreate`. Filament completely drops dehydrated fields from the payload map before those hooks execute. If it's not a DB column, manually `unset()` the key inside the hook instead.
- **FRONTEND MEDIA ASSETS**: Any `ImageColumn` or preview resolving uploaded media directly to the public web root must strictly use `->disk('webapp_public')`. Using the default `disk('public')` forces a `/storage/` URL injection which fundamentally breaks all native `/uploads/...` image previews across this infrastructure.
- **MEDIA CREATION SCHEMA**: When manually generating `Media` records via Eloquent (e.g. inside `mutateFormDataBeforeSave`), you MUST explicitly inject the full required field set: `type` ('image'), `path` (the disk path), `url` (the public path), `name` (the file basename), `extension`, and `mimetype`. The `medias` table STRICTLY requires these definitions and lacks default values; omitting any triggers a fatal SQL 1364 Exception (500 Error).
12: - **EDITORIAL UI PROTOCOL**: The `PostResource` form follows a **Newsroom Layout** (2-column Split). Writing Desk (Headline/Editor) stays in the wide main column; Metadata (Status/Media/Categories) stays in the sidebar. Character counts for Headlines (75 max recommended) and Meta descriptions (160 max) must be visible.
13: - **SMART AUDIT PROTOCOL**: Do not use spammy notifications for grammar/content quality. Collect all suggestions into the `audit_results` field within the **Quality Audit** section. This section should only be visible/expanded if errors are present.
- **AUTH & SECURITY PROTOCOL**:
  - **MAIL**: Gmail SMTP is mandatory for all system notifications (2FA, Password Reset, Verification).
  - **TOKENS**: Password reset tokens must be hashed in the database. The raw token is sent only in the email.
  - **VERIFICATION**: Email verification uses the `/verify-email` frontend route. URL parameters must include `token` and `email`.
  - **ENUMERATION**: Always return generic success messages for password reset requests to prevent user enumeration.
- **MULTI-LANGUAGE PROTOCOL**:
  - The frontend supports **Bengali** and **English**. 
  - UI components (Breadcrumbs, Toggles) must support i18n switching.

## 📌 Project Architecture (Updated April 11, 2026)
- **Domain**: `newsthetruth.com` (Managed via **GoDaddy**).
- **Frontend (UI)**: Next.js 15 (React 19) hosted on **Vercel** (`newsthetruth.com`).
- **Backend (API & Admin)**: Laravel 11 (PHP 8.2) hosted on VPS with its own subdomain: **`https://backend.newsthetruth.com`**.
- **Security**: Raw IP `117.252.16.132` is masked. SSL is active on the backend.

## 🛠 Verified Environment & Tools
- **PHP 8.2**: `gd` extension active.
- **SSH Key**: `C:\Users\HPi9\.ssh\id_ed25519_ntt`
- **VPS Host**: `backend.newsthetruth.com` (117.252.16.132)
- **OFFICIAL BACKUPS**: 
  - **Full Snapshot**: `/root/backups/NTT_BACKUP_2026.tar.gz` (11GB, Codebase & Media, April 13).
  - **Database (Fresh)**: `/root/backups/ntt_db_latest.sql.gz` (29MB Compressed, April 14).
  - **Local Sync**: `D:\NTT_WEBSITE\backups\ntt_db_latest_20260414.sql.gz`.

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

### June 25, 2026: Tri-Attribution Decoupling & Dashboard Quick Create Action Button
- **Tri-Attribution Decoupling**: Solved critical `user_id` hijacking bug where saving a post attributed to a reporter overwritten the admin author ID. Implemented `published_by` tracking (`Post::saving` hook + `CreatePost.php`) to safely record admin/editor accountability while leaving `user_id` untouched for frontend API display. Backfilled 4,808 existing records.
- **Dashboard Quick Create Button**: Designed and deployed prominent solid crimson red **`+ CREATE ARTICLE`** action button widget (`QuickCreatePostWidget`) directly onto the Filament Dashboard next to `AccountWidget`.
- **Filament Grid Stretch Alignment**: Fixed widget grid alignment by structuring widget markup inside `<section class="fi-wi-widget" style="display: flex; height: 100%; width: 100%;">` to guarantee exact CSS Grid height stretch matching default widgets.

### April 17, 2026: Production Auth Verification Finalization
- **Verification Link Alignment**: Resolved a 404 error on staff email verification by synchronizing backend link generation with the frontend `/verify-email` route.
- **Staff Verification Lifecycle**: Verified the complete end-to-end flow from Admin trigger to staff email receipt and successful token validation on the production server.

### April 15, 2026: Authentication Security Hardening & Mail Migration
- **Gmail SMTP Integration**: Migrated the email infrastructure to Gmail SMTP to resolve persistent delivery failures for authentication notifications.
- **Security Hardening**: Implemented token-hashing for password resets and enforced user-enumeration prevention strategies (silent failure messages).
- **Staff Onboarding Control**: Integrated a manual 2FA-onboarding system and admin-only verification triggers directly into the Filament Staff Resource dashboard.

### April 14, 2026: Database Integrity & Comprehensive Backup
- **Missing Data Resolution**: Identified that April 13 snapshots were codebase-focused and missing the latest articles/tables.
- **Final DB Backup**: Generated a fresh `mysqldump` (123MB raw, 29MB compressed) on the VPS and synced it locally. This ensures 100% protection for all articles, users, and media metadata.
- **Context Hardening**: Consolidated all backup paths and system protocols into this master file for immediate clarity in next sessions.

### April 13, 2026: Subtitle & Attribution Stability Fixes. Corrected permission protocol (`nginx` -> `apache`) and fixed article attribution glitch by patching `Post` model and adding sync logic to `PostResource`. Resolved fatal 500 exceptions in Filament Grids caused by invalid `Stack::padding()` chaining. Restored broken Media Upload flow by exposing `dehydrated` lifecycle paths, enforcing `webapp_public` disk mapping, and satisfying strict SQL schema constraints (providing full Media field set: type, path, url, name, extension, mimetype) to prevent 1364 SQL errors.

### April 13, 2026 (17:45 IST): Post-Modern Editorial Transformation.
- **Newsroom Layout**: Re-engineered the "New Post" page into a professional 2-column split layout (2/3 Writing Area, 1/3 Sidebar).
- **Consolidated Smart Audit**: Eliminated spammy toast notifications by implementing a persistent, collapsible "Quality Audit" section that aggregates grammar and spelling suggestions.
- **UX Precision**: Integrated live character counters for headlines and SEO metadata, and added contextual icons to all editorial sections.
- **Protocol Update**: Documented the "Newsroom UI Protocol" and "Smart Audit Protocol" in the master context to ensure design consistency in future sessions.

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

*Last Updated: June 25, 2026 19:10 IST*
