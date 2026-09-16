---

#### 📄 Isi untuk file `desain.md`
```markdown
# Design Document: Jara

## 1. Identitas & Anggota Proyek
- **Nama Proyek:** Jara (Advanced To-Do List)
- **Deskripsi Singkat:** Sistem manajemen tugas kolaboratif berbasis web untuk mengelola tugas secara terstruktur, menetapkan prioritas, menugaskan tim, dan memantau tenggat waktu secara real-time.

| Nama | NIM | Peran | Fokus Tanggung Jawab (SRS) |
| :--- | :--- | :--- | :--- |
| **Nawaal Hanif M. A.** | 24060124120041 | Project Manager | Orkestrasi Repositori, Git Workflow, Code Reviewer, Verifikasi Keamanan Query & Merge Pull Request. |
| **Adam Mulya Rasyid** | 24060124140179 | Programmer 1 | **SRS-01**: Manajemen Akun (Tambah/Hapus Pengguna oleh Admin) dan Sistem Hak Akses (Policies/Gates). |
| **Muhammad Fahri** | 24060124120037 | Programmer 2 | **SRS-02**: CRUD Task List, Kolaborasi via Pivot Table, dan Logika Hapus List secara Atomik (DB Transaction). |
| **Arga Yura Danendra** | 24060124140191 | Programmer 3 | **SRS-03**: CRUD Task, Penugasan Spesifik, Prioritas, Tenggat Waktu (Deadline), dan Status Penyelesaian. |
| **Mutiara Ayu Pramono** | 24060123140131 | Programmer 4 | **SRS-04**: Implementasi Antarmuka UI/UX (Tailwind CSS) dan Visualisasi Dashboard Progres Tim. |

---

## 2. Deskripsi Arsitektur Sistem
Jara menggunakan pola arsitektur **MVC (Model-View-Controller)** standar Laravel. Sistem memisahkan hierarki data menjadi entitas "List" (wadah proyek) dan "Task" (tugas satuan). Sistem ini diwajibkan menggunakan perlindungan Middleware, *Prepared Statements* via Eloquent untuk mencegah *SQL Injection*, serta *Database Transactions* untuk menjaga integritas data lintas tabel saat penghapusan.

---

## 3. Aktor & Hak Akses
1. **Admin Sistem:** Bertanggung jawab penuh terhadap akun. Memiliki akses eksklusif ke rute/fitur tambah dan hapus pengguna sistem.
2. **Owner (Pemilik Proyek):** Kreator dari sebuah *Task List*. Satu-satunya yang berhak menambah kolaborator ke dalam proyek dan menghapus daftar miliknya.
3. **Collaborator (Anggota Tim):** Pengguna yang diundang ke dalam *Task List*. Berhak melihat isi proyek, membuat/mengedit tugas, ditugaskan ke sebuah task, dan memperbarui status penyelesaian tugas.

---

## 4. Rancangan Database (Skema Relasional)

Sistem menggunakan 4 tabel utama yang saling berelasi:

### A. Tabel `users`
- `id` (Primary Key)
- `name` (String)
- `email` (String, Unique)
- `password` (String, Hashed)
- `role` (Enum: `'admin'`, `'user'`)

### B. Tabel `task_lists`
- `id` (Primary Key)
- `name` (String)
- `description` (Text, Nullable)
- `owner_id` (Foreign Key -> `users.id`)

### C. Tabel Pivot `task_list_user` (Sistem Kolaborasi)
- `task_list_id` (Foreign Key -> `task_lists.id`)
- `user_id` (Foreign Key -> `users.id`)

### D. Tabel `tasks`
- `id` (Primary Key)
- `task_list_id` (Foreign Key -> `task_lists.id`)
- `title` (String)
- `priority` (Enum: `'rendah'`, `'sedang'`, `'tinggi'`)
- `due_date` (DateTime, Nullable)
- `assignee_id` (Foreign Key -> `users.id`, Nullable)
- `status` (Boolean: `false` = pending, `true` = done)

---

## 5. Tahapan Branching & Aturan Pull Request (PR)
- Seluruh Programmer wajib bekerja di branch dengan format `feature/nama-fitur` atau `feature/srs-xx-nama`.
- **Aturan Review PM:** Setiap Pull Request yang masuk akan diaudit oleh Project Manager. PR akan langsung ditolak (*Request Changes*) jika ditemukan:
  1. Penggunaan `DB::raw()` pada input pengguna.
  2. Ketiadaan fungsi `DB::transaction()` pada operasi hapus relasional.
  3. Logika controller yang mengabaikan pengecekan otorisasi pengguna.