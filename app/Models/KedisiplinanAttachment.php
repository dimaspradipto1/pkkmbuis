<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KedisiplinanAttachment extends Model
{
    protected $fillable = [
        'category',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::deleting(function ($attachment) {
            if ($attachment->file_path) {
                // Delete from Laravel public disk
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($attachment->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->file_path);
                }

                // Extra safety check for direct file path in storage/app/public/
                $storageFile = storage_path('app/public/' . $attachment->file_path);
                if (file_exists($storageFile)) {
                    @unlink($storageFile);
                }
            }
        });
    }
}
