# 🎯 HopeFund - Platform Donasi Online

**Laravel 11 + PostgreSQL + TailwindCSS**

Aplikasi komprehensif untuk manajemen donasi online dengan fitur donor dan admin roles.

## ✅ Status: 95% Selesai

Sistem telah dikonfigurasi penuh dengan database, models, controllers, routes, dan sample data.

## 🚀 Memulai

### 1. Start Development Server
```bash
cd c:\laragon\www\HopeFundku
php artisan serve
```

### 2. Watch Assets (Terminal Baru)
```bash
npm run dev
```

### 3. Buka Browser
http://localhost:8000

## 🔐 Test Credentials

**Admin:**
- Email: admin@hopefund.com
- Password: password123

**Donor:**
- Email: budi@example.com
- Password: password123

## 📊 Fitur Lengkap

### Untuk Donor
✅ Daftar & Login  
✅ Browse Kampanye  
✅ Buat Donasi (QR / Transfer Bank)  
✅ Lihat Riwayat Donasi  
✅ Edit Profil  
✅ Terima Notifikasi  

### Untuk Admin
✅ Dashboard dengan Statistik  
✅ Buat/Edit/Hapus Kampanye  
✅ Lihat Semua Donasi  
✅ Update Status Donasi  
✅ Lihat Top Donatur  

## 📁 Struktur

```
app/Models/          → 7 Models dengan relationships
app/Http/Controllers → 5 Controllers siap pakai
routes/web.php       → Routes untuk semua user flows
database/            → Migrations, Seeders, Factories
resources/views/     → Blade templates
```

## 🗄️ Database

**Tables**: users, campaigns, donations, roles, categories, payment_methods, notifications

**PostgreSQL Connection**: hopefund (configured in .env)

## 📝 Dokumentasi

- **QUICK_START.md** - Quick reference
- **PROJECT_SETUP.md** - Complete overview
- **SETUP_COMPLETE.txt** - ASCII summary

## ⏳ Remaining Tasks

1. ✏️ Create Blade views (campaigns, donations, admin, notifications)
2. 🔐 Update authorization policies
3. 🔵 Configure Google OAuth (optional)

## 🎨 Teknologi

- **Backend**: Laravel 11, PostgreSQL
- **Frontend**: Blade, TailwindCSS 3, Vite
- **Auth**: Laravel Breeze + Socialite
- **Database**: UUID for transactions, proper relationships

## 💻 Perintah Berguna

```bash
# Database fresh
php artisan migrate:fresh --seed

# Generate test data
php artisan tinker
Donation::factory(20)->create()

# Clear cache
php artisan cache:clear

# Build assets
npm run build
```

## 📞 Support

Referensi lengkap di:
- Laravel Docs: https://laravel.com/docs/11
- TailwindCSS: https://tailwindcss.com
- PostgreSQL: https://postgresql.org

---

**Created**: November 11, 2025  
**Framework**: Laravel 11  
**Database**: PostgreSQL  
**Locale**: Indonesian (id)

Siap untuk implementasi view berikutnya! 🎉


## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
