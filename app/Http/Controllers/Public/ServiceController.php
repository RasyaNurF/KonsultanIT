<?php

namespace App\Http\Controllers\Public;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\SolutionCategory;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->where('status', PublishStatus::Published->value)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $industries = Industry::query()
            ->where('status', PublishStatus::Published->value)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $solutionCategories = SolutionCategory::query()
            ->where('status', PublishStatus::Published->value)
            ->whereHas('solutions', fn ($query) => $query->where('status', PublishStatus::Published->value))
            ->with(['solutions' => fn ($query) => $query->where('status', PublishStatus::Published->value)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $educationCaseStudy = Portfolio::query()
            ->where('status', PublishStatus::Published->value)
            ->where('category', 'Pendidikan')
            ->with('client')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->first();

        $industryCaseStudies = Portfolio::query()
            ->where('status', PublishStatus::Published->value)
            ->where(function ($query) {
                $query->whereNull('category')->orWhere('category', '!=', 'Pendidikan');
            })
            ->with('client')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        $segments = [
            [
                'id' => 'perguruan-tinggi',
                'title' => 'Perguruan Tinggi',
                'summary' => 'Sistem yang mengikuti alur akademik, administrasi, dan pelaporan kampus.',
                'description' => 'Satukan proses dan data kampus melalui sistem yang dirancang mengikuti cara kerja institusi Anda.',
                'challenges' => ['Data akademik tersebar di banyak aplikasi dan spreadsheet.', 'Pelaporan dan rekap rutin menyita waktu tim.', 'Sistem lama sulit dikembangkan saat kebutuhan kampus berubah.'],
                'solutions' => ['Sistem informasi akademik dan administrasi kampus.', 'Integrasi antarsistem dan otomasi pertukaran data.', 'Dashboard dan laporan untuk kebutuhan operasional.'],
                'benefits' => ['Alur kerja akademik dan administrasi lebih terhubung.', 'Data lebih mudah ditemukan dan dipantau.', 'Sistem dapat dikembangkan bersama kebutuhan kampus.'],
                'case_studies' => $educationCaseStudy ? collect([$educationCaseStudy]) : collect(),
            ],
            [
                'id' => 'sekolah',
                'title' => 'Sekolah',
                'summary' => 'Dukungan digital untuk penerimaan siswa, administrasi, dan komunikasi sekolah.',
                'description' => 'Buat proses sekolah lebih mudah diakses oleh pengelola, guru, siswa, dan orang tua melalui aplikasi yang sesuai kebutuhan.',
                'challenges' => ['Pendaftaran dan administrasi siswa masih bergantung pada proses manual.', 'Informasi sekolah tersimpan di beberapa tempat.', 'Orang tua dan staf kesulitan mengikuti status proses secara jelas.'],
                'solutions' => ['Sistem penerimaan siswa dan administrasi sekolah.', 'Portal informasi untuk siswa dan orang tua.', 'Integrasi data dan laporan untuk pengelola sekolah.'],
                'benefits' => ['Informasi sekolah tersedia melalui kanal yang lebih teratur.', 'Proses administrasi lebih mudah ditelusuri.', 'Pengalaman digital dapat disesuaikan dengan peran pengguna.'],
                'case_studies' => collect(),
            ],
            [
                'id' => 'industri',
                'title' => 'Industri & Bisnis',
                'summary' => 'Aplikasi dan integrasi untuk proses kerja perusahaan dan organisasi.',
                'description' => 'Hubungkan proses operasional yang tersebar dengan sistem yang dibangun sesuai alur kerja dan kebutuhan bisnis.',
                'challenges' => ['Proses antarbagian masih mengandalkan input berulang.', 'Laporan operasional perlu dirangkum dari banyak sumber.', 'Aplikasi yang ada belum terhubung dengan kebutuhan tim.'],
                'solutions' => ['Sistem informasi internal untuk proses bisnis.', 'Portal, dashboard, dan aplikasi web atau mobile.', 'Integrasi API dengan aplikasi dan layanan yang sudah digunakan.'],
                'benefits' => ['Aktivitas rutin dapat berjalan dalam alur yang lebih jelas.', 'Informasi operasional lebih mudah diakses tim terkait.', 'Pengembangan sistem mengikuti prioritas bisnis.'],
                'case_studies' => $industryCaseStudies,
            ],
        ];

        return view('solutions.segments', [
            'services' => $services,
            'industries' => $industries,
            'segments' => $segments,
            'solutionCategories' => $solutionCategories,
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->status === PublishStatus::Published, 404);

        $service->load(['industries' => fn ($query) => $query
            ->where('status', PublishStatus::Published->value)
            ->orderBy('sort_order')
            ->orderBy('name')]);

        $related = Service::query()
            ->where('status', PublishStatus::Published->value)
            ->whereKeyNot($service->id)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('services.show', [
            'service' => $service,
            'related' => $related,
        ]);
    }
}
