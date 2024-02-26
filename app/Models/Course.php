<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'price',
        'package_id'
    ];

    public function package(): BelongsTo{
        return $this->belongsTo(Package::class);
    }

    public function registerMediaCollections(): void{
        $this->addMediaCollection('course_images');
    }

    public function purchases(){
        return $this->morphMany(Purchase::class, 'purchasable');
    }
}
