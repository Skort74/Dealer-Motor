# Fix Payment System Issues - Dealer-Motor Order-Service

## Current Progress
✅ Step 1: Create TODO.md (done)
✅ Step 2: Fix CSS Linter Errors in payment.php (done)

## Remaining Steps

### Step 2: Fix CSS Linter Errors in payment.php
- Edit `order-service/app/Views/orders/payment.php`
- Add fallbacks: `var(--radius, 8px)`, `var(--radius-lg, 12px)`
- Target lines ~22-24 (payment-reminder styles)
- Test: Reload VSCode → no red underlines

### Step 3: Fix Migration Mismatch
- Rename `order-service/app/Database/Migrations/2026-05-03-163742_AddPaymentStatusToOrders.php` → `2024-10-28-000000_AddPaymentFieldsToOrders.php`
- Update content: ENUM(['belum_bayar','menunggu','berhasil','gagal','kadaluarsa']), default 'belum_bayar'
- Add columns: payment_method, payment_deadline, paid_at

### Step 4: Run Migrations & Verify
```
cd order-service
php spark migrate:status
php spark migrate
```
- Check `DESCRIBE orders;` → correct payment_status ENUM

### Step 5: Test Full Flow
```
cd order-service && php spark serve
```
- Create order `/orders/create`
- Go to payment `/orders/payment/{id}`
- Submit payment
- Verify: DB updated, no errors, stock sync works

### Step 6: Complete
```
rm TODO.md
```
Use `attempt_completion` when all ✅

**Next Action**: Edit payment.php CSS → Mark Step 2 ✅

