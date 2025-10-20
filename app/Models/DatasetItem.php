<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatasetItem extends Model
{
    protected $fillable = [
        'dataset_id','type','text','category','difficulty','ipa','metadata','audio_path','tts_voice'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }
}
