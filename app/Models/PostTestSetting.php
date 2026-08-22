<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTestSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'modul',
        'is_active',
    ];

    protected $casts = [
        'modul' => 'integer',
        'is_active' => 'boolean',
    ];

    public static function isActive(int $modul): bool
    {
        $setting = static::firstOrCreate(
            ['modul' => $modul],
            ['is_active' => false]
        );

        return (bool) $setting->is_active;
    }

    public static function toggle(int $modul): bool
    {
        $setting = static::firstOrCreate(
            ['modul' => $modul],
            ['is_active' => false]
        );

        $setting->is_active = !$setting->is_active;
        $setting->save();

        return (bool) $setting->is_active;
    }

    public static function setAll(bool $active): void
    {
        for ($i = 1; $i <= 4; $i++) {
            static::updateOrCreate(
                ['modul' => $i],
                ['is_active' => $active]
            );
        }
    }

    public static function getActiveModules(): array
    {
        $active = [];
        for ($i = 1; $i <= 4; $i++) {
            if (\App\Models\ModulSetting::isActive($i) && static::isActive($i)) {
                $active[] = $i;
            }
        }
        return $active;
    }

    public static function getActiveCount(): int
    {
        return count(static::getActiveModules());
    }
}
