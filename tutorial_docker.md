# Tutorial Docker untuk Aplikasi Laravel

Panduan ini membahas cara setting aplikasi dengan Docker, mulai dari konsep dasar sampai studi kasus menjalankan aplikasi Laravel web pribadi dengan MySQL.

Project studi kasus:

```text
https://github.com/triyono777/projek_cloud_1
```

Folder lokal project:

```text
/Users/triyono/Projek/projek_cloud_1
```

## 1. Apa Itu Docker

Docker adalah alat untuk menjalankan aplikasi di dalam container.

Container bisa dibayangkan seperti kotak aplikasi yang berisi semua kebutuhan aplikasi agar bisa berjalan, misalnya:

- PHP
- Composer
- Extension PHP
- Source code aplikasi
- Konfigurasi server
- Tool pendukung

Dengan Docker, aplikasi tidak terlalu bergantung pada setting komputer masing-masing. Selama komputer memiliki Docker, aplikasi bisa dijalankan dengan cara yang hampir sama.

Contoh masalah tanpa Docker:

```text
Laptop A memakai PHP 8.4
Laptop B memakai PHP 8.1
Server memakai PHP 8.2
```

Akibatnya aplikasi bisa berjalan di satu tempat tetapi error di tempat lain.

Dengan Docker:

```text
Semua menjalankan aplikasi dari image yang sama
```

Hasilnya environment lebih konsisten.

## 2. Analogi Sederhana Docker

Bayangkan aplikasi Laravel seperti makanan yang ingin dikirim ke banyak tempat.

Tanpa Docker:

- Setiap tempat harus menyiapkan dapur sendiri.
- Kompor bisa berbeda.
- Bahan bisa kurang.
- Cara memasak bisa tidak sama.

Dengan Docker:

- Aplikasi dikemas bersama alat dan kebutuhannya.
- Cara menjalankan aplikasi ditulis di file konfigurasi.
- Komputer lain cukup menjalankan container.

Analogi singkat:

```text
Dockerfile = resep membuat paket aplikasi
Image = paket aplikasi yang sudah dibuat
Container = paket aplikasi yang sedang berjalan
Docker Compose = cara menjalankan beberapa container sekaligus
```

## 3. Bagaimana Memulai Docker

Untuk pemula, urutan belajar Docker yang disarankan adalah:

1. Install Docker Desktop.
2. Cek perintah `docker` dan `docker compose`.
3. Pahami apa itu image dan container.
4. Buat atau baca `Dockerfile`.
5. Buat atau baca `compose.yaml`.
6. Jalankan aplikasi dengan `docker compose up`.
7. Cek aplikasi di browser.
8. Lihat log container jika terjadi error.
9. Hentikan container dengan `docker compose down`.

Perintah dasar yang sering dipakai:

```bash
docker --version
docker compose version
docker compose build
docker compose up
docker compose up -d
docker compose ps
docker compose logs
docker compose down
```

Penjelasan singkat:

- `docker --version` mengecek Docker sudah terinstall.
- `docker compose version` mengecek Docker Compose tersedia.
- `docker compose build` membuat image.
- `docker compose up` menjalankan container.
- `docker compose up -d` menjalankan container di background.
- `docker compose ps` melihat container yang aktif.
- `docker compose logs` melihat log container.
- `docker compose down` menghentikan container.

## 4. File Apa Saja yang Dibuat

Untuk setting Docker pada aplikasi Laravel, file utama yang perlu dibuat atau disiapkan adalah:

```text
Dockerfile
compose.yaml
docker/start.sh
.dockerignore
.env
```

Fungsi masing-masing file:

- `Dockerfile`: instruksi untuk membuat image aplikasi Laravel.
- `compose.yaml`: konfigurasi untuk menjalankan container Laravel dan MySQL.
- `docker/start.sh`: script yang dijalankan saat container aplikasi mulai.
- `.dockerignore`: daftar file yang tidak perlu ikut masuk saat build image.
- `.env`: konfigurasi aplikasi Laravel, database, port, dan environment lokal.

Jika membuat setting Docker dari nol, perintah awalnya bisa seperti ini:

```bash
mkdir -p docker
touch Dockerfile
touch compose.yaml
touch docker/start.sh
touch .dockerignore
cp .env.example .env
```

Setelah file dibuat, isi masing-masing file dengan konfigurasi yang sesuai. Pada bagian berikutnya, tutorial ini membahas isi `Dockerfile`, `compose.yaml`, dan `docker/start.sh` satu per satu.

Untuk studi kasus ini, file tersebut sudah tersedia di repository. Tugas kita adalah memahami isinya, melakukan setting `.env`, lalu menjalankan aplikasi dengan Docker.

## 5. Tujuan Pembelajaran

Setelah mengikuti tutorial ini, mahasiswa diharapkan bisa:

- Memahami fungsi Docker dalam pengembangan aplikasi web.
- Memahami perbedaan image, container, volume, network, dan Docker Compose.
- Membuat konfigurasi `Dockerfile` untuk aplikasi Laravel.
- Membuat konfigurasi `compose.yaml` untuk menjalankan Laravel dan MySQL.
- Menjalankan aplikasi Laravel secara lokal dengan Docker.
- Melakukan migration dan seeding database melalui container.
- Melakukan troubleshooting dasar Docker.

## 6. Kenapa Menggunakan Docker

Tanpa Docker, setiap komputer harus menginstall PHP, Composer, MySQL, extension PHP, dan konfigurasi server secara manual. Masalah yang sering muncul:

- Versi PHP berbeda.
- Extension PHP belum lengkap.
- MySQL tidak sama versinya.
- Project berjalan di laptop satu orang, tetapi gagal di laptop lain.
- Deployment ke cloud membutuhkan konfigurasi ulang.

Dengan Docker, environment aplikasi dibuat dalam container. Artinya:

- Versi PHP bisa dikunci.
- Dependency sistem bisa didefinisikan di `Dockerfile`.
- MySQL bisa dijalankan sebagai container.
- Aplikasi lebih mudah dipindahkan ke komputer lain atau cloud.

Alur sederhananya:

```text
Source Code Laravel -> Dockerfile -> Docker Image -> Docker Container -> Aplikasi Berjalan
```

Untuk aplikasi yang butuh database:

```text
Laravel Container + MySQL Container -> Docker Compose
```

## 7. Istilah Penting Docker

### Image

Image adalah template atau paket aplikasi. Image berisi sistem operasi kecil, runtime, dependency, dan file aplikasi.

Contoh:

```text
php:8.4-cli
mysql:8.4
```

### Container

Container adalah image yang sedang dijalankan.

Contoh:

```text
projek_cloud_1_app
projek_cloud_1_db
```

### Dockerfile

`Dockerfile` adalah file instruksi untuk membuat image aplikasi.

Contoh isi instruksi:

```dockerfile
FROM php:8.4-cli
WORKDIR /var/www
COPY . .
CMD ["start-app"]
```

### Docker Compose

Docker Compose dipakai untuk menjalankan banyak container sekaligus.

Pada project Laravel ini, Docker Compose menjalankan:

- Container `app` untuk Laravel.
- Container `db` untuk MySQL.
- Container `phpmyadmin` untuk mengelola MySQL lewat browser.

### Volume

Volume dipakai untuk menyimpan data agar tidak hilang saat container dimatikan.

Pada project ini:

- `mysql_data` menyimpan data MySQL.
- `vendor_data` menyimpan dependency Composer di container.

### Port Mapping

Port mapping menghubungkan port container ke port komputer lokal.

Contoh:

```yaml
ports:
  - "8000:8000"
```

Artinya:

- Port `8000` di komputer lokal diarahkan ke port `8000` di container.
- Aplikasi bisa dibuka di `http://localhost:8000`.

## 8. Prasyarat

Pastikan Docker Desktop sudah terinstall dan berjalan.

Langkah memulai Docker Desktop untuk pemula:

1. Download Docker Desktop dari website Docker.
2. Install Docker Desktop sesuai sistem operasi.
3. Buka Docker Desktop.
4. Tunggu sampai Docker aktif.
5. Buka terminal.
6. Jalankan perintah cek versi Docker.

Cek Docker:

```bash
docker --version
```

Cek Docker Compose:

```bash
docker compose version
```

Jika perintah di atas berhasil, Docker siap digunakan.

## 9. Clone Project Studi Kasus

Masuk ke folder kerja:

```bash
cd /Users/triyono/Projek
```

Clone project:

```bash
git clone https://github.com/triyono777/projek_cloud_1.git
```

Masuk ke folder project:

```bash
cd projek_cloud_1
```

Jika project sudah ada, cukup masuk ke folder project:

```bash
cd /Users/triyono/Projek/projek_cloud_1
```

Cek isi project:

```bash
ls
```

File Docker yang dipakai:

- `Dockerfile`
- `compose.yaml`
- `docker/start.sh`
- `.dockerignore`

## 10. Struktur Docker pada Project Laravel

Struktur file penting:

```text
projek_cloud_1/
├── Dockerfile
├── compose.yaml
├── docker/
│   └── start.sh
├── .dockerignore
├── .env.example
├── app/
├── database/
├── resources/
└── routes/
```

Penjelasan:

- `Dockerfile` membuat image aplikasi Laravel.
- `compose.yaml` menjalankan Laravel dan MySQL.
- `docker/start.sh` adalah script saat container Laravel mulai berjalan.
- `.dockerignore` mengatur file yang tidak perlu ikut masuk build image.
- `.env.example` adalah contoh konfigurasi environment.

## 11. Alur Setting Docker untuk Laravel

Secara praktis, setting Docker untuk aplikasi Laravel dilakukan dengan urutan berikut:

1. Buat `Dockerfile`.
2. Buat folder `docker/`.
3. Buat script `docker/start.sh`.
4. Buat `compose.yaml`.
5. Buat `.dockerignore`.
6. Buat `.env` dari `.env.example`.
7. Generate `APP_KEY`.
8. Build image aplikasi.
9. Jalankan container Laravel dan MySQL.
10. Cek aplikasi di browser.

Pada project ini, file `Dockerfile`, `compose.yaml`, `docker/start.sh`, dan `.dockerignore` sudah dibuat. Jadi langkah praktik yang perlu dilakukan setelah clone adalah:

```bash
cp .env.example .env
docker compose build app
docker compose run --rm --no-deps app php artisan key:generate
docker compose up --build
```

Makna tiap langkah:

- `cp .env.example .env` membuat konfigurasi lokal Laravel.
- `docker compose build app` membuat image aplikasi dari `Dockerfile`.
- `docker compose run --rm --no-deps app php artisan key:generate` mengisi `APP_KEY`.
- `docker compose up --build` menjalankan service Laravel dan MySQL.

Setelah itu, buka:

```text
http://localhost:8000
```

Jika halaman terbuka, berarti setting Docker lokal berhasil.

## 12. Membuat File Environment

Laravel membutuhkan file `.env`.

Buat dari contoh:

```bash
cp .env.example .env
```

Pastikan konfigurasi database lokal seperti ini:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=projek_cloud_1
DB_USERNAME=projek_cloud_1
DB_PASSWORD=secret123
PHPMYADMIN_PORT=8081
```

Penjelasan:

- `DB_HOST=db` berarti Laravel mengakses MySQL melalui nama service `db` di Docker Compose.
- `DB_PORT=3306` adalah port MySQL di dalam container.
- `DB_DATABASE=projek_cloud_1` adalah nama database.
- `DB_USERNAME=projek_cloud_1` adalah username database.
- `DB_PASSWORD=secret123` adalah password database lokal.
- `PHPMYADMIN_PORT=8081` adalah port phpMyAdmin di komputer lokal.

Generate `APP_KEY` Laravel:

```bash
docker compose build app
docker compose run --rm --no-deps app php artisan key:generate
```

Cek hasilnya:

```bash
grep APP_KEY .env
```

Jika benar, hasilnya seperti:

```text
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

`APP_KEY` wajib ada karena Laravel menggunakannya untuk session, cookie, enkripsi, dan login.

## 13. Membahas Dockerfile Laravel

File yang dipakai:

```text
Dockerfile
```

Isi utama:

```dockerfile
FROM php:8.4-cli
```

Artinya image aplikasi memakai PHP 8.4 CLI sebagai dasar.

Install dependency sistem:

```dockerfile
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    default-mysql-client \
    netcat-openbsd \
    && docker-php-ext-install pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*
```

Penjelasan:

- `git` dipakai Composer untuk mengambil package.
- `unzip` dipakai saat ekstraksi package.
- `libzip-dev` dibutuhkan extension zip.
- `default-mysql-client` berguna untuk tool MySQL.
- `netcat-openbsd` dipakai untuk mengecek apakah database sudah siap.
- `pdo_mysql` adalah extension PHP agar Laravel bisa konek ke MySQL.
- `zip` adalah extension yang sering dibutuhkan package PHP.

Menyalin Composer:

```dockerfile
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
```

Mengatur folder kerja:

```dockerfile
WORKDIR /var/www
```

Menyalin script startup:

```dockerfile
COPY docker/start.sh /usr/local/bin/start-app
RUN chmod +x /usr/local/bin/start-app
```

Install dependency Laravel:

```dockerfile
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts
```

Menyalin source code:

```dockerfile
COPY . .
```

Membuka port aplikasi:

```dockerfile
EXPOSE 8000
```

Menjalankan aplikasi:

```dockerfile
CMD ["start-app"]
```

Kesimpulan:

- `Dockerfile` membuat image Laravel.
- Semua dependency PHP dan Composer dipasang di image.
- Saat container berjalan, command `start-app` dipanggil.

## 14. Membahas compose.yaml

File yang dipakai:

```text
compose.yaml
```

Project ini memiliki tiga service:

- `app`
- `db`
- `phpmyadmin`

### Service app

```yaml
app:
  build:
    context: .
    dockerfile: Dockerfile
  container_name: projek_cloud_1_app
  restart: unless-stopped
  ports:
    - "${APP_PORT:-8000}:8000"
  env_file:
    - .env
  depends_on:
    db:
      condition: service_healthy
  volumes:
    - .:/var/www
    - vendor_data:/var/www/vendor
  command: ["start-app"]
```

Penjelasan:

- `build` berarti image dibuat dari `Dockerfile`.
- `container_name` memberi nama container.
- `restart: unless-stopped` membuat container restart otomatis kecuali dihentikan manual.
- `ports` membuka aplikasi di `localhost:8000`.
- `env_file` membaca konfigurasi dari `.env`.
- `depends_on` membuat aplikasi menunggu MySQL sehat.
- `volumes` menghubungkan folder project ke container.
- `command` menjalankan script `start-app`.

### Service db

```yaml
db:
  image: mysql:8.4
  container_name: projek_cloud_1_db
  restart: unless-stopped
  environment:
    MYSQL_DATABASE: ${DB_DATABASE:-projek_cloud_1}
    MYSQL_USER: ${DB_USERNAME:-projek_cloud_1}
    MYSQL_PASSWORD: ${DB_PASSWORD:-secret123}
    MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD:-root123}
  ports:
    - "${DB_FORWARD_PORT:-3307}:3306"
  volumes:
    - mysql_data:/var/lib/mysql
  healthcheck:
    test: ["CMD", "mysqladmin", "ping", "-h", "localhost", "-uroot", "-p${DB_ROOT_PASSWORD:-root123}"]
    interval: 5s
    timeout: 5s
    retries: 20
    start_period: 15s
```

Penjelasan:

- `image: mysql:8.4` memakai image MySQL resmi.
- `environment` membuat database, user, dan password.
- `ports` membuka MySQL dari host di port `3307`.
- `volumes` menyimpan data database.
- `healthcheck` mengecek apakah MySQL sudah siap menerima koneksi.

### Service phpmyadmin

```yaml
phpmyadmin:
  image: phpmyadmin:5.2
  container_name: projek_cloud_1_phpmyadmin
  restart: unless-stopped
  depends_on:
    db:
      condition: service_healthy
  environment:
    PMA_HOST: db
    PMA_PORT: 3306
    UPLOAD_LIMIT: 64M
  ports:
    - "${PHPMYADMIN_PORT:-8081}:80"
```

Penjelasan:

- `image: phpmyadmin:5.2` memakai image phpMyAdmin.
- `depends_on` membuat phpMyAdmin menunggu MySQL sehat.
- `PMA_HOST: db` mengarahkan phpMyAdmin ke service MySQL.
- `PMA_PORT: 3306` memakai port MySQL di dalam network Docker.
- `UPLOAD_LIMIT: 64M` menaikkan batas upload file SQL.
- `ports` membuka phpMyAdmin di `http://localhost:8081`.

### Volumes

```yaml
volumes:
  mysql_data:
  vendor_data:
```

Penjelasan:

- `mysql_data` menjaga data MySQL tetap ada walaupun container dimatikan.
- `vendor_data` menjaga folder `vendor` Composer di container.

## 15. Membahas docker/start.sh

File yang dipakai:

```text
docker/start.sh
```

Script ini dijalankan saat container Laravel mulai berjalan.

Bagian awal:

```sh
#!/usr/bin/env sh
set -eu

cd /var/www
```

Penjelasan:

- `set -eu` membuat script berhenti jika ada error atau variable kosong yang tidak valid.
- `cd /var/www` masuk ke folder aplikasi.

Mapping variable database:

```sh
export DB_CONNECTION="${DB_CONNECTION:-mysql}"
export DB_HOST="${DB_HOST:-${MYSQLHOST:-}}"
export DB_PORT="${DB_PORT:-${MYSQLPORT:-3306}}"
export DB_DATABASE="${DB_DATABASE:-${MYSQLDATABASE:-}}"
export DB_USERNAME="${DB_USERNAME:-${MYSQLUSER:-}}"
export DB_PASSWORD="${DB_PASSWORD:-${MYSQLPASSWORD:-}}"
```

Bagian ini membuat aplikasi bisa berjalan di lokal dan Railway.

Install Composer jika `vendor` belum ada:

```sh
if [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi
```

Menunggu database siap:

```sh
if [ -n "${DB_HOST:-}" ]; then
  echo "Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
  until nc -z "${DB_HOST}" "${DB_PORT:-3306}"; do
    sleep 2
  done
fi
```

Menjalankan migration dan seeder:

```sh
php artisan migrate --force
php artisan db:seed --force
```

Menjalankan Laravel server:

```sh
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
```

## 16. Menjalankan Aplikasi Laravel dengan Docker

Pastikan berada di folder project:

```bash
cd /Users/triyono/Projek/projek_cloud_1
```

Build dan jalankan container:

```bash
docker compose up --build
```

Jika ingin berjalan di background:

```bash
docker compose up -d --build
```

Tampilan container di Docker Desktop:

![Docker Desktop menampilkan container Laravel dan MySQL](image_tutorial/docker_dekstop.png)

Cek container:

```bash
docker compose ps
```

Jika berhasil, akan ada container:

```text
projek_cloud_1_app
projek_cloud_1_db
```

## 17. Membuka Aplikasi Laravel

Buka browser:

```text
http://localhost:8000
```

Tampilan halaman utama:

![Halaman utama web pribadi Laravel](image_tutorial/home_page.png)

Cek endpoint health:

```bash
curl -sS http://localhost:8000/health
```

Hasil yang diharapkan:

```json
{
  "status": "ok",
  "database": {
    "connection": "mysql",
    "status": "connected"
  }
}
```

## 18. Studi Kasus: Web Pribadi Laravel dengan Blog

Aplikasi Laravel ini adalah studi kasus web pribadi dengan fitur blog.

Fitur aplikasi:

- Halaman blog publik di `/`.
- Detail artikel blog di `/blog/{slug}`.
- Login admin di `/login`.
- Dashboard manajemen blog di `/dashboard/posts`.
- Tambah artikel.
- Edit artikel.
- Publish atau draft artikel.
- Hapus artikel.
- Health check di `/health`.

Akun admin default:

```text
Email: admin@example.com
Password: password
```

Buka login:

```text
http://localhost:8000/login
```

Buka dashboard setelah login:

```text
http://localhost:8000/dashboard/posts
```

Tampilan dashboard:

![Dashboard admin manajemen blog](image_tutorial/dashboard_admin.png)

Alur praktik studi kasus:

1. Jalankan aplikasi dengan Docker.
2. Buka halaman utama.
3. Cek `/health`.
4. Login sebagai admin.
5. Tambah artikel baru.
6. Edit artikel.
7. Ubah status artikel menjadi draft.
8. Publish kembali artikel.
9. Hapus artikel jika diperlukan.

## 19. Perintah Laravel di Container

Menjalankan migration:

```bash
docker compose exec app php artisan migrate
```

Menjalankan seeder:

```bash
docker compose exec app php artisan db:seed
```

Melihat route:

```bash
docker compose exec app php artisan route:list
```

Menjalankan test:

```bash
docker compose exec app php artisan test
```

Masuk ke shell container:

```bash
docker compose exec app sh
```

Keluar dari shell container:

```bash
exit
```

## 20. Mengakses MySQL Lokal

MySQL berjalan di dalam container pada port `3306`.

Dari komputer host, MySQL dibuka di port `3307`.

Konfigurasi koneksi dari aplikasi database client:

```text
Host: 127.0.0.1
Port: 3307
Database: projek_cloud_1
Username: projek_cloud_1
Password: secret123
```

Jika menggunakan command line MySQL:

```bash
mysql -h 127.0.0.1 -P 3307 -u projek_cloud_1 -p projek_cloud_1
```

Masukkan password:

```text
secret123
```

Jika ingin memakai phpMyAdmin, buka:

```text
http://localhost:8081
```

Login phpMyAdmin:

```text
Server: db
Username: projek_cloud_1
Password: secret123
```

Catatan:

- phpMyAdmin hanya untuk kebutuhan lokal.
- Service ini tidak dipakai oleh Railway karena Railway menggunakan `Dockerfile`, bukan `compose.yaml`.

## 21. Menghentikan dan Membersihkan Container

Menghentikan container:

```bash
docker compose down
```

Menghentikan dan menghapus volume:

```bash
docker compose down -v
```

Peringatan:

- `docker compose down` hanya menghentikan container.
- `docker compose down -v` menghapus data volume, termasuk data MySQL lokal.
- Gunakan `-v` hanya jika ingin reset database dari awal.

Build ulang tanpa cache:

```bash
docker compose build --no-cache
```

Jalankan ulang:

```bash
docker compose up --build
```

## 22. Workflow Harian Developer

Workflow yang disarankan:

1. Masuk ke folder project.
2. Jalankan Docker.
3. Edit kode Laravel.
4. Cek hasil di browser.
5. Jalankan test.
6. Commit perubahan.

Perintah:

```bash
cd /Users/triyono/Projek/projek_cloud_1
docker compose up -d
docker compose exec app php artisan test
git status
git add .
git commit -m "Pesan perubahan"
git push
```

Jika ada migration baru:

```bash
docker compose exec app php artisan migrate
```

Jika ingin reset data demo:

```bash
docker compose exec app php artisan db:seed --force
```

## 23. Troubleshooting Docker

### Docker belum berjalan

Gejala:

```text
Cannot connect to the Docker daemon
```

Solusi:

- Buka Docker Desktop.
- Tunggu sampai status Docker aktif.
- Jalankan ulang perintah Docker.

### Port 8000 sudah dipakai

Jalankan aplikasi dengan port lain:

```bash
APP_PORT=8080 docker compose up --build
```

Buka:

```text
http://localhost:8080
```

### Port MySQL 3307 sudah dipakai

Gunakan port lain:

```bash
DB_FORWARD_PORT=3308 docker compose up --build
```

Koneksi MySQL dari host menjadi:

```text
127.0.0.1:3308
```

### Port phpMyAdmin 8081 sudah dipakai

Gunakan port lain:

```bash
PHPMYADMIN_PORT=8082 docker compose up --build
```

Buka:

```text
http://localhost:8082
```

### APP_KEY kosong

Gejala:

- Login bermasalah.
- Session tidak berjalan.
- Laravel menampilkan error key.

Solusi:

```bash
docker compose run --rm --no-deps app php artisan key:generate
```

### Database belum siap

Cek log:

```bash
docker compose logs db
docker compose logs app
```

Project ini sudah memakai `healthcheck` dan `start.sh` untuk menunggu database, tetapi jika MySQL baru pertama kali dibuat, proses awal bisa memakan waktu beberapa saat.

### Vendor bermasalah

Jika dependency Laravel bermasalah:

```bash
docker compose down
docker volume rm projek_cloud_1_vendor_data
docker compose up --build
```

Jika nama volume berbeda, cek:

```bash
docker volume ls
```

### Database ingin direset

Reset total database lokal:

```bash
docker compose down -v
docker compose up --build
```

Setelah itu migration dan seeder akan berjalan ulang.

## 24. Ringkasan Perintah Penting

Setup awal:

```bash
git clone https://github.com/triyono777/projek_cloud_1.git
cd projek_cloud_1
cp .env.example .env
docker compose build app
docker compose run --rm --no-deps app php artisan key:generate
docker compose up --build
```

Menjalankan di background:

```bash
docker compose up -d --build
```

Cek container:

```bash
docker compose ps
```

Cek health:

```bash
curl -sS http://localhost:8000/health
```

Test Laravel:

```bash
docker compose exec app php artisan test
```

Stop container:

```bash
docker compose down
```

Reset database:

```bash
docker compose down -v
docker compose up --build
```

## 25. Latihan

Latihan dasar:

1. Clone project.
2. Buat `.env`.
3. Generate `APP_KEY`.
4. Jalankan Docker Compose.
5. Buka `http://localhost:8000`.
6. Cek `http://localhost:8000/health`.
7. Login dashboard.
8. Tambah artikel blog.
9. Jalankan test.
10. Commit perubahan.

Latihan lanjutan:

1. Ubah port aplikasi dari `8000` ke `8080`.
2. Reset database dengan `docker compose down -v`.
3. Jalankan ulang aplikasi dan pastikan seeder membuat data awal.
4. Tambahkan satu migration baru.
5. Jalankan migration dari container.

## 26. Kesimpulan

Docker membantu membuat environment Laravel yang konsisten. Pada studi kasus ini, Docker dipakai untuk menjalankan:

- Laravel sebagai service `app`.
- MySQL sebagai service `db`.
- Volume untuk menyimpan data database dan dependency Composer.
- Healthcheck agar aplikasi menunggu database siap.
- Script startup untuk migration, seeding, dan menjalankan server.

Dengan alur ini, project Laravel bisa dijalankan lebih mudah di komputer lokal dan siap dikembangkan menuju deployment cloud.
