# User Guide — CrossFlow by Crosstech Solutions

This guide covers everything a user can do on the CrossFlow platform.

---

## Getting Started

### 1. Register

**URL:** `http://yourdomain.com/register`

Fill in:
- First and last name
- Username
- Email address
- Mobile number (optional)
- Password
- Country
- Referral code (if you have one)

After registering you may need to verify your email before accessing your dashboard.

---

### 2. Login

**URL:** `http://yourdomain.com/login`

Login with your username or email and password.

You can also login with:
- Google account
- Facebook account
- LinkedIn account

---

### 3. Email & Mobile Verification

If verification is required:
- Check your email for a verification code
- Enter the code on the verification screen
- You can request a new code if it expires

---

### 4. Two-Factor Authentication (2FA)

**URL:** `/user/twofactor`

For extra security:
1. Go to Profile → Two-Factor Authentication
2. Scan the QR code with Google Authenticator or Authy
3. Enter the 6-digit code to enable 2FA
4. On every login you will be asked for your 2FA code

To disable 2FA, go to the same page and click Disable.

---

## Dashboard

**URL:** `/user/dashboard`

Your dashboard shows:
- Account balance summary
- Active plan status and progress
- Recent orders
- Recent transactions
- Quick links to trade, deposit, withdraw

---

## Plans (Challenges)

### 5. View Available Plans

**URL:** `/user/plan`

Browse all available trading challenge plans. Each plan shows:
- Plan name
- Price to join
- Virtual capital amount
- Profit target
- Max drawdown limit
- Number of phases

### 6. Buy a Plan

**URL:** `/user/plan/buy/{id}`

1. Click **Buy** on any plan
2. Review the plan details and fee
3. Select a payment method (your wallet balance or a payment gateway)
4. Complete payment
5. Your challenge account is activated immediately after payment is confirmed

### 7. Plan Progress

**URL:** `/user/plan/plan/progress`

Track your progress through each phase:
- Current phase
- Current profit/loss
- Drawdown remaining
- Trading days completed vs required
- Pass/fail status for each rule

### 8. Plan History

**URL:** `/user/plan/history`

View all plans you have purchased — active, completed, expired, and failed.

### 9. Renew a Plan

If a plan expires or fails, you can renew it from the plan history page without going through the full purchase flow again.

---

## Trading

### 10. Trade Page

**URL:** `/trade` or from your dashboard → Trade

The trading interface includes:
- Live price chart (TradingView integration)
- Order book
- Recent trades feed
- Your open positions

### 11. Place an Order

On the trade page:
1. Select a coin pair (e.g. BTC/USDT)
2. Choose order type — Buy or Sell
3. Enter the amount
4. Click **Place Order**

Your order goes into the order book and is matched by the system.

### 12. Add Pair to Favorites

Click the star icon next to any coin pair to save it to your favorites for quick access.

---

## Orders

### 13. Open Orders

**URL:** `/user/order/open`

View all your currently active orders waiting to be matched or filled.

Actions:
- **Cancel** an open order — returns the reserved funds to your wallet

### 14. Completed Orders

**URL:** `/user/order/completed`

All orders that have been successfully matched and filled.

### 15. Canceled Orders

**URL:** `/user/order/canceled`

All orders you or the system have canceled.

### 16. Order History

**URL:** `/user/order/history`

Full history of all your orders across all statuses.

### 17. Trade History

**URL:** `/user/trade/history`

Individual trade records showing each fill — price, quantity, time, profit/loss per trade.

---

## Wallet

### 18. Wallet List

**URL:** `/user/wallet/list`

View all your currency wallets and their current balances.

### 19. Wallet Detail

**URL:** `/user/wallet/{currencySymbol}`

Click into any wallet to see:
- Current balance
- Transaction history for that currency

### 20. Convert Currency

From the wallet page you can convert one currency to another at the current rate.

---

## Deposits

### 21. Make a Deposit

**URL:** From dashboard → Deposit

Steps:
1. Select a payment gateway (Stripe, PayPal, crypto, bank transfer, etc.)
2. Enter the deposit amount
3. Complete the payment on the gateway's page
4. Funds appear in your wallet once confirmed

For **manual gateways** (bank transfer):
1. You receive payment instructions (bank account details, wallet address, etc.)
2. Make the transfer
3. Upload your payment receipt/proof on the confirmation page
4. Admin reviews and approves — funds credited within the processing time shown

### 22. Deposit History

**URL:** `/user/deposit/history`

View all your past deposits with their status — Pending, Approved, Rejected.

---

## Withdrawals

### 23. Withdraw Funds

**URL:** `/user/withdraw`

Note: KYC verification is required before you can withdraw.

Steps:
1. Go to Withdraw
2. Select a withdrawal method
3. Enter the amount
4. Fill in your payment details (bank account, wallet address, etc.)
5. Review the preview screen (shows fees and net amount)
6. Submit — admin reviews and processes the withdrawal

### 24. Withdrawal History

**URL:** `/user/withdraw/history`

Track all your withdrawal requests and their current status.

---

## KYC Verification

### 25. Submit KYC

**URL:** `/user/kyc-form`

Required before you can withdraw funds.

Steps:
1. Go to Profile → KYC Verification
2. Fill in the required personal details
3. Upload your ID document (passport, national ID, or driver's license)
4. Upload address proof (utility bill, bank statement)
5. Submit for review

### 26. Check KYC Status

**URL:** `/user/kyc-data`

See whether your KYC is:
- **Pending** — admin has not reviewed yet
- **Approved** — you are fully verified
- **Rejected** — you need to resubmit with corrected documents

---

## Profile & Settings

### 27. Profile Settings

**URL:** `/user/profile-setting`

Update:
- First and last name
- Email address
- Mobile number
- Profile photo
- Address details
- Country

### 28. Change Password

**URL:** `/user/change-password`

Enter your current password and a new password to update your login credentials.

---

## Transactions

### 29. Transaction History

**URL:** `/user/transactions`

Full ledger of all wallet movements — deposits, withdrawals, plan purchases, trading profits and losses, fees.

---

## Support

### 30. Create a Support Ticket

**URL:** From the main site navigation → Support

Steps:
1. Click **Open New Ticket**
2. Enter a subject
3. Describe your issue
4. Attach files if needed (screenshots, documents)
5. Submit

### 31. Reply to a Ticket

Open any existing ticket from your ticket list and type your reply in the message box.

### 32. Close a Ticket

Once your issue is resolved, you can close the ticket yourself from the ticket view page.

---

## Notifications

### 33. Notification Bell

In the top navigation bar, click the bell icon to see your latest notifications — deposit confirmations, withdrawal updates, KYC status, plan phase updates, and admin messages.

### 34. Push Notifications

If your device supports it, you can allow push notifications for real-time alerts.

---

## Security Tips

- Enable 2FA for maximum account security
- Never share your password
- Complete KYC early so withdrawals are not delayed
- Use a strong, unique password
- Check your login history regularly from Profile settings

---

## Quick Reference

| Action | URL |
|--------|-----|
| Dashboard | `/user/dashboard` |
| Buy a plan | `/user/plan` |
| Trade | `/trade` |
| Open orders | `/user/order/open` |
| Order history | `/user/order/history` |
| Trade history | `/user/trade/history` |
| Wallet | `/user/wallet/list` |
| Deposit | via dashboard |
| Deposit history | `/user/deposit/history` |
| Withdraw | `/user/withdraw` |
| Withdrawal history | `/user/withdraw/history` |
| KYC form | `/user/kyc-form` |
| Profile settings | `/user/profile-setting` |
| Change password | `/user/change-password` |
| 2FA settings | `/user/twofactor` |
| Transactions | `/user/transactions` |
| Plan progress | `/user/plan/plan/progress` |
| Plan history | `/user/plan/history` |

---

*Guide prepared by Crosstech Solutions — CrossFlow Platform*
