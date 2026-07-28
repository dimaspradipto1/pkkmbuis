<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikatSetting extends Model
{
    protected $guarded = ['id'];

    /**
     * Singleton settings row (always id 1). Used both to read display fields
     * and, under lockForUpdate(), to atomically hand out the next sequential
     * certificate number.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
