<?php
// Quick migration script to add payment fields
$host = '127.0.0.1';
$db   = 'dealer_order_service';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if columns already exist
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'payment_status'");
    if ($stmt->rowCount() > 0) {
        echo "Payment columns already exist. Skipping.\n";
        exit(0);
    }
    
    $pdo->exec("ALTER TABLE orders 
        ADD COLUMN payment_method VARCHAR(50) NULL AFTER catatan,
        ADD COLUMN payment_status ENUM('belum_bayar','menunggu','berhasil','gagal','kadaluarsa') DEFAULT 'belum_bayar' AFTER payment_method,
        ADD COLUMN payment_deadline DATETIME NULL AFTER payment_status,
        ADD COLUMN paid_at DATETIME NULL AFTER payment_deadline
    ");
    
    // Set payment_deadline for existing orders (12 hours from created_at)
    $pdo->exec("UPDATE orders SET payment_deadline = DATE_ADD(created_at, INTERVAL 12 HOUR) WHERE payment_deadline IS NULL");
    
    echo "SUCCESS: Payment columns added to orders table.\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
