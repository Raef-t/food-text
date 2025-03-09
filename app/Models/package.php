<?php

namespace App\Models;

use App\Scopes\StoreScope;
use App\Scopes\ZoneScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;


class package extends Model
{

    protected $fillable = [
        'name',
        'price',
        'km',
        'status',
    ];
public function user():HasMany
{
    return $this->hasMany(User::class);
}
public function Subscriptions():HasMany{
    return $this->hasMany(Subscriptions::class);
}

    public function scopeActive($query): mixed
    {
        return $query->where('status', 1);
    }



    /**
     * @return void
     */
    protected static function booted(): void
    {

    }
}
