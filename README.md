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

## Daftar Endpoint API

Semua *endpoint* di luar autentikasi wajib menyertakan header `Accept: application/json` dan `Authorization: Bearer <token>`.

| Modul | Method | Endpoint | Keterangan |
| :--- | :--- | :--- | :--- |
| **Auth** | `POST` | `/api/register` | Mendaftarkan akun baru |
| **Auth** | `POST` | `/api/login` | Masuk dan mendapatkan *Bearer Token* |
| **Auth** | `POST` | `/api/logout` | Keluar dan menghapus token aktif |
| **Projects** | `GET / POST` | `/api/projects` | Lihat semua / buat proyek baru |
| **Projects** | `GET / PUT / DELETE` | `/api/projects/{id}` | Detail / ubah / hapus proyek |
| **Labels** | `GET / POST` | `/api/labels` | Lihat semua / buat label baru |
| **Labels** | `GET / PUT / DELETE` | `/api/labels/{id}` | Detail / ubah / hapus label |
| **Tasks** | `GET / POST` | `/api/tasks` | Lihat tugas (mendukung `?search=`, filter) / buat tugas & subtugas |
| **Tasks** | `GET / PUT / DELETE` | `/api/tasks/{id}` | Detail / ubah / hapus tugas |
| **Dashboard** | `GET` | `/api/dashboard` | Ringkasan statistik tugas, prioritas, dan proyek |

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