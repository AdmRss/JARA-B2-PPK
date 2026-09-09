# Jara - Advanced To-Do List
Jara adalah sistem manajemen tugas kolaboratif berbasis web yang memungkinkan pengguna untuk mengelola tugas secara terstruktur, menetapkan prioritas, dan memantau tenggat waktu. Sistem ini mendukung fitur kolaborasi tim dalam sebuah daftar tugas (task list) serta menyediakan hak akses administratif untuk pengelolaan pengguna sistem.

# Tim
| Nama | NIM | Peran | Detail Tugas |
| :--- | :--- | :--- | :--- |
| **Adam Mulya Rasyid** | 24060124140179 | Project Manager | Git Workflow & Orkestrasi Repo |
| **Nawaal Hanif Mumtaz Arriye** | 24060124120041 | Programmer | Admin & Task Lists |
| **Arga Yura Danendra** | 24060124140191 | Programmer | Task CRUD & Progress Monitoring |

# User Story
Sebagai pengguna, saya ingin membuat daftar tugas, menetapkan prioritas waktu, dan mengundang rekan tim ke dalam daftar tersebut, sehingga kami dapat memantau dan berkolaborasi dalam penyelesaian tugas secara real-time dan terorganisir.

# Tech Stack
* Backend: Laravel 12 (PHP)
* Package Manager: Composer & NPM
* Database: MySQL

**Setup
# 1. Clone repository & masuk ke folder proyek
https://github.com/AdmRss/JARA-B2-PPK.git
cd JARA-B2-PPK

# 2. Install dependency backend dan frontend
composer install && npm install

# 3. Setup environment dan database
cp .env.example .env && php artisan key:generate

# 4. Eksekusi migrasi tabel dan seeder akun default
php artisan migrate --seed

# 5. Jalankan server backend dan frontend secara bersamaan
php artisan serve & npm run dev


# Workflow
1. Programmer melakukan checkout dari main ke branch fitur (contoh: feature/admin-and-lists).
2. Implementasi fitur dan lakukan commit dengan format wajib Head & Body (tanpa co-author).
3. Push ke branch fitur masing-masing. Dilarang keras melakukan push langsung ke main.
4. Project Manager menarik fitur, menyelesaikan merge conflict, dan menggabungkannya ke main.

# Default Accounts
| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | admin@jara.test | password |
| **Owner** | user@jara.test | password |
