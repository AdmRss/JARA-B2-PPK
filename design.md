# Design Document: Jara

## 1. Identitas & Anggota Proyek
- **Nama Proyek:** Jara (Advanced To-Do List)
- **Deskripsi Singkat:** Sistem manajemen tugas kolaboratif berbasis web untuk mengelola tugas secara terstruktur, menetapkan prioritas, dan memantau tenggat waktu.

| Nama | NIM | Peran | Fokus Tanggung Jawab |
| :--- | :--- | :--- | :--- |
| **Adam Mulya Rasyid** | 24060124140179 | Project Manager | Pengelolaan Repositori, Git Workflow, Merge & Manajemen Konflik, Dokumentasi (README & Design). |
| **Nawaal Hanif Mumtaz Arriye** | 24060124120041 | Programmer (Dev 1) | Modul Manajemen Akun (Admin) & Manajemen Wadah Tugas (Task List & Kolaborator). |
| **Arga Yura Danendra** | 24060124140191 | Programmer (Dev 2) | Modul CRUD Item Tugas (Task) serta Logika Pelacakan Status & Progres (Progress Tracking). |

---

## 2. Deskripsi Sistem & Tech Stack
Jara adalah perangkat lunak manajemen tugas kolaboratif. Aplikasi ini memisahkan hierarki data menjadi "List" (proyek/daftar) dan "Task" (item tugas). Fitur unggulan dari sistem ini adalah pembagian kepemilikan proyek, di mana kreator list dapat mengizinkan entitas pengguna lain untuk bekerja di ranah list yang sama.

- **Framework & Bahasa:** Laravel 12 (PHP), Composer, NPM, JavaScript, HTML/CSS
- **Database:** MySQL
- **Frontend:** Blade Templating Engine + Vite
- **Pola Arsitektur:** MVC (Model-View-Controller) standar Laravel dengan pemisahan logika rute, pengontrol, dan basis data relasional.

---

## 3. Aktor & Hak Akses
1. **Admin:** Bertanggung jawab atas integritas data pengguna. Dapat menambah (registrasi internal) dan menghapus pengguna.
2. **Owner:** Pengguna yang membuat *Task List*. Memiliki hak untuk mengedit atribut list dan mengelola *collaborator*.
3. **Collaborator:** Pengguna yang di-assign ke dalam sebuah *Task List*. Dapat berinteraksi dengan tugas di dalamnya (buat, edit, ubah status).

---

## 4. Rancangan Database (Skema Relasional)
Sistem menggunakan 4 tabel utama:

### A. Tabel `users`
- `id` (PK, BigInt)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `role` (enum: `'admin'`, `'user'`)
- `timestamps`

### B. Tabel `task_lists`
- `id` (PK, BigInt)
- `name` (string)
- `description` (text, nullable)
- `owner_id` (FK -> `users.id`)
- `timestamps`

### C. Tabel `task_list_user` (Pivot Kolaborasi)
- `task_list_id` (FK -> `task_lists.id`)
- `user_id` (FK -> `users.id`)
- `timestamps`

### D. Tabel `tasks`
- `id` (PK, BigInt)
- `task_list_id` (FK -> `task_lists.id`)
- `title` (string)
- `priority` (enum: `'low'`, `'mid'`, `'high'`)
- `due_date` (datetime, nullable)
- `status` (boolean: `false` = pending, `true` = done)
- `timestamps`

---

## 5. Tahapan Branching & Aturan Tim
- **Nawaal:** Membuat branch `feature/admin-and-lists`. Fokus mengelola tabel `users`, `task_lists`, dan tabel pivot.
- **Arga:** Membuat branch `feature/task-and-progress`. Fokus pada operasi tabel `tasks` serta logika agregasi persentase progres di controller.
- **Catatan Penting:** Dilarang keras melakukan *commit* atau *push* langsung pada branch `main`. Seluruh perubahan wajib melalui *branch* fitur masing-masing untuk di-*merge* oleh Project Manager.