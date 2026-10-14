# PRD UCC Job Portal

Oct 14, 2026 · @Burg

UCC Job Portal menerima pengajuan lowongan dari perusahaan, memprosesnya lewat review Admin UCC, dan menghasilkan poster SVG 1080 x 1350 piksel untuk lowongan yang disetujui. Versi 2.1 menambahkan desain database dan diagram alur.

## 1. Informasi Dokumen

| Item | Isi |
| --- | --- |
| Nama produk | UCC Job Portal |
| Organisasi | UCC Career Center |
| Jenis produk | Portal pengajuan lowongan kerja dan pembuatan poster |
| Versi | 2.1 |
| Status | Baseline implementasi berjalan |
| Tanggal | 14 Oktober 2026 |
| Dokumen terkait | README.md, Issue.md |

| Versi | Tanggal | Perubahan |
| --- | --- | --- |
| 2.1 | 14 Oktober 2026 | Menambah arsitektur sistem, diagram state dan flowchart, ERD, kamus data, indeks, alur data per operasi, matriks hak akses, dan pertanyaan terbuka. |
| 2.0 | 14 Oktober 2026 | Baseline implementasi berjalan. |

Catatan: nama tabel yang disebut di PRD 2.0 (users, job\_vacancies, job\_edit\_logs) dipakai apa adanya. Nama kolom lain di dokumen ini adalah usulan dan perlu dicocokkan dengan migration di repository.

## 2. Ringkasan, Masalah, dan Sasaran

Portal ini mengganti pengumpulan data lowongan secara manual dengan alur terkontrol: perusahaan mendaftar dan mengisi profil sekali, mengirim lowongan, Admin UCC memeriksa, dan lowongan yang disetujui diubah menjadi poster.

### 2.1 Masalah dan solusi

| Masalah | Solusi di produk |
| --- | --- |
| Data lowongan dikumpulkan manual dan berulang | Form pengajuan hanya meminta data posisi dan kebutuhan rekrutmen |
| Profil perusahaan diisi ulang di setiap pengajuan | Profil disimpan di akun (tabel users) dan disalin ke lowongan saat pengajuan |
| Alur review tidak jelas | Status dikontrol sistem: pending, approved, rejected, revised |
| Alasan penolakan sulit ditindaklanjuti | Reject wajib beralasan, alasan tampil di daftar dan detail perusahaan |
| Riwayat perubahan tidak tercatat | Setiap revisi menyimpan alasan di job\_edit\_logs |
| Pembuatan materi publikasi lambat | Poster SVG 1080 x 1350 piksel dibuat dari data lowongan yang disetujui |

### 2.2 Sasaran dan indikator keberhasilan

| Sasaran | Indikator | Cara verifikasi |
| --- | --- | --- |
| Pengajuan tanpa isi ulang profil | Form pengajuan tidak memuat field profil perusahaan | AC-02 |
| Reject dapat ditindaklanjuti | Setiap lowongan berstatus rejected memiliki admin\_reject\_reason terisi | AC-03 |
| Keterlacakan revisi | Setiap perubahan lowongan menghasilkan satu baris job\_edit\_logs | AC-04 |
| Poster terkendali | Poster hanya bisa dibuat saat status approved | AC-05 |
| Isolasi data antar perusahaan | Akses lintas perusahaan ditolak oleh policy | AC-06 |

## 3. Aktor dan Hak Akses

Ada dua aktor: Perusahaan (role company) dan Admin UCC (role admin). Role disimpan di kolom role pada tabel users dan dipisahkan oleh middleware.

| Fitur | Perusahaan | Admin UCC |
| --- | --- | --- |
| Register dan login | Ya | Login saja |
| Kelola profil perusahaan | Ya, milik sendiri | Tidak |
| Buat lowongan | Ya | Tidak |
| Lihat daftar dan detail lowongan | Milik sendiri | Semua perusahaan |
| Ubah lowongan beserta alasan perubahan | Milik sendiri, sesuai policy | Tidak |
| Hapus lowongan | Milik sendiri, sesuai policy | Tidak |
| Approve lowongan | Tidak | Ya |
| Reject lowongan dengan alasan | Tidak | Ya |
| Lihat alasan reject | Milik sendiri | Semua |
| Lihat dan tandai log alasan revisi dibaca | Perusahaan hanya melihat riwayat relevan miliknya | Ya |
| Pilih template, preview, dan unduh poster | Tidak | Ya, hanya status approved |

## 4. Ruang Lingkup dan Arsitektur

### 4.1 Ruang lingkup

| Termasuk | Di luar baseline |
| --- | --- |
| Autentikasi dan otorisasi berbasis role | Portal kandidat untuk melamar |
| Profil perusahaan | Integrasi otomatis ke job board eksternal |
| CRUD lowongan perusahaan | Notifikasi email atau WhatsApp otomatis |
| Status pengajuan dan review Admin | Multi-level approval selain Admin UCC |
| Reject, revisi, dan audit log alasan perubahan | Editor poster bebas seperti aplikasi desain |
| Preview dan unduh poster | Analitik performa lowongan |
| Validasi input dan upload logo | Banyak organisasi dalam satu tenant |
| Deployment serverless dengan database MySQL-compatible |  |

### 4.2 Arsitektur sistem

Aplikasi berupa satu aplikasi Laravel yang dijalankan serverless, terhubung ke database MySQL-compatible dan object storage untuk logo.

&#91;embedded content: arsitektur sistem · 1 aplikasi, 2 penyimpanan\]

Setiap request melewati middleware role, policy, dan validasi sebelum masuk ke controller. Logo disimpan di object storage, bukan di filesystem serverless.

## 5. Kebutuhan Fungsional

Kolom "Tabel terdampak" menghubungkan tiap kebutuhan dengan desain database di bagian 8 sampai 10.

| ID | Kebutuhan | Aktor | Tabel terdampak |
| --- | --- | --- | --- |
| FR-01 | Registrasi perusahaan | Perusahaan | users |
| FR-02 | Pengaturan akun perusahaan | Perusahaan | users |
| FR-03 | Pengajuan lowongan | Perusahaan | job\_vacancies, users (dibaca) |
| FR-04 | Daftar dan detail lowongan | Perusahaan | job\_vacancies, job\_edit\_logs (dibaca) |
| FR-05 | Pengubahan dan revisi lowongan | Perusahaan | job\_vacancies, job\_edit\_logs |
| FR-06 | Review Admin | Admin | job\_vacancies (dibaca) |
| FR-07 | Approve | Admin | job\_vacancies |
| FR-08 | Reject | Admin | job\_vacancies |
| FR-09 | Audit alasan perubahan | Admin | job\_edit\_logs |
| FR-10 | Poster lowongan | Admin | job\_vacancies |

### FR-01: Registrasi perusahaan

Form menerima field berikut. Data profil disimpan pada akun di tabel users.

| Field | Wajib | Aturan |
| --- | --- | --- |
| Nama perusahaan | Ya | Teks |
| Logo perusahaan | Tidak | JPG atau PNG, maksimal 10 MB |
| Email akun | Ya | Format email, dipakai untuk login, unik (usulan) |
| Bidang perusahaan | Ya | Teks |
| Provinsi | Ya | Teks |
| Website atau media sosial | Ya | Teks atau URL |
| Email perusahaan | Ya | Format email |
| Nama narahubung | Ya | Teks |
| Jabatan narahubung | Ya | Teks |
| Kontak atau WhatsApp | Ya | Teks angka |
| Password dan konfirmasi | Ya | Harus sama, disimpan dengan hashing Laravel |

Kolom "Wajib" mengikuti kalimat AC-01 (field wajib yang kosong menghasilkan pesan validasi). Daftar field wajib yang pasti perlu dikonfirmasi dengan validasi aktual di Form Request.

### FR-02: Pengaturan akun perusahaan

Perusahaan dapat memperbarui semua field FR-01 kecuali password. Perubahan email akun mengulang verifikasi email jika fitur verifikasi diaktifkan. Perubahan profil tidak mengubah lowongan lama; salinan di lowongan hanya diperbarui saat lowongan itu diubah (FR-05).

### FR-03: Pengajuan lowongan

Sistem mengambil data profil dari akun, yaitu nama perusahaan, logo, bidang, provinsi, website atau media sosial, email perusahaan, nama narahubung, jabatan, dan kontak. Data ini tidak muncul di form.

| Field form | Wajib | Aturan |
| --- | --- | --- |
| Posisi yang dibutuhkan | Ya | Teks |
| Jumlah kebutuhan | Ya | Bilangan bulat positif (usulan) |
| Kualifikasi dan persyaratan | Ya | Teks panjang |
| Deskripsi pekerjaan | Ya | Teks panjang |
| Informasi tambahan atau benefit | Tidak | Teks panjang |
| Alamat | Ya | Teks |
| Lokasi penempatan | Ya | Teks |
| Batas akhir lamaran | Ya | Tanggal, tidak boleh lampau (usulan) |
| Cara melamar | Ya | Teks |
| Alamat atau cara pengiriman lamaran | Ya | Teks |
| Gaji yang ditawarkan | Tidak | Teks |
| Caption promosi | Tidak | Teks |
| Persetujuan penggunaan informasi | Ya | Harus dicentang |
| Persetujuan publikasi | Ya | Harus dicentang |

Saat disimpan, status = pending dan salinan profil perusahaan terbaru ikut tersimpan di baris lowongan.

### FR-04: Daftar dan detail lowongan perusahaan

- Daftar hanya berisi lowongan milik perusahaan yang login, dengan pagination dan filter status (pending, approved, rejected, revised).
- Detail menampilkan alasan reject Admin dan riwayat alasan revisi yang relevan.
- Akses ke lowongan perusahaan lain ditolak oleh policy.

### FR-05: Pengubahan dan revisi lowongan

Setiap perubahan wajib menyertakan alasan perubahan minimal 5 karakter.

| Status sebelum | Status sesudah | Efek lain |
| --- | --- | --- |
| rejected | revised | Satu baris job\_edit\_logs baru berstatus unread; salinan profil diperbarui |
| pending, approved, atau revised | pending | Sama seperti di atas |

Snapshot data lama tidak ditampilkan di halaman audit, tetapi data historis lama tetap dipertahankan.

### FR-06: Review Admin

Dashboard menampilkan antrean per status, jumlah lowongan per status (query agregasi), detail pengajuan, informasi perusahaan dan posisi, serta aksi approve dan reject.

### FR-07: Approve

Status menjadi approved, alasan reject dan alasan revisi dikosongkan, dan fitur poster tersedia.

### FR-08: Reject

Admin wajib mengisi alasan minimal 5 karakter. Status menjadi rejected, alasan disimpan di admin\_reject\_reason dan disalin ke rejection\_reason untuk data lama. Alasan revisi perusahaan sebelumnya dikosongkan. Modal reject dapat ditutup dengan Escape.

### FR-09: Audit alasan perubahan

Admin melihat tanggal dan waktu, perusahaan, lowongan, alasan, dan status baca (unread atau read), serta dapat menandai log sebagai read.

### FR-10: Poster lowongan

Hanya untuk status approved. Admin membuka preview, memilih template 1 atau 2, menyimpan pilihan, lalu mengunduh SVG dengan viewBox 1080 x 1350. Poster memuat logo, nama perusahaan, posisi, kualifikasi, lokasi, alamat, informasi lamaran, dan batas akhir.

## 6. Status dan Transisi

Status lowongan hanya berubah lewat aksi yang tercantum di bawah. Pengguna tidak dapat mengirim status langsung dari form.

| Dari | Aksi | Pelaku | Ke | Syarat |
| --- | --- | --- | --- | --- |
| (baru) | Ajukan | Perusahaan | pending | Dua persetujuan dicentang |
| pending | Approve | Admin | approved | Tidak ada |
| pending | Reject | Admin | rejected | Alasan minimal 5 karakter |
| pending | Ubah | Perusahaan | pending | Alasan perubahan minimal 5 karakter |
| approved | Ubah | Perusahaan | pending | Sesuai policy; alasan perubahan minimal 5 karakter |
| rejected | Ubah | Perusahaan | revised | Alasan perubahan minimal 5 karakter |
| revised | Approve | Admin | approved | Tidak ada |
| revised | Reject | Admin | rejected | Alasan minimal 5 karakter |
| revised | Ubah | Perusahaan | pending menurut FR-05, revised menurut tabel PRD 2.0 | Belum konsisten, lihat pertanyaan terbuka |

&#91;embedded content: state lowongan · 4 status, 6 transisi\]

Lowongan yang ditolak hanya bisa kembali ke antrean lewat revisi, bukan langsung ke approved.

Status draft ada di skema untuk kompatibilitas dan bukan bagian alur utama.

## 7. Alur Proses

Flowchart berikut menunjukkan siapa melakukan apa dari registrasi sampai poster, termasuk putaran revisi setelah reject.

&#91;embedded content: flowchart swimlane · 3 pelaku, 1 keputusan, 1 putaran revisi\]

### 7.1 Alur perusahaan baru

1. Pengguna membuka halaman register dan mengisi identitas akun serta profil perusahaan.
2. Sistem memvalidasi input dan file logo (JPG atau PNG, maksimal 10 MB).
3. Jika valid, sistem membuat baris users dengan role company dan menyimpan logo ke object storage.
4. Pengguna masuk ke dashboard perusahaan.
5. Profil dapat diperbaiki kapan saja lewat Pengaturan Akun.

### 7.2 Alur pengajuan

1. Perusahaan membuka form lowongan; sistem membaca profil dari users dan tidak menampilkannya di form.
2. Perusahaan mengisi informasi posisi dan mencentang dua persetujuan.
3. Sistem memvalidasi, menyalin profil terbaru ke baris lowongan, dan menyimpan dengan status pending.
4. Lowongan muncul di daftar perusahaan dan di antrean Admin.

### 7.3 Alur reject dan revisi

1. Admin membuka pengajuan dan menekan Reject.
2. Admin mengisi alasan (minimal 5 karakter); tanpa alasan, aksi ditolak.
3. Sistem menyimpan status rejected, alasan di admin\_reject\_reason dan rejection\_reason, lalu mengosongkan alasan revisi lama.
4. Perusahaan membaca alasan di daftar atau detail lowongan.
5. Perusahaan memperbaiki data dan menulis alasan perubahan (minimal 5 karakter).
6. Dalam satu transaksi, sistem memperbarui lowongan menjadi revised, menyalin profil terbaru, dan menambah baris job\_edit\_logs berstatus unread.
7. Admin melihat lowongan di antrean dan log di halaman audit, lalu memproses ulang.

### 7.4 Alur poster

1. Admin membuka lowongan berstatus approved; status lain ditolak dengan pesan.
2. Admin memilih template 1 atau 2; sistem menampilkan preview SVG.
3. Admin mengonfirmasi generate; sistem menyimpan poster\_template.
4. Admin mengunduh SVG berukuran 1080 x 1350 piksel.

## 8. Desain Database

Satu akun company memiliki banyak lowongan, dan setiap lowongan memiliki banyak log perubahan. Detail kolom ada di bagian 9, indeks dan constraint di bagian 10.

### 8.1 Diagram relasi (ERD)

&#91;embedded content: ERD · 3 tabel, 2 relasi satu-ke-banyak\]

### 8.2 Catatan desain

- Profil perusahaan disimpan sekali di users. Lowongan menyimpan salinannya agar isi pengajuan tidak berubah diam-diam saat profil diedit.
- Salinan profil di lowongan diperbarui hanya saat perusahaan mengubah lowongan tersebut (FR-05).
- Alasan reject ditulis ke dua kolom (admin\_reject\_reason dan rejection\_reason) selama data lama belum dimigrasi; pembacaan memakai kolom utama lebih dulu, lalu fallback.
- job\_edit\_logs hanya bertambah (append-only) dari sisi perusahaan; Admin hanya mengubah read\_status.
- Poster tidak disimpan sebagai file; hanya poster\_template dan waktu generate yang disimpan, SVG dirender ulang dari data lowongan.

## 9. Kamus Data

Kolom "Dasar" memisahkan isi yang disebut di PRD 2.0 dari nama dan tipe kolom yang diusulkan di sini. Usulan perlu dicocokkan dengan migration aktual sebelum dianggap final.

### 9.1 Tabel users

Menyimpan identitas login dan, untuk role company, profil perusahaan.

| Kolom | Tipe | Null | Keterangan | Dasar |
| --- | --- | --- | --- | --- |
| id | BIGINT UNSIGNED, PK | Tidak | Auto increment | Usulan |
| role | ENUM(admin, company) | Tidak | Dipakai middleware | PRD |
| email | VARCHAR(255), unik | Tidak | Email login | PRD |
| email\_verified\_at | TIMESTAMP | Ya | Diisi jika verifikasi email aktif | PRD |
| password | VARCHAR(255) | Tidak | Hash Laravel | PRD |
| company\_name | VARCHAR(255) | Ya | Kosong untuk admin | PRD |
| logo\_path | VARCHAR(255) | Ya | Lokasi file di object storage | PRD |
| industry | VARCHAR(255) | Ya | Bidang perusahaan | PRD |
| province | VARCHAR(100) | Ya | Provinsi | PRD |
| website\_social | VARCHAR(255) | Ya | Website atau media sosial | PRD |
| company\_email | VARCHAR(255) | Ya | Email perusahaan | PRD |
| contact\_name | VARCHAR(255) | Ya | Nama narahubung | PRD |
| contact\_position | VARCHAR(255) | Ya | Jabatan narahubung | PRD |
| contact\_phone | VARCHAR(50) | Ya | Kontak atau WhatsApp | PRD |
| created\_at, updated\_at | TIMESTAMP | Ya | Standar Laravel | Usulan |

### 9.2 Tabel job\_vacancies

Satu baris per pengajuan. Berisi referensi perusahaan, salinan profil saat pengajuan, data lowongan, status, alasan, dan metadata poster.

| Kolom | Tipe | Null | Keterangan | Dasar |
| --- | --- | --- | --- | --- |
| id | BIGINT UNSIGNED, PK | Tidak | Auto increment | Usulan |
| user\_id | BIGINT UNSIGNED, FK ke users.id | Tidak | Pemilik lowongan | PRD |
| company\_name, industry, province, website\_social, company\_email, contact\_name, contact\_position, contact\_phone | Sama seperti users | Ya | Salinan profil saat pengajuan atau revisi terakhir | PRD |
| company\_logo\_path | VARCHAR(255) | Ya | Salinan logo | Usulan |
| position\_title | VARCHAR(255) | Tidak | Posisi yang dibutuhkan | PRD |
| vacancy\_count | INT UNSIGNED | Tidak | Jumlah kebutuhan | PRD |
| qualifications | TEXT | Tidak | Kualifikasi dan persyaratan | PRD |
| job\_description | TEXT | Tidak | Deskripsi pekerjaan | PRD |
| additional\_info | TEXT | Ya | Informasi tambahan atau benefit | PRD |
| address | VARCHAR(500) | Tidak | Alamat | PRD |
| work\_location | VARCHAR(255) | Tidak | Lokasi penempatan | PRD |
| application\_deadline | DATE | Tidak | Batas akhir lamaran | PRD |
| apply\_method | VARCHAR(255) | Tidak | Cara melamar | PRD |
| apply\_destination | VARCHAR(500) | Tidak | Alamat atau cara pengiriman lamaran | PRD |
| salary | VARCHAR(255) | Ya | Gaji yang ditawarkan | PRD |
| promo\_caption | TEXT | Ya | Caption promosi | PRD |
| consent\_information | BOOLEAN | Tidak | Persetujuan penggunaan informasi | PRD |
| consent\_publication | BOOLEAN | Tidak | Persetujuan publikasi | PRD |
| status | ENUM(draft, pending, approved, rejected, revised) | Tidak | Default pending; draft hanya kompatibilitas | PRD |
| admin\_reject\_reason | TEXT | Ya | Kolom utama alasan reject | PRD |
| rejection\_reason | TEXT | Ya | Legacy, diisi sama dengan kolom utama; dibaca sebagai fallback | PRD |
| company\_revision\_reason | TEXT | Ya | Alasan revisi terakhir dari perusahaan; dikosongkan saat approve dan reject | PRD |
| poster\_template | TINYINT UNSIGNED | Ya | 1 atau 2 | PRD |
| poster\_generated\_at | TIMESTAMP | Ya | Waktu generate dikonfirmasi | Usulan |
| created\_at, updated\_at | TIMESTAMP | Ya | Standar Laravel | Usulan |

### 9.3 Tabel job\_edit\_logs

Audit alasan perubahan. Satu baris per perubahan lowongan oleh perusahaan.

| Kolom | Tipe | Null | Keterangan | Dasar |
| --- | --- | --- | --- | --- |
| id | BIGINT UNSIGNED, PK | Tidak | Auto increment | Usulan |
| job\_vacancy\_id | BIGINT UNSIGNED, FK ke job\_vacancies.id | Tidak | Lowongan yang diubah | PRD |
| reason | TEXT | Tidak | Alasan perubahan, minimal 5 karakter | PRD |
| old\_snapshot | JSON | Ya | Data historis lama; tidak lagi ditulis dan tidak ditampilkan di UI | PRD |
| read\_status | ENUM(unread, read) | Tidak | Default unread | PRD |
| created\_at | TIMESTAMP | Tidak | Waktu perubahan | PRD |

## 10. Indeks, Constraint, dan Alur Data

### 10.1 Relasi dan constraint

| Relasi atau aturan | Detail |
| --- | --- |
| users 1 ke N job\_vacancies | job\_vacancies.user\_id mengacu ke users.id; hanya akun role company yang memiliki lowongan |
| job\_vacancies 1 ke N job\_edit\_logs | job\_edit\_logs.job\_vacancy\_id mengacu ke job\_vacancies.id |
| Hapus lowongan | Log ikut terhapus (ON DELETE CASCADE) agar tidak ada log yatim; konfirmasi dengan implementasi aktual |
| Hapus akun company | Tolak jika masih punya lowongan (RESTRICT); konfirmasi dengan implementasi aktual |
| Email login | Unik di users |
| Status | Hanya nilai enum yang valid; perpindahan status diatur di service, bukan input pengguna |
| Poster | poster\_template hanya boleh 1 atau 2 dan hanya diisi saat status approved |

### 10.2 Indeks

Indeks mendukung filter dan sorting utama (NFR-02).

| Tabel | Indeks | Dipakai oleh |
| --- | --- | --- |
| users | UNIQUE (email) | Login, validasi registrasi |
| users | (role) | Pemisahan admin dan company |
| job\_vacancies | (user\_id, status) | Daftar lowongan perusahaan dengan filter status |
| job\_vacancies | (status, created\_at) | Antrean Admin per status, urut terbaru |
| job\_vacancies | (status) | Hitung jumlah lowongan per status (agregasi) |
| job\_edit\_logs | (job\_vacancy\_id, created\_at) | Riwayat revisi per lowongan |
| job\_edit\_logs | (read\_status, created\_at) | Daftar log belum dibaca di halaman audit |

### 10.3 Alur data per operasi

Tabel ini menjawab: operasi apa mengubah tabel dan kolom mana. Semua operasi tulis yang menyentuh lebih dari satu tabel dijalankan dalam satu transaksi database.

| Operasi | Pelaku | Baca | Tulis |
| --- | --- | --- | --- |
| Register | Perusahaan | users (cek email unik) | INSERT users (role = company, profil, logo\_path); file logo ke object storage |
| Ubah profil | Perusahaan | users | UPDATE users; verifikasi ulang jika email berubah |
| Ajukan lowongan | Perusahaan | users (profil terbaru) | INSERT job\_vacancies dengan status = pending, salinan profil, dua persetujuan |
| Ubah lowongan | Perusahaan | job\_vacancies, users | UPDATE job\_vacancies (data, salinan profil, status baru: rejected menjadi revised, lainnya menjadi pending, company\_revision\_reason); INSERT job\_edit\_logs (reason, read\_status = unread) |
| Approve | Admin | job\_vacancies | UPDATE status = approved; admin\_reject\_reason, rejection\_reason, company\_revision\_reason = NULL |
| Reject | Admin | job\_vacancies | UPDATE status = rejected; admin\_reject\_reason dan rejection\_reason = alasan; company\_revision\_reason = NULL |
| Tandai log dibaca | Admin | job\_edit\_logs | UPDATE read\_status = read |
| Pilih template dan generate | Admin | job\_vacancies (status harus approved) | UPDATE poster\_template, poster\_generated\_at |
| Unduh poster | Admin | job\_vacancies, users atau salinan logo | Tidak ada; SVG dirender dari data lowongan |

## 11. Kebutuhan Nonfungsional dan Acceptance Criteria

### 11.1 Kebutuhan nonfungsional

| ID | Area | Kebutuhan |
| --- | --- | --- |
| NFR-01 | Keamanan | Semua area internal memerlukan autentikasi. Middleware memisahkan role admin dan company. Policy mencegah akses lintas perusahaan. Input divalidasi dengan Form Request. Password di-hash. Upload dibatasi MIME type dan ukuran. Data sensitif tidak disimpan di repository. |
| NFR-02 | Performa | Daftar memakai pagination. Statistik memakai query agregasi. Query hanya mengambil kolom yang perlu. Kolom filter dan sorting utama diindeks (bagian 10.2). Asset dibangun mode production. Halaman tidak mengirim snapshot JSON besar yang tidak dipakai UI. |
| NFR-03 | Kompatibilitas | Laravel 11 dan PHP 8.2. Database MySQL-compatible termasuk TiDB Cloud. Frontend Blade, Tailwind CSS, dan Alpine.js. Deployment Vercel Serverless melalui konfigurasi repository. |
| NFR-04 | Responsif dan aksesibilitas | Form dan tabel dapat dipakai di desktop dan mobile tanpa horizontal overflow. Kontrol interaktif punya focus state. Modal reject dapat ditutup dengan Escape. Target sentuh utama minimal 44 piksel. |
| NFR-05 | Observability dan pemulihan | Error validasi tampil di form terkait. Flash message untuk hasil aksi penting. Migration dijalankan eksplisit dengan php artisan migrate --force. Storage production memakai object storage, bukan filesystem sementara serverless. |

Catatan: PRD 2.0 menulis "React JS atau lebih baru" pada NFR-03, sementara frontend yang disebut adalah Blade, Tailwind CSS, dan Alpine.js. Kalimat React saya hapus karena tampak sisa dokumen lain; mohon konfirmasi.

### 11.2 Acceptance criteria

| ID | Skenario | Kriteria lulus |
| --- | --- | --- |
| AC-01 | Registrasi | Akun company tersimpan dengan seluruh profil; field wajib kosong menghasilkan pesan validasi; logo valid tersimpan |
| AC-02 | Pengajuan | Form tidak menampilkan field profil; data profil terbaru terambil; tersimpan dengan status pending |
| AC-03 | Reject | Tanpa alasan ditolak; alasan tersimpan; perusahaan melihat alasan; data legacy terbaca lewat fallback rejection\_reason |
| AC-04 | Revisi | Alasan perubahan wajib; revisi dari rejected menjadi revised; Admin melihat log alasan |
| AC-05 | Poster | Preview hanya untuk approved; hanya template yang didukung bisa dipilih; unduhan berformat SVG dengan viewBox 1080 x 1350 |
| AC-06 | Otorisasi | Perusahaan tidak bisa membuka, mengedit, atau menghapus data perusahaan lain; endpoint Admin hanya untuk role admin |

## 12. Rencana Lanjutan, Keputusan, dan Pertanyaan Terbuka

### 12.1 Keputusan implementasi

- Profil perusahaan berada di users; lowongan menyimpan salinan profil agar isi pengajuan konsisten.
- admin\_reject\_reason adalah kolom utama alasan reject; rejection\_reason hanya untuk kompatibilitas data lama.
- Audit log baru tidak menampilkan snapshot JSON di UI.
- Poster dihasilkan sebagai SVG, bukan JPG.
- Migration production dijalankan manual dan eksplisit, bukan otomatis pada setiap cold start.

### 12.2 Rencana pengembangan lanjutan

1. Notifikasi email saat status berubah.
2. Riwayat status yang lengkap (tabel status\_histories), bukan hanya edit log.
3. Pengaturan template poster lewat konfigurasi Admin.
4. Filter dan pencarian lowongan berdasarkan posisi atau perusahaan.
5. Pengujian browser untuk register, profil, reject, revisi, dan unduh.
6. Seeder demo yang idempotent dan aman untuk non-production.
7. Penghapusan atau migrasi bertahap kolom legacy setelah data lama divalidasi.

### 12.3 Pertanyaan terbuka

- [ ] Apa nama kolom aktual di migration untuk alasan revisi perusahaan dan metadata poster?
- [ ] Apakah hapus lowongan menghapus log (CASCADE) atau memakai soft delete?
- [ ] Apakah transisi pending ke pending (ubah saat masih pending) perlu membuat log?
- [ ] Bisakah Admin mengubah template setelah poster pernah di-generate?
- [ ] Dari mana poster memuat logo: users.logo\_path atau salinan di lowongan?
- [ ] Apakah "React JS" pada NFR-03 benar bagian dari stack?

* [ ] Saat perusahaan mengubah lowongan berstatus revised, hasilnya pending (FR-05) atau tetap revised (tabel status PRD 2.0)?
