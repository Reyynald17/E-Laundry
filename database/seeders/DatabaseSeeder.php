<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Order;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Services
        Service::insert([
            ['name' => 'Cuci Kiloan Regular', 'price_per_kg' => 7000, 'unit' => 'kg', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cuci Karpet', 'price_per_kg' => 15000, 'unit' => 'm2', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Setrika Express', 'price_per_kg' => 10000, 'unit' => 'kg', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cuci Bedcover', 'price_per_kg' => 20000, 'unit' => 'pcs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dry Cleaning', 'price_per_kg' => 25000, 'unit' => 'pcs', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Data Customers
        Customer::insert([
            ['name' => 'Budi Santoso', 'phone' => '081234567890', 'address' => 'Jl. Mawar No. 12, Jakarta Selatan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Siti Rahma', 'phone' => '081987654321', 'address' => 'Jl. Anggrek No. 45, Bandung', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Andi Wijaya', 'phone' => '085678901234', 'address' => 'Jl. Melati No. 8, Surabaya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dewi Lestari', 'phone' => '087712345678', 'address' => 'Jl. Kenanga No. 23, Yogyakarta', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rian Pratama', 'phone' => '082134567890', 'address' => 'Jl. Dahlia No. 17, Semarang', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Siti Nurhaliza', 'phone' => '083890123456', 'address' => 'Jl. Flamboyan No. 5, Malang', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Eko Prasetyo', 'phone' => '085212345678', 'address' => 'Jl. Kamboja No. 30, Medan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fitriani', 'phone' => '089612345678', 'address' => 'Jl. Tulip No. 14, Makassar', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hendra Gunawan', 'phone' => '081345678901', 'address' => 'Jl. Teratai No. 9, Palembang', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nia Ramadhani', 'phone' => '087890123456', 'address' => 'Jl. Bougenville No. 21, Denpasar', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Data Order
        $order = Order::create([
            'customer_id' => 1,
            'invoice_code' => 'INV-20260915-001',
            'order_date' => now(),
            'completion_date' => now()->addDays(2),
            'status' => 'pending',
            'total_price' => 41000,
        ]);

        // 4. Data Pivot Detail
        $order->services()->attach([
            1 => ['qty' => 3, 'subtotal' => 21000],
            3 => ['qty' => 2, 'subtotal' => 20000],
        ]);
    }
}