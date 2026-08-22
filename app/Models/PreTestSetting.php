<?php

namespace App\Models;

use Illuminate\Support\Facades\File;

class PreTestSetting
{
    protected static string $filePath = 'pretest_settings.json';

    public static function getFilePath(): string
    {
        return storage_path('app/' . static::$filePath);
    }

    public static function getAllStatuses(): array
    {
        $file = static::getFilePath();
        if (File::exists($file)) {
            $data = json_decode(File::get($file), true);
            if (is_array($data)) {
                $result = [];
                for ($i = 1; $i <= 4; $i++) {
                    $result[$i] = isset($data[$i]) ? (bool) $data[$i] : true;
                }
                return $result;
            }
        }

        // Default: all pretests 1-4 active
        return [
            1 => true,
            2 => true,
            3 => true,
            4 => true,
        ];
    }

    public static function isActive(int $modul): bool
    {
        $statuses = static::getAllStatuses();
        return (bool) ($statuses[$modul] ?? true);
    }

    public static function toggle(int $modul): bool
    {
        $statuses = static::getAllStatuses();
        $statuses[$modul] = !($statuses[$modul] ?? true);

        $file = static::getFilePath();
        $dir = dirname($file);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        File::put($file, json_encode($statuses, JSON_PRETTY_PRINT));

        return (bool) $statuses[$modul];
    }

    public static function setAll(bool $active): void
    {
        $statuses = [
            1 => $active,
            2 => $active,
            3 => $active,
            4 => $active,
        ];

        $file = static::getFilePath();
        $dir = dirname($file);
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        File::put($file, json_encode($statuses, JSON_PRETTY_PRINT));
    }
}
