<?php

namespace Database\Seeders;

use App\Enums\PublishStatus;
use App\Models\Article;
use App\Models\BlogCategory;
use App\Models\Career;
use App\Models\CompanyProfile;
use App\Models\Hero;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CompanyContentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        CompanyProfile::query()->updateOrCreate(
            ['name' => 'KIT Konsultan IT'],
            [
                'tagline' => 'Mitra teknologi informasi untuk perusahaan Indonesia.',
                'description' => 'Perusahaan jasa teknologi informasi di Jakarta. Membangun aplikasi, sistem, dan infrastruktur TI bersama tim Anda sejak 2015.',
                'about' => 'KIT Konsultan IT berdiri di Jakarta pada 2015. Kami melayani perusahaan menengah hingga enterprise — sebagai pelaksana proyek sekaligus mitra pemeliharaan jangka panjang.',
                'vision' => 'Menjadi mitra teknologi paling dipercaya bagi perusahaan Indonesia.',
                'mission' => "Menyediakan solusi digital yang rapi dan terukur.\nBekerja transparan dengan kontrak tertulis.\nMenyerahkan kode sumber dan pengetahuan sepenuhnya kepada klien.",
                'values' => [
                    'Kontrak kerja tertulis: lingkup, jadwal, biaya, dan garansi tercantum jelas.',
                    'Tim tetap, bukan lepas: engineer yang mengerjakan proyek Anda adalah karyawan kami.',
                    'Serah terima penuh: kode sumber, dokumentasi, kredensial, dan pelatihan operator.',
                ],
                'email' => 'halo@nusakode.id',
                'phone' => '+62 21 5000 1234',
                'address' => 'Jakarta Selatan, Indonesia',
                'founded_year' => 2015,
                'is_active' => true,
            ],
        );

        Hero::query()->updateOrCreate(
            ['title' => 'Teknologi yang merapikan cara bisnis Anda bekerja'],
            [
                'highlight' => 'bekerja',
                'description' => 'Aplikasi, sistem, dan infrastruktur TI untuk perusahaan — lingkup tertulis, biaya tetap, serah terima penuh.',
                'cta_label' => 'Diskusikan Kebutuhan Anda',
                'cta_url' => '/kontak',
                'secondary_cta_label' => 'Lihat hasil kerja kami',
                'secondary_cta_url' => '/portfolio',
                'image_path' => 'img/hero.jpg',
                'sort_order' => 1,
                'status' => PublishStatus::Published,
            ],
        );

        $categories = ['Panduan', 'Keamanan', 'Operasional'];
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

        // Tautkan artikel lama ke kategori baru berdasarkan kolom string.
        foreach (BlogCategory::all() as $category) {
            Article::query()
                ->where('category', $category->name)
                ->whereNull('blog_category_id')
                ->update(['blog_category_id' => $category->id]);
        }

        $teams = [
            ['Andi Pratama', 'Chief Technology Officer', 'Memimpin arsitektur sistem dan standar engineering KIT Konsultan IT sejak 2016.'],
            ['Sinta Maharani', 'Head of Product Design', 'Merancang pengalaman pengguna yang sederhana untuk sistem perusahaan yang kompleks.'],
            ['Rizky Firmansyah', 'Lead Backend Engineer', 'Spesialis Laravel, integrasi API, dan performa basis data skala menengah.'],
            ['Dewi Anggraini', 'Project Manager', 'Menjaga lingkup, jadwal, dan komunikasi proyek tetap transparan.'],
        ];

        foreach ($teams as $index => [$name, $position, $bio]) {
            Team::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'position' => $position,
                    'bio' => $bio,
                    'sort_order' => $index + 1,
                    'status' => PublishStatus::Published,
                ],
            );
        }

        $careers = [
            [
                'Backend Engineer (Laravel)',
                'Engineering',
                'Jakarta Selatan · Hybrid',
                'Full-time',
                'Membangun dan memelihara aplikasi web perusahaan dengan Laravel dan MySQL.',
                "Pengalaman 2+ tahun dengan Laravel.\nMemahami REST API dan antrean.\nTerbiasa menulis pengujian.",
                "Mengembangkan modul sesuai spesifikasi.\nMenulis dokumentasi teknis.\nBerkolaborasi dengan tim frontend dan PM.",
            ],
            [
                'UI/UX Designer',
                'Design',
                'Jakarta Selatan · Hybrid',
                'Full-time',
                'Merancang antarmuka sistem internal yang mudah digunakan staf non-teknis.',
                "Portofolio desain aplikasi web/dashboard.\nMemahami design system.\nMampu membuat prototipe interaktif.",
                "Menyusun wireframe dan prototipe.\nMelakukan uji kegunaan sederhana.\nMenjaga konsistensi komponen desain.",
            ],
        ];

        foreach ($careers as $index => [$title, $department, $location, $type, $description, $requirements, $responsibilities]) {
            Career::query()->updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'department' => $department,
                    'location' => $location,
                    'employment_type' => $type,
                    'description' => $description,
                    'requirements' => $requirements,
                    'responsibilities' => $responsibilities,
                    'deadline' => Carbon::now()->addMonths(2)->toDateString(),
                    'sort_order' => $index + 1,
                    'status' => PublishStatus::Published,
                ],
            );
        }
    }
}
