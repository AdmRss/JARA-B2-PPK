# Jara - Advanced To-Do List

Jara adalah sistem manajemen tugas kolaboratif berbasis web yang memungkinkan pengguna untuk mengelola tugas secara terstruktur, menetapkan prioritas, menugaskan anggota tim, dan memantau tenggat waktu. Sistem ini dirancang dengan standar keamanan pencegahan SQL Injection (Prepared Statements), eksekusi data atomik (DB Transactions), dan pengelolaan hak akses tingkat lanjut.

## Tim Pengembang
- **Nawaal Hanif Mumtaz Arriye** (24060124120041) - Project Manager: Git Workflow, Code Review, Security Check & Merge PR
- **Adam Mulya Rasyid** (24060124140179) - Programmer: SRS-01 (Keamanan Inti & Manajemen Admin)
- **Muhammad Fahri** (24060124120037) - Programmer: SRS-02 (Manajemen Daftar Tugas & Operasi Atomik)
- **Arga Yura Danendra** (24060124140191) - Programmer: SRS-03 (Task CRUD, Prioritas, Deadline & Penugasan)
- **Mutiara Ayu Pramono** (24060123140131) - Programmer: SRS-04 (Integrasi UI/UX & Dashboard Progres)

## User Story Utama
Sebagai pengguna, saya ingin dapat membuat proyek tugas, mengatur tenggat waktu dan prioritas, serta mengundang anggota tim untuk berkolaborasi, sehingga kami dapat memantau progres pekerjaan secara terorganisir dengan jaminan keamanan data secara menyeluruh.

## Tech Stack
- **Backend:** Laravel 11 (PHP)
- **Frontend:** Tailwind CSS, Blade Templating
- **Database:** SQLite (via Eloquent ORM)

## Cara Menjalankan Aplikasi Lokal
Karena repositori sudah di-clone dan dependensi sudah terinstal, cukup jalankan perintah berikut di terminal:
```bash
php artisan serve

Alur Kerja (Workflow) Tim
- Programmer wajib menarik kode terbaru (git pull origin main).
- Membuat branch fitur baru dari main (contoh: feature/srs-02-fahri).
- Menerapkan kode dengan standar keamanan proyek (Eloquent, DB Transaction, Policy).
- Melakukan commit dan push ke branch fitur. Dilarang melakukan push langsung ke branch main.
- Membuat Pull Request (PR) di GitHub.
- Project Manager akan mereview keamanan kode sebelum menyetujui dan melakukan Merge ke branch main.

Akun Default (Testing)
- Akun Admin
    Email: admin@jara.test
    Password: password

- Akun User Biasa
    Email: arga@jara.test
    Password: password