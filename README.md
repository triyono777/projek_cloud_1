# Projek Cloud 1

Tutorial praktik Laravel + Docker + MySQL + Railway CLI.

Proyek ini disiapkan agar bisa dipakai langsung untuk:

1. Menjalankan Laravel secara lokal dengan Docker.
2. Menghubungkan Laravel ke MySQL container.
3. Menguji endpoint aplikasi dan koneksi database.
4. Mengelola blog web pribadi melalui dashboard login.
5. Men-deploy Laravel ke Railway menggunakan Dockerfile.
6. Menambahkan MySQL service di Railway lewat CLI.

## Stack

* Laravel 13
* PHP 8.4
* MySQL 8.4
* phpMyAdmin 5.2 untuk kebutuhan lokal
* Docker Compose
* Railway CLI
* Railway service berbasis Dockerfile

## Struktur File Penting

* `Dockerfile`: image PHP untuk aplikasi Laravel
* `compose.yaml`: stack lokal Laravel + MySQL + phpMyAdmin
* `docker/start.sh`: startup script untuk menunggu database dan menjalankan migrasi
* `railway.json`: konfigurasi build dan deploy Railway
* `routes/web.php`: route halaman utama dan endpoint `/health`
* `railway.json`: Railway memakai `/up` untuk health check platform

## 1. Menjalankan Proyek Secara Lokal

Masuk ke folder proyek:

```bash
cd /Users/triyono/Projek/projek_cloud_1
```

Jalankan container:

```bash
docker compose up --build
```

Setelah service aktif, buka:

* Aplikasi: [http://localhost:8000](http://localhost:8000)
* Health check: [http://localhost:8000/health](http://localhost:8000/health)
* phpMyAdmin: [http://localhost:8081](http://localhost:8081)

Jika ingin menjalankan di background:

```bash
docker compose up -d --build
```

Untuk menghentikan service:

```bash
docker compose down
```

## 2. Konfigurasi MySQL Lokal

Konfigurasi default lokal:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=projek_cloud_1
DB_USERNAME=projek_cloud_1
DB_PASSWORD=secret123
```

Port MySQL yang diteruskan ke host:

```text
3307 -> 3306
```

Jadi jika ingin mengakses MySQL dari host, gunakan:

* Host: `127.0.0.1`
* Port: `3307`
* Database: `projek_cloud_1`
* Username: `projek_cloud_1`
* Password: `secret123`

Jika ingin mengelola database lewat browser, buka phpMyAdmin:

```text
http://localhost:8081
```

Login phpMyAdmin:

* Server: `db`
* Username: `projek_cloud_1`
* Password: `secret123`

## 3. Perintah Laravel yang Berguna

Menjalankan migrasi manual:

```bash
docker compose exec app php artisan migrate
```

Masuk ke shell container aplikasi:

```bash
docker compose exec app sh
```

Menjalankan test:

```bash
php artisan test
```

## 4. Fitur Aplikasi

Fitur utama:

* Halaman blog publik di `/`
* Detail post di `/blog/{slug}`
* Login dashboard di `/login`
* Dashboard manajemen blog di `/dashboard/posts`
* CRUD post blog: tambah, edit, publish/draft, hapus
* Health check aplikasi dan database di `/health`

Akun admin default dibuat oleh seeder:

```text
Email: admin@example.com
Password: password
```

## 5. Tutorial Deployment ke Railway CLI

### 5.1 Login ke Railway

Cek versi CLI:

```bash
railway --version
```

Login jika belum:

```bash
railway login
```

Cek user:

```bash
railway whoami
```

### 5.2 Buat Project Railway

Dari folder proyek:

```bash
railway init --name projek-cloud-1
```

Jika project sudah pernah dibuat di Railway, gunakan:

```bash
railway link
```

Lihat status project:

```bash
railway status
```

### 5.3 Tambahkan MySQL di Railway

Tambahkan database MySQL:

```bash
railway add --database mysql
```

Railway akan membuat service MySQL dan menyediakan variabel pada service database seperti:

```env
MYSQLHOST
MYSQLPORT
MYSQLDATABASE
MYSQLUSER
MYSQLPASSWORD
```

Jika aplikasi dibuat dari template yang otomatis mewarisi variabel database, startup script proyek ini akan memetakan variabel Railway tersebut ke konfigurasi Laravel:

```env
DB_HOST=${MYSQLHOST}
DB_PORT=${MYSQLPORT}
DB_DATABASE=${MYSQLDATABASE}
DB_USERNAME=${MYSQLUSER}
DB_PASSWORD=${MYSQLPASSWORD}
```

Jika menggunakan `railway up` dari folder lokal dan service aplikasi tidak otomatis menerima variabel MySQL, salin variabel MySQL ke service aplikasi:

```bash
railway variable list --service MySQL --json
railway variable set MYSQLHOST="mysql.railway.internal" --service projek-cloud-1
railway variable set MYSQLPORT="3306" --service projek-cloud-1
railway variable set MYSQLDATABASE="railway" --service projek-cloud-1
railway variable set MYSQLUSER="root" --service projek-cloud-1
railway variable set MYSQLPASSWORD="password-dari-railway" --service projek-cloud-1
```

Ganti `password-dari-railway` dengan nilai asli `MYSQLPASSWORD` dari service MySQL Railway.

### 5.4 Tambahkan Service Aplikasi

Jika project belum punya service aplikasi:

```bash
railway add --service projek-cloud-1
```

Jika memakai repo GitHub:

```bash
railway add --service projek-cloud-1 --repo https://github.com/triyono777/projek_cloud_1
```

### 5.5 Set Environment Variables

Buat APP_KEY:

```bash
docker compose run --rm --no-deps app php artisan key:generate --show
```

Set variabel aplikasi satu per satu:

```bash
railway variable set APP_NAME="Projek Cloud 1" --service projek-cloud-1
railway variable set APP_ENV="production" --service projek-cloud-1
railway variable set APP_DEBUG="false" --service projek-cloud-1
railway variable set APP_KEY="base64:ISI_APP_KEY_ANDA" --service projek-cloud-1
```

Pastikan service aplikasi juga memiliki variabel MySQL dari service database:

```bash
railway variable list --service MySQL --json
railway variable set MYSQLHOST="mysql.railway.internal" --service projek-cloud-1
railway variable set MYSQLPORT="3306" --service projek-cloud-1
railway variable set MYSQLDATABASE="railway" --service projek-cloud-1
railway variable set MYSQLUSER="root" --service projek-cloud-1
railway variable set MYSQLPASSWORD="password-dari-railway" --service projek-cloud-1
```

Ganti `password-dari-railway` dengan nilai asli `MYSQLPASSWORD` dari service MySQL Railway.

Jika Railway sudah memberi public domain, salin domain dari service aplikasi bagian public networking atau domain URL.

Contoh lokasi domain Railway:

![Lokasi domain URL Railway](image_tutorial/railway_lokasi_domian_url.png)

Set `APP_URL` agar sama dengan domain Railway yang aktif:

```bash
railway variable set APP_URL="https://domain-anda.up.railway.app" --service projek-cloud-1
```

### 5.6 Deploy dari Folder Lokal

Deploy dari folder proyek:

```bash
railway up --service projek-cloud-1
```

Jika ingin build dan deploy tanpa menempel ke log:

```bash
railway up --service projek-cloud-1 --detach
```

Lihat deployment:

```bash
railway deployment
```

Lihat log:

```bash
railway logs --service projek-cloud-1
```

### 5.7 Buat Domain Publik

Generate domain Railway untuk service:

```bash
railway domain --service projek-cloud-1
```

Setelah domain aktif, cek:

```text
https://domain-anda.up.railway.app
https://domain-anda.up.railway.app/up
https://domain-anda.up.railway.app/health
```

Setelah domain aktif, update `APP_URL` jika belum sama dengan domain tersebut:

```bash
railway variable set APP_URL="https://domain-anda.up.railway.app" --service projek-cloud-1
```

## 6. Alur Kerja yang Disarankan

1. Jalankan lokal dengan `docker compose up --build`.
2. Pastikan halaman utama dan `/health` bisa diakses.
3. Commit perubahan ke Git.
4. Push ke repository remote.
5. Jalankan `railway init` atau `railway link`.
6. Tambahkan MySQL dengan `railway add --database mysql`.
7. Tambahkan service aplikasi dengan `railway add --service projek-cloud-1`.
8. Set variabel aplikasi dengan `railway variable set`.
9. Deploy dengan `railway up --service projek-cloud-1`.
10. Generate domain dengan `railway domain --service projek-cloud-1`.

## 7. Troubleshooting

### Port 8000 sudah dipakai

Ubah port host:

```bash
APP_PORT=8080 docker compose up --build
```

### Port 3307 sudah dipakai

Ubah port database host:

```bash
DB_FORWARD_PORT=3308 docker compose up --build
```

### Port 8081 phpMyAdmin sudah dipakai

Ubah port phpMyAdmin:

```bash
PHPMYADMIN_PORT=8082 docker compose up --build
```

### Container app gagal terkoneksi ke database lokal

Cek log:

```bash
docker compose logs app
docker compose logs db
```

### Deploy Railway gagal

Cek:

* `railway status`
* `railway logs --service projek-cloud-1`
* variabel `APP_KEY`
* service MySQL sudah dibuat
* variabel `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, dan `MYSQLPASSWORD` tersedia

### Health check Railway gagal

Railway memakai `/up` untuk mengecek apakah container Laravel hidup. Endpoint `/health` dipakai untuk cek aplikasi plus database.

Cek log:

```bash
railway logs --service projek-cloud-1
```

Jika terminal menampilkan `Deploy failed`, `service unavailable`, dan banyak request ke `/health`, ambil update terbaru lalu deploy ulang:

```bash
git pull
railway up --service projek-cloud-1
```

Jika `/up` berhasil tetapi `/`, `/login`, atau `/health` masih menampilkan `500 Server Error`, cek variable production:

```bash
railway variable list --service projek-cloud-1
```

Pastikan `APP_KEY`, `APP_URL`, dan semua variable MySQL sudah tersedia. Setelah variable diperbaiki, deploy ulang:

```bash
railway up --service projek-cloud-1
```

Jika `/up` berhasil tetapi `/health` menunjukkan database `disconnected`, pastikan MySQL service sudah dibuat dan service aplikasi mendapat variabel MySQL.

## 8. Endpoint yang Tersedia

* `/` menampilkan landing page proyek
* `/login` menampilkan form login admin
* `/dashboard/posts` menampilkan dashboard manajemen blog
* `/up` menampilkan status aplikasi hidup untuk Railway health check
* `/health` menampilkan JSON status aplikasi dan status koneksi database

## Referensi

* [Railway CLI](https://docs.railway.com/guides/cli)
* [Railway Deployments](https://docs.railway.com/guides/deployments)
* [Railway Databases](https://docs.railway.com/guides/databases)
