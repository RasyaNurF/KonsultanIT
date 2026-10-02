<?php

namespace Database\Seeders;

use App\Enums\PublishStatus;
use App\Enums\ResourceType;
use App\Models\Resource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ResourceContentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $now = Carbon::now();

        foreach ($this->resources($now) as $index => $data) {
            Resource::query()->updateOrCreate(
                ['slug' => $data['slug'] ?? Str::slug($data['title'])],
                $data + [
                    'sort_order' => $index + 1,
                    'status' => PublishStatus::Published,
                    'cta_url' => null,
                ],
            );
        }

        // Retire unchanged sample announcements that asserted unverified company milestones.
        foreach ([
            'KIT Konsultan IT Resmi Menjadi Mitra Anvis BI' => 'KIT Konsultan IT resmi menjalin kemitraan dengan Anvis BI untuk memperluas layanan analitik bisnis bagi klien di Indonesia. Kemitraan ini melengkapi portofolio solusi ERP yang sudah berjalan.',
            'KIT Konsultan IT Membuka Kantor Baru di Surabaya' => 'Untuk mendekatkan layanan ke klien, KIT Konsultan IT membuka kantor perwakilan di Surabaya. Kantor ini menjadi basis tim implementasi dan dukungan untuk wilayah Jawa Timur.',
            'Pencapaian: 120+ Proyek Terselesaikan' => 'KIT Konsultan IT mencatat pencapaian lebih dari 120 proyek yang telah berjalan di produksi, melayani klien dari berbagai industri seperti perbankan hingga manufaktur.',
            'Liputan: Strategi Cloud untuk Perusahaan Menengah' => 'Dalam sebuah liputan media, tim KIT Konsultan IT membahas pentingnya migrasi cloud bertahap dan bagaimana perusahaan menengah dapat memulainya tanpa mengganggu operasional.',
            'Penghargaan Mitra Implementasi Terbaik 2026' => 'KIT Konsultan IT menerima penghargaan sebagai mitra implementasi terbaik tahun 2026, diberikan berdasarkan kualitas proyek, ketepatan waktu, dan kepuasan klien.',
        ] as $title => $body) {
            Resource::query()
                ->where('type', ResourceType::News->value)
                ->where('title', $title)
                ->where('body', $body)
                ->update(['status' => PublishStatus::Draft->value]);
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function resources(Carbon $now): array
    {
        return [
            ...$this->events($now),
            ...$this->goLive($now),
            ...$this->whitepapers($now),
            ...$this->ebooks($now),
            ...$this->news($now),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function events(Carbon $now): array
    {
        return [
            [
                'type' => ResourceType::Event,
                'slug' => 'tech-summit-2026-transformasi-digital-enterprise',
                'title' => 'Contoh: Forum Transformasi Digital Enterprise',
                'excerpt' => 'Contoh rangkaian diskusi tentang cloud, keamanan informasi, dan integrasi sistem untuk bisnis.',
                'body' => "Contoh forum ini memperlihatkan bagaimana diskusi teknologi dapat disusun berdasarkan kebutuhan pengambil keputusan dan tim pelaksana. Topiknya mencakup adopsi cloud yang terukur, keamanan informasi berbasis risiko, serta integrasi sistem yang mendukung proses bisnis.\n\nSetiap sesi dirancang sebagai percakapan praktis: memahami tantangan, membandingkan pendekatan, dan menyusun pertanyaan yang perlu dijawab sebelum proyek dimulai. Rangkaian agenda di bawah bersifat ilustratif dan dapat disesuaikan dengan kebutuhan kegiatan yang sebenarnya.",
                'location' => null,
                'organizer' => null,
                'starts_at' => $now->copy()->addMonths(2)->setTime(9, 0),
                'ends_at' => $now->copy()->addMonths(2)->setTime(16, 30),
                'register_url' => null,
                'external_url' => null,
                'cta_label' => null,
                'agenda' => [
                    ['time' => '09:00', 'title' => 'Registrasi & Networking', 'description' => 'Penerimaan peserta dan kopi pagi.'],
                    ['time' => '09:30', 'title' => 'Keynote: Peta Jalan Digital 2026', 'description' => 'Tren dan prioritas teknologi enterprise tahun ini.'],
                    ['time' => '11:00', 'title' => 'Panel: Adopsi Cloud yang Terukur', 'description' => 'Studi kasus migrasi bertahap di berbagai sektor.'],
                    ['time' => '15:30', 'title' => 'Demo & Penutup', 'description' => 'Area demo solusi dan sesi tanya jawab.'],
                ],
                'speakers' => null,
                'published_at' => $now->copy()->subDays(5),
            ],
            [
                'type' => ResourceType::Event,
                'slug' => 'webinar-keamanan-informasi-untuk-rumah-sakit',
                'title' => 'Contoh: Webinar Keamanan Informasi untuk Rumah Sakit',
                'excerpt' => 'Contoh webinar tentang kesiapan organisasi kesehatan menghadapi insiden siber.',
                'body' => "Contoh webinar ini berfokus pada cara rumah sakit mengidentifikasi sistem dan data yang paling penting, lalu menentukan tanggung jawab saat terjadi gangguan. Pembahasan menempatkan kebutuhan layanan pasien dan koordinasi antartim sebagai konteks utama.\n\nAgenda di bawah menunjukkan bentuk sesi yang mungkin digunakan: pengantar risiko, pembagian peran, dan latihan respons sederhana. Jadwal ini hanya ilustrasi dan belum membuka pendaftaran.",
                'location' => 'Online',
                'organizer' => null,
                'starts_at' => $now->copy()->addWeeks(3)->setTime(13, 0),
                'ends_at' => $now->copy()->addWeeks(3)->setTime(15, 0),
                'register_url' => null,
                'external_url' => null,
                'cta_label' => null,
                'agenda' => [
                    ['time' => '13:00', 'title' => 'Pembukaan', 'description' => 'Konteks ancaman di sektor kesehatan.'],
                    ['time' => '13:20', 'title' => 'Menyusun Rencana Respons Insiden', 'description' => 'Kerangka kerja dan pembagian peran.'],
                ],
                'speakers' => null,
                'published_at' => $now->copy()->subDays(3),
            ],
            [
                'type' => ResourceType::Event,
                'slug' => 'workshop-erp-untuk-manufaktur',
                'title' => 'Contoh: Workshop ERP untuk Manufaktur',
                'excerpt' => 'Contoh lokakarya tentang pemetaan proses dan kesiapan data sebelum implementasi ERP.',
                'body' => "Contoh lokakarya ini menggambarkan kegiatan satu hari untuk memetakan proses bisnis, menilai kesiapan data, dan menyusun rencana perpindahan ke ERP. Peserta dapat bekerja dengan skenario proses yang dekat dengan operasi manufaktur.\n\nHasil yang dituju adalah daftar pertanyaan dan prioritas yang dapat dibawa ke diskusi internal. Kegiatan ini merupakan ilustrasi format workshop, bukan dokumentasi acara yang telah diselenggarakan.",
                'location' => null,
                'organizer' => null,
                'starts_at' => $now->copy()->subMonths(2)->setTime(9, 0),
                'ends_at' => $now->copy()->subMonths(2)->setTime(17, 0),
                'recording_url' => null,
                'cta_label' => null,
                'gallery' => null,
                'published_at' => $now->copy()->subMonths(3),
            ],
            [
                'type' => ResourceType::Event,
                'slug' => 'talkshow-karier-di-bidang-data',
                'title' => 'Contoh: Talkshow Karier di Bidang Data',
                'excerpt' => 'Contoh diskusi tentang jalur karier dan keterampilan dalam bidang data.',
                'body' => "Contoh talkshow ini menggambarkan diskusi tentang peran analis data, data engineer, dan pekerjaan terkait. Pembahasan dapat dimulai dari tugas harian tiap peran, lalu bergerak ke keterampilan yang perlu diasah dan cara membangun portofolio belajar.\n\nFormat tanya jawab memberi ruang bagi peserta untuk membandingkan berbagai jalur karier. Kegiatan ini merupakan ilustrasi topik, bukan pengumuman acara yang telah dikonfirmasi.",
                'location' => null,
                'organizer' => null,
                'starts_at' => $now->copy()->subMonth()->setTime(14, 0),
                'ends_at' => $now->copy()->subMonth()->setTime(16, 30),
                'cta_label' => null,
                'gallery' => null,
                'published_at' => $now->copy()->subMonths(2),
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function goLive(Carbon $now): array
    {
        return [
            [
                'type' => ResourceType::GoLive,
                'slug' => 'go-live-hris-di-manufaktur-nasional',
                'title' => 'Contoh Implementasi HRIS untuk Operasional Manufaktur',
                'industry' => 'Manufaktur',
                'cover_image_path' => 'img/editorial/go-live-manufacturing.png',
                'excerpt' => 'Skenario ilustratif untuk menyatukan data kehadiran, pengajuan, dan administrasi SDM di lingkungan manufaktur.',
                'body' => "Contoh skenario ini menggambarkan kebutuhan HRIS ketika data karyawan dan kehadiran berasal dari beberapa lokasi kerja. Tim operasional membutuhkan alur yang konsisten agar administrasi harian lebih mudah ditelusuri.\n\nPendekatan dimulai dengan memetakan sumber data, aturan persetujuan, dan proses yang paling sering digunakan. Integrasi perangkat kehadiran dapat diuji pada satu lokasi lebih dahulu sebelum diperluas.\n\nSetelah alur inti stabil, organisasi dapat menyiapkan pelatihan pengguna, pemeriksaan kualitas data, serta rencana dukungan pasca peluncuran. Hasil nyata harus diukur dari data proyek yang telah diverifikasi.",
                'metrics' => [],
                'gallery' => [],
                'published_at' => $now->copy()->subDays(10),
            ],
            [
                'type' => ResourceType::GoLive,
                'slug' => 'dashboard-keuangan-untuk-bank-daerah',
                'title' => 'Contoh Dashboard Keuangan Lintas Cabang',
                'industry' => 'Perbankan',
                'excerpt' => 'Skenario ilustratif untuk merangkum data keuangan cabang dalam tampilan yang mudah dibaca pengambil keputusan.',
                'body' => "Dalam skenario ini, data pelaporan berasal dari beberapa unit dan perlu ditinjau secara konsisten. Sebelum membuat dashboard, tim perlu menyepakati definisi indikator serta sumber data yang menjadi acuan.\n\nPengembangan dapat dimulai dari beberapa laporan paling penting, kemudian menguji ketepatan angka dengan pemilik proses. Jadwal pembaruan data, pembatasan akses, dan jejak perubahan harus dirancang bersama.\n\nSetelah dashboard digunakan, organisasi dapat membandingkan waktu penyusunan laporan dan kualitas keputusan terhadap kondisi awal. Angka dampak hanya layak dipublikasikan setelah melalui pengukuran proyek nyata.",
                'metrics' => [],
                'gallery' => ['img/work-2.jpg'],
                'published_at' => $now->copy()->subDays(24),
            ],
            [
                'type' => ResourceType::GoLive,
                'slug' => 'portal-ritel-multi-cabang-di-distribusi',
                'title' => 'Contoh Portal Pemesanan dan Stok Multi-Cabang',
                'industry' => 'Ritel & Distribusi',
                'excerpt' => 'Skenario ilustratif untuk menghubungkan pemesanan, ketersediaan barang, dan pelaporan antar lokasi.',
                'body' => "Ketika pencatatan persediaan tersebar di banyak lokasi, tim sering menghabiskan waktu untuk memastikan data mana yang paling baru. Portal bersama dapat menjadi titik masuk untuk permintaan dan perubahan stok.\n\nAlur perlu dirancang dari kejadian nyata: barang diterima, dipindahkan, dipesan, lalu dikirim. Setiap perpindahan memerlukan penanggung jawab dan status yang mudah ditelusuri.\n\nPenerapan bertahap membantu tim memeriksa kesesuaian data dan kebiasaan kerja sebelum menambah lokasi. Pengurangan selisih stok hanya dapat diklaim setelah ada pengukuran sebelum dan sesudah implementasi.",
                'metrics' => [],
                'gallery' => ['img/work-3.jpg', 'img/work-4.jpg'],
                'published_at' => $now->copy()->subDays(40),
            ],
            [
                'type' => ResourceType::GoLive,
                'slug' => 'sistem-akademik-terpadu-di-universitas',
                'title' => 'Contoh Sistem Akademik Terpadu',
                'industry' => 'Pendidikan',
                'excerpt' => 'Skenario ilustratif untuk menyederhanakan alur penerimaan, administrasi akademik, dan layanan mahasiswa.',
                'body' => "Dalam skenario kampus, proses penerimaan dan akademik sering melibatkan beberapa unit dengan kebutuhan data berbeda. Langkah awalnya adalah memetakan perjalanan mahasiswa dari pendaftaran hingga layanan sehari-hari.\n\nSistem dapat dibangun per modul dengan hak akses sesuai peran. Pengujian bersama staf administrasi, pengajar, dan mahasiswa membantu menemukan istilah atau langkah yang membingungkan.\n\nSebelum peluncuran luas, tim perlu menyiapkan migrasi data, panduan penggunaan, dan jalur dukungan. Pengalaman pengguna dapat dinilai melalui masukan nyata setelah sistem digunakan.",
                'metrics' => [],
                'gallery' => ['img/about.jpg'],
                'published_at' => $now->copy()->subDays(58),
            ],
            [
                'type' => ResourceType::GoLive,
                'slug' => 'backup-terpusat-untuk-perusahaan-energi',
                'title' => 'Contoh Sistem Backup Terpusat untuk Operasional Energi',
                'industry' => 'Energi',
                'excerpt' => 'Skenario ilustratif untuk menata jadwal pencadangan dan prosedur pemulihan data lintas lokasi.',
                'body' => "Data operasional yang tersebar memerlukan kebijakan cadangan yang jelas. Setiap jenis data perlu memiliki prioritas pemulihan, penanggung jawab, dan masa simpan yang sesuai kebutuhan organisasi.\n\nRancangan teknis dapat menggabungkan pencadangan otomatis, pemantauan kegagalan, dan penyimpanan terpisah dari sistem utama. Keberhasilan salinan cadangan belum berarti proses pemulihan akan berjalan baik.\n\nKarena itu, simulasi pemulihan perlu dijadwalkan dan dicatat. Target waktu pemulihan baru dapat dinyatakan sebagai hasil ketika sudah diuji pada kondisi yang mewakili operasi nyata.",
                'metrics' => [],
                'gallery' => ['img/hero.jpg'],
                'published_at' => $now->copy()->subDays(75),
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function whitepapers(Carbon $now): array
    {
        return [
            [
                'type' => ResourceType::Whitepaper,
                'title' => 'Panduan Keamanan Informasi untuk Perusahaan',
                'excerpt' => 'Kerangka praktis menyusun kebijakan keamanan informasi berdasarkan ISO 27001.',
                'page_count' => null,
                'body' => "Kebijakan keamanan informasi membantu organisasi menyepakati cara melindungi data, menetapkan tanggung jawab, dan merespons risiko. Dokumen yang efektif perlu berangkat dari proses nyata, bukan sekadar daftar kontrol yang sulit dijalankan.\n\nMulailah dengan mengidentifikasi aset yang paling penting: data pelanggan, sistem operasional, akun dengan hak akses tinggi, dan layanan pihak ketiga. Untuk tiap aset, catat pemiliknya, dampak jika terganggu, serta kontrol yang sudah berjalan. Pemetaan ini membantu tim memilih prioritas secara proporsional.\n\nKerangka ISO 27001 dapat digunakan untuk menata kebijakan, pemeriksaan berkala, dan perbaikan. Namun penerapannya perlu disesuaikan dengan kapasitas organisasi. Tinjau kebijakan bersama pemilik proses dan uji apakah prosedur insiden benar-benar dapat dijalankan saat dibutuhkan.",
                'toc' => ['Identifikasi aset dan penilaian risiko', 'Menyusun kontrol berdasarkan ISO 27001', 'Audit internal dan perbaikan berkelanjutan'],
                'file_path' => null,
                'external_url' => null,
                'cta_label' => null,
                'published_at' => $now->copy()->subDays(14),
            ],
            [
                'type' => ResourceType::Whitepaper,
                'title' => '3 Insight Kunci Skills-Based Talent Management',
                'excerpt' => 'Cara mengelola talenta berbasis keterampilan di era otomatisasi.',
                'page_count' => null,
                'body' => "Pengelolaan talenta berbasis keterampilan menempatkan kemampuan yang dibutuhkan pekerjaan sebagai dasar pengembangan tim. Pendekatan ini memberi gambaran yang lebih rinci daripada jabatan saja, terutama saat kebutuhan proyek berubah cepat.\n\nLangkah awalnya adalah menyusun daftar keterampilan yang relevan dengan tujuan bisnis, lalu menilai kemampuan yang sudah dimiliki secara konsisten. Penilaian perlu melibatkan atasan dan anggota tim agar hasilnya tidak hanya bergantung pada data administratif.\n\nPeta keterampilan dapat membantu merencanakan pelatihan, mobilitas internal, dan rekrutmen. Teknologi mendukung pencatatan dan pembaruan data, tetapi keputusan tetap memerlukan percakapan tentang pengalaman, minat, dan kesempatan berkembang bagi setiap orang.",
                'toc' => ['Mengapa keterampilan lebih penting', 'Memetakan keterampilan organisasi', 'Teknologi pendukung'],
                'file_path' => null,
                'external_url' => null,
                'cta_label' => null,
                'published_at' => $now->copy()->subDays(20),
            ],
            [
                'type' => ResourceType::Whitepaper,
                'title' => 'Total Cost of Ownership: Cloud vs On-Premise',
                'excerpt' => 'Analisis biaya memilih cloud dibanding infrastruktur on-premise.',
                'page_count' => null,
                'body' => "Perbandingan cloud dan infrastruktur on-premise sering berhenti pada biaya langganan atau harga server. Padahal total cost of ownership juga mencakup kapasitas tim, pemeliharaan, lisensi, keamanan, konektivitas, dan biaya perubahan ketika kebutuhan meningkat.\n\nSusun perbandingan dengan periode waktu dan beban kerja yang sama. Masukkan biaya awal, pengeluaran rutin, serta kebutuhan cadangan dan pemulihan. Asumsi penggunaan perlu ditulis jelas agar hasil perhitungan dapat ditinjau ulang saat volume data atau pengguna berubah.\n\nPilihan terbaik bergantung pada pola penggunaan, kewajiban pengelolaan data, dan kemampuan operasional organisasi. Model TCO membantu memisahkan biaya yang pasti dari perkiraan, sehingga diskusi investasi menjadi lebih transparan.",
                'toc' => ['Komponen biaya yang terlupakan', 'Model perbandingan TCO', 'Studi kasus migrasi'],
                'file_path' => null,
                'external_url' => null,
                'cta_label' => null,
                'published_at' => $now->copy()->subDays(30),
            ],
            [
                'type' => ResourceType::Whitepaper,
                'title' => 'Menyiapkan Data untuk ERP',
                'excerpt' => 'Langkah praktis membersihkan data sebelum implementasi ERP.',
                'page_count' => null,
                'body' => "Implementasi ERP membutuhkan data yang dapat dipercaya. Jika kode barang, pelanggan, atau saldo awal tidak konsisten, sistem baru hanya akan memindahkan masalah lama ke proses yang lebih terintegrasi.\n\nAudit data sebaiknya dimulai sebelum konfigurasi akhir. Tentukan pemilik tiap kelompok data, aturan penamaan, sumber utama, dan cara menangani duplikasi. Hasil audit menjadi dasar untuk memilih data yang perlu dibersihkan atau diarsipkan.\n\nRencana migrasi perlu mencakup pemetaan kolom, percobaan impor, pemeriksaan hasil, dan prosedur koreksi. Libatkan pengguna yang memahami proses harian untuk memvalidasi data, lalu lakukan uji sebelum perpindahan operasional agar masalah bisa ditemukan lebih awal.",
                'toc' => ['Audit kualitas data', 'Pembersihan dan deduplikasi', 'Rencana migrasi'],
                'file_path' => null,
                'external_url' => null,
                'cta_label' => null,
                'published_at' => $now->copy()->subDays(45),
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function ebooks(Carbon $now): array
    {
        return [
            [
                'type' => ResourceType::Ebook,
                'title' => 'Langkah Pertama Adopsi ERP',
                'excerpt' => 'E-book untuk tim yang baru memulai perjalanan implementasi ERP.',
                'page_count' => null,
                'body' => "ERP menyatukan proses yang sebelumnya berjalan di banyak aplikasi dan berkas. Nilainya baru terasa ketika organisasi memahami alur kerja yang ingin diperbaiki, data yang harus dipindahkan, dan peran tiap tim dalam perubahan tersebut.\n\nPanduan ini mengajak pembaca memulai dari pemetaan proses, bukan daftar modul. Pilih satu alur yang paling sering menimbulkan pekerjaan ulang, lalu catat siapa yang membuat data, siapa yang menyetujui, dan kapan informasi dibutuhkan. Hasil pemetaan menjadi dasar untuk menentukan kebutuhan sistem.\n\nSetelah prioritas jelas, siapkan data awal dan jalankan uji coba bersama pengguna. Peluncuran bertahap memberi ruang untuk memperbaiki prosedur, melatih tim, dan mengukur hasil sebelum sistem diperluas ke bagian lain.",
                'chapters' => [
                    ['title' => 'Memetakan proses bisnis', 'description' => 'Mengidentifikasi proses yang terdampak.'],
                    ['title' => 'Memilih modul dan menyiapkan data', 'description' => 'Menentukan prioritas dan data awal.'],
                    ['title' => 'Rencana go-live', 'description' => 'Peluncuran dan dukungan pasca implementasi.'],
                ],
                'file_path' => null,
                'external_url' => null,
                'cta_label' => null,
                'published_at' => $now->copy()->subDays(18),
            ],
            [
                'type' => ResourceType::Ebook,
                'title' => 'Membangun Tim Keamanan Siber Pertama',
                'excerpt' => 'Panduan praktis menyusun tim dan proses keamanan dari nol.',
                'page_count' => null,
                'body' => "Keamanan siber bukan hanya pekerjaan satu spesialis. Bahkan organisasi yang belum memiliki tim khusus tetap perlu menentukan siapa yang menjaga akses, meninjau perubahan, dan merespons ketika terjadi gangguan.\n\nPanduan ini membantu menyusun tanggung jawab minimum yang realistis. Mulailah dengan daftar aset penting, pemilik tiap sistem, dan cara melaporkan kejadian yang tidak biasa. Proses sederhana yang dijalankan rutin lebih berguna daripada kebijakan panjang yang tidak dipahami tim.\n\nLangkah berikutnya adalah melatih respons insiden dan meninjau hasilnya. Setiap latihan memberi masukan tentang jalur komunikasi, kebutuhan pencatatan, dan keputusan yang harus diambil lebih cepat saat kejadian nyata.",
                'chapters' => [
                    ['title' => 'Peran dan tanggung jawab minimum', 'description' => 'Siapa mengerjakan apa di awal.'],
                    ['title' => 'Proses deteksi dan respons', 'description' => 'Alur kerja saat terjadi insiden.'],
                    ['title' => 'Meningkatkan kematangan', 'description' => 'Peta jalan 12 bulan.'],
                ],
                'file_path' => null,
                'external_url' => null,
                'cta_label' => null,
                'published_at' => $now->copy()->subDays(33),
            ],
            [
                'type' => ResourceType::Ebook,
                'title' => 'Panduan Integrasi API untuk Pemula',
                'excerpt' => 'Dasar-dasar menghubungkan sistem lewat API agar data mengalir otomatis.',
                'page_count' => null,
                'body' => "API memungkinkan dua sistem bertukar informasi dengan aturan yang jelas. Namun integrasi yang dapat diandalkan memerlukan lebih dari satu permintaan yang berhasil: tim juga harus memahami sumber data, waktu pembaruan, dan cara menangani kegagalan.\n\nPanduan ini memperkenalkan istilah dasar melalui contoh alur bisnis sederhana. Pembaca diajak membedakan data yang perlu disinkronkan langsung dari data yang cukup diperbarui berkala, kemudian menyusun kontrak yang dapat dipahami kedua pihak.\n\nBagian akhir membahas pemantauan, percobaan ulang, dan pencatatan kesalahan. Dengan rancangan ini, masalah integrasi dapat ditemukan serta diperbaiki tanpa mengandalkan pemeriksaan manual setiap hari.",
                'chapters' => [
                    ['title' => 'Konsep dasar API', 'description' => 'Istilah dan cara kerja.'],
                    ['title' => 'Pola integrasi umum', 'description' => 'Sinkronisasi dan antrean.'],
                    ['title' => 'Menangani kegagalan', 'description' => 'Retry, antrean, dan pemantauan.'],
                ],
                'file_path' => null,
                'external_url' => null,
                'cta_label' => null,
                'published_at' => $now->copy()->subDays(50),
            ],
            [
                'type' => ResourceType::Ebook,
                'title' => 'Checklist Transformasi Digital',
                'excerpt' => 'Daftar periksa praktis menyusun agenda transformasi digital.',
                'page_count' => null,
                'body' => "Transformasi digital tidak harus dimulai dengan mengganti semua sistem. Langkah pertama adalah memahami proses yang paling menyita waktu, informasi yang sulit dipercaya, dan pengalaman pengguna yang perlu diperbaiki.\n\nChecklist ini mengelompokkan pertanyaan ke dalam tiga tahap: kesiapan organisasi, penentuan prioritas, dan pengukuran kemajuan. Gunakan jawabannya untuk memilih beberapa inisiatif yang dapat diuji dengan lingkup jelas, bukan membuat daftar proyek yang terlalu panjang.\n\nTinjau kembali hasilnya secara berkala bersama pemilik proses. Prioritas dapat berubah ketika organisasi memperoleh data baru atau ketika solusi awal memperlihatkan kebutuhan yang sebelumnya tidak terlihat.",
                'chapters' => [
                    ['title' => 'Menilai kesiapan organisasi', 'description' => 'Memetakan kondisi saat ini.'],
                    ['title' => 'Menyusun prioritas', 'description' => 'Memilih inisiatif berdampak tinggi.'],
                    ['title' => 'Mengukur kemajuan', 'description' => 'Metrik dan tinjauan berkala.'],
                ],
                'file_path' => null,
                'external_url' => null,
                'cta_label' => null,
                'published_at' => $now->copy()->subDays(60),
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function news(Carbon $now): array
    {
        return [
            [
                'type' => ResourceType::News,
                'title' => 'Fokus Digital: Menata Fondasi Sistem Sebelum Menambah Fitur',
                'excerpt' => 'Catatan redaksi KIT tentang langkah awal yang membantu perusahaan menentukan prioritas pengembangan sistem secara lebih jelas.',
                'cover_image_path' => 'img/editorial/news-workspace.png',
                'body' => "Permintaan fitur sering datang dari banyak arah. Ada kebutuhan pelanggan, target penjualan, dan pekerjaan internal yang ingin dipercepat. Sebelum semuanya masuk antrean pengembangan, perusahaan perlu memahami bagian mana yang paling menghambat pekerjaan hari ini.\n\nMulailah dengan memetakan satu alur kerja penting. Ikuti perjalanan data dari saat dibuat sampai dipakai untuk mengambil keputusan. Tandai input yang berulang, persetujuan yang menunggu terlalu lama, dan informasi yang harus dipindah secara manual. Temuan ini membantu tim membedakan masalah proses dari masalah tampilan.\n\nLangkah berikutnya adalah menyepakati sistem yang menjadi sumber data utama. Jika satu informasi dapat diubah di beberapa tempat, laporan mudah berbeda. Kejelasan kepemilikan data menjadi dasar bagi pengembangan fitur maupun integrasi di kemudian hari.\n\nPrioritas yang baik mempertimbangkan dampak dan kesiapan. Proses dengan manfaat besar mungkin tetap perlu menunggu bila data dasarnya belum rapi. Sebaliknya, perbaikan kecil pada alur yang sering dipakai dapat menghasilkan manfaat lebih cepat.\n\nSetelah perubahan dirilis, ukur hasilnya dengan indikator yang disepakati sejak awal. Waktu proses, kesalahan input, dan masukan pengguna memberi gambaran yang lebih jujur daripada sekadar menghitung jumlah fitur yang selesai.",
                'published_at' => $now->copy()->subDay(),
            ],
            [
                'title' => 'Catatan Teknologi: Integrasi API Perlu Rencana Saat Layanan Gagal',
                'type' => ResourceType::News,
                'excerpt' => 'Integrasi yang baik tetap dapat dipantau dan dipulihkan ketika salah satu sistem tidak merespons.',
                'cover_image_path' => 'img/work-4.jpg',
                'body' => "Menghubungkan dua aplikasi melalui API tampak sederhana ketika semua permintaan berhasil. Tantangan sebenarnya muncul ketika data terlambat, layanan tujuan tidak tersedia, atau format respons berubah. Karena itu, rancangan integrasi perlu membahas kegagalan sejak awal.\n\nTim perlu menentukan data mana yang boleh dicoba ulang dan mana yang berisiko menimbulkan transaksi ganda. Identitas unik untuk setiap pekerjaan membantu sistem mengenali permintaan yang sama. Catatan kejadian yang jelas memudahkan operator menemukan titik gangguan.\n\nBuat jalur pemulihan yang dapat dijalankan tanpa mengubah kode. Antrean pekerjaan, notifikasi untuk kegagalan berulang, dan prosedur pengiriman ulang memberi ruang bagi tim untuk menangani insiden secara terkendali.\n\nUji integrasi dengan skenario koneksi terputus dan data tidak valid sebelum digunakan oleh pengguna. Hasil pengujian ini membantu menetapkan ekspektasi layanan dan tanggung jawab antar tim.",
                'published_at' => $now->copy()->subDays(3),
            ],
            [
                'title' => 'Panduan Redaksi: Membaca Kebutuhan Keamanan Aplikasi Sejak Awal Proyek',
                'type' => ResourceType::News,
                'excerpt' => 'Hak akses, data pribadi, dan jejak aktivitas sebaiknya menjadi bagian dari pembahasan kebutuhan, bukan tambahan di akhir.',
                'cover_image_path' => 'img/work-2.jpg',
                'body' => "Keamanan sering dibahas menjelang peluncuran, padahal keputusan paling berpengaruh dibuat saat kebutuhan sistem disusun. Jenis data yang disimpan, siapa yang dapat melihatnya, dan berapa lama data diperlukan akan memengaruhi rancangan aplikasi.\n\nMulai dengan daftar peran pengguna dan tindakan yang boleh dilakukan masing-masing. Periksa kasus yang mudah terlewat, seperti staf yang berpindah divisi atau akun yang tidak lagi aktif. Hak akses yang jelas mengurangi risiko sekaligus membantu pengalaman pengguna.\n\nIdentifikasi informasi yang perlu dilindungi lebih ketat. Batasi data yang ditampilkan, rekam perubahan penting, dan siapkan proses penghapusan atau koreksi bila diperlukan. Keputusan ini perlu dipahami oleh tim produk dan operasional, bukan hanya pengembang.\n\nSebelum rilis, lakukan pengujian dengan akun dari peran yang berbeda. Pastikan pengguna tidak dapat melihat data di luar kewenangannya dan setiap kesalahan ditangani tanpa membuka informasi sensitif.",
                'published_at' => $now->copy()->subDays(6),
            ],
            [
                'title' => 'Dari Ide ke Rilis: Mengapa Umpan Balik Pengguna Perlu Datang Lebih Cepat',
                'type' => ResourceType::News,
                'excerpt' => 'Pengujian alur sederhana bersama pengguna dapat menunjukkan masalah lebih awal daripada menunggu produk selesai sepenuhnya.',
                'cover_image_path' => 'img/editorial/insight-team.png',
                'body' => "Dalam proyek digital, asumsi kecil dapat berkembang menjadi pekerjaan besar. Istilah pada tombol, urutan formulir, dan informasi yang dianggap jelas oleh tim proyek belum tentu masuk akal bagi pengguna. Karena itu, pengujian tidak perlu menunggu seluruh fitur selesai.\n\nTunjukkan prototipe atau alur kerja awal kepada beberapa pengguna yang mewakili tugas berbeda. Berikan tujuan yang perlu mereka capai dan amati langkah yang mereka ambil. Hindari menjelaskan cara memakai produk terlalu cepat; kebingungan yang muncul justru menunjukkan bagian yang perlu diperbaiki.\n\nCatat pola masalah, bukan hanya komentar pribadi. Jika beberapa orang berhenti pada langkah yang sama, kemungkinan alurnya perlu diubah. Kelompokkan temuan berdasarkan dampaknya terhadap tugas utama.\n\nUlangi pengujian setelah perbaikan. Siklus kecil yang berlangsung rutin lebih mudah dikelola dan membantu tim merilis produk yang terasa jelas sejak penggunaan pertama.",
                'published_at' => $now->copy()->subDays(9),
            ],
            [
                'title' => 'Mengenal Tahap Serah Terima Sistem yang Siap Dikelola Tim Internal',
                'type' => ResourceType::News,
                'excerpt' => 'Dokumentasi, akses, dan pelatihan perlu dipersiapkan bersama agar sistem tetap dapat dirawat setelah proyek selesai.',
                'cover_image_path' => 'img/work-3.jpg',
                'body' => "Sebuah sistem bisa berjalan baik pada hari peluncuran tetapi sulit dirawat beberapa bulan kemudian. Risiko ini biasanya muncul ketika pengetahuan proyek hanya berada pada satu tim atau satu orang. Serah terima perlu direncanakan sejak awal pekerjaan.\n\nPastikan organisasi memiliki akses ke repositori, lingkungan kerja, dan layanan pendukung. Daftar akun harus mencakup pemilik, jenis akses, dan prosedur penggantian kredensial. Hindari ketergantungan pada akun pribadi.\n\nDokumentasi sebaiknya membantu pekerjaan nyata: cara menjalankan aplikasi, memperbarui konfigurasi, memeriksa kesalahan, dan mengembalikan layanan jika ada gangguan. Dokumen yang singkat tetapi dapat diuji lebih bermanfaat daripada berkas panjang yang tidak pernah dibuka.\n\nLakukan latihan bersama tim penerima. Minta mereka menjalankan satu perubahan kecil atau simulasi pemulihan dengan dukungan tim pengembang. Bagian yang masih membingungkan dapat diperbaiki sebelum masa dukungan berakhir.",
                'published_at' => $now->copy()->subDays(13),
            ],
            [
                'type' => ResourceType::News,
                'title' => 'Perawatan Aplikasi Setelah Rilis: Apa yang Perlu Dipantau?',
                'excerpt' => 'Kualitas aplikasi dijaga melalui pemantauan, perbaikan rutin, dan umpan balik yang ditindaklanjuti.',
                'cover_image_path' => 'img/work-1.jpg',
                'body' => "Setelah aplikasi diluncurkan, pekerjaan tim beralih dari membangun fitur ke menjaga layanan tetap dapat diandalkan. Keluhan pengguna hanya menunjukkan sebagian masalah; tim perlu melihat kondisi sistem secara lebih menyeluruh.\n\nMulailah dari indikator yang terkait langsung dengan pengalaman pengguna: waktu respons, jumlah kesalahan, keberhasilan pekerjaan terjadwal, dan kelancaran alur utama. Catat perubahan agar gangguan dapat dihubungkan dengan rilis tertentu.\n\nSediakan jalur pelaporan yang mudah bagi pengguna. Setiap laporan perlu mencatat langkah untuk mengulang masalah, dampaknya, dan siapa yang menangani. Pola keluhan yang berulang dapat menunjukkan kebutuhan perbaikan desain maupun proses.\n\nJadwalkan pembaruan dependensi, pemeriksaan cadangan, dan peninjauan hak akses. Perawatan kecil yang rutin membantu mencegah pekerjaan darurat yang lebih mahal.",
                'published_at' => $now->copy()->subDays(17),
            ],
            [
                'type' => ResourceType::News,
                'title' => 'Pengalaman Mobile yang Baik Dimulai dari Tugas Paling Penting',
                'excerpt' => 'Antarmuka ponsel perlu mengutamakan pekerjaan utama pengguna, bukan memadatkan seluruh halaman desktop.',
                'cover_image_path' => 'img/work-2.jpg',
                'body' => "Layar ponsel memberi ruang lebih sedikit, tetapi pengguna tetap perlu menyelesaikan tugas yang sama. Menyusutkan seluruh isi halaman desktop sering membuat tombol kecil, formulir panjang, dan informasi penting sulit ditemukan.\n\nTentukan tugas utama pada setiap halaman. Letakkan tindakan yang paling dibutuhkan di area yang mudah dijangkau, lalu susun informasi pendukung secara bertahap. Pengguna seharusnya dapat memahami langkah berikutnya tanpa mencari di banyak bagian.\n\nPeriksa formulir dengan data nyata. Gunakan label yang jelas, jenis papan ketik sesuai isian, serta pesan kesalahan yang menjelaskan cara memperbaiki input. Simpan kemajuan bila proses membutuhkan waktu lama.\n\nUji dengan perangkat dan koneksi yang berbeda. Kecepatan halaman, ukuran sentuhan, dan keterbacaan teks sama pentingnya dengan tampilan visual.",
                'published_at' => $now->copy()->subDays(21),
            ],
            [
                'type' => ResourceType::News,
                'title' => 'Migrasi Data yang Rapi Mengurangi Masalah Saat Sistem Baru Digunakan',
                'excerpt' => 'Pembersihan, pemetaan, dan uji coba data sebaiknya selesai sebelum tanggal perpindahan sistem.',
                'cover_image_path' => 'img/work-3.jpg',
                'body' => "Perpindahan ke sistem baru sering terlihat sebagai pekerjaan teknis memindahkan tabel. Pada praktiknya, tantangan terbesar adalah memastikan data lama masih punya arti yang sama di proses baru. Nama kolom dapat cocok, tetapi aturan bisnis di belakangnya berbeda.\n\nInventarisasi sumber data lebih dulu. Cari duplikasi, nilai kosong, dan entri yang tidak lagi dipakai. Sepakati aturan pembersihan bersama pemilik proses agar keputusan tidak hanya ditentukan oleh tim teknis.\n\nBuat pemetaan yang dapat ditinjau: dari mana setiap data berasal, ke mana ia dipindah, dan bagaimana nilainya diubah. Jalankan migrasi percobaan dengan sebagian data, lalu minta pengguna memeriksa hasil pada alur kerja nyata.\n\nSiapkan rencana cadangan untuk hari perpindahan. Tentukan kapan data lama berhenti diubah, bagaimana perubahan terakhir disalin, dan kriteria kapan tim perlu kembali ke sistem sebelumnya.",
                'published_at' => $now->copy()->subDays(25),
            ],
            [
                'type' => ResourceType::News,
                'title' => 'Mengukur Kinerja Website dari Tujuan Bisnis, Bukan Hanya Kunjungan',
                'excerpt' => 'Angka kunjungan menjadi lebih berguna ketika dikaitkan dengan pertanyaan, formulir, dan tindakan penting pengunjung.',
                'cover_image_path' => 'img/editorial/news-workspace.png',
                'body' => "Jumlah pengunjung adalah titik awal, bukan ukuran keberhasilan satu-satunya. Website perusahaan perlu membantu orang menemukan informasi, memahami layanan, dan menghubungi tim ketika siap berdiskusi.\n\nTentukan tindakan yang bermakna untuk setiap jenis halaman. Pada artikel, pengguna mungkin perlu berpindah ke layanan terkait. Pada halaman layanan, tujuan yang lebih dekat adalah membuka formulir kontak atau meminta konsultasi.\n\nPerhatikan perjalanan pengguna, bukan hanya halaman akhir. Jika banyak orang berhenti sebelum mengirim formulir, periksa panjang isian, kejelasan pesan, dan pengalaman pada ponsel. Data kuantitatif sebaiknya dilengkapi dengan masukan langsung dari pengunjung atau tim penjualan.\n\nTinjau hasil secara berkala dan ubah satu hal dalam satu waktu. Dengan cara itu, tim lebih mudah memahami perubahan mana yang benar-benar membantu.",
                'published_at' => $now->copy()->subDays(29),
            ],
        ];
    }
}
