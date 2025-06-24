<p align="center">
    <h1 align="center">Lapor Pak</h1>
</p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Tentang Lapor Pak

Lapor Pak adalah sistem pelaporan masyarakat berbasis web yang memungkinkan warga untuk melaporkan berbagai masalah di lingkungan mereka dengan mudah dan cepat. Aplikasi ini dibangun menggunakan Laravel framework dengan fitur-fitur modern seperti:

-   **Interface yang User-Friendly** - Antarmuka yang mudah digunakan untuk semua kalangan
-   **Sistem Pelaporan Real-time** - Pelaporan dengan foto dan lokasi GPS
-   **Dashboard Admin** - Panel admin untuk mengelola laporan dan status
-   **Tracking Progress** - Sistem pelacakan status laporan untuk masyarakat
-   **Kategorisasi Otomatis** - AI/ML untuk mengkategorikan laporan secara otomatis
-   **Responsive Design** - Kompatibel dengan desktop dan mobile
-   **Authentication & Authorization** - Sistem login yang aman dengan role-based access

## Fitur Utama

### Untuk Masyarakat

-   📱 **Buat Laporan** - Laporkan masalah dengan foto dan lokasi
-   📍 **GPS Integration** - Lokasi otomatis terdeteksi
-   📊 **Track Progress** - Pantau status laporan Anda
-   🤖 **Smart Categorization** - Kategori laporan otomatis dengan AI
-   📷 **Camera Integration** - Ambil foto langsung dari aplikasi

### Untuk Admin

-   🎛️ **Dashboard Management** - Kelola semua laporan dari satu tempat
-   👥 **User Management** - Kelola data masyarakat
-   📋 **Report Categories** - Kelola kategori laporan
-   📈 **Status Updates** - Update progress laporan dengan bukti
-   🗺️ **Map View** - Lihat lokasi laporan di peta

## Teknologi yang Digunakan

-   **Backend**: Laravel 10.x
-   **Frontend**: Blade Templates, Bootstrap, JavaScript
-   **Database**: MySQL
-   **Storage**: Laravel File Storage
-   **Authentication**: Laravel Sanctum
-   **Maps**: OpenStreetMap/Leaflet
-   **AI/ML**: Integration dengan Gemini API dan model lokal
-   **Icons**: FontAwesome
-   **Notifications**: SweetAlert

## Requirements

-   PHP >= 8.1
-   Composer
-   Node.js & NPM
-   MySQL
-   XAMPP/LAMP/WAMP (untuk development)

## Instalasi

1. **Clone repository**

```bash
git clone https://github.com/username/lapor-pak.git
cd lapor-pak
```

2. **Install dependencies**

```bash
composer install
npm install
```

3. **Setup environment**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi database**
   Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lapor_pak
DB_USERNAME=root
DB_PASSWORD=
```

5. **Setup storage link**

```bash
php artisan storage:link
```

6. **Run migrations dan seeders**

```bash
php artisan migrate
php artisan db:seed
```

7. **Build assets**

```bash
npm run dev
# atau untuk production
npm run build
```

8. **Start development server**

```bash
php artisan serve
```

## Default Admin Account

Setelah menjalankan seeder, Anda dapat login sebagai admin dengan:

-   **Email**: admin@laporpak.com
-   **Password**: password

## Struktur Project

```
app/
├── Http/Controllers/
│   ├── Admin/          # Controller untuk admin
│   └── Auth/           # Controller authentication
├── Models/             # Eloquent models
├── Interfaces/         # Repository interfaces
└── Repositories/       # Repository implementations

resources/
├── views/
│   ├── pages/
│   │   ├── admin/      # View admin
│   │   ├── app/        # View user
│   │   └── auth/       # View authentication
│   └── layouts/        # Layout templates

public/
└── assets/
    └── app/           # CSS, JS, dan assets aplikasi
```

## API Integration

Aplikasi ini terintegrasi dengan:

-   **Gemini AI** untuk kategorisasi laporan otomatis
-   **Machine Learning Model** lokal sebagai fallback
-   **OpenStreetMap** untuk peta dan geocoding

## Contributing

Jika Anda ingin berkontribusi pada project ini:

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## License

Project ini menggunakan lisensi [MIT License](https://opensource.org/licenses/MIT).

## Contact

Untuk pertanyaan atau dukungan, silakan hubungi:

-   Email: admin@laporpak.com
-   Website: [Lapor Pak](https://laporpak.com)

---

**Dibuat dengan ❤️ menggunakan Laravel Framework**
