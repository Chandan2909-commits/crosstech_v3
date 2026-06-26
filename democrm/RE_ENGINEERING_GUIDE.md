# PropLab Reverse Engineering Guide

This guide explains how to analyze this application, understand how it works, and rebuild a clean product from the same architectural ideas. It is written for learning and product planning. Do not copy protected branding, proprietary code, licensed vendor modules, or activation/license controls into a commercial product.

## 1. What This Application Is

The project is a Laravel-based proprietary/funded trading platform, not a traditional CRM.

Core product domain:

- Users register and complete verification.
- Users buy funded trading plans.
- Plans assign virtual trading capital.
- Users place buy/sell orders for crypto/fiat pairs.
- Cron jobs match orders, calculate profit/loss, update phases, and expire plans.
- Admin manages users, plans, orders, payments, withdrawals, KYC, tickets, pages, settings, and notifications.

CRM-like parts already present:

- User management
- KYC/customer verification
- Support ticketing
- Subscriber list
- Notifications by email/SMS/push
- Admin dashboard
- Reports
- CMS/page builder
- System settings

## 2. Folder Structure

Project root:

```text
PropLab v1.0 Nulled/
  Documentation/
  Files/
  Download More Nulled Scripts.html
  RE_ENGINEERING_GUIDE.md
```

Important application paths:

```text
Files/index.php                         Public entry point
Files/install/index.php                 Browser installer
Files/install/database.sql              Full database schema and seed data
Files/core/                             Laravel application
Files/core/app/                         Models, controllers, libs, middleware
Files/core/routes/                      Route definitions
Files/core/resources/views/             Blade templates
Files/core/config/                      Laravel config files
Files/core/vendor/                      Composer dependencies
Files/assets/                           Public CSS, JS, fonts, images
```

Important route files:

```text
Files/core/routes/web.php               Public site, trading page, support ticket routes
Files/core/routes/user.php              User login, dashboard, plan, order, wallet, withdrawal routes
Files/core/routes/admin.php             Admin dashboard, users, plans, payments, reports, settings
Files/core/routes/ipn.php               Payment gateway callbacks
Files/core/routes/console.php           Artisan console routes
```

## 3. Technology Specifications

Backend:

- PHP 8.3+
- Laravel 11
- Composer
- MySQL 8.0+ or MariaDB 10.6+

Frontend:

- Blade templates
- Bootstrap-style CSS
- jQuery
- Vite available through `package.json`
- Prebuilt public assets under `Files/assets`

Major Composer packages:

- `laravel/framework`
- `laravel/sanctum`
- `laravel/socialite`
- `pusher/pusher-php-server`
- `intervention/image`
- `guzzlehttp/guzzle`
- Payment SDKs for Stripe, Razorpay, PayPal, Paystack, Mollie, Coinbase, CoinPayments, BTCPay, etc.
- Notification SDKs for Twilio, Vonage, SendGrid, Mailjet, MessageBird

## 4. Important Finding: Route Wrapper

`Files/core/bootstrap/app.php` contains this import:

```php
use Laramin\Utility\VugiChugi;
```

The route groups are wrapped like this:

```php
Route::namespace('App\Http\Controllers')->middleware([VugiChugi::mdNm()])->group(function(){
    ...
});
```

The package files exist under:

```text
Files/core/vendor/laramin/utility/
```

For reverse engineering, document this as a vendor dependency. Do not remove, bypass, or clone license/activation logic. For your own CRM, create a fresh Laravel app and use normal Laravel middleware instead.

## 5. How To Start The Existing App Locally

This machine currently does not expose `php` or `composer` in PATH, so the app cannot be started until PHP is installed or configured.

Required local setup:

1. Install PHP 8.3+.
2. Enable required extensions:
   - BCMath
   - Ctype
   - cURL
   - DOM
   - Fileinfo
   - GD
   - JSON
   - Mbstring
   - OpenSSL
   - PCRE
   - PDO
   - pdo_mysql
   - Tokenizer
   - XML
   - Filter
   - Hash
   - Session
   - Zip
3. Install MySQL 8+ or MariaDB 10.6+.
4. Create an empty database.
5. Serve the `Files` directory as the public web root.

Using PHP's built-in server:

```powershell
cd "C:\Users\Chandan\Downloads\PropLab v1.0 Nulled\PropLab v1.0 Nulled"
php -S 127.0.0.1:8000 -t Files
```

Then open:

```text
http://127.0.0.1:8000/install/index.php
```

Admin URL after installation:

```text
http://127.0.0.1:8000/admin
```

Production-style hosting:

- Upload contents of `Files` to web root.
- Ensure `Files/index.php` is the public entry.
- Ensure `Files/core/storage` and cache paths are writable.
- Run the installer from `/install/index.php`.

## 6. Main Data Model

The full schema is in:

```text
Files/install/database.sql
```

Core identity tables:

- `admins`
- `users`
- `user_logins`
- `device_tokens`
- `password_resets`
- `admin_password_resets`

Trading/product tables:

- `plans`
- `plan_phases`
- `phase_logics`
- `logic_boxes`
- `plan_histories`
- `user_phases`
- `phase_logs`
- `user_profit_losses`
- `orders`
- `trades`
- `wallets`
- `currencies`
- `markets`
- `coin_pairs`
- `market_data`
- `favorite_pairs`
- `bot_configs`

Payment/finance tables:

- `deposits`
- `withdrawals`
- `withdraw_methods`
- `transactions`
- `gateways`
- `gateway_currencies`

CRM-like/support tables:

- `support_tickets`
- `support_messages`
- `support_attachments`
- `subscribers`
- `notification_templates`
- `notification_logs`

CMS/settings tables:

- `general_settings`
- `frontends`
- `pages`
- `languages`
- `extensions`
- `forms`
- `cron_jobs`
- `cron_schedules`
- `cron_job_logs`
- `update_logs`

## 7. Request Flow

High-level HTTP flow:

```text
Browser
  -> Files/index.php
  -> Files/core/bootstrap/app.php
  -> routes/web.php, routes/user.php, routes/admin.php, routes/ipn.php
  -> Controller
  -> Model/Lib
  -> Blade view or JSON response
```

Public visitor flow:

```text
Home page
  -> market pages / crypto pages / content pages
  -> register or login
  -> user dashboard
```

User flow:

```text
Register/Login
  -> complete user data
  -> email/mobile/2FA authorization if enabled
  -> buy plan
  -> deposit/payment
  -> wallet funded
  -> place order
  -> order matched by cron
  -> trade/transaction records created
  -> plan progress calculated
```

Admin flow:

```text
Admin login
  -> dashboard
  -> manage users
  -> manage plans and logic boxes
  -> manage orders/trades
  -> manage deposits/withdrawals
  -> manage support tickets
  -> configure gateways/notifications/settings
  -> manage frontend pages/content
```

## 8. Core Business Logic Files

Plan purchase:

```text
Files/core/app/Http/Controllers/User/PlanController.php
Files/core/app/Models/Plan.php
Files/core/app/Models/PlanHistory.php
```

Order placement:

```text
Files/core/app/Http/Controllers/User/OrderController.php
Files/core/app/Models/Order.php
```

Trade page and public trading API:

```text
Files/core/app/Http/Controllers/TradeController.php
Files/core/resources/views/templates/basic/trade/
```

Trade matching:

```text
Files/core/app/Lib/TradeManager.php
```

Cron automation:

```text
Files/core/app/Http/Controllers/CronController.php
```

Admin users:

```text
Files/core/app/Http/Controllers/Admin/ManageUsersController.php
Files/core/resources/views/admin/users/
```

Admin plans:

```text
Files/core/app/Http/Controllers/Admin/ManagePlanController.php
Files/core/app/Http/Controllers/Admin/LogicBoxController.php
Files/core/resources/views/admin/plan/
```

Admin navigation:

```text
Files/core/resources/views/admin/partials/sidenav.json
Files/core/resources/views/admin/partials/sidenav.blade.php
```

Settings:

```text
Files/core/app/Http/Controllers/Admin/GeneralSettingController.php
Files/core/resources/views/admin/setting/
```

Support:

```text
Files/core/app/Http/Controllers/TicketController.php
Files/core/app/Http/Controllers/Admin/SupportTicketController.php
Files/core/resources/views/admin/support/
Files/core/resources/views/templates/basic/user/support/
```

## 9. How To Reverse Engineer It Systematically

Use this workflow.

### Step 1: Inventory

List all files except dependency/build folders:

```powershell
rg --files -g '!vendor' -g '!node_modules' -g '!storage/logs'
```

Capture:

- Routes
- Controllers
- Models
- Blade views
- Database tables
- Cron jobs
- Public assets
- Config files

### Step 2: Map Routes To Features

Start with these files:

```text
Files/core/routes/web.php
Files/core/routes/user.php
Files/core/routes/admin.php
Files/core/routes/ipn.php
```

For each route, record:

- URL
- HTTP method
- Controller method
- Middleware
- View returned
- Database tables touched

Example:

```text
Route: user/plan
Controller: User\PlanController@list
View: Template::user.plan.list
Tables: plans, plan_phases, phase_logics, logic_boxes
Purpose: show active funded-trading plans
```

### Step 3: Map Models To Tables

For every file in `Files/core/app/Models`, identify:

- Table name
- Relationships
- Scopes
- Accessors
- Business methods

Example:

```text
Model: App\Models\Order
Table: orders
Relationships:
  - belongsTo User
  - belongsTo CoinPair
  - hasMany Trade
Scopes:
  - open
  - completed
  - canceled
  - buySideOrder
  - sellSideOrder
```

### Step 4: Follow One Complete Feature End-To-End

For plan purchase:

```text
routes/user.php
  -> PlanController@buy
  -> PlanController@savePlan
  -> Gateway\PaymentController
  -> Plan::subscribe
  -> PlanHistory, Wallet, Transaction
  -> notification templates
  -> user dashboard
```

For order matching:

```text
routes/web.php / trade routes
  -> User\OrderController@save
  -> Order model
  -> Wallet debit
  -> Transaction record
  -> CronController@trade
  -> TradeManager::trade
  -> Trade records
  -> Wallet credit
  -> notification
```

### Step 5: Document Screens

For each admin/user screen, document:

- Blade file
- Controller method
- Route name
- Form fields
- Validation rules
- Actions/buttons
- Tables updated

### Step 6: Separate Generic Platform From Trading Domain

Generic platform pieces:

- Auth
- Admin layout
- User layout
- Settings
- Notifications
- Support tickets
- File uploads
- Reports
- CMS pages
- Language manager

Trading-specific pieces:

- Plans
- Logic boxes
- Phase logic
- Wallets
- Currencies
- Markets
- Coin pairs
- Orders
- Trades
- Bot config
- Profit/loss cron

For your CRM, keep the generic platform ideas and replace the trading domain.

## 10. How To Rebuild This As Your Own CRM

Recommended approach: create a fresh Laravel project and rebuild the CRM modules cleanly.

Do not base your commercial product directly on the nulled code. Use this project as a learning reference only.

### New CRM Domain Model

Create these tables:

```text
companies
contacts
leads
deals
pipelines
pipeline_stages
activities
tasks
notes
attachments
lead_sources
custom_fields
custom_field_values
tags
taggables
```

Optional SaaS tables:

```text
tenants
teams
team_user
subscriptions
plans
invoices
payments
audit_logs
webhooks
integrations
```

### CRM Table Responsibilities

`companies`:

- Business/customer organization
- Fields: name, website, industry, size, owner_id, status

`contacts`:

- People inside companies
- Fields: company_id, first_name, last_name, email, phone, role, owner_id

`leads`:

- Unqualified opportunities
- Fields: name, email, phone, source_id, status, score, owner_id

`deals`:

- Sales opportunities
- Fields: company_id, contact_id, pipeline_id, stage_id, title, value, probability, expected_close_date, status, owner_id

`pipeline_stages`:

- Kanban stages such as New, Qualified, Proposal, Negotiation, Won, Lost

`activities`:

- Calls, emails, meetings, WhatsApp messages, demos

`tasks`:

- Follow-ups and reminders

`notes`:

- Internal notes attached to leads, deals, companies, or contacts

`attachments`:

- Uploaded files attached to CRM records

## 11. File Mapping: What To Replace

Replace trading modules:

```text
Old: PlanController
New: ProductPlanController or SubscriptionPlanController

Old: ManagePlanController
New: PipelineController / StageController

Old: OrderController
New: DealController / ActivityController

Old: TradeController
New: CRM dashboard / LeadController / DealKanbanController

Old: TradeManager
New: LeadAssignmentService / DealAutomationService

Old: WalletController
New: InvoiceController / PaymentController if needed

Old: CoinPairController
New: ProductController or ServiceController

Old: MarketController
New: SegmentController / TerritoryController
```

Keep or rebuild similar modules:

```text
ManageUsersController        Keep concept, adapt to team/customer users
SupportTicketController      Keep concept
NotificationController       Keep concept
SubscriberController         Keep concept
FrontendController           Keep concept if your CRM has marketing pages
GeneralSettingController     Keep concept
ReportController             Adapt to CRM reports
```

## 12. Suggested CRM Routes

Admin routes:

```php
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('companies', CompanyController::class);
    Route::resource('contacts', ContactController::class);
    Route::resource('leads', LeadController::class);
    Route::resource('deals', DealController::class);
    Route::resource('pipelines', PipelineController::class);
    Route::resource('tasks', TaskController::class);

    Route::get('deals/kanban/{pipeline}', [DealKanbanController::class, 'index'])->name('deals.kanban');
    Route::post('deals/{deal}/move', [DealKanbanController::class, 'move'])->name('deals.move');
    Route::post('leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');
});
```

User/team routes:

```php
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('my-leads', [LeadController::class, 'mine'])->name('leads.mine');
    Route::get('my-deals', [DealController::class, 'mine'])->name('deals.mine');
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
});
```

## 13. Suggested CRM Controllers

Minimum controllers:

```text
Admin/DashboardController.php
Admin/CompanyController.php
Admin/ContactController.php
Admin/LeadController.php
Admin/DealController.php
Admin/PipelineController.php
Admin/DealKanbanController.php
Admin/TaskController.php
Admin/ActivityController.php
Admin/ReportController.php
Admin/SettingController.php
```

Service classes:

```text
app/Services/LeadConversionService.php
app/Services/LeadAssignmentService.php
app/Services/DealStageService.php
app/Services/CRMNotificationService.php
app/Services/ReportBuilderService.php
```

## 14. Suggested CRM Screens

Admin:

- Dashboard
- Companies
- Contacts
- Leads
- Deals pipeline
- Tasks
- Activities
- Support tickets
- Reports
- Team/users
- Settings
- Notifications

User/salesperson:

- My dashboard
- My leads
- My deals
- My tasks
- Calendar/follow-ups
- Support tickets
- Profile/settings

Deal detail page:

- Deal summary
- Company and contact
- Value and expected close date
- Pipeline stage
- Activity timeline
- Notes
- Files
- Tasks
- Email/SMS/WhatsApp logs

## 15. CRM Admin Menu Replacement

Replace trading entries in:

```text
Files/core/resources/views/admin/partials/sidenav.json
```

Old menu items to remove:

- Manage Plans
- Bot Configure
- Manage Order
- Deposits if not needed
- Withdrawals if not needed
- Currency/Market/Coin Pair settings

New menu items:

```json
{
  "dashboard": {
    "title": "Dashboard",
    "icon": "las la-home",
    "route_name": "admin.dashboard",
    "menu_active": "admin.dashboard"
  },
  "crm": {
    "title": "CRM",
    "icon": "las la-briefcase",
    "menu_active": "admin.crm*",
    "submenu": [
      {
        "title": "Companies",
        "route_name": "admin.companies.index",
        "menu_active": "admin.companies*"
      },
      {
        "title": "Contacts",
        "route_name": "admin.contacts.index",
        "menu_active": "admin.contacts*"
      },
      {
        "title": "Leads",
        "route_name": "admin.leads.index",
        "menu_active": "admin.leads*"
      },
      {
        "title": "Deals",
        "route_name": "admin.deals.index",
        "menu_active": "admin.deals*"
      },
      {
        "title": "Tasks",
        "route_name": "admin.tasks.index",
        "menu_active": "admin.tasks*"
      }
    ]
  }
}
```

## 16. Status Constants For CRM

Replace trading statuses with CRM statuses in a fresh `Status` class:

```php
class Status
{
    const ENABLE = 1;
    const DISABLE = 0;

    const USER_ACTIVE = 1;
    const USER_BAN = 0;

    const LEAD_NEW = 1;
    const LEAD_CONTACTED = 2;
    const LEAD_QUALIFIED = 3;
    const LEAD_UNQUALIFIED = 4;
    const LEAD_CONVERTED = 5;

    const DEAL_OPEN = 1;
    const DEAL_WON = 2;
    const DEAL_LOST = 3;

    const TASK_PENDING = 1;
    const TASK_COMPLETED = 2;
    const TASK_OVERDUE = 3;

    const ACTIVITY_CALL = 1;
    const ACTIVITY_EMAIL = 2;
    const ACTIVITY_MEETING = 3;
    const ACTIVITY_NOTE = 4;
}
```

## 17. CRM Business Workflows

Lead capture:

```text
Website form / manual entry / import
  -> lead created
  -> owner assigned
  -> notification sent
  -> task created
```

Lead qualification:

```text
Lead contacted
  -> status updated
  -> activity logged
  -> lead score updated
  -> convert to company/contact/deal if qualified
```

Deal pipeline:

```text
Deal created
  -> assigned to pipeline stage
  -> displayed in kanban
  -> activities/tasks logged
  -> moved between stages
  -> won/lost
```

Support:

```text
Customer creates ticket
  -> support message created
  -> admin replies
  -> ticket answered/closed
```

Reports:

```text
Leads by source
Deals by stage
Revenue forecast
Won/lost ratio
Salesperson performance
Task completion rate
Activity volume
```

## 18. Migration Plan From This App Concept To Your CRM

Phase 1: Understand and document

- Route map
- Database map
- Controller map
- Screen map
- Business workflow map

Phase 2: New Laravel foundation

- Create fresh Laravel 11 project.
- Add auth/admin auth.
- Build admin layout.
- Build user/team layout.
- Add settings table.
- Add notification templates.

Phase 3: CRM core

- Companies
- Contacts
- Leads
- Deals
- Pipelines
- Tasks
- Activities
- Notes
- Attachments

Phase 4: CRM workflows

- Lead import
- Lead assignment
- Lead conversion
- Deal kanban
- Task reminders
- Activity timeline
- Notifications

Phase 5: Reports and SaaS

- Dashboard metrics
- Sales reports
- Team performance
- Subscription billing if needed
- Tenant/team isolation if SaaS

Phase 6: Polish

- Permissions
- Audit logs
- Exports
- Webhooks
- Integrations
- Tests
- Security hardening

## 19. What Not To Copy

Avoid copying:

- Vendor branding
- Product name/logo
- Marketplace documentation
- Licensed package activation logic
- Payment gateway proprietary configuration data
- Exact Blade templates/assets if license does not permit reuse
- Seeded demo data
- Any obfuscated/vendor-specific code

Safe to learn from:

- Laravel route organization
- Controller/model separation
- Blade layout structure
- Admin menu JSON concept
- Notification template concept
- Support ticket workflow
- Settings/CMS concept
- Report page patterns

## 20. Clean Product Blueprint

If building a company CRM inspired by this architecture, use this structure:

```text
app/
  Constants/
    Status.php
  Http/
    Controllers/
      Admin/
        DashboardController.php
        CompanyController.php
        ContactController.php
        LeadController.php
        DealController.php
        PipelineController.php
        TaskController.php
        ReportController.php
        SettingController.php
      User/
        DashboardController.php
        TaskController.php
  Models/
    Company.php
    Contact.php
    Lead.php
    Deal.php
    Pipeline.php
    PipelineStage.php
    Activity.php
    Task.php
    Note.php
    Attachment.php
  Services/
    LeadConversionService.php
    LeadAssignmentService.php
    DealStageService.php
    CRMNotificationService.php
database/
  migrations/
resources/
  views/
    admin/
      companies/
      contacts/
      leads/
      deals/
      pipelines/
      tasks/
      reports/
    user/
routes/
  web.php
  admin.php
  user.php
```

## 21. Minimum Viable CRM Build Checklist

Build these first:

- Admin login
- Dashboard
- Companies CRUD
- Contacts CRUD
- Leads CRUD
- Deals CRUD
- Pipeline stages
- Deal kanban
- Tasks
- Activity timeline
- Notes
- Support tickets
- Basic reports
- Settings

Then add:

- Email/SMS notifications
- Imports/exports
- Roles and permissions
- SaaS subscriptions
- Integrations
- Automations
- Mobile responsive polish

## 22. Exact Reverse Engineering Template

Use this table for each feature you inspect:

```text
Feature:
URL:
Route name:
Route file:
Controller:
Method:
Model(s):
Database table(s):
Blade view:
Request validation:
Business rules:
Notifications:
Cron dependency:
External API dependency:
Can reuse concept for CRM:
CRM replacement:
```

Example:

```text
Feature: Support tickets
URL: /ticket
Route file: routes/web.php
Controller: TicketController
Models: SupportTicket, SupportMessage, SupportAttachment
Views: templates/basic/user/support
Business rules: create ticket, reply, close, download attachments
Can reuse concept for CRM: Yes
CRM replacement: Customer support module
```

## 23. Final Recommendation

Treat this project as a reference specimen:

- Study how routes, controllers, models, views, and database tables connect.
- Reuse generic architectural ideas.
- Rebuild your own CRM in a clean Laravel codebase.
- Replace the trading product domain with CRM domain entities.
- Keep support, notifications, settings, reports, and admin UX as conceptual references.

The fastest clean path is not "edit PropLab until it becomes a CRM". The fastest maintainable path is "build a fresh CRM while using PropLab as an architectural map".
