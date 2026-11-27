# Todoist Clone — Backend REST API

Repositori ini berisi implementasi sistem *backend* **RESTful API** yang mengadopsi fitur inti aplikasi manajemen tugas **Todoist**. Proyek ini dikembangkan untuk memenuhi Tugas Besar mata kuliah **Praktikum Pemrograman Berorientasi Objek (PPBO)** pada Program Studi S1 Sistem Informasi, Fakultas MIPA, Universitas Tanjungpura.

Sistem dibangun menggunakan **Laravel v13** dengan menerapkan empat pilar Pemrograman Berorientasi Objek (*Encapsulation, Inheritance, Abstraction, Polymorphism*) serta prinsip desain **SOLID** dan **Factory Pattern**.

---

## Fitur Utama

* **Autentikasi Pengguna (Laravel Sanctum):** Mendukung *register*, *login*, dan *logout* berbasis *Bearer Token* sehingga data setiap pengguna terisolasi dengan aman.
* **Manajemen Proyek (*Projects*):** Operasi CRUD lengkap untuk mengelompokkan tugas ke dalam proyek berkode warna.
* **Manajemen Tugas (*Tasks*):** Operasi CRUD lengkap dengan judul, deskripsi, tenggat waktu (*deadline*), tingkat prioritas (`low`, `medium`, `high`), dan status pengerjaan (`pending`, `in_progress`, `completed`).
* **Tugas Bertingkat (*Subtasks*):** Pemecahan tugas utama menjadi subtugas bersarang menggunakan relasi mandiri (*self-referencing*).
* **Label & Kategori (*Labels*):** Penandaan tugas fleksibel menggunakan relasi *Many-to-Many*.
* **Pencarian & Filter:** Pencarian tugas berdasarkan kata kunci (`search`) serta penyaringan kombinasi berdasarkan `status`, `priority`, `project_id`, dan `label_id`.
* **Prioritas Dinamis & Dashboard Statistik:** Penentuan warna label (`badge_color`) dan pesan pengingat (`reminder_message`) secara dinamis menggunakan `PriorityFactory`, serta ringkasan statistik produktivitas pengguna.

---

## Penerapan Konsep PBO & Arsitektur

* **Enkapsulasi:** Pembatasan atribut melalui properti `$fillable` dan penyembunyian data sensitif (`password`, `remember_token`) melalui `$hidden` pada lapisan Model (`app/Models/`).
* **Pewarisan (*Inheritance*):** Seluruh Model mewarisi `Model`/`Authenticatable` dan seluruh Kontroler mewarisi kelas dasar `Controller`.
* **Abstraksi & Polimorfisme:** Kontrak `TaskPriorityInterface` diimplementasikan oleh kelas `HighPriority`, `MediumPriority`, dan `LowPriority` di dalam `app/Services/Priority/`.
* **Factory Pattern:** Kelas `PriorityFactory::make()` menangani pembuatan objek prioritas secara dinamis tanpa membebani kontroler.
* **Single Responsibility Principle (SRP):** Validasi input dipisahkan ke dalam kelas `FormRequest` (`StoreProjectRequest`, `StoreLabelRequest`, `StoreTaskRequest`).

---

## Teknologi yang Digunakan

* **Framework:** Laravel v13 (PHP)
* **Autentikasi:** Laravel Sanctum
* **Basis Data:** MySQL (Laragon)
* **Pengujian API:** EchoAPI

---

## API Endpoints

API menyediakan 19 route terdaftar yang mencakup autentikasi, manajemen proyek, manajemen label, manajemen tugas, dan dashboard.

Seluruh endpoint menggunakan prefix `/api`.

### 1. Authentication

| Method | Endpoint | Deskripsi | Akses |
|---|---|---|---|
| POST | `/api/register` | Registrasi pengguna baru | Public |
| POST | `/api/login` | Login pengguna | Public |
| POST | `/api/logout` | Logout dan menghapus token autentikasi | Protected |

### 2. Projects

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/projects` | Menampilkan daftar proyek |
| POST | `/api/projects` | Membuat proyek baru |
| GET | `/api/projects/{id}` | Menampilkan detail proyek |
| PUT/PATCH | `/api/projects/{id}` | Memperbarui proyek |
| DELETE | `/api/projects/{id}` | Menghapus proyek |

### 3. Labels

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/labels` | Menampilkan daftar label |
| POST | `/api/labels` | Membuat label baru |
| GET | `/api/labels/{id}` | Menampilkan detail label |
| PUT/PATCH | `/api/labels/{id}` | Memperbarui label |
| DELETE | `/api/labels/{id}` | Menghapus label |

### 4. Tasks

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/tasks` | Menampilkan daftar tugas |
| POST | `/api/tasks` | Membuat tugas atau subtugas |
| GET | `/api/tasks/{id}` | Menampilkan detail tugas |
| PUT/PATCH | `/api/tasks/{id}` | Memperbarui tugas |
| DELETE | `/api/tasks/{id}` | Menghapus tugas |

**Fitur pencarian dan filter tugas**

Pencarian dan filter dilakukan melalui query parameter pada endpoint `GET /api/tasks`, bukan melalui endpoint terpisah.

| Parameter | Fungsi |
|---|---|
| `search` | Mencari tugas berdasarkan judul atau deskripsi |
| `status` | Memfilter tugas berdasarkan status |
| `priority` | Memfilter tugas berdasarkan prioritas |
| `project_id` | Memfilter tugas berdasarkan proyek |
| `label_id` | Memfilter tugas berdasarkan label |
| `include_subtasks` | Menentukan apakah subtugas ikut ditampilkan |

Contoh penggunaan:

```http
GET /api/tasks?status=completed
GET /api/tasks?priority=high
GET /api/tasks?project_id=1
GET /api/tasks?label_id=1
GET /api/tasks?search=laporan
GET /api/tasks?include_subtasks=true
```

Secara default, daftar tugas menampilkan tugas utama beserta relasi subtugasnya. Parameter pencarian atau `include_subtasks` dapat digunakan untuk menampilkan subtugas sebagai bagian dari hasil daftar.

### 5. Dashboard

| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/dashboard` | Menampilkan statistik dashboard pengguna |

### Autentikasi Endpoint

Endpoint yang memiliki akses **Protected** wajib menggunakan token autentikasi Laravel Sanctum.

Token diperoleh setelah pengguna berhasil melakukan login.

Tambahkan header berikut pada request:

```http
Authorization: Bearer {token}
Accept: application/json
```

### Catatan

- Endpoint proyek, label, tugas, logout, dan dashboard membutuhkan autentikasi.
- Endpoint register dan login dapat diakses tanpa token.
- Subtugas dikelola melalui endpoint tugas menggunakan relasi `parent_id`, bukan melalui kelompok endpoint tersendiri.
- Metode `PUT` dan `PATCH` tersedia untuk memperbarui data.

---

## Cara Menjalankan di Lokal

1. *Clone* repositori ini:
   ```bash
   git clone https://github.com/bit-ryy/Tubes-ToDoList.git
   cd Tubes-ToDoList
   ```
2. Instal dependensi PHP:
   ```bash
   composer install
   ```
3. Salin file konfigurasi lingkungan dan sesuaikan pengaturan database MySQL di `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Jalankan migrasi tabel:
   ```bash
   php artisan migrate
   ```
5. Nyalakan server lokal:
   ```bash
   php artisan serve
   ```
6. Akses REST API melalui `http://127.0.0.1:8000/api` menggunakan EchoAPI atau Postman.

---

## Tim Pengembang

* Dhini Zakiyatul Wafa (H1101251020)
* Alfonsius Pernandes Roby (H1101251033)
* Deslo Aldo Anggoro (H1101251036)
* Phasacola Grey Kalista (H1101251037)
* Iren Meiliani Taslim (H1101251062)
* Maulana Malik Ibrahim (H1101251063)