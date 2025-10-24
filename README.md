# 🗂️ E-Request System — Laravel (Blade Fullstack)

## 📘 Deskripsi
**E-Request System** adalah sistem internal berbasis Laravel + Blade untuk Technical Test

---

## ⚙️ Instalasi & Setup Project

### 1. Clone Project
```bash
git clone https://github.com/rizkysafdila/e-request-laravel.git
cd e-request-laravel
```

### 2. Install Dependencies
```bash
composer install
npm install
npm run build
```

### 3. Jalankan migrasi dan seeder
```bash
php artisan migrate --seed
```

### 4. Link storage
```bash
php artisan storage:link
```

### 5. Jalankan Server
```bash
php artisan serve
```
Akses di: [http://localhost:8000](http://localhost:8000)

---

## 🔑 Instruksi Login

Gunakan kredensial hasil seeder:
| Role          | Email                                           | Password |
| ------------- | ----------------------------------------------- | -------- |
| **Admin**     | [admin@gmail.com](mailto:admin@gmail.com)       | password |
| **Requestor** | [rizky@gmail.com](mailto:rizky@gmail.com)       | password |
| **Approver**  | [approver@gmail.com](mailto:approver@gmail.com) | password |

## 🔄 Alur Request & Approval
### 🧍 Role Requestor
1. Login → klik New Request
2. Isi form dan simpan (status: draft)
3. Klik Submit → status berubah ke submitted
4. Request tidak bisa diedit lagi

### 👩‍💼 Role Approver
1. Melihat daftar request dengan status submitted
2. Dapat memilih:
   - ✅ Approve → status approved
   - ❌ Reject → status rejected (wajib isi alasan)

### 👨‍💻 Role Admin
1. Melihat semua request (termasuk yang dihapus)
2. Bisa filter request (active/deleted)
3. Bisa restore request dari Trash
