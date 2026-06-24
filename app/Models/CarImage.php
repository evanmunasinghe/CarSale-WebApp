<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CarImage extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'image_path',
        'position',
    ];

    public function car() : BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function getUrlAttribute(): string
    {
        if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }

        return asset('storage/' . $this->image_path);
    }
}
