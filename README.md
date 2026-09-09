# Jara - Advanced To-Do List App

Jara adalah aplikasi berbasis web untuk mengelola tugas pribadi maupun tim. Pengguna dapat membuat daftar tugas, menetapkan prioritas dan tenggat waktu, serta mengundang kolaborator. Admin bertugas mengelola akun pengguna di dalam sistem.

## Tim Pengembang
- Adam (Project Manager)
- Nawaal (Developer 1 - Admin & Task Lists)
- Arga (Developer 2 - Task CRUD & Progress)

## Persyaratan Praktikum (Wajib Dibaca)
1. Branching: Dilarang keras melakukan push langsung ke `main`. Gunakan branch `feature/nama-fitur`.
2. Commit Message: Wajib menggunakan format Conventional Commits (Head & Body).
   - Contoh: `feat(task): tambah fitur prioritas` (baris baru) `Menambahkan kolom enum untuk prioritas tugas.`
3. Co-Author: Dilarang menggunakan tag *co-author*, termasuk dari AI.

## Instalasi Lokal
1. `git clone [url-repo]`
2. `cd jara-app`
3. `composer install`
4. `cp .env.example .env`
5. `php artisan key:generate`
6. `php artisan migrate`