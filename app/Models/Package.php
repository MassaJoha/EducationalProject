<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Package extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'price'
    ];

    public function courses(): HasMany{
        return $this->hasMany(Course::class);
    }

    public function registerMediaCollections(): void{
        $this->addMediaCollection('package_images');
    }

    public function purchases(){
        return $this->morphMany(Purchase::class, 'purchasable');
    }
}