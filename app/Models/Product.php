<?php

namespace App\Models;

use App\Models\User;
use Spatie\Image\Enums\Fit;
use App\Enums\ProductStatusEnum;
use App\Enums\RolesEnum;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Filament\Resources\ProductResource\Pages\ProductVariations;



class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    // protected $casts = [
    //     'variations' => 'array'
    // ];


    public function registerMediaConversions(?Media $media = null): void
    {
        // $this
        //     ->addMediaConversion('preview')
        //     ->fit(Fit::Contain, 100, 100)
        //     ;
        $this
            ->addMediaConversion('tumb')
            ->width(100);

        $this
            ->addMediaConversion('small')
            // ->fit(Fit::Contain, 300, 300)

            ->width(480);

        $this
            ->addMediaConversion('large')
            // ->fit(Fit::Contain, 300, 300)

            ->width(1200)
        ;
    }


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    public function variationTypes(): HasMany
    {
        return $this->hasMany(VariationType::class);
    }


    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }

    function scopeForVendor(Builder $query)
    {
        /** @var User $user */
        $user = Auth::user();
        return $query->where('created_by', $user->id);

    }
    function scopePublished(Builder $query)
    {
        // /** @var User $user */
        // $user = Auth::user();
        return $query->where('status', ProductStatusEnum::Published);

    }
}
