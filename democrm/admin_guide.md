# Admin Guide — CrossFlow by Crosstech Solutions

This guide covers everything an admin can do in the CrossFlow dashboard.

---

## Accessing the Admin Panel

URL: `http://yourdomain.com/admin`

Login with your admin username and password. After login you land on the **Dashboard**.

---

## 1. Dashboard

**URL:** `/admin/dashboard`

What you see:
- Total Users, Active Users, Email Unverified, Mobile Unverified counts
- Total Orders, Open Orders, Completed Orders, Canceled Orders
- Deposit and Withdrawal summary
- Order summary table (recent trades)
- Deposit vs Withdrawal chart

---

## 2. Manage Plans (Challenges)

**URL:** `/admin/plan`

This is where you create and manage trading challenges that users buy.

### 2.1 Create a Plan

`/admin/plan/add`

Fields:
- Plan name
- Price (what the user pays)
- Trading capital (virtual funded amount)
- Profit target %
- Max daily drawdown %
- Max total drawdown %
- Minimum trading days
- Status (active/inactive)

### 2.2 Add Phases to a Plan

Each plan has phases. Example:
- Phase 1: Evaluation challenge
- Phase 2: Verification
- Phase 3: Funded account

For each phase you define logic boxes (pass/fail rules).

### 2.3 Logic Boxes

`/admin/plan/logic-box`

Logic boxes are the rule engine for each phase. You define:
- What metric to check (profit, drawdown, trading days, etc.)
- The threshold value
- Whether it is a pass condition or a fail condition

### 2.4 Plan History

`/admin/plan/history`

View all plan purchases by all users — who bought what plan, when, and at what status.

### 2.5 Enable/Disable a Plan

From the plan list, toggle the status button to activate or deactivate a plan without deleting it.

---

## 3. Bot Configuration

**URL:** `/admin/bot/config`

Configure the trading bot that simulates market activity. Settings include:
- Bot trading pairs
- Trade frequency
- Trade size ranges
- Enable/disable bot

---

## 4. Manage Orders

### 4.1 Open Orders

`/admin/order/open`

View all currently open buy/sell orders across all users.

### 4.2 Order History

`/admin/order/history`

View all completed, canceled, and historical orders. Can be filtered by user.

### 4.3 Trade History

`/admin/trade/history`

View individual trades that resulted from matched orders.

---

## 5. Manage Users

**URL:** `/admin/users`

### 5.1 User Lists

| View | URL |
|------|-----|
| All Users | `/admin/users` |
| Active Users | `/admin/users/active` |
| Banned Users | `/admin/users/banned` |
| Email Verified | `/admin/users/email-verified` |
| Email Unverified | `/admin/users/email-unverified` |
| Mobile Unverified | `/admin/users/mobile-unverified` |
| KYC Unverified | `/admin/users/kyc-unverified` |
| KYC Pending | `/admin/users/kyc-pending` |
| Users With Balance | `/admin/users/with-balance` |

### 5.2 User Detail

`/admin/users/detail/{id}`

From a user's detail page you can:
- View profile, balance, plan history
- Edit user information
- Add or subtract balance manually
- Ban or activate the user account
- Login as that user (for support/debugging)
- Send a direct notification to that user
- View their notification history

### 5.3 KYC Approval

`/admin/users/kyc-pending`

Steps:
1. Go to KYC Pending list
2. Click a user to view their submitted documents
3. Review the uploaded ID and address proof
4. Click **Approve** or **Reject**
5. User gets notified automatically

### 5.4 Bulk Notifications

`/admin/users/send-notification`

Send an email, SMS, or push notification to a filtered segment of users at once.

---

## 6. Deposits

**URL:** `/admin/deposit`

| View | URL |
|------|-----|
| All Deposits | `/admin/deposit/all` |
| Pending | `/admin/deposit/pending` |
| Approved | `/admin/deposit/approved` |
| Rejected | `/admin/deposit/rejected` |
| Successful | `/admin/deposit/successful` |

### Approving a Manual Deposit

1. Go to `/admin/deposit/pending`
2. Click **Details** on a deposit
3. Review the payment proof uploaded by the user
4. Click **Approve** to credit the user's wallet
5. Click **Reject** to decline and notify the user

Automatic gateway deposits are confirmed without manual action via payment webhooks.

---

## 7. Withdrawals

**URL:** `/admin/withdraw`

| View | URL |
|------|-----|
| All Withdrawals | `/admin/withdraw/all` |
| Pending | `/admin/withdraw/pending` |
| Approved | `/admin/withdraw/approved` |
| Rejected | `/admin/withdraw/rejected` |

### Approving a Withdrawal

1. Go to `/admin/withdraw/pending`
2. Click **Details**
3. Review the request (amount, method, user details)
4. Click **Approve** — user's wallet is debited, payment is marked sent
5. Click **Reject** — amount is returned to user's wallet

### Withdrawal Methods

`/admin/withdraw/method`

Create and manage withdrawal methods (bank transfer, crypto, etc.) that users can use.

---

## 8. Payment Gateways

`/admin/gateway`

### 8.1 Automatic Gateways

`/admin/gateway/automatic`

Configure API keys for:
- Stripe
- PayPal
- Razorpay
- Paystack
- Coinbase
- CoinPayments
- BTCPay
- Mollie
- And more

Each gateway has its own credential fields. Enable/disable each one independently.

### 8.2 Manual Gateways

`/admin/gateway/manual`

Create custom payment methods with:
- Name and logo
- Minimum/maximum deposit amount
- Processing time text
- Instructions for the user
- Fields to collect (e.g. transaction ID, receipt upload)

---

## 9. Reports

### 9.1 Transaction Report

`/admin/report/transaction`

All wallet transactions across all users — deposits, withdrawals, plan purchases, profits.

### 9.2 Login History

`/admin/report/login/history`

Track when and from where users logged in. Includes IP address lookup.

### 9.3 Notification History

`/admin/report/notification/history`

Log of all notifications sent through the system.

---

## 10. Support Tickets

`/admin/ticket`

| View | URL |
|------|-----|
| All Tickets | `/admin/ticket` |
| Pending | `/admin/ticket/pending` |
| Answered | `/admin/ticket/answered` |
| Closed | `/admin/ticket/closed` |

Actions per ticket:
- Reply to the user
- Download attachments
- Close the ticket
- Delete the ticket

---

## 11. Subscribers

`/admin/subscriber`

- View all email subscribers
- Remove individual subscribers
- Send a bulk email to all subscribers

---

## 12. Currency Manager

`/admin/currency`

| Section | URL |
|---------|-----|
| Crypto Currencies | `/admin/currency/crypto` |
| Fiat Currencies | `/admin/currency/fiat` |
| All Currencies | `/admin/currency/all` |

Add, edit, enable/disable currencies. Import currencies in bulk.

---

## 13. Markets and Coin Pairs

### Markets

`/admin/market/list`

Create trading markets (e.g. Crypto, Forex, Commodities).

### Coin Pairs

`/admin/coin/pair/list`

Create and manage tradeable pairs (e.g. BTC/USDT, ETH/BTC).

For each pair:
- Base and quote currency
- Minimum/maximum order size
- Status (active/inactive)

---

## 14. Currency Data Provider

`/admin/currency/data/provider`

Configure which external data source feeds live price data into the platform. Enable/disable and set one as default.

---

## 15. KYC Settings

`/admin/kyc-setting`

Configure the KYC verification form:
- Enable or disable KYC requirement
- Add custom input fields (text, file upload, select)
- Make fields required or optional

---

## 16. Notifications

### Email Settings

`/admin/notification/email/setting`

Configure SMTP or API-based email delivery (SendGrid, Mailjet, etc.). Send a test email.

### SMS Settings

`/admin/notification/sms/setting`

Configure SMS gateway (Twilio, Vonage, MessageBird). Send a test SMS.

### Push Notifications

`/admin/notification/notification/push/setting`

Configure Firebase push notifications for mobile/web.

### Notification Templates

`/admin/notification/templates`

Edit the content of every automated notification the system sends — registration, deposit, withdrawal, plan purchase, KYC approval, etc.

### Global Toggles

Turn email, SMS, and push notifications on or off globally without deleting configuration.

---

## 17. Extensions / Plugins

`/admin/extensions`

Enable or configure third-party integrations:
- Google reCAPTCHA
- Custom CAPTCHA
- Google Analytics
- Facebook Comments
- Tawk.to Live Chat

---

## 18. Frontend / CMS

### Frontend Sections

`/admin/frontend/frontend-sections`

Edit the content of your public-facing website sections — hero, features, how it works, testimonials, FAQ, etc.

### Page Builder

`/admin/frontend/manage-pages`

Create custom static pages (Terms, Privacy Policy, About Us) with a visual editor and set their URL slugs.

### Templates

`/admin/frontend/templates`

Switch between available frontend themes.

### SEO

`/admin/seo`

Set meta title, meta description, and keywords for the homepage and other pages.

---

## 19. System Settings

### General Settings

`/admin/general-setting`

- Site name, tagline
- Base currency
- Timezone
- Date and time format
- User registration toggle
- Force HTTPS

### System Configuration

`/admin/setting/system-configuration`

- Email verification on/off
- Mobile verification on/off
- KYC required on/off
- Google login on/off
- 2FA on/off
- Referral system on/off

### Logo and Favicon

`/admin/setting/logo-icon`

Upload your site logo, dark logo, and favicon.

### Custom CSS

`/admin/custom-css`

Add global custom CSS that applies across the entire site.

### Cookie Consent

`/admin/cookie`

Enable/disable GDPR cookie consent banner and edit its text.

### Maintenance Mode

`/admin/maintenance-mode`

Put the site into maintenance mode with a custom message. Only admins can access the panel while maintenance is active.

### Pusher Configuration

`/admin/pusher-configuration`

Configure Pusher credentials for real-time trade updates and notifications.

### Chart Settings

`/admin/chart/setting`

Configure the TradingView chart integration settings.

### Social Login Credentials

`/admin/setting/social/credentials`

Configure Google, Facebook, and LinkedIn OAuth credentials for social login.

---

## 20. Language Manager

`/admin/language`

- Add new languages
- Edit translation keys for any language
- Import language files
- Set a language as default

---

## 21. Cron Jobs

`/admin/cron/index`

Manage the automated background jobs that power the platform:
- Trade matching
- Profit/loss calculation
- Phase progression
- Plan expiry

You can create cron schedules, enable/disable them, view logs, and manually run jobs.

---

## 22. System Tools

### System Info

`/admin/system/info`

View PHP version, installed extensions, server configuration.

### Server Info

`/admin/system/server-info`

Detailed server environment information.

### Optimize

`/admin/system/optimize`

Run Laravel cache optimization. Use after making configuration changes.

### System Update

`/admin/system/system-update`

Check for and apply platform updates.

---

## 23. Admin Profile

`/admin/profile`

Update your admin username, email, and profile photo.

`/admin/password`

Change your admin password.

---

## 24. Notifications (Admin's Own)

`/admin/notifications`

View all system notifications directed at the admin account. Mark as read, delete individually or in bulk.

---

*Guide prepared by Crosstech Solutions — CrossFlow Platform*
