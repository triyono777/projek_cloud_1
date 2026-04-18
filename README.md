# Projek Cloud 1

Tutorial praktik Laravel + Docker + MySQL + Render CLI.

Proyek ini disiapkan agar bisa dipakai langsung untuk:

1. Menjalankan Laravel secara lokal dengan Docker.
2. Menghubungkan Laravel ke MySQL container.
3. Menguji endpoint aplikasi dan koneksi database.
4. Menyiapkan deployment ke Render berbasis Docker.

## Stack

* Laravel 13
* PHP 8.4
* MySQL 8.4
* Docker Compose
* Render Web Service berbasis Docker

## Struktur File Penting

* `Dockerfile`: image PHP untuk aplikasi Laravel
* `compose.yaml`: stack lokal Laravel + MySQL
* `docker/start.sh`: startup script untuk menunggu database dan menjalankan migrasi
* `render.yaml`: blueprint Render untuk web service
* `routes/web.php`: route halaman utama dan endpoint `/health`

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

## 4. Tutorial Deployment ke Render

### Catatan penting

Render mendukung deployment aplikasi Docker dengan baik. Berdasarkan dokumentasi Render CLI terbaru, CLI mendukung login, pemilihan workspace, validasi `render.yaml`, melihat service, dan memicu deploy manual. CLI juga dapat memvalidasi blueprint dengan `render blueprints validate` dan memicu deploy dengan `render deploys create`.

Untuk aplikasi ini:

* Web service di Render menggunakan `Dockerfile` dari repositori ini.
* Database **MySQL di Render tidak disediakan sebagai managed service bawaan** seperti Postgres, jadi untuk produksi Anda perlu menyediakan MySQL eksternal.
* Untuk tahap belajar, gunakan MySQL lokal via Docker. Untuk deployment Render, isi variabel `DB_*` dengan MySQL eksternal yang Anda miliki.

### 4.1 Login ke Render CLI

Jika belum login:

```bash
render login
```

Periksa workspace:

```bash
render workspaces
```

Pilih workspace aktif:

```bash
render workspace set
```

### 4.2 Validasi Blueprint

Repositori ini sudah menyediakan `render.yaml`.

Validasi file tersebut:

```bash
render blueprints validate render.yaml
```

Jika valid, Render akan menerima struktur blueprint tanpa error skema.

### 4.3 Push ke Git Repository

Render paling mudah digunakan dengan repo GitHub/GitLab.

Contoh:

```bash
git remote add origin <URL_REPO_ANDA>
git push -u origin main
```

### 4.4 Buat Web Service di Dashboard Render

Langkah yang paling stabil untuk pembuatan awal service:

1. Buka dashboard Render.
2. Pilih **New +**.
3. Pilih **Web Service**.
4. Hubungkan repository proyek ini.
5. Pastikan runtime yang dipakai adalah **Docker**.
6. Pastikan `Dockerfile` dan `render.yaml` terbaca dari root project.

### 4.5 Isi Environment Variables di Render

Set minimal berikut:

```env
APP_NAME=Projek Cloud 1
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-render-anda.onrender.com
APP_KEY=base64:ISI_APP_KEY_ANDA
DB_CONNECTION=mysql
DB_HOST=HOST_MYSQL_PRODUKSI
DB_PORT=3306
DB_DATABASE=NAMA_DB
DB_USERNAME=USERNAME_DB
DB_PASSWORD=PASSWORD_DB
```

Anda bisa membuat `APP_KEY` lokal dengan:

```bash
php artisan key:generate --show
```

### 4.6 Deploy Ulang dengan Render CLI

Setelah service sudah dibuat, lihat daftar service:

```bash
render services
```

Lalu trigger deploy manual:

```bash
render deploys create <SERVICE_ID> --wait
```

Jika ingin melihat daftar deploy:

```bash
render deploys list <SERVICE_ID>
```

## 5. Alur Kerja yang Disarankan

1. Jalankan lokal dengan `docker compose up --build`.
2. Pastikan halaman utama dan `/health` bisa diakses.
3. Commit perubahan ke Git.
4. Push ke repository remote.
5. Validasi `render.yaml` dengan Render CLI.
6. Hubungkan repo ke Render.
7. Isi environment variables produksi.
8. Deploy dan verifikasi endpoint `/health`.

## 6. Troubleshooting

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

### Container app gagal terkoneksi ke database

Cek log:

```bash
docker compose logs app
docker compose logs db
```

### Deploy Render gagal

Cek:

* nilai `APP_KEY`
* nilai `APP_URL`
* kredensial MySQL produksi
* log deploy pada dashboard Render

## 7. Endpoint yang Tersedia

* `/` menampilkan landing page proyek
* `/health` menampilkan JSON status aplikasi dan status koneksi database

## Referensi

* [Render CLI](https://render.com/docs/cli)
* [Deploying on Render](https://render.com/docs/deploys/)
* [Blueprint YAML Reference](https://render.com/docs/blueprint-spec)
