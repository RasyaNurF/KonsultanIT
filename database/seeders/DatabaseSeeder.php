<?php

namespace Database\Seeders;

use App\Enums\AdminRole;
use App\Models\SeoMeta;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(LandingContentSeeder::class);
        $this->call(CompanyContentSeeder::class);
        $this->call(SolutionContentSeeder::class);
        $this->call(ResourceContentSeeder::class);
        $this->call(ArticleContentSeeder::class);
        $this->call(AnnouncementSeeder::class);

        User::query()->updateOrCreate(
            ['email' => 'admin@nusakode.id'],
            [
                'name' => 'Admin Nusakode',
                'password' => Hash::make('password'),
                'role' => AdminRole::SuperAdmin,
                'job_title' => 'Administrator',
                'is_active' => true,
            ],
        );

        SiteSetting::putMany([
            'company_name' => 'PT Nusakode Teknologi',
            'tagline' => 'Mitra teknologi informasi untuk perusahaan Indonesia.',
            'email' => 'halo@nusakode.id',
            'phone' => '+62 21 5000 1234',
            'whatsapp' => '622150001234',
            'address' => "Jakarta Selatan, DKI Jakarta\nIndonesia",
            'facebook' => 'https://facebook.com/nusakode',
            'instagram' => 'https://instagram.com/nusakode',
            'linkedin' => 'https://linkedin.com/company/nusakode',
            'copyright' => 'PT Nusakode Teknologi. Seluruh hak cipta dilindungi.',
            'maintenance_mode' => '0',
        ]);

        foreach (SeoMeta::defaultPaths() as $path) {
            SeoMeta::query()->firstOrCreate(
                ['path' => $path],
                [
                    'label' => $path === '/' ? 'Beranda' : 'Kontak',
                    'is_indexable' => true,
                ],
            );
        }
    }
}
