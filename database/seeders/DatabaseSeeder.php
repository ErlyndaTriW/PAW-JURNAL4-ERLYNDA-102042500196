<?php
    
    namespace Database\Seeders;

    use App\Models\Assignment;
    use App\Models\MataKuliah;
    use App\Models\Material;
    use App\Models\Submission;
    use App\Models\User;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash;

    class DatabaseSeeder extends Seeder
    {
        public function run(): void
        {
            // 1. Akun Dosen Pengampu Default
            $dosen = User::updateOrCreate(
                ['email' => 'dosen@celoe.test'],
                [
                    'name' => 'Dr. Ir. Budi Santoso, M.T.',
                    'password' => Hash::make('dosen123'),
                    'role' => 'dosen',
                    'nomor_induk' => '198501012015041001',
                ]
            );

            // 2. Akun Mahasiswa Default
            $mahasiswa1 = User::updateOrCreate(
                ['email' => 'mahasiswa@celoe.test'],
                [
                    'name' => 'Ahmad Pratama',
                    'password' => Hash::make('mahasiswa123'),
                    'role' => 'mahasiswa',
                    'nomor_induk' => '1301210001',
                ]
            );

            // 3. Mata Kuliah Dasar (Modul 3)
            $course1 = MataKuliah::updateOrCreate(
                ['kode_mk' => 'IF301'],
                [
                    'nama_mk' => 'Pengembangan Aplikasi Website',
                    'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                    'sks' => 4,
                    'semester' => 'Ganjil 2026/2027',
                    'dosen_id' => $dosen->id,
                ]
            );

            // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama (status approved)
            $course1->students()->syncWithoutDetaching([
                $mahasiswa1->id => ['status' => 'approved'],
            ]);

            // 5. Tambahkan Materi Contoh
            Material::updateOrCreate(
                ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
                [
                    'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                    'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
                ]
            );
                    // 6. Mahasiswa Tambahan
            $mahasiswa2 = User::updateOrCreate(
                ['email' => 'siti@celoe.test'],
                [
                    'name' => 'Siti Nurhaliza',
                    'password' => Hash::make('mahasiswa123'),
                    'role' => 'mahasiswa',
                    'nomor_induk' => '1301210002',
                ]
            );

            // 7. Dua Mata Kuliah Tambahan (semester dan SKS berbeda)
            $course2 = MataKuliah::updateOrCreate(
                ['kode_mk' => 'IF204'],
                [
                    'nama_mk' => 'Basis Data Lanjut',
                    'deskripsi' => 'Mata kuliah yang membahas query lanjutan, indexing, transaksi, dan optimasi basis data relasional.',
                    'sks' => 3,
                    'semester' => 'Genap 2026/2027',
                    'dosen_id' => $dosen->id,
                ]
            );

            $course3 = MataKuliah::updateOrCreate(
                ['kode_mk' => 'IF402'],
                [
                    'nama_mk' => 'Etika Profesi',
                    'deskripsi' => 'Mata kuliah yang membahas etika, tanggung jawab, dan hukum dalam profesi teknologi informasi.',
                    'sks' => 2,
                    'semester' => 'Ganjil 2027/2028',
                    'dosen_id' => $dosen->id,
                ]
            );

            // 8. Pendaftaran Mahasiswa (status approved)
            $course1->students()->syncWithoutDetaching([
                $mahasiswa2->id => ['status' => 'approved'],
            ]);
            $course2->students()->syncWithoutDetaching([
                $mahasiswa1->id => ['status' => 'approved'],
                $mahasiswa2->id => ['status' => 'approved'],
            ]);

            // 9. Dua Tugas Perkuliahan
            $tugas1 = Assignment::updateOrCreate(
                ['course_id' => $course1->id, 'judul' => 'Implementasi CRUD Laravel'],
                [
                    'deskripsi' => 'Buatlah aplikasi CRUD sederhana menggunakan Laravel dan jelaskan alur kerjanya.',
                    'deadline' => now()->addDays(14),
                ]
            );

            $tugas2 = Assignment::updateOrCreate(
                ['course_id' => $course2->id, 'judul' => 'Optimasi Query dengan Index'],
                [
                    'deskripsi' => 'Analisis tiga query lambat dan tunjukkan perbaikannya menggunakan index.',
                    'deadline' => now()->addDays(21),
                ]
            );

            // 10. Contoh Pengumpulan Tugas (sudah dinilai, belum dinilai, dan perlu perbaikan)
            Submission::updateOrCreate(
                ['assignment_id' => $tugas1->id, 'user_id' => $mahasiswa1->id],
                [
                    'jawaban' => 'Aplikasi CRUD dibuat dengan resource controller, model Eloquent, dan view Blade.',
                    'nilai' => 88,
                    'catatan_dosen' => 'Struktur kode rapi, tambahkan validasi yang lebih lengkap.',
                    'submitted_at' => now(),
                ]
            );

            Submission::updateOrCreate(
                ['assignment_id' => $tugas1->id, 'user_id' => $mahasiswa2->id],
                [
                    'jawaban' => 'Saya membuat CRUD data buku dengan migrasi, model, controller, dan tampilan tabel.',
                    'nilai' => null,
                    'catatan_dosen' => null,
                    'submitted_at' => now(),
                ]
            );

            Submission::updateOrCreate(
                ['assignment_id' => $tugas2->id, 'user_id' => $mahasiswa1->id],
                [
                    'jawaban' => 'Query dioptimalkan dengan menambahkan index pada kolom yang sering dicari.',
                    'nilai' => 65,
                    'catatan_dosen' => 'Analisis belum menyertakan perbandingan waktu eksekusi.',
                    'submitted_at' => now(),
                ]
            );
            // TODO 8: Tambahkan minimal 2 mata kuliah lagi dengan semester berbeda, 1 mahasiswa tambahan, 2 tugas, dan contoh data submission
        }
    }