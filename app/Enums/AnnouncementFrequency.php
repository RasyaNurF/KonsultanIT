<?php

namespace App\Enums;

enum AnnouncementFrequency: string
{
    case Always = 'always';
    case Session = 'session';
    case Days = 'days';

    public function label(): string
    {
        return match ($this) {
            self::Always => 'Setiap kunjungan (per tab)',
            self::Session => 'Sekali per sesi browser',
            self::Days => 'Sekali per beberapa hari',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Always => 'Muncul saat membuka situs di tab baru; tidak berulang saat berpindah halaman.',
            self::Session => 'Muncul sekali selama browser masih terbuka (lintas tab).',
            self::Days => 'Muncul sekali, lalu disimpan di cookie selama jumlah hari yang ditentukan.',
        };
    }
}
