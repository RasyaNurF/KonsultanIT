<?php

namespace App\Enums;

enum AnnouncementPlacement: string
{
    case Bar = 'bar';
    case Popup = 'popup';
    case Cookie = 'cookie';

    public function label(): string
    {
        return match ($this) {
            self::Bar => 'Bilah Atas',
            self::Popup => 'Pop-up',
            self::Cookie => 'Persetujuan Cookie',
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::Bar => 'brand',
            self::Popup => 'violet',
            self::Cookie => 'sky',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Bar => 'Tampil di atas navbar pada seluruh halaman.',
            self::Popup => 'Modal yang muncul saat pengunjung membuka website.',
            self::Cookie => 'Banner persetujuan penggunaan cookie.',
        };
    }
}
