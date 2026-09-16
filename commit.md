# Panduan Commit - Proyek Jara
Dokumen ini memuat Standar Operasional Prosedur (SOP) untuk penulisan pesan commit (Commit Message) yang wajib dipatuhi oleh seluruh programmer dalam proyek Jara.

## 1. Aturan Wajib (Format Head & Body)
Setiap commit **dilarang keras** hanya menggunakan satu baris pesan. Commit wajib dipisah menjadi 2 bagian:
- **Head (Judul):** Maksimal 50 karakter. Menjelaskan secara singkat apa yang dilakukan.
- **Body (Deskripsi):** Menjelaskan detail *kenapa* perubahan itu dibuat dan *apa* dampaknya.

Dilarang menggunakan fitur *co-author* saat melakukan commit.

## 2. Format Tipe Commit (Prefix Wajib)
Gunakan salah satu *prefix* (awalan) berikut pada Head:
- `feat:` -> Untuk penambahan fitur baru (Backend/Frontend).
- `fix:` -> Untuk memperbaiki bug atau error.
- `docs:` -> Untuk menambah/mengubah dokumentasi (README, desain, dsb).
- `chore:` -> Untuk hal teknis non-fitur (update dependencies, seeder, dsb).
- `refactor:` -> Untuk merapikan kode tanpa mengubah fungsionalitas.

## 3. Contoh Cara Eksekusi di Terminal
Untuk membuat commit dengan *Head* dan *Body* di terminal, gunakan dua bendera `-m`:

```bash
git commit -m "feat: tambahkan relasi pivot task_list_user" -m "Menambahkan tabel pivot dan relasi Eloquent Many-to-Many pada model User dan TaskList untuk mengelola anggota kolaborator sesuai SRS-02."