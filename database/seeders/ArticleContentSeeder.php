<?php

namespace Database\Seeders;

use App\Enums\PublishStatus;
use App\Models\Article;
use App\Models\BlogCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ArticleContentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $categories = ['Panduan', 'Keamanan', 'Operasional', 'Teknologi'];

        foreach ($categories as $index => $name) {
            BlogCategory::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => 'Artikel '.$name.' dari tim KIT Konsultan IT.',
                    'sort_order' => $index + 1,
                    'status' => PublishStatus::Published,
                ],
            );
        }

        $categoryIds = BlogCategory::query()->pluck('id', 'name');

        $articles = [
            [
                'Merencanakan Aplikasi Bisnis yang Benar-Benar Dipakai Tim',
                'Panduan',
                'img/editorial/insight-team.png',
                '1 Oktober 2026',
                'Aplikasi yang berhasil dimulai dari alur kerja pengguna, bukan daftar fitur yang panjang. Berikut langkah praktis menyusun fondasinya.',
                "Mulai dari pekerjaan yang paling sering dilakukan. Sebelum membahas layar dan teknologi, amati bagaimana tim menyelesaikan satu tugas dari awal sampai akhir. Catat data yang mereka cari, keputusan yang perlu diambil, dan hambatan yang membuat pekerjaan berulang. Peta sederhana ini jauh lebih berguna daripada daftar fitur tanpa konteks.\n\nLibatkan pengguna sejak awal. Wawancarai orang yang akan memakai sistem setiap hari, bukan hanya penanggung jawab proyek. Tanyakan contoh pekerjaan nyata dan minta mereka menunjukkan alat yang dipakai sekarang. Perbedaan antara proses resmi dan praktik di lapangan sering menjadi sumber kebutuhan paling penting.\n\nTentukan hasil yang ingin diukur. Misalnya waktu memproses satu permintaan, jumlah kesalahan input, atau lamanya menyiapkan laporan. Dengan ukuran awal yang jelas, tim dapat mengevaluasi apakah aplikasi baru benar-benar memperbaiki pekerjaan.\n\nBangun versi awal dengan alur inti. Prioritaskan satu proses yang utuh dan dapat diuji oleh pengguna. Fitur tambahan bisa menyusul setelah alur utama terbukti bermanfaat. Pendekatan bertahap memudahkan umpan balik dan mengurangi risiko mengembangkan bagian yang ternyata jarang dipakai.\n\nSiapkan pemilik proses dan rencana adopsi. Tentukan siapa yang menjawab pertanyaan pengguna, memperbaiki data awal, dan memutuskan perubahan berikutnya. Aplikasi yang baik tetap membutuhkan dukungan manusia agar menjadi kebiasaan kerja baru.",
            ],
            [
                'Lima Keputusan Penting Sebelum Mengintegrasikan Dua Sistem',
                'Teknologi',
                'img/editorial/news-workspace.png',
                '29 September 2026',
                'Integrasi bukan sekadar menghubungkan API. Kepemilikan data, kegagalan sinkronisasi, dan keamanan perlu disepakati sejak awal.',
                "Ketika dua sistem saling bertukar data, pertanyaan pertama bukan cara mengirimnya, melainkan siapa pemilik data yang benar. Sepakati sistem sumber untuk setiap informasi penting, seperti data pelanggan, harga, dan status transaksi. Tanpa keputusan ini, koreksi pada satu sistem bisa ditimpa oleh sistem lain.\n\nPilih kapan data harus diperbarui. Tidak semua proses membutuhkan sinkronisasi seketika. Laporan harian mungkin cukup diperbarui terjadwal, sedangkan status pembayaran menuntut pembaruan lebih cepat. Kebutuhan waktu menentukan rancangan integrasi dan biaya operasionalnya.\n\nRancang skenario gagal sebagai bagian dari alur normal. Koneksi dapat terputus, data dapat ditolak, dan layanan pihak ketiga dapat melambat. Tentukan antrean, percobaan ulang, catatan kesalahan, dan prosedur pemeriksaan manual sebelum integrasi digunakan di produksi.\n\nBatasi akses sesuai kebutuhan. Gunakan kredensial terpisah, izin minimum, serta pencatatan aktivitas yang dapat ditinjau. Data sensitif perlu dilindungi saat dikirim dan saat disimpan. Tim juga perlu tahu siapa yang bertanggung jawab jika suatu akses harus dicabut.\n\nUji dengan data yang menyerupai kondisi nyata. Periksa duplikasi, nilai kosong, perubahan format, dan pembaruan berulang. Keberhasilan satu permintaan contoh belum cukup membuktikan integrasi siap digunakan setiap hari.",
            ],
            [
                'Checklist Peluncuran Website Perusahaan yang Sering Terlewat',
                'Operasional',
                'img/work-3.jpg',
                '25 September 2026',
                'Sebelum website ditayangkan, periksa kinerja, aksesibilitas, konten, analitik, dan alur kontak agar pengunjung mendapat pengalaman yang utuh.',
                "Peluncuran website bukan akhir dari proyek desain. Inilah saat halaman mulai bertemu pengunjung, mesin pencari, dan perangkat yang berbeda. Pemeriksaan akhir sebaiknya dilakukan dengan daftar yang dapat diulang, bukan sekadar melihat halaman utama sekali.\n\nPeriksa seluruh jalur penting. Coba navigasi, formulir kontak, tombol tindakan, dan tautan dari halaman yang berbeda. Pastikan pesan sukses dan gagal mudah dipahami. Formulir yang tampil rapi tetapi tidak mengirim pesan adalah kegagalan yang sering baru diketahui setelah ada calon klien yang hilang.\n\nUji pada layar dan koneksi yang berbeda. Buka halaman di ponsel, tablet, dan desktop. Cek ukuran teks, area tombol, serta apakah gambar memperlambat halaman di jaringan seluler. Gambar sebaiknya mempunyai teks alternatif yang menjelaskan fungsinya.\n\nRapikan informasi untuk pencarian. Setiap halaman penting memerlukan judul yang spesifik, deskripsi singkat, dan alamat yang mudah dibaca. Periksa pula apakah halaman yang tidak perlu muncul di pencarian sudah ditandai dengan tepat.\n\nTetapkan cara memantau setelah tayang. Pantau kesalahan formulir, halaman yang paling sering dikunjungi, dan pertanyaan masuk. Temuan pada minggu pertama menjadi bahan perbaikan yang lebih berharga daripada menebak kebutuhan pengguna sebelum peluncuran.",
            ],
            [
                'Menyusun Kerangka Acuan Kerja Proyek Software Agar Tidak Bengkak',
                'Panduan',
                'img/work-3.jpg',
                '11 September 2026',
                'Kerangka acuan kerja yang rapi adalah pengaman anggaran paling murah. Panduan ini membahas cara menuliskannya tanpa istilah yang membingungkan.',
                "Kerangka Acuan Kerja (KAK) sering dianggap formalitas administratif. Padahal dokumen ini yang menentukan apakah proyek software Anda selesai sesuai anggaran atau melebar tanpa kendali.\n\nMulai dari masalah, bukan solusi. Banyak KAK langsung menyebut teknologi tertentu padahal yang perlu diselesaikan adalah proses bisnis yang lambat. Tuliskan kondisi saat ini, dampaknya, dan hasil yang diinginkan.\n\nTetapkan batas lingkup secara eksplisit. Sebutkan apa yang termasuk dan — yang tak kalah penting — apa yang tidak termasuk. Daftar 'di luar lingkup' mencegah permintaan tambahan yang muncul di tengah jalan.\n\nAkhiri dengan kriteria penerimaan yang bisa diuji. Alih-alih 'sistem cepat', tulis 'halaman memuat di bawah dua detik pada 50 pengguna serentak'. Kriteria yang terukur membuat serah terima jadi objektif.",
            ],
            [
                'Jadwal Pengujian Penetrasi yang Wajar untuk Aplikasi Perusahaan',
                'Keamanan',
                'img/work-4.jpg',
                '9 September 2026',
                'Pengujian penetrasi bukan sekadar kewajiban tahunan. Simak cara menyusun jadwal yang memberi nilai nyata bagi tim Anda.',
                "Banyak perusahaan melakukan pengujian penetrasi sekali setahun demi memenuhi audit. Hasilnya dilaporkan, lalu dilupakan. Padahal nilai terbesar dari pentest ada pada tindak lanjutnya.\n\nTetapkan pemicu pengujian, bukan hanya tanggal. Uji kembali setiap kali ada perubahan besar: fitur autentikasi baru, integrasi pihak ketiga, atau perubahan infrastruktur. Risiko muncul saat sistem berubah, bukan saat kalender berputar.\n\nPisahkan pengujian otomatis dari manual. Pemindai kerentanan bisa jalan setiap malam di pipeline CI. Pengujian manual yang mendalam dilakukan pada momen kunci saja karena membutuhkan waktu dan tenaga ahli.\n\nPastikan temuan punya pemilik. Setiap kerentanan harus dikaitkan dengan penanggung jawab dan tenggat. Laporan tanpa tindak lanjut hanya menciptakan rasa aman yang palsu.",
            ],
            [
                'Checklist Serah Terima Aplikasi dari Vendor ke Tim Internal',
                'Operasional',
                'img/work-2.jpg',
                '5 September 2026',
                'Serah terima yang buruk membuat tim internal bergantung pada vendor selamanya. Ini daftar periksa yang kami pakai.',
                "Momen paling berisiko dalam sebuah proyek bukan saat coding, melainkan saat pemilik proyek berganti dari vendor ke tim internal. Di titik ini pengetahuan mudah hilang.\n\nMulai dari kode sumber dan repositori. Pastikan akses penuh, riwayat versi lengkap, dan tidak ada dependensi pada akun pribadi siapa pun. Kredensial harus berpindah ke akun milik perusahaan.\n\nDokumentasi yang berguna bukan yang tebal. Cukup arsitektur sistem, cara menjalankan di lokal, prosedur deploy, dan daftar integrasi pihak ketiga. Sertakan rekaman sesi penjelasan agar bisa ditonton ulang.\n\nAkhiri dengan pelatihan dan masa garansi. Tim internal harus bisa melakukan perubahan kecil sendiri sebelum kontrak berakhir. Garansi memberi ruang aman ketika masalah tak terduga muncul di produksi.",
            ],
            [
                'Kapan Perusahaan Sebaiknya Migrasi ke Cloud?',
                'Teknologi',
                'img/hero.jpg',
                '2 September 2026',
                'Cloud bukan selalu lebih murah. Kenali sinyal bahwa infrastruktur on-premise sudah menjadi beban.',
                "Pertanyaan yang tepat bukan 'apakah harus ke cloud', melainkan 'kapan'. Migrasi yang dipaksakan akan membengkak biaya tanpa manfaat yang jelas.\n\nSinyal pertama adalah pertumbuhan yang sulit diikuti. Setiap lonjakan pengguna memaksa pembelian server baru yang perlu berbulan-bulan untuk disiapkan. Cloud memungkinkan penambahan kapasitas dalam hitungan menit.\n\nSinyal kedua adalah beban operasional yang menyita tim. Jika engineer terbaik Anda lebih banyak mengurus patching dan backup daripada membangun produk, itu tanda infrastruktur sebaiknya diserahkan ke pihak yang spesialis.\n\nSinyal ketiga adalah kebutuhan keamanan dan kepatuhan. Penyedia cloud besar menawarkan kontrol keamanan dan sertifikasi yang mahal untuk direplikasi sendiri. Mulailah dari beban kerja yang paling rendah risiko, lalu evaluasi sebelum memindahkan sistem inti.",
            ],
            [
                'Mengukur Keberhasilan Proyek Software Setelah Go-Live',
                'Panduan',
                'img/work-1.jpg',
                '30 Agustus 2026',
                'Go-live bukan garis akhir. Metrik apa yang layak dipantau tiga bulan pertama setelah sistem mengudara?',
                "Banyak proyek dianggap selesai saat tombol deploy ditekan. Padahal keberhasilan sebenarnya baru terlihat setelah sistem dipakai sehari-hari oleh pengguna nyata.\n\nPilih sedikit metrik yang bermakna. Untuk sistem internal, waktu penyelesaian tugas dan tingkat kesalahan input biasanya lebih relevan daripada jumlah klik. Untuk layanan pelanggan, waktu respons dan tingkat penyelesaian di percobaan pertama lebih penting.\n\nBandingkan dengan kondisi sebelum sistem ada. Tanpa garis dasar (baseline), angka metrik tidak bercerita. Catat berapa lama proses berjalan secara manual sebelumnya agar perbaikan terlihat jelas.\n\nAdakan tinjauan tiga bulan. Kumpulkan masukan pengguna, ukur ulang metrik, dan tentukan prioritas perbaikan berikutnya. Sistem yang baik tumbuh melalui iterasi, bukan sekali jadi.",
            ],
            [
                'Kenapa Dokumentasi Sering Gagal dan Cara Memperbaikinya',
                'Operasional',
                'img/about.jpg',
                '27 Agustus 2026',
                'Dokumentasi ditinggalkan bukan karena malas, tetapi karena alurnya salah. Ini pendekatan yang bertahan lama.',
                "Setiap tim berjanji akan menulis dokumentasi. Beberapa bulan kemudian dokumen itu sudah tidak sesuai dengan kenyataan. Akar masalahnya bukan pada niat, melainkan pada alur kerja.\n\nTulis dekat dengan perubahan. Dokumentasi yang ditulis seminggu setelah kode selesai sudah setengah basi. Paling baik tulis di pull request yang sama dengan perubahannya.\n\nSimpan di satu tempat yang dicari orang. Jika dokumentasi tersebar di tiga alat berbeda, tidak ada yang akan mempercayainya. Satukan tempat dan pastikan bisa dicari.\n\nUji dengan orang baru. Cara tercepat mengetahui dokumentasi sudah dapat dipakai adalah meminta anggota tim baru mengikutinya dari nol. Setiap kebingungan mereka adalah kekurangan dokumen, bukan kekurangan mereka.",
            ],
            [
                'Mitos dan Fakta Seputar Keamanan Aplikasi Web',
                'Keamanan',
                'img/work-4.jpg',
                '24 Agustus 2026',
                'Firewall saja tidak cukup. Beberapa anggapan umum soal keamanan justru membuat tim lengah.',
                "Keamanan aplikasi sering dianggap urusan tim infrastruktur. Padahal sebagian besar serangan nyata menyusup lewat celah di kode aplikasi sendiri.\n\nMitos pertama: cukup pakai pemindai otomatis. Pemindai memang membantu menemukan kerentanan umum, tetapi logika bisnis yang salah hanya terlihat lewat pengujian manual.\n\nMitos kedua: aplikasi internal tidak perlu dilindungi. Justru sistem internal menyimpan data paling sensitif. Penyerang yang sudah masuk ke jaringan akan mencari sistem yang paling lunak proteksinya.\n\nFaktanya, keamanan adalah proses berkelanjutan. Validasi input, pengelolaan akses berbasis peran, pencatatan aktivitas, dan pembaruan dependensi secara rutin jauh lebih efektif daripada sekadar memasang alat mahal lalu melupakannya.",
            ],
            [
                'Memilih Teknologi yang Tepat Tanpa Terjebak Tren',
                'Teknologi',
                'img/work-2.jpg',
                '21 Agustus 2026',
                'Teknologi terbaru belum tentu cocok untuk tim Anda. Kerangka sederhana untuk mengambil keputusan yang tahan lama.',
                "Setiap tahun muncul kerangka kerja dan basis data baru yang katanya wajib diadopsi. Mengikuti semuanya hanya akan membuat sistem Anda sulit dirawat.\n\nMulai dari kompetensi tim. Teknologi terbaik adalah yang dikuasai tim Anda hari ini, atau yang bisa mereka pelajari dengan cepat. Dukungan komunitas yang luas mempercepat penyelesaian masalah.\n\nPertimbangkan umur dukungan. Periksa seberapa lama proyek itu dirawat secara aktif dan berapa sering pembaruan keamanannya. Teknologi yang ditinggalkan pengembangnya akan menjadi utang teknis di kemudian hari.\n\nUji dengan pilot kecil. Sebelum memutuskan untuk sistem inti, bangun modul kecil sebagai percobaan. Keputusan berbasis hasil nyata jauh lebih aman daripada berbasis popularitas.",
            ],
            [
                'Panduan Estimasi Biaya Proyek Software untuk Pemilik Bisnis',
                'Panduan',
                'img/work-1.jpg',
                '18 Agustus 2026',
                'Mengapa estimasi biaya software sulit dipastikan? Cara memahami struktur biaya tanpa latar belakang teknis.',
                "Pertanyaan 'berapa biayanya' hampir selalu muncul di awal diskusi. Menjawabnya butuh pemahaman atas lingkup, bukan sekadar tebakan dari jumlah halaman atau tombol.\n\nBiaya ditentukan oleh kompleksitas, bukan jumlah fitur. Satu fitur integrasi dengan sistem lama bisa memakan waktu lebih banyak daripada sepuluh halaman tampilan sederhana.\n\nSertakan biaya di luar pembangunan. Desain, pengujian, deployment, pelatihan, dan pemeliharaan berkelanjutan adalah bagian dari total kepemilikan. Mengejar harga awal termurah sering berujung biaya besar di kemudian hari.\n\nMinta rincian per tahap dan asumsi. Estimasi yang baik menjelaskan asumsi yang dipakai sehingga Anda tahu kondisi apa yang bisa mengubah angka tersebut. Dengan begitu, anggaran bisa direncanakan secara realistis.",
            ],
            [
                'Menerapkan Kontrol Akses Berbasis Peran tanpa Membuat Pusing Pengguna',
                'Keamanan',
                'img/work-3.jpg',
                '15 Agustus 2026',
                'Hak akses yang terlalu banyak berbahaya, terlalu sedikit menghambat. Cara menemukan keseimbangannya.',
                "Kontrol akses berbasis peran sering berakhir menjadi daftar perizinan yang panjang dan sulit dipahami. Akibatnya, admin memberi akses berlebihan agar pekerjaan cepat selesai.\n\nMulai dari peran nyata di organisasi. Alih-alih mendefinisikan puluhan izin individual, petakan peran yang benar-benar ada seperti staf operasional, supervisor, dan manajer. Setiap peran mendapat kumpulan izin yang jelas.\n\nPisahkan izin melihat dari mengubah. Kesalahan umum adalah menggabungkan keduanya. Memisahkannya memungkinkan lebih banyak orang mengakses informasi tanpa memperbesar risiko perubahan data.\n\nTinjau secara berkala. Saat orang berpindah peran, akses lama sering tertinggal. Lakukan peninjauan rutin dan hapus akses yang sudah tidak relevan. Ini sekali gus menjadi bagian dari kesiapan audit.",
            ],
            [
                'Menyiapkan Tim Internal untuk Mengelola Sistem Baru',
                'Operasional',
                'img/about.jpg',
                '12 Agustus 2026',
                'Pelatihan satu kali tidak pernah cukup. Bagaimana membuat tim internal benar-benar menguasai sistem baru.',
                "Implementasi sistem baru sering disertai satu sesi pelatihan di akhir proyek. Sesinya ramai, tetapi sebulan kemudian pertanyaan yang sama muncul kembali.\n\nLibatkan tim sejak awal. Pengguna yang terlibat dalam proses perancangan akan memahami alasan di balik setiap keputusan. Mereka juga menjadi orang pertama yang dimintai tolong oleh rekan sekerja.\n\nSediakan bahan belajar yang bisa diakses kapan saja. Rekaman singkat per alur kerja lebih berguna daripada satu video panjang yang sulit dicari ulang. Sertakan panduan tertulis yang ringkas dan bisa dicetak.\n\nBangun jaringan pendukung internal. Tunjuk beberapa 'super user' di tiap divisi yang bisa menjawab pertanyaan sehari-hari. Dengan begitu, tim IT tidak kewalahan dan pengetahuan tetap tinggal di dalam organisasi.",
            ],
            [
                'Audit Trail: Fitur Kecil yang Menyelamatkan Banyak Perusahaan',
                'Teknologi',
                'img/work-2.jpg',
                '9 Agustus 2026',
                'Saat terjadi selisih data, pertanyaannya selalu sama: siapa mengubah apa dan kapan. Di sinilah audit trail berperan.',
                "Audit trail sering dianggap fitur tambahan yang hanya dibutuhkan perusahaan besar. Padahal sistem sekecil apa pun akan menghadapi pertanyaan tentang perubahan data.\n\nCatat siapa, apa, dan kapan. Tiga informasi ini sudah menjawab sebagian besar kebutuhan investigasi. Untuk data sensitif, simpan juga nilai sebelum dan sesudah perubahan.\n\nJangan hanya mencatat perubahan yang berhasil. Percobaan akses yang ditolak juga berharga untuk mendeteksi pola mencurigakan atau kesalahan konfigurasi izin.\n\nPastikan catatan tidak bisa diubah sembarangan. Audit trail yang dapat disunting oleh pengguna biasa kehilangan nilainya. Batasi akses hanya untuk peran tertentu dan simpan dalam jangka waktu yang sesuai kebijakan.",
            ],
            [
                'Integrasi Pembayaran: Hal yang Perlu Diperhatikan Sebelum Memilih',
                'Panduan',
                'img/work-4.jpg',
                '6 Agustus 2026',
                'Biaya transaksi bukan satu-satunya pertimbangan. Perhatikan keandalan, dukungan, dan kemudahan rekonsiliasi.',
                "Memilih penyedia pembayaran sering diputuskan hanya berdasarkan biaya transaksi termurah. Padahal dampaknya terasa jauh lebih luas di operasional harian.\n\nPerhatikan keandalan dan waktu aktif. Satu gangguan pada jam sibuk bisa kehilangan banyak transaksi. Cari tahu riwayat uptime dan bagaimana penyedia menangani insiden.\n\nNilai kemudahan integrasi. Dokumentasi yang jelas, lingkungan uji yang lengkap, dan webhook yang andall mempercepat pembangunan sekaligus mengurangi bug di produksi.\n\nPikirkan rekonsiliasi keuangan. Laporan yang mudah dicocokkan, biaya yang transparan, dan kemampuan menangani refund serta pembatalan akan menghemat waktu tim keuangan setiap bulan.",
            ],
            [
                'Monitoring Aplikasi: Sinyal Awal Sebelum Pengguna Mengeluh',
                'Teknologi',
                'img/hero.jpg',
                '3 Agustus 2026',
                'Cara mengetahui masalah sebelum pengguna melapor, dan metrik apa yang perlu dipantau.',
                "Masalah paling mahal adalah yang diketahui dari pengaduan pengguna. Dengan monitoring yang tepat, tim Anda bisa tahu lebih dulu dan bertindak sebelum dampaknya melebar.\n\nPantau dari sisi pengguna, bukan hanya server. Server bisa terlihat sehat sementara aplikasi lambat karena kueri basis data yang berat atau integrasi pihak ketiga yang melambat.\n\nTetapkan ambang peringatan yang masuk akal. Terlalu banyak peringatan membuat tim mengabaikannya. Fokus pada metrik yang langsung berdampak pada pengalaman pengguna seperti waktu muat dan tingkat kesalahan.\n\nHubungkan monitoring dengan proses perbaikan. Peringatan yang tidak memiliki prosedur tindak lanjut hanya menjadi kebisingan. Pastikan ada jalur jelas dari deteksi hingga perbaikan.",
            ],
            [
                'Panduan Pengadaan Perangkat Lunak untuk Perusahaan Menengah',
                'Operasional',
                'img/work-1.jpg',
                '31 Juli 2026',
                'Membeli lisensi perangkat lunak punya pertimbangan tersendiri. Hindari jebakan biaya tersembunyi.',
                "Pengadaan perangkat lunak sering dipandang sebagai urusan administratif belaka. Padahal keputusan di tahap ini menentukan biaya dan kenyamanan kerja bertahun-tahun ke depan.\n\nHitung total kepemilikan, bukan hanya harga lisensi. Biaya pelatihan, integrasi, dan pemeliharaan sering lebih besar dari harga awal yang tertera.\n\nPerhatikan model lisensi. Berlangganan memberi fleksibilitas dan pembaruan berkelanjutan, sementara lisensi perpetual unggul untuk penggunaan jangka panjang yang stabil. Pilih sesuai pola pemakaian.\n\nUji sebelum membeli dalam skala besar. Jalankan percobaan terbatas dengan tim kecil untuk menilai kemudahan pemakaian dan kualitas dukungan. Keputusan besar sebaiknya berdasarkan pengalaman nyata.",
            ],
            [
                'Membangun Budaya Kode yang Bersih di Tim Kecil',
                'Teknologi',
                'img/work-3.jpg',
                '28 Juli 2026',
                'Tim kecil justru paling diuntungkan oleh kode yang rapi. Mulai dari kebiasaan sederhana yang konsisten.',
                "Di tim kecil, setiap orang mengerjakan banyak hal. Kode yang berantakan cepat menjadi beban karena tidak ada yang punya waktu khusus untuk merapikannya.\n\nSepakati standar dengan alat, bukan debat. Gunakan pemformat kode otomatis dan pemeriksa gaya di pipeline. Dengan begitu, diskusi beralih dari selera pribadi ke kualitas logika.\n\nJaga perubahan tetap kecil. Pull request yang ringkas lebih mudah ditinjau dan lebih aman digabungkan. Perubahan besar sekali jadi menyulitkan peninjauan sekaligus menunda umpan balik.\n\nTulis uji untuk logika yang penting. Tidak perlu mengejar cakupan seratus persen. Fokuskan pengujian pada bagian yang paling sering berubah dan paling mahal jika salah. Itu sudah memberi rasa aman yang signifikan.",
            ],
            [
                'Menyusun SLA Pemeliharaan yang Adil untuk Kedua Belah Pihak',
                'Operasional',
                'img/about.jpg',
                '25 Juli 2026',
                'SLA yang baik melindungi klien sekaligus memberi ruang kerja yang sehat bagi penyedia jasa.',
                "Perjanjian tingkat layanan (SLA) sering ditulis sepihak dan berakhir sulit dijalankan. Akibatnya, kedua belah pihak sama-sama frustrasi.\n\nBedakan tingkat keparahan masalah. Gangguan yang membuat sistem tidak bisa dipakai jelas berbeda dari permintaan perbaikan tampilan. Waktu respons dan waktu penyelesaian harus mencerminkan perbedaan tersebut.\n\nDefinisikan jam layanan dengan jelas. Apakah dukungan mencakup akhir pekan dan hari libur? Jika ya, kesepakatan biaya harus mengikutinya. Kejelasan ini mencegah salah paham di saat mendesak.\n\nCantumkan cara melaporkan dan mengevaluasi. Satu kanal pelaporan resmi, riwayat tiket yang tercatat, dan tinjauan berkala membuat kedua pihak bisa menilai kinerja secara objektif dan memperbaiki hal yang perlu.",
            ],
            [
                'Mengelola Utang Teknis Tanpa Menghentikan Pengembangan',
                'Teknologi',
                'img/work-4.jpg',
                '22 Juli 2026',
                'Utang teknis tidak harus dilunasi sekaligus. Strategi menyicilnya sambil terus mengirim nilai ke pengguna.',
                "Setiap sistem yang berkembang cepat akan menumpuk utang teknis. Menolak mengakuinya sama saja menunda masalah yang akan semakin mahal.\n\nPetakan dan prioritaskan. Tidak semua utang teknis sama mendesak. Beri peringkat berdasarkan risiko dan dampaknya terhadap kecepatan kerja sehari-hari.\n\nSisihkan porsi tetap tiap sprint. Misalnya seperlima kapasitas tim untuk perbaikan. Dengan begitu, pelunasan berjalan konsisten tanpa menghentikan penambahan fitur baru.\n\nKaitkan perbaikan dengan pekerjaan yang sedang berjalan. Saat menyentuh modul tertentu untuk fitur baru, sekaligus rapikan bagian yang paling bermasalah di sekitar area itu. Pendekatan ini alami dan efisien.",
            ],
        ];

        foreach ($articles as [$title, $category, $image, $date, $excerpt, $body]) {
            Article::query()->updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'category' => $category,
                    'blog_category_id' => $categoryIds[$category] ?? null,
                    'featured_image_path' => $image,
                    'excerpt' => $excerpt,
                    'body' => $body,
                    'author_id' => null,
                    'status' => PublishStatus::Published,
                    'published_at' => Carbon::parse(str_replace(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'], ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'], $date)),

                ],
            );
        }
    }
}
