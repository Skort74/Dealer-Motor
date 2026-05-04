<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentStatusToOrders extends Migration
{
    public function up()
    {
        // Menambahkan kolom payment_status ke tabel orders
        $this->forge->addColumn('orders', [
            'payment_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'paid', 'failed'],
                'default'    => 'pending',
                'after'      => 'status' // meletakkan kolom setelah kolom 'status'
            ],
        ]);
    }

    public function down()
    {
        // Menghapus kolom payment_status jika migration di-rollback
        $this->forge->dropColumn('orders', 'payment_status');
    }
}