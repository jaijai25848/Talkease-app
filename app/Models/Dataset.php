<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Dataset extends Model
{
    protected $fillable = [
        'name','slug','language','type','level','is_public','item_count','description'
    ];

    protected static function booted()
    {
        static::creating(function (Dataset $d) {
            if (empty($d->slug)) $d->slug = Str::slug($d->name);
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(DatasetItem::class);
    }
}
