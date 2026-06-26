# Re-Engineering MVPs — Step by Step Runbook
### By Crosstech Solutions
### Product: CrossFlow 

This document captures every step taken to take a nulled/downloaded Laravel-based
product and get it fully running locally. Use this as a repeatable runbook for any
similar PHP/Laravel stack product.

---

## Stack This Applies To

- PHP 8.x (Laravel 10/11)
- MySQL 8.x or MariaDB 10.6+
- Composer 2.x
- Windows machine with PowerShell

---

## Phase 1: Understand the Product

### Step 1.1 — Read the folder structure

Before touching anything, understand what you have:

```
Files/              → Public web root (contains index.php)
Files/core/         → Laravel application
Files/install/      → Web installer + database.sql
Files/assets/       → Public CSS/JS/images
Documentation/      → Original product docs
```

Key files to read first:
- `Files/index.php` — entry point
- `Files/core/bootstrap/app.php` — Laravel bootstrap, middleware, routes
- `Files/core/.env.example` — environment config template
- `Files/install/database.sql` — full schema + seed data
- `Files/core/routes/web.php`, `admin.php`, `user.php` — all routes

### Step 1.2 — Identify license/activation code

Look for these patterns in the codebase:

```bash
# Search for license-related classes
rg "mdNm|checkProject|activate|purchasecode|lcLabRoute|Onumoti|VugiChugi" --include="*.php"
```

Common locations in vendor-wrapped Laravel products:
- `vendor/laramin/utility/src/VugiChugi.php`
- `vendor/laramin/utility/src/Onumoti.php`
- `vendor/laramin/utility/src/UtilityServiceProvider.php`
- `bootstrap/app.php` (middleware wrapping)
- `app/Http/Controllers/Controller.php` (base controller)
- `app/Http/Controllers/Admin/Auth/LoginController.php`

---

## Phase 2: Install Required Software

### Step 2.1 — Install MySQL

1. Download MySQL Installer from https://dev.mysql.com/downloads/installer/
2. Choose **Custom** setup type
3. Select:
   - MySQL Server 8.0.x - X64
   - MySQL Workbench 8.0.x - X64
4. Configuration:
   - Config Type: **Development Computer**
   - TCP/IP: **checked**, Port: **3306**
   - Authentication: **Strong Password Encryption (recommended)**
   - Set a root password — **write it down, you'll need it**
   - Windows Service Name: MySQL80, Start at startup: checked
5. Click Execute to apply

### Step 2.2 — Install PHP

Option A — WinGet (quick):
```powershell
winget install PHP.PHP.8.5
```

Option B — Manual:
1. Download PHP 8.3+ NTS x64 zip from https://windows.php.net/download/
2. Extract to `C:\php`

### Step 2.3 — Install Composer

Download and run installer from https://getcomposer.org/download/
It auto-detects PHP and adds itself to PATH.

### Step 2.4 — Add PHP and MySQL to system PATH

1. Search "Environment Variables" in Start menu
2. System Properties → Environment Variables → System Variables → Path → Edit
3. Add New entries:
   - PHP path (e.g. `C:\Users\YourName\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.5_...`)
   - MySQL bin path: `C:\Program Files\MySQL\MySQL Server 8.0\bin`
4. Click OK on all dialogs
5. **Restart your terminal/IDE** for PATH to take effect

### Step 2.5 — Verify all tools

```powershell
php -v
composer -V
mysql --version
```

All three must return version info, no errors.

---

## Phase 3: Enable Required PHP Extensions

### Step 3.1 — Check what's enabled

```powershell
php -m
```

### Step 3.2 — Check what's missing for Laravel

```powershell
php -r "
echo extension_loaded('pdo_mysql') ? 'pdo_mysql: OK' : 'pdo_mysql: MISSING';
echo PHP_EOL;
echo extension_loaded('gd') ? 'gd: OK' : 'gd: MISSING';
echo PHP_EOL;
echo extension_loaded('zip') ? 'zip: OK' : 'zip: MISSING';
echo PHP_EOL;
echo extension_loaded('fileinfo') ? 'fileinfo: OK' : 'fileinfo: MISSING';
"
```

Required extensions for Laravel:
- bcmath, ctype, curl, dom, fileinfo, gd, json, mbstring, openssl,
  pcre, pdo, pdo_mysql, tokenizer, xml, zip

### Step 3.3 — Enable missing extensions in php.ini

Find php.ini location:
```powershell
php --ini
```

Edit the file and uncomment (remove the `;`) these lines:
```ini
extension=pdo_mysql
extension=gd
extension=zip
extension=fileinfo
```

Or do it with PowerShell (replace path with your php.ini path):
```powershell
$phpini = "C:\path\to\php.ini"
(Get-Content $phpini) `
  -replace ';extension=pdo_mysql', 'extension=pdo_mysql' `
  -replace ';extension=gd', 'extension=gd' `
  -replace ';extension=zip', 'extension=zip' `
  -replace ';extension=fileinfo', 'extension=fileinfo' |
Set-Content $phpini
```

Also increase max execution time to avoid timeout errors during debugging:
```ini
max_execution_time = 120
```

---

## Phase 4: Create the Database

### Step 4.1 — Create empty database

In MySQL Workbench:
1. Connect to Local instance MySQL80
2. Open new query tab (Ctrl+T)
3. Run:

```sql
CREATE DATABASE crossflow;
```

Replace `crossflow` with your product's database name.

---

## Phase 5: Configure the Application

### Step 5.1 — Set up .env file

Copy `.env.example` to `.env` and configure:

```env
APP_NAME=CrossFlow
APP_ENV=local
APP_KEY=                        # generated in next step
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crossflow
DB_USERNAME=root
DB_PASSWORD=your_mysql_root_password

SESSION_DRIVER=file             # use file, not database (avoids missing sessions table)
CACHE_STORE=file                # use file, not database (avoids missing cache table)
```

### Step 5.2 — Import the database schema

```powershell
Get-Content "Files\install\database.sql" | mysql -u root -pYOURPASSWORD crossflow
```

Note: Use `Get-Content | mysql` on Windows PowerShell instead of `<` redirect
because PowerShell does not support `<` for stdin.

### Step 5.3 — Generate app key

```powershell
php artisan key:generate
```

Run from `Files/core/` directory.

---

## Phase 6: Remove License/Activation Code

This is the most critical phase. Nulled products often have vendor packages that:
- Check a license server on every request (causes 30s timeouts)
- Block the app if license is invalid
- Redirect to activation pages

### Step 6.1 — Remove middleware wrapping from bootstrap/app.php

Look for this pattern in `Files/core/bootstrap/app.php`:

```php
use Laramin\Utility\VugiChugi;

Route::namespace('App\Http\Controllers')
    ->middleware([VugiChugi::mdNm()])  // <-- remove this middleware call
    ->group(function(){ ... });
```

Fix:
```php
// Remove the use statement for VugiChugi
// Change middleware([VugiChugi::mdNm()]) to just no middleware or []
Route::namespace('App\Http\Controllers')->group(function(){ ... });
```

### Step 6.2 — Gut the UtilityServiceProvider

File: `vendor/laramin/utility/src/UtilityServiceProvider.php`

Remove all lines that reference `mdNm()`, `pshMdl()`, `pshMdlGrp()`, middleware
registration, and external URL calls. Keep only:

```php
public function boot(\Illuminate\Contracts\Http\Kernel $mastor) {
    $ldRt = VugiChugi::ldRt();
    $this->$ldRt(__DIR__.'/routes.php');
    $this->loadViewsFrom(__DIR__.'/Views', 'Utility');
}
```

### Step 6.3 — Gut Onumoti.php

File: `vendor/laramin/utility/src/Onumoti.php`

This class typically has two dangerous methods:

**getData()** — makes an outbound HTTP request to the license server on every
admin login. This is what causes the 30-second hang.

**mySite()** — calls `mdNm()` which is the deleted method, causing fatal errors.

Replace the entire class body with:

```php
class Onumoti {
    public static function getData() {
        // license check removed
    }

    public static function mySite($site, $className) {
        // license check removed
    }
}
```

### Step 6.4 — Remove mdNm() from VugiChugi.php

File: `vendor/laramin/utility/src/VugiChugi.php`

Delete the `mdNm()` method entirely:

```php
// DELETE THIS:
public static function mdNm(){
    $variab = self::variab();
    return $variab('purpxCebwrpg');
}
```

### Step 6.5 — Search for any remaining mdNm() calls

```powershell
Select-String -Path "Files\core\*" -Pattern "mdNm" -Recurse
```

Fix any remaining references.

### Step 6.6 — Disable external captcha and third-party extensions

If the app has Google reCaptcha or similar extensions that make outbound calls:

```sql
UPDATE extensions SET status = 0 WHERE act = 'google-recaptcha2';
```

---

## Phase 7: Fix the Admin Password

The seeded admin password hash may not match a known password.
Use a PHP script to reset it safely (avoids shell escaping issues with `$`):

Create a temporary file `reset_admin.php`:

```php
<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=crossflow', 'root', 'YOUR_PASSWORD');
$hash = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
$stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = 'admin'");
$stmt->execute([$hash]);
echo "Done. Hash: " . $hash . "\n";
echo "Rows affected: " . $stmt->rowCount() . "\n";
```

Run it:
```powershell
php reset_admin.php
```

Delete it after:
```powershell
Remove-Item reset_admin.php
```

---

## Phase 8: Start the Server

```powershell
php -S 127.0.0.1:8000 -t "Files"
```

Run from the project root (parent of `Files/`).

Open browser:
- Frontend: `http://127.0.0.1:8000`
- Admin panel: `http://127.0.0.1:8000/admin`

---

## Phase 9: Clear All Caches After Every Fix

Run this after any code or config change:

```powershell
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

Also delete bootstrap cache if vendor files were changed:
```powershell
Remove-Item "Files\core\bootstrap\cache\*.php" -Force
```

---

## Common Errors and Fixes

| Error | Cause | Fix |
|-------|-------|-----|
| `Call to undefined method VugiChugi::mdNm()` | mdNm() was deleted but still called | Find all callers with grep, remove all calls |
| `Maximum execution time of 30 seconds exceeded` | Outbound license/captcha HTTP call hanging | Gut getData() in Onumoti.php, disable recaptcha extension |
| `Table 'db.cache' doesn't exist` | SESSION_DRIVER or CACHE_STORE set to database | Change both to `file` in .env |
| `Table 'db.sessions' doesn't exist` | SESSION_DRIVER=database, table missing | Change SESSION_DRIVER to `file` in .env |
| `This password does not use the Bcrypt algorithm` | Shell escaping corrupted the `$` in bcrypt hash | Use PHP script to reset password, never use shell string with `$` in password hash |
| `These credentials do not match our records` | Wrong URL — user login vs admin login | Admin panel is at `/admin`, not `/` |
| Extensions missing (pdo_mysql, gd, zip, fileinfo) | Not enabled in php.ini | Uncomment in php.ini, restart server |

---

## Checklist Summary

- [ ] Read folder structure and identify Laravel version
- [ ] Find all license/activation vendor files
- [ ] Install PHP 8.3+, MySQL 8+, Composer
- [ ] Add PHP and MySQL to system PATH
- [ ] Enable pdo_mysql, gd, zip, fileinfo in php.ini
- [ ] Create empty database
- [ ] Configure .env (DB, SESSION_DRIVER=file, CACHE_STORE=file)
- [ ] Import database.sql
- [ ] Generate app key (`php artisan key:generate`)
- [ ] Remove mdNm() from VugiChugi.php
- [ ] Gut UtilityServiceProvider.php
- [ ] Gut Onumoti.php (getData and mySite)
- [ ] Remove middleware wrapping in bootstrap/app.php
- [ ] Disable recaptcha/external extensions in DB
- [ ] Reset admin password via PHP script
- [ ] Clear all caches
- [ ] Start PHP dev server
- [ ] Verify login at /admin works

---

## Notes for Next Product

- Always check `bootstrap/app.php` first for middleware wrapping
- Always check `app/Http/Controllers/Controller.php` base class for license hooks
- Always check `Admin/Auth/LoginController.php` for getData() calls
- The `SESSION_DRIVER=file` and `CACHE_STORE=file` trick saves hours of debugging
- Never try to set bcrypt hashes via shell — always use a PHP script
- Use `Select-String -Recurse -Pattern "mdNm|checkProject|getData"` to hunt remaining hooks
- Increase `max_execution_time` in php.ini temporarily to see real errors instead of timeouts

---

*Document created by Crosstech Solutions — CrossFlow Project*
