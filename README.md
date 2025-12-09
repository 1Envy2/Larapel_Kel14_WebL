# HopeFund - Platform Galangan Dana Transparan

**Laravel 12 + PostgreSQL + TailwindCSS + Vite**

Platform galangan dana modern dengan transparansi penuh. Admin mengelola kampanye, dan publik dapat berdonasi serta melacak alokasi dana secara real-time.

Semua fitur inti telah diimplementasikan dan teruji dengan sempurna:
- Backend & Database lengkap
- Authentication & Authorization
- Campaign & Donation Management
- Transparansi & Alokasi Dana
- Admin Dashboard & Analytics
- Activity Logging & Audit Trail
- Views & Frontend

---

## Quick Start

### Prerequisites
- PHP 8.2+
- PostgreSQL
- Node.js & npm
- Composer

### Installation

```bash
# 1. Clone dan setup
cd c:\laragon\www\HopeFund

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
copy .env.example .env
php artisan key:generate

# 4. Database setup
php artisan migrate --seed

# 5. Build assets
npm run build
```

### Running Development Server

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Watch Assets:**
```bash
npm run dev
```

Buka browser: **http://localhost:8000**

---

## Test Credentials

### Admin Account
```
Email:    admin@hopefund.com
Password: password123
Role:     admin
```

### Donor Account
```
Email:    budi@example.com
Password: password123
Role:     donor
```

---

## Fitur Aplikasi

### Untuk Donor & Publik
| Fitur | Status | Deskripsi |
|-------|--------|-----------|
| **Browse Kampanye** | ✅ | Lihat semua kampanye galangan dana aktif |
| **Filter & Search** | ✅ | Cari kampanye berdasarkan kategori & keywords |
| **Lihat Detail Kampanye** | ✅ | Info lengkap, progress, & updates terbaru |
| **Buat Donasi** | ✅ | Donasi via Transfer Bank atau QR Code |
| **Status Donasi** | ✅ | Upload bukti & tracking status real-time |
| **Riwayat Donasi** | ✅ | Lihat semua donasi yang pernah dilakukan |
| **Lihat Alokasi Dana** | ✅ | Transparansi penuh - kemana dana digunakan |
| **Comment & Updates** | ✅ | Komentar pada kampanye & terima updates |
| **Edit Profil** | ✅ | Update nama, email, avatar, & kontak |

### Untuk Admin
| Fitur | Status | Deskripsi |
|-------|--------|-----------|
| **Dashboard Analytics** | ✅ | Statistik donasi, kampanye, & donatur terkemuka |
| **Buat Kampanye** | ✅ | Inisiasi galangan dana baru |
| **Edit Kampanye** | ✅ | Update detail, target, & status kampanye |
| **Hapus Kampanye** | ✅ | Hapus kampanye jika diperlukan |
| **Posting Updates** | ✅ | Bagikan perkembangan kampanye ke donatur |
| **Kelola Donasi** | ✅ | Verifikasi & update status donasi (pending → successful) |
| **Kelola Alokasi** | ✅ | Record & dokumentasi penggunaan dana |
| **Lihat Aktivitas** | ✅ | Melihat detail aktivitas donatur |

---

## Arsitektur & Struktur Database

### Models (7)
```
User              → Donor & Admin (roles: 'donor' atau 'admin')
Campaign          → Kampanye galangan dana
Donation          → Transaksi donasi
Allocation        → Penggunaan dana (transparansi)
Category          → Kategori kampanye
PaymentMethod     → Metode pembayaran (Transfer)
```

### Key Relationships
- `User` → hasMany `Donation`, `Campaign`, `ActivityLog`
- `Campaign` → hasMany `Donation`, `Allocation`, `CampaignUpdate`, `CampaignComment`
- `Donation` → belongsTo `User`, `Campaign`, `PaymentMethod`
- `Allocation` → belongsTo `Campaign`, `User` (admin)

### Controllers (5)
- **AdminController** - Dashboard, kampanye CRUD, donasi manage
- **CampaignController** - Browse, detail, search, filter, save
- **DonationController** - Create, store, history, verify
- **AllocationController** - Public transparency, admin allocation management
- **HomeController** - Landing page & authenticated dashboard

### Routes Structure
```
/ (root)                 → Landing page (campaigns index)
/campaigns              → Browse semua kampanye
/campaigns/{id}         → Detail kampanye + donation options
/transparansi           → Public allocation log (transparansi)

/donations              → Donasi saya (authenticated)
/donations/create/{id}  → Form donasi
/donations/history      → Riwayat donasi

/admin/*                → Admin dashboard & management (admin only)
/profile/*              → User profile management (authenticated)
```

---

## Frontend Components

### Views Structure
```
resources/views/
├── layouts/
│   ├── app.blade.php          (authenticated layout)
│   ├── guest.blade.php        (public layout)
│   ├── admin.blade.php        (admin dashboard)
│   ├── navigation.blade.php   (navbar)
│   └── footer.blade.php
├── campaigns/
│   ├── index.blade.php        (browse campaigns)
│   └── show.blade.php         (campaign detail)
├── donations/
│   ├── index.blade.php        (my donations)
│   ├── create.blade.php       (donation form)
│   ├── show.blade.php         (donation detail)
│   └── success.blade.php      (confirmation)
├── allocations/
│   └── index.blade.php        (public transparency log)
├── admin/
│   ├── dashboard.blade.php
│   ├── campaigns/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── donations/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   └── allocations/
│       ├── index.blade.php
│       ├── create.blade.php
│       ├── select-campaign.blade.php
│       └── show.blade.php
├── home.blade.php             (dashboard)
├── dashboard.blade.php        (auth dashboard)
└── components/
    └── (15+ reusable components)
```

### Styling & Framework
- **TailwindCSS 3** - Utility-first styling
- **Vite** - Fast asset bundling
- **Alpine.js** - Interactive components
- **Responsive Design** - Mobile-first approach

---

## Database Schema

### Key Tables

**users**
```
id, name, email, password, role (donor|admin), phone, address, 
avatar, google_id, otp_code, email_verified_at, timestamps
```

**campaigns**
```
id, title, description, target_amount, collected_amount, status 
(active|completed|draft), category_id, image, story, end_date, timestamps
```

**donations**
```
id, transaction_id (UUID), donor_id, campaign_id, amount, 
payment_method_id, status (pending|successful|failed), proof_image, 
message, donor_name, donor_email, anonymous, timestamps
```

**allocations**
```
id, campaign_id, admin_id, description, amount, allocation_date, 
proof_image, timestamps
```

---

## Authentication & Authorization

### Authentication Flow
1. **Register/Login** → Email verification via OTP
2. **OAuth Option** → Login via Google (Socialite)
3. **Role-Based Access** → Middleware untuk donor vs admin

### Middleware
- `auth` - Must be authenticated
- `verified` - Must verify email
- `donor` - Access level: Donor
- `admin` - Access level: Admin
- `NoCache` - Prevent caching sensitive data

### Policies
- **CampaignPolicy** - Admin dapat manage campaigns
- **DonationPolicy** - Donor/Admin dapat view donation mereka

---

## Key Features Breakdown

### 1️. Transparansi Dana
- **Public Allocation Log** - Siapa saja bisa lihat `/transparansi`
- **Proof Images** - Setiap alokasi dilengkapi bukti foto
- **Detailed Tracking** - Tahu persis kemana dana digunakan
- **Historical Records** - All transactions logged & auditable

### 2️. Campaign Management
- **Admin-Only Creation** - Only admins dapat buat kampanye
- **Dynamic Status** - active → completed (auto-update bila target tercapai)
- **Category Organization** - Organize by kategori (education, health, dll)
- **Campaign Updates** - Admin post updates → donatur ternotifikasi
- **Progress Tracking** - Visual progress bar & percentage

### 3️. Donation System
- **Multiple Payment Methods** - Transfer Bank, QR Code
- **Proof Verification** - Upload bukti pembayaran
- **Real-time Status** - pending → successful → notification
- **Anonymous Donation** - Option untuk donasi anonim
- **Donor Analytics** - Track total donated, campaigns supported, impact score

### 4️. Activity Logging
- **Full Audit Trail** - Setiap action (create, update, delete) tercatat
- **Change Tracking** - Old vs new values disimpan
- **Admin Accountability** - Who did what & when
- **Searchable Logs** - Query activity history

### 5️. User Experience
- **Responsive Design** - Desktop, tablet, mobile
- **Fast Performance** - Vite + optimized queries
- **Intuitive UI** - Clear CTAs, easy navigation
- **Real-time Feedback** - Validation, success messages

---

## Statistics & Metrics (Dashboard)

Admin dapat melihat:
- Total donation amount (successful)
- Active campaigns count
- Pending donations waiting approval
- Top 5 donors by total amount
- Recent donations feed
- All-time metrics

---

## Deployment

### Production Setup
```bash
# Build assets
npm run build

# Configure .env for production
DB_CONNECTION=pgsql
APP_ENV=production

# Run migrations on server
php artisan migrate --force

# Cache config & routes
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Server Requirements
- PHP 8.2+ with extensions: pdo_pgsql, gd, mbstring
- PostgreSQL 12+
- Nginx/Apache with rewrite rules
- 512MB+ RAM

---

## 🛠️ Development Commands

```bash
# Database
php artisan migrate                 # Run migrations
php artisan migrate:fresh --seed    # Fresh + seed
php artisan tinker                  # Interactive shell

# Testing
php artisan test                    # Run PHPUnit tests

# Code Quality
./vendor/bin/pint                   # Format code

# Assets
npm run dev                         # Watch mode
npm run build                       # Production build

# Cache
php artisan cache:clear            # Clear all cache
php artisan config:cache           # Cache config

# Logs
php artisan logs                    # View recent logs
```

---

## Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| **Backend** | Laravel | 12 |
| **Database** | PostgreSQL | 12+ |
| **Frontend** | Blade + TailwindCSS | - |
| **Asset Pipeline** | Vite | 7+ |
| **Authentication** | Laravel Breeze + Socialite | - |
| **Validation** | Laravel Validation | - |
| **Storage** | Local Storage + S3 Ready | - |

---

## Configuration Files

### Environment (.env)
```
APP_NAME=HopeFund
APP_ENV=production
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=hopefund
QUEUE_CONNECTION=database
MAIL_MAILER=smtp
```

### Key Config
- `config/app.php` - Application settings
- `config/auth.php` - Authentication config
- `config/database.php` - Database connections
- `config/mail.php` - Email configuration
- `config/filesystems.php` - Storage configuration

---

## Troubleshooting

### Database Connection Error
```bash
php artisan tinker
DB::connection()->getPdo()  # Test connection
```

### Cache Issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Permission Issues
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage
```

---

## Contributors

- [ADYATMA YUSUF FARABI]([https://github.com/alice](https://github.com/1Envy2))
- [AATHIFAH DIHYAN CALYSTA]([https://github.com/alice](https://github.com/aathifahdc))
- [WAHYU SRI ARYO PANGESTU]([https://github.com/alice](https://github.com/wahyuwsap))
- [DYMAZ SATYA PUTRA]([https://github.com/alice](https://github.com/DYmazeh))

---

## Project Information

| Aspek | Detail |
|-------|--------|
| **Project Name** | HopeFund |
| **Purpose** | Platform Galangan Dana Transparan |
| **Framework** | Laravel 12 |
| **Database** | PostgreSQL |
| **Created** | November 2025 |
| **Last Updated** | December 2025 |
| **Status** | Production Ready |
| **License** | MIT |

---

## Architecture Highlights

### Clean Code Principles
- ✅ Models dengan proper relationships
- ✅ Controllers with single responsibility
- ✅ Route organization dengan groups & middleware
- ✅ View components reusable
- ✅ Activity logging untuk audit trail

### Security Features
- ✅ CSRF Protection
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ Password Hashing (bcrypt)
- ✅ Email Verification
- ✅ Role-based Access Control
- ✅ Policy-based Authorization

### Performance Optimizations
- ✅ Query optimization (eager loading)
- ✅ Database indexing
- ✅ Caching strategy
- ✅ Asset minification (Vite)
- ✅ Lazy loading images


**HopeFund** - Transparansi & Kepercayaan dalam Setiap Donasi 

Siap digunakan untuk operasional penuh! 

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
