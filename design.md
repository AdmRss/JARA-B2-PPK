```markdown
# Design Document: Jara

# 1. Identitas Proyek
- **Nama Proyek:** Jara (Advanced To-Do List)
- **Project Manager:** Adam Mulya Rasyid
- **Developer 1 (SRS-001, SRS-002):** Nawaal Hanif Mumtaz Arriye / 24060124120041
- **Developer 2 (SRS-003, SRS-004):** Arga Yura Danendra 24060124140191 

# 2. Deskripsi Sistem
Jara adalah perangkat lunak manajemen tugas kolaboratif. Aplikasi ini memisahkan hierarki data menjadi "List" (proyek/daftar) dan "Task" (item tugas). Fitur unggulan dari sistem ini adalah pembagian kepemilikan proyek, di mana kreator list dapat mengizinkan entitas pengguna lain untuk bekerja di ranah list yang sama. 

# 3. Aktor & Hak Akses
1. **Admin:** Bertanggung jawab atas integritas data pengguna. Dapat menambah (registrasi internal) dan menghapus pengguna.
2. **Owner:** Pengguna yang membuat *Task List*. Memiliki hak untuk mengedit atribut list dan mengelola *collaborator*.
3. **Collaborator:** Pengguna yang di-assign ke dalam sebuah *Task List*. Dapat berinteraksi dengan tugas di dalamnya (buat, edit, ubah status).

# 4. Rancangan Database (Skema Relasional)
Sistem menggunakan 4 tabel utama:
1.  **Tabel `users`**
    - `id` (PK)
    - `name` (string)
    - `email` (string, unique)
    - `password` (string)
    - `role` (enum: 'admin', 'user')
2.  **Tabel `task_lists`**
    - `id` (PK)
    - `name` (string)
    - `description` (text)
    - `owner_id` (FK -> users.id)
3.  **Tabel `task_list_user` (Pivot Kolaborasi)**
    - `task_list_id` (FK -> task_lists.id)
    - `user_id` (FK -> users.id)
4.  **Tabel `tasks`**
    - `id` (PK)
    - `task_list_id` (FK -> task_lists.id)
    - `title` (string)
    - `priority` (enum: 'low', 'mid', 'high')
    - `due_date` (datetime)
    - `status` (boolean: false=pending, true=done)

# 5. Tahapan Branching
- **Nawaal:** Membuat branch `feature/admin-and-lists`. Fokus mengelola tabel `users`, `task_lists`, dan tabel pivot.
- **Arga:* Membuat branch `feature/task-and-progress`. Fokus pada operasi tabel `tasks` serta logika agregasi persentase progres di controller.
- Dilarang keras melakukan komit langsung pada branch `main`.