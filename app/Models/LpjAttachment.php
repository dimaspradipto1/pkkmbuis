<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LpjAttachment extends Model
{
    protected $table = 'lpj';

    protected $fillable = [
        'link',
        'file',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::deleting(function ($attachment) {
            if ($attachment->file) {
                // Delete from Laravel public disk
                if (Storage::disk('public')->exists($attachment->file)) {
                    Storage::disk('public')->delete($attachment->file);
                }

                // Extra safety check for direct file path in storage/app/public/
                $storageFile = storage_path('app/public/' . $attachment->file);
                if (file_exists($storageFile)) {
                    @unlink($storageFile);
                }
            }
        });
    }
}
