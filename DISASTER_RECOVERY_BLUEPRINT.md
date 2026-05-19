# News The Truth (NTT) - Disaster Recovery & Architectural Blueprint

> [!CAUTION]
> **Keep this document safe.** It contains the architectural map of your entire platform. If your local computer fails, use this document to restore your development environment and understand the production setup.

## 1. Codebase Repositories

Your full codebase is successfully secured on GitHub across two main repositories. Both repositories use Personal Access Token (PAT) authentication.

### Frontend Repository
- **URL:** `https://github.com/newsthetruthindia/NTT-FINAL-.git`
- **Tech Stack:** Next.js, React, TypeScript, Tailwind CSS
- **Local Path:** `d:\ntt-frontend`
- **Hosting:** Vercel (Configured via `vercel.json` and `.env.vercel`)
- **Key Environment Variables (from `.env.vercel`):**
  - `NEXT_PUBLIC_API_URL`: `https://newsthetruth.com/api`
  - `NEXT_PUBLIC_SITE_URL`: `https://newsthetruth.com`

### Backend / Server Repository
- **URL:** `https://github.com/newsthetruthindia/newsthetruth.git`
- **Tech Stack:** Laravel (PHP), MySQL, Nginx
- **Local Path:** `d:\NTT_LOCAL_SERVER`
- **DevOps Scripts:** All critical deployment and diagnostic scripts previously stored in `d:\NTT_WEBSITE` have been backed up into the `devops_scripts/` directory within this backend repository.

---

## 2. Server Architecture & Configuration

### Production Server (VPS)
- **Web Server:** Nginx (Configuration reference: `ntt_nginx.conf` and `vps_configs/ntt_vps.conf`)
- **Database:** MySQL
  - **Database Name:** `newstew1_main`
  - **User:** `newstew1_newsthet`
- **Cache / Sessions:** File-based driver for sessions and cache, queue connection is `database`.
- **Mail Services:** Configured via Google SMTP (`newsthetruthindia@gmail.com`).

### Deployment Workflow
The platform uses custom scripts to synchronize the local environment and the production server.
- **Frontend:** Automatically built and deployed via Vercel when pushed to the GitHub repository.
- **Backend:** Managed via FTP and SSH scripts found in `devops_scripts/` (e.g., `push_backend.ps1`, `vps_sync_final.sh`, `check_ftp.ps1`).

---

## 3. Disaster Recovery Plan

If your local computer malfunctions, follow these exact steps to rebuild your environment on a new machine:

### Step 1: Install Prerequisites
1. Install **Git**.
2. Install **PHP (>= 8.1)** and **Composer** (for Laravel).
3. Install **Node.js** and **npm** (for Next.js).
4. Install **MySQL** locally.

### Step 2: Restore Backend (Laravel)
1. Clone the backend repository:
   ```bash
   git clone https://github.com/newsthetruthindia/newsthetruth.git NTT_LOCAL_SERVER
   cd NTT_LOCAL_SERVER
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Copy the `.env.example` to `.env` (or recover your `.env` from a secure vault) and fill in the DB and SMTP credentials.
4. Set the Application Key:
   ```bash
   php artisan key:generate
   ```
5. Restore the database from your latest `.sql` dump (e.g., `ntt_codebase.tar.gz` contents or live backups).

### Step 3: Restore Frontend (Next.js)
1. Clone the frontend repository:
   ```bash
   git clone https://github.com/newsthetruthindia/NTT-FINAL-.git ntt-frontend
   cd ntt-frontend
   ```
2. Install dependencies:
   ```bash
   npm install
   ```
3. Set up the `.env.local` file with the required `NEXT_PUBLIC_API_URL`.
4. Run the development server:
   ```bash
   npm run dev
   ```

### Step 4: Recover DevOps Scripts
Your deployment scripts, server diagnostics, and FTP configuration files are safely stored in the `devops_scripts/` folder inside the `NTT_LOCAL_SERVER` repository. If you need to deploy a patch, use the `.ps1` or `.sh` scripts located there.

> [!IMPORTANT]
> Always ensure your `.env` files are added to `.gitignore` and never committed to GitHub. Keep a secure, off-site backup of your production `.env` files and large database `.sql` dumps, as GitHub cannot host files larger than 100MB.
