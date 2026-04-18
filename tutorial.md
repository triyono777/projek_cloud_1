# Tutorial Laravel, Docker, MySQL, dan Railway

Panduan ini dibuat untuk pemula yang ingin memahami cara membuat, menjalankan, dan men-deploy aplikasi Laravel berbasis Docker dengan database MySQL ke Railway.

Project yang dipakai:

```text
/Users/triyono/Projek/projek_cloud_1
```

Aplikasi ini adalah web pribadi dengan fitur:

- Halaman blog publik
- Detail artikel blog
- Login admin
- Dashboard manajemen blog
- Tambah, edit, publish/draft, dan hapus artikel
- Health check aplikasi dan database

## 1. Gambaran Alur

Sebelum masuk ke perintah teknis, pahami dulu alurnya:

1. Laravel adalah framework PHP untuk membuat aplikasi web.
2. MySQL dipakai untuk menyimpan data user dan artikel blog.
3. Docker dipakai agar aplikasi dan database bisa dijalankan dengan lingkungan yang konsisten.
4. Docker Compose dipakai untuk menjalankan dua container sekaligus: aplikasi Laravel dan database MySQL.
5. Railway dipakai untuk men-deploy aplikasi ke internet.
6. Railway CLI dipakai agar proses deploy bisa dilakukan dari terminal.

Alur praktiknya:

```text
Kode Laravel -> Docker lokal -> MySQL lokal -> GitHub -> Railway -> MySQL Railway -> Domain publik
```

## 2. Prasyarat

Pastikan software berikut sudah tersedia:

- Docker Desktop
- Git
- PHP dan Composer
- Railway CLI
- Akun GitHub
- Akun Railway

Cek versi Docker:

```bash
docker --version
docker compose version
```

Cek versi PHP dan Composer:

```bash
php --version
composer --version
```

Cek Railway CLI:

```bash
railway --version
```

Jika perintah di atas belum dikenali, berarti software belum terpasang atau belum masuk ke `PATH`.

## 3. Masuk ke Folder Project

Masuk ke folder project:

```bash
cd /Users/triyono/Projek/projek_cloud_1
```

Cek isi folder:

```bash
ls
```

File penting yang akan sering dipakai:

- `Dockerfile`: resep untuk membuat image Docker aplikasi Laravel.
- `compose.yaml`: konfigurasi Docker Compose untuk menjalankan Laravel dan MySQL secara lokal.
- `docker/start.sh`: script startup aplikasi.
- `railway.json`: konfigurasi build dan deploy Railway.
- `.env`: konfigurasi environment lokal Laravel.
- `routes/web.php`: daftar route aplikasi web.
- `database/seeders/DatabaseSeeder.php`: data awal user admin dan contoh artikel blog.

Folder gambar tutorial:

```text
image_tutorial/
```

Gambar di folder tersebut dipakai sebagai panduan visual agar langkah-langkah lebih mudah diikuti saat dibaca di GitHub.

## 4. Memahami Dockerfile

File `Dockerfile` menjelaskan cara membangun container aplikasi Laravel.

Isi utamanya:

```dockerfile
FROM php:8.4-cli
```

Artinya aplikasi memakai image dasar PHP 8.4 CLI.

Bagian berikut menginstall dependency sistem:

```dockerfile
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    default-mysql-client \
    netcat-openbsd \
    && docker-php-ext-install pdo_mysql zip
```

Penjelasan:

- `git` dipakai Composer untuk mengambil package.
- `unzip` dipakai untuk ekstraksi package.
- `default-mysql-client` berguna untuk koneksi ke MySQL.
- `netcat-openbsd` dipakai script startup untuk mengecek apakah database sudah siap.
- `pdo_mysql` adalah extension PHP agar Laravel bisa terkoneksi ke MySQL.
- `zip` sering dibutuhkan package Laravel.

Bagian ini memasang Composer:

```dockerfile
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
```

Bagian ini mengatur folder kerja container:

```dockerfile
WORKDIR /var/www
```

Bagian ini memasang dependency Laravel:

```dockerfile
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts
```

Bagian ini menyalin semua source code ke container:

```dockerfile
COPY . .
```

Bagian ini membuka port aplikasi:

```dockerfile
EXPOSE 8000
```

Bagian ini menjalankan script startup:

```dockerfile
CMD ["start-app"]
```

## 5. Memahami Docker Compose

File `compose.yaml` dipakai untuk menjalankan lebih dari satu container.

Di project ini ada dua service:

- `app`: container Laravel.
- `db`: container MySQL.

Service aplikasi:

```yaml
app:
  build:
    context: .
    dockerfile: Dockerfile
  ports:
    - "${APP_PORT:-8000}:8000"
  env_file:
    - .env
  depends_on:
    db:
      condition: service_healthy
```

Penjelasan:

- `build` berarti Docker Compose membangun image dari `Dockerfile`.
- `ports` berarti port lokal `8000` diarahkan ke port container `8000`.
- `env_file` berarti container membaca konfigurasi dari `.env`.
- `depends_on` berarti aplikasi menunggu database sehat sebelum dijalankan.

Service database:

```yaml
db:
  image: mysql:8.4
  environment:
    MYSQL_DATABASE: ${DB_DATABASE:-projek_cloud_1}
    MYSQL_USER: ${DB_USERNAME:-projek_cloud_1}
    MYSQL_PASSWORD: ${DB_PASSWORD:-secret123}
    MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD:-root123}
  ports:
    - "${DB_FORWARD_PORT:-3307}:3306"
```

Penjelasan:

- MySQL berjalan di container pada port `3306`.
- Dari komputer lokal, MySQL bisa diakses lewat port `3307`.
- Database default bernama `projek_cloud_1`.
- User default bernama `projek_cloud_1`.
- Password default adalah `secret123`.

## 6. Menyiapkan File Environment Lokal

Laravel membaca konfigurasi dari file `.env`.

Jika `.env` belum ada, buat dari contoh:

```bash
cp .env.example .env
```

Buat application key:

```bash
php artisan key:generate
```

Pastikan konfigurasi database lokal seperti ini:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=projek_cloud_1
DB_USERNAME=projek_cloud_1
DB_PASSWORD=secret123
```

Penting:

- Untuk Laravel di dalam container, host database adalah `db`.
- Untuk aplikasi database client di komputer host, gunakan `127.0.0.1` dan port `3307`.

## 7. Menjalankan Aplikasi Secara Lokal

Jalankan build dan container:

```bash
docker compose up --build
```

Setelah perintah berjalan, Docker Desktop akan menampilkan container aplikasi dan database.

![Docker Desktop menampilkan container Laravel dan MySQL](image_tutorial/docker_dekstop.png)

Jika ingin berjalan di background:

```bash
docker compose up -d --build
```

Saat container berjalan, buka:

```text
http://localhost:8000
```

Tampilan halaman utama web pribadi:

![Halaman utama web pribadi Laravel](image_tutorial/home_page.png)

Cek health endpoint:

```text
http://localhost:8000/health
```

Jika berhasil, response kurang lebih seperti ini:

```json
{
  "status": "ok",
  "app": "Projek Cloud 1",
  "environment": "local",
  "database": {
    "connection": "mysql",
    "status": "connected"
  }
}
```

## 8. Menjalankan Perintah Laravel di Docker

Jalankan migration:

```bash
docker compose exec app php artisan migrate
```

Jalankan seeder:

```bash
docker compose exec app php artisan db:seed
```

Lihat daftar route:

```bash
docker compose exec app php artisan route:list
```

Masuk ke shell container:

```bash
docker compose exec app sh
```

Menghentikan container:

```bash
docker compose down
```

Menghentikan container sekaligus menghapus data MySQL lokal:

```bash
docker compose down -v
```

Hati-hati dengan `-v` karena data database lokal akan hilang.

## 9. Login Dashboard Blog

Aplikasi memiliki akun admin default dari seeder:

```text
Email: admin@example.com
Password: password
```

Buka halaman login:

```text
http://localhost:8000/login
```

Setelah login, buka dashboard:

```text
http://localhost:8000/dashboard/posts
```

Tampilan dashboard admin untuk manajemen blog:

![Dashboard admin manajemen blog](image_tutorial/dashboard_admin.png)

Di dashboard, admin bisa:

- Melihat daftar artikel.
- Menambah artikel.
- Mengedit artikel.
- Mengubah status publish/draft.
- Menghapus artikel.

## 10. Menjalankan Test

Test dipakai untuk memastikan fitur penting tidak rusak.

Jalankan test:

```bash
php artisan test
```

Jika ingin menjalankan dari container:

```bash
docker compose exec app php artisan test
```

Contoh hasil sukses:

```text
Tests: 6 passed
```

## 11. Menyiapkan Git dan GitHub

Cek status Git:

```bash
git status
```

Tambahkan file yang berubah:

```bash
git add .
```

Buat commit:

```bash
git commit -m "Tulis pesan perubahan"
```

Push ke GitHub:

```bash
git push
```

Repository project:

```text
https://github.com/triyono777/projek_cloud_1
```

Kenapa GitHub penting?

- Kode tersimpan aman di remote repository.
- Railway bisa mengambil kode dari GitHub.
- Riwayat perubahan project jelas melalui commit.

## 12. Daftar Railway

Langkah daftar Railway:

1. Buka `https://railway.com`.
2. Klik sign up atau login.
3. Gunakan akun GitHub agar lebih mudah menghubungkan repository.
4. Berikan izin Railway untuk membaca repository GitHub jika diminta.
5. Setelah login, Railway dashboard siap digunakan.

Tampilan halaman utama Railway:

![Halaman utama Railway](image_tutorial/railway_homepage.png)

Tampilan halaman login Railway:

![Halaman login Railway](image_tutorial/railway_login.png)

Railway menyediakan project yang berisi satu atau beberapa service.

Contoh service:

- Service aplikasi Laravel.
- Service database MySQL.

## 13. Cara Pakai Railway CLI

Railway CLI adalah alat terminal untuk mengelola project Railway tanpa harus selalu membuka dashboard web.

Dengan Railway CLI, kita bisa:

- Login ke akun Railway.
- Membuat project baru.
- Menghubungkan folder lokal ke project Railway.
- Membuat service aplikasi.
- Membuat database MySQL.
- Mengatur environment variable.
- Deploy aplikasi.
- Melihat log aplikasi.
- Membuat domain publik.
- Mengecek status deployment.

### 13.1 Install Railway CLI

Jika Railway CLI belum terpasang, install dengan npm:

```bash
npm install -g @railway/cli
```

Contoh halaman dokumentasi atau instruksi install Railway CLI:

![Panduan install Railway CLI](image_tutorial/railway_install.png)

Cek apakah sudah berhasil:

```bash
railway --version
```

Jika muncul nomor versi, Railway CLI sudah siap dipakai.

Jika perintah `railway` tidak ditemukan, kemungkinan penyebabnya:

- Node.js atau npm belum terinstall.
- Folder global npm belum masuk ke `PATH`.
- Terminal perlu ditutup dan dibuka ulang.

### 13.2 Login ke Railway

Login ke akun Railway:

```bash
railway login
```

Biasanya Railway akan membuka browser. Ikuti proses login sampai selesai.

Contoh proses login Railway CLI di terminal:

![Login Railway CLI di terminal](image_tutorial/railway_cli_login.png)

Cek akun yang aktif:

```bash
railway whoami
```

Jika berhasil, terminal akan menampilkan email atau identitas akun Railway.

### 13.3 Melihat Bantuan Perintah

Jika lupa perintah, gunakan:

```bash
railway help
```

Untuk bantuan pada perintah tertentu:

```bash
railway help up
railway help variable
railway help logs
```

Biasakan mengecek `help` karena opsi CLI bisa bertambah mengikuti versi Railway.

### 13.4 Membuat Project Railway Baru

Masuk dulu ke folder project:

```bash
cd /Users/triyono/Projek/projek_cloud_1
```

Buat project Railway baru:

```bash
railway init --name projek-cloud-1
```

Penjelasan:

- `railway init` membuat project Railway.
- `--name projek-cloud-1` memberi nama project.
- Folder lokal akan dikaitkan dengan project Railway tersebut.

Setelah selesai, cek status:

```bash
railway status
```

### 13.5 Menghubungkan Folder Lokal ke Project yang Sudah Ada

Jika project sudah pernah dibuat di Railway, gunakan:

```bash
railway link
```

Railway CLI akan menampilkan daftar project. Pilih project yang sesuai.

Setelah link berhasil, cek:

```bash
railway status
```

Output yang benar biasanya menampilkan:

```text
Project: projek-cloud-1
Environment: production
Service: projek-cloud-1
```

Contoh tampilan dashboard project Railway setelah service aplikasi dan database tersedia:

![Dashboard project Railway](image_tutorial/rail_way_dashboard.png)

### 13.6 Memilih Environment

Railway mendukung environment seperti `production`, `staging`, atau environment lain.

Cek environment aktif:

```bash
railway status
```

Jika ingin mengganti environment:

```bash
railway environment
```

Pilih environment yang ingin dipakai.

Untuk pemula, gunakan `production` agar sederhana.

### 13.7 Membuat Service Aplikasi

Service aplikasi adalah tempat Laravel berjalan.

Buat service aplikasi:

```bash
railway add --service projek-cloud-1
```

Jika service sudah ada, tidak perlu membuat lagi.

Cek status:

```bash
railway status
```

### 13.8 Membuat Database MySQL

Buat database MySQL:

```bash
railway add --database mysql
```

Railway akan membuat service database bernama `MySQL` atau nama serupa.

Cek service di project:

```bash
railway status
```

Cek variable database:

```bash
railway variable list --service MySQL
```

Variable penting dari MySQL:

```text
MYSQLHOST
MYSQLPORT
MYSQLDATABASE
MYSQLUSER
MYSQLPASSWORD
```

Variable ini dipakai Laravel untuk koneksi database production.

### 13.9 Melihat Environment Variable

Melihat variable service aplikasi:

```bash
railway variable list --service projek-cloud-1
```

Melihat variable service MySQL:

```bash
railway variable list --service MySQL
```

Melihat variable dalam format JSON:

```bash
railway variable list --service MySQL --json
```

Contoh tampilan variable database MySQL di Railway:

![Variable database MySQL Railway](image_tutorial/railway_variabel_database.png)

Format JSON berguna jika ingin menyalin nilai variable dengan lebih rapi.

### 13.10 Mengatur Environment Variable

Set variable aplikasi Laravel:

```bash
railway variable set APP_NAME="Projek Cloud 1" APP_ENV=production APP_DEBUG=false --service projek-cloud-1
```

Buat `APP_KEY`:

```bash
php artisan key:generate --show
```

Set `APP_KEY`:

```bash
railway variable set APP_KEY=base64:ISI_APP_KEY_ANDA --service projek-cloud-1
```

Set `APP_URL` jika sudah punya domain:

```bash
railway variable set APP_URL=https://projek-cloud-1-production.up.railway.app --service projek-cloud-1
```

Jika variable MySQL belum tersedia di service aplikasi, set manual:

```bash
railway variable set MYSQLHOST="mysql.railway.internal" --service projek-cloud-1
railway variable set MYSQLPORT="3306" --service projek-cloud-1
railway variable set MYSQLDATABASE="railway" --service projek-cloud-1
railway variable set MYSQLUSER="root" --service projek-cloud-1
railway variable set MYSQLPASSWORD="password-dari-railway" --service projek-cloud-1
```

Penting:

- Ganti `password-dari-railway` dengan password asli dari variable `MYSQLPASSWORD` di service MySQL Railway.
- Jangan mengetik password asli di file dokumentasi.
- Jangan commit password production ke GitHub.
- Simpan secret hanya di Railway variable.

### 13.11 Deploy Aplikasi

Deploy aplikasi dari folder lokal:

```bash
railway up --service projek-cloud-1
```

Deploy tanpa mengikuti log terus-menerus:

```bash
railway up --service projek-cloud-1 --detach
```

Tambahkan pesan deploy:

```bash
railway up --service projek-cloud-1 --detach --message "Deploy Laravel blog"
```

Penjelasan:

- `railway up` mengirim source code lokal ke Railway.
- `--service projek-cloud-1` menentukan service target.
- `--detach` membuat terminal langsung kembali setelah upload.
- `--message` memberi keterangan deployment.

### 13.12 Mengecek Deployment

Lihat daftar deployment:

```bash
railway deployment list --service projek-cloud-1
```

Jika ingin output JSON:

```bash
railway deployment list --service projek-cloud-1 --json --limit 1
```

Status yang umum:

- `BUILDING`: Railway sedang membangun image.
- `DEPLOYING`: Railway sedang menjalankan container.
- `SUCCESS`: Deploy berhasil.
- `FAILED`: Deploy gagal.

Jika gagal, lanjut cek log.

### 13.13 Melihat Log

Lihat log aplikasi:

```bash
railway logs --service projek-cloud-1
```

Log berguna untuk mencari error seperti:

- `APP_KEY` belum diset.
- Database tidak bisa diakses.
- Migration gagal.
- Health check gagal.
- Port aplikasi salah.

### 13.14 Membuat Domain Publik

Buat domain Railway:

```bash
railway domain --service projek-cloud-1
```

Setelah domain dibuat, cek di browser:

```text
https://domain-anda.up.railway.app
```

Untuk project ini:

```text
https://projek-cloud-1-production.up.railway.app
```

### 13.15 Membuka Shell Railway

Jika perlu menjalankan command dalam konteks Railway variable, gunakan:

```bash
railway run php artisan route:list
```

Contoh lain:

```bash
railway run php artisan migrate --force
railway run php artisan db:seed --force
```

Catatan:

- Untuk project ini migration dan seeder sudah dijalankan otomatis oleh `docker/start.sh`.
- `railway run` berguna untuk command manual saat debugging.

### 13.16 Alur Railway CLI yang Direkomendasikan

Untuk deploy pertama kali:

```bash
cd /Users/triyono/Projek/projek_cloud_1
railway login
railway init --name projek-cloud-1
railway add --database mysql
railway add --service projek-cloud-1
php artisan key:generate --show
railway variable set APP_NAME="Projek Cloud 1" APP_ENV=production APP_DEBUG=false APP_KEY=base64:ISI_APP_KEY_ANDA --service projek-cloud-1
railway variable list --service MySQL --json
railway variable set MYSQLHOST="mysql.railway.internal" --service projek-cloud-1
railway variable set MYSQLPORT="3306" --service projek-cloud-1
railway variable set MYSQLDATABASE="railway" --service projek-cloud-1
railway variable set MYSQLUSER="root" --service projek-cloud-1
railway variable set MYSQLPASSWORD="password-dari-railway" --service projek-cloud-1
railway up --service projek-cloud-1 --detach
railway domain --service projek-cloud-1
railway logs --service projek-cloud-1
```

Untuk deploy berikutnya setelah ada perubahan kode:

```bash
git status
git add .
git commit -m "Pesan perubahan"
git push
railway up --service projek-cloud-1 --detach
railway deployment list --service projek-cloud-1
```

### 13.17 Kesalahan Umum Railway CLI

Jika `railway status` tidak menampilkan project yang benar:

```bash
railway link
```

Jika salah service saat deploy:

```bash
railway up --service projek-cloud-1 --detach
```

Jika aplikasi tidak bisa konek database:

```bash
railway variable list --service projek-cloud-1
railway variable list --service MySQL
```

Jika deploy gagal tanpa alasan jelas:

```bash
railway logs --service projek-cloud-1
railway deployment list --service projek-cloud-1
```

Jika belum login:

```bash
railway login
railway whoami
```

## 14. Apa yang Terjadi Saat Aplikasi Start

Railway menjalankan command:

```bash
start-app
```

Command ini berasal dari file:

```text
docker/start.sh
```

Script ini melakukan beberapa hal:

1. Masuk ke folder `/var/www`.
2. Membaca environment variable Railway.
3. Mengubah variabel `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, dan `MYSQLPASSWORD` menjadi konfigurasi database Laravel.
4. Menunggu database siap.
5. Menjalankan migration.
6. Menjalankan seeder.
7. Menjalankan Laravel server.

Bagian penting:

```sh
php artisan migrate --force
php artisan db:seed --force
```

Penjelasan:

- `migrate --force` membuat atau memperbarui tabel database di production.
- `db:seed --force` mengisi data awal seperti user admin dan contoh artikel.
- `--force` diperlukan karena Laravel biasanya meminta konfirmasi saat berjalan di production.

## 15. Validasi Setelah Deploy

Setelah deploy, cek beberapa halaman:

```bash
curl -sS https://projek-cloud-1-production.up.railway.app/health
```

Cek halaman utama:

```bash
curl -sS https://projek-cloud-1-production.up.railway.app
```

Cek halaman login:

```bash
curl -I https://projek-cloud-1-production.up.railway.app/login
```

Cek dashboard tanpa login:

```bash
curl -I https://projek-cloud-1-production.up.railway.app/dashboard/posts
```

Hasil yang benar:

- `/health` mengembalikan status `ok`.
- Halaman utama menampilkan blog.
- `/login` mengembalikan HTTP `200`.
- `/dashboard/posts` mengembalikan redirect ke `/login` jika belum login.

## 16. Troubleshooting Docker

### Port 8000 sudah dipakai

Jalankan dengan port lain:

```bash
APP_PORT=8080 docker compose up --build
```

Buka:

```text
http://localhost:8080
```

### Port 3307 sudah dipakai

Jalankan MySQL lokal dengan port lain:

```bash
DB_FORWARD_PORT=3308 docker compose up --build
```

### Container gagal terkoneksi ke database

Cek log:

```bash
docker compose logs app
docker compose logs db
```

Cek container aktif:

```bash
docker compose ps
```

### Ingin reset database lokal

Hentikan container dan hapus volume:

```bash
docker compose down -v
docker compose up --build
```

Setelah itu database akan dibuat ulang dari awal.

## 17. Troubleshooting Railway

### Deploy gagal

Cek status:

```bash
railway status
```

Cek log:

```bash
railway logs --service projek-cloud-1
```

Cek deployment:

```bash
railway deployment list --service projek-cloud-1
```

### Health check gagal

Kemungkinan penyebab:

- Aplikasi tidak berjalan.
- `APP_KEY` belum diset.
- Database MySQL belum dibuat.
- Variabel MySQL belum tersedia di service aplikasi.
- Migration gagal.

Cek variable:

```bash
railway variable list --service projek-cloud-1
```

Pastikan minimal ada:

```text
APP_KEY
APP_ENV
APP_DEBUG
MYSQLHOST
MYSQLPORT
MYSQLDATABASE
MYSQLUSER
MYSQLPASSWORD
```

### Error database

Cek apakah service MySQL ada:

```bash
railway status
```

Cek variable MySQL:

```bash
railway variable list --service MySQL
```

Jika perlu, set ulang variable MySQL ke service aplikasi.

### Redirect menjadi http, bukan https

Aplikasi Laravel di Railway berjalan di belakang proxy. Project ini sudah mengatur trusted proxy di:

```text
bootstrap/app.php
```

Jika masalah muncul lagi, pastikan konfigurasi trusted proxy tidak dihapus.

## 18. Ringkasan Perintah Penting

Menjalankan lokal:

```bash
docker compose up --build
```

Menjalankan lokal di background:

```bash
docker compose up -d --build
```

Menghentikan lokal:

```bash
docker compose down
```

Migration:

```bash
docker compose exec app php artisan migrate
```

Seeder:

```bash
docker compose exec app php artisan db:seed
```

Test:

```bash
php artisan test
```

Login Railway:

```bash
railway login
```

Hubungkan project:

```bash
railway link
```

Deploy:

```bash
railway up --service projek-cloud-1 --detach
```

Log Railway:

```bash
railway logs --service projek-cloud-1
```

## 19. Latihan untuk Mahasiswa

Latihan dasar:

1. Jalankan aplikasi lokal dengan Docker.
2. Buka halaman blog.
3. Login ke dashboard.
4. Tambahkan satu artikel baru.
5. Ubah artikel menjadi draft.
6. Publish kembali artikel tersebut.
7. Jalankan test.
8. Commit perubahan.
9. Push ke GitHub.
10. Deploy ulang ke Railway.

Latihan pengembangan:

1. Tambahkan field kategori artikel.
2. Tambahkan halaman profil pemilik web.
3. Tambahkan upload gambar artikel.
4. Tambahkan validasi slug unik.
5. Tambahkan pagination di halaman blog.

## 20. Kesimpulan

Dengan project ini, alur cloud computing yang dipelajari adalah:

- Membuat aplikasi web Laravel.
- Menghubungkan aplikasi ke database MySQL.
- Membungkus aplikasi dengan Docker.
- Menjalankan multi-container dengan Docker Compose.
- Menyimpan kode di GitHub.
- Men-deploy aplikasi ke Railway.
- Mengelola environment variable production.
- Mengecek aplikasi production dengan health check.

Jika semua langkah berhasil, berarti aplikasi sudah bisa dijalankan secara lokal dan diakses publik lewat Railway.
