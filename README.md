# 🧺 E-Londri — Sistem Manajemen & Kasir Laundry Digital

![Laravel Version](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge\&logo=laravel)
![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge\&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge\&logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

E-Londri adalah aplikasi **manajemen operasional dan sistem kasir laundry digital** berbasis RESTful API. Aplikasi ini dibuat untuk membantu proses pencatatan transaksi, pengelolaan pelanggan dan layanan laundry, serta pemantauan status pengerjaan laundry.

---

## 📋 Daftar Isi

* [Struktur Tim & Pembagian Peran](#-struktur-tim--pembagian-peran)
* [Arsitektur & Teknologi](#-arsitektur--teknologi)
* [Struktur Basis Data & ERD](#-struktur-basis-data--erd)
* [Panduan Instalasi & Konfigurasi Lokal](#-panduan-instalasi--konfigurasi-lokal)
* [Dokumentasi RESTful API](#-dokumentasi-restful-api)
* [Pengujian (Testing)](#-pengujian-testing)
* [Lisensi](#-lisensi)

---

## 👥 Struktur Tim & Pembagian Peran

| Peran                  | Anggota Tim     | Tanggung Jawab Utama                                                                                  |
| :--------------------- | :-------------- | :---------------------------------------------------------------------------------------------------- |
| **Project Manager**    | Fathin Aznar Al Hanif | Mengatur timeline, Kanban, QA fungsional, dan dokumentasi proyek.                                     |
| **Database Analyst**   | Farand Altaf Emian Lubis | Merancang ERD, migrasi database, relasi Eloquent, Seeder, dan Factory.                                |
| **Backend Developer**  | Mhd Fathan Al Aziz | Mengembangkan RESTful API, validasi request, API Resource, dan transaksi database.                    |
| **Frontend Developer** | Ibnu Halim Lase | Membuat tampilan kasir, menghubungkan frontend dengan API, validasi client-side, dan menangani error. |

---

## 🛠️ Arsitektur & Teknologi

Teknologi yang digunakan dalam pengembangan E-Londri:

* **Framework Backend:** Laravel 12
* **Database:** MySQL 8.0
* **Authentication:** Laravel Sanctum / JWT
* **API Documentation & Testing:** Postman Collection v2.1
* **Version Control:** Git & GitHub

---

## 🗄️ Struktur Basis Data & ERD

E-Londri menggunakan **4 tabel utama** yang saling berhubungan untuk mengelola data pelanggan, layanan, transaksi, dan detail transaksi.

```text
[ customers ] (1) <--- (N) [ orders ] (N) <---> (N) [ services ]
                                      │
                                      │
                               [ order_details ]
```

### Penjelasan Entitas

1. **`customers` — Master Pelanggan**
   Menyimpan informasi pelanggan seperti `name`, `phone`, dan `address`.

2. **`services` — Master Layanan**
   Menyimpan daftar paket layanan laundry seperti `name`, `price_per_kg`, dan `unit`.

3. **`orders` — Data Transaksi**
   Menyimpan informasi transaksi seperti `invoice_code`, `order_date`, `completion_date`, `status`, dan `total_price`.

4. **`order_details` — Detail Transaksi**
   Menyimpan detail layanan yang dipilih dalam suatu transaksi, termasuk `qty` dan `subtotal`.

Tabel `order_details` menjadi penghubung antara **orders** dan **services** dengan relasi **Many-to-Many**.

---

## ⚙️ Panduan Instalasi & Konfigurasi Lokal

Ikuti langkah berikut untuk menjalankan E-Londri di komputer lokal.

### 1. Prasyarat Sistem

Pastikan perangkat sudah memiliki:

* PHP >= 8.2
* Composer >= 2.x
* MySQL Server
* Git

### 2. Clone Repository

```bash
git clone https://github.com/Reyynald17/E-Laundry/tree/barulahpokoknya
```

Masuk ke folder proyek:

```bash
cd elondri-pengayaan
```

### 3. Install Dependensi

```bash
composer install
```

### 4. Konfigurasi File `.env`

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Kemudian generate application key:

```bash
php artisan key:generate
```

### 5. Konfigurasi Database

Buka file `.env`, kemudian sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_elondri
DB_USERNAME=root
DB_PASSWORD=
```

Buat database dengan nama:

```text
db_elondri
```

### 6. Migrasi & Seeding Data

Jalankan perintah berikut untuk membuat tabel sekaligus memasukkan data dummy:

```bash
php artisan migrate:fresh --seed
```

### 7. Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Setelah berhasil, API dapat diakses melalui:

```text
http://127.0.0.1:8000
```

---

## 🔗 Dokumentasi RESTful API

### Endpoint Utama

| Method    | Endpoint                  | Deskripsi                                   | Access       |
| :-------- | :------------------------ | :------------------------------------------ | :----------- |
| **GET**   | `/api/orders`             | Menampilkan seluruh transaksi               | Public/Kasir |
| **POST**  | `/api/orders`             | Membuat transaksi laundry baru              | Kasir        |
| **GET**   | `/api/orders/{id}`        | Menampilkan detail transaksi berdasarkan ID | Public/Kasir |
| **PATCH** | `/api/orders/{id}/status` | Memperbarui status laundry                  | Kasir/Admin  |

### Contoh Payload Request

**POST `/api/orders`**

```json
{
    "customer_id": 1,
    "completion_date": "2026-09-16",
    "services": [
        {
            "service_id": 1,
            "qty": 3
        },
        {
            "service_id": 2,
            "qty": 1
        }
    ]
}
```

### Contoh Response Success

**201 Created**

```json
{
    "status": true,
    "message": "Transaksi laundry berhasil dibuat",
    "data": {
        "id": 12,
        "invoice_code": "INV-20260914-482",
        "order_date": "2026-09-14 08:30:00",
        "completion_date": "2026-09-16",
        "status": "pending",
        "total_price": 45000,
        "customer": {
            "id": 1,
            "name": "Budi Santoso",
            "phone": "08123456789"
        },
        "details": [
            {
                "service_id": 1,
                "service_name": "Cuci Kiloan Regular",
                "price_per_kg": 10000,
                "qty": 3,
                "subtotal": 30000
            },
            {
                "service_id": 2,
                "service_name": "Setrika Express",
                "price_per_kg": 15000,
                "qty": 1,
                "subtotal": 15000
            }
        ]
    }
}
```

### Contoh Response Error

**422 Unprocessable Entity**

```json
{
    "message": "Pelanggan wajib dipilih. (and 1 more error)",
    "errors": {
        "customer_id": [
            "Pelanggan wajib dipilih."
        ],
        "services": [
            "Minimal pilih 1 layanan laundry."
        ]
    }
}
```

---

## 🧪 Pengujian (Testing)

Pengujian RESTful API dilakukan menggunakan **Postman**.

File collection tersedia di:

```text
docs/E-Londri_API.postman_collection.json
```

### Skenario Pengujian

1. **200 OK** — Menampilkan daftar transaksi dan detail order.
2. **201 Created** — Berhasil membuat transaksi baru dengan perhitungan total harga otomatis.
3. **422 Unprocessable Entity** — Menampilkan error ketika data yang dikirim tidak lengkap.
4. **404 Not Found** — Menampilkan error ketika ID transaksi tidak ditemukan.

---

## 📄 Lisensi

Proyek **E-Londri** menggunakan lisensi **MIT**.

---

### 👨‍💻 E-Londri

Sistem ini dikembangkan sebagai proyek pembelajaran untuk menerapkan konsep **Laravel, RESTful API, database relasional, dan sistem kasir laundry digital**.
