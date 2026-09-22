<?php

namespace App\Enums;

enum AdminRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Editor = 'editor';
    case User = 'user';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::Editor => 'Editor',
            self::User => 'User',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Akses penuh termasuk pengguna dan pengaturan.',
            self::Admin => 'Akses operasional: CRM, proyek, konten.',
            self::Editor => 'Akses konten: artikel, portfolio, media.',
            self::User => 'Anggota terdaftar tanpa akses panel admin.',
        };
    }

    /**
     * @return list<string>
     */
    public function abilities(): array
    {
        return match ($this) {
            self::SuperAdmin => ['*'],
            self::Admin => [
                'dashboard.view',
                'lead.view', 'lead.manage',
                'message.view', 'message.manage',
                'client.view', 'client.manage',
                'project.view', 'project.manage',
                'portfolio.view', 'portfolio.manage',
                'service.view', 'service.manage',
                'solution-category.view', 'solution-category.manage',
                'solution.view', 'solution.manage',
                'resource.view', 'resource.manage',
                'industry.view', 'industry.manage',
                'article.view', 'article.manage',
                'blog-category.view', 'blog-category.manage',
                'testimonial.view', 'testimonial.manage',
                'team.view', 'team.manage',
                'career.view', 'career.manage',
                'company.view', 'company.manage',
                'hero.view', 'hero.manage',
                'media.view', 'media.manage',
                'seo.view', 'seo.manage',
                'announcement.view', 'announcement.manage',
                'settings.view',
                'profile.manage',
            ],
            self::Editor => [
                'dashboard.view',
                'portfolio.view', 'portfolio.manage',
                'article.view', 'article.manage',
                'blog-category.view',
                'testimonial.view', 'testimonial.manage',
                'team.view', 'team.manage',
                'career.view',
                'company.view',
                'hero.view', 'hero.manage',
                'media.view', 'media.manage',
                'service.view',
                'solution-category.view',
                'solution.view',
                'resource.view',
                'industry.view',
                'seo.view', 'seo.manage',
                'announcement.view', 'announcement.manage',
                'profile.manage',
            ],
            self::User => [],
        };
    }

    public function can(string $ability): bool
    {
        $abilities = $this->abilities();

        return in_array('*', $abilities, true) || in_array($ability, $abilities, true);
    }
}
