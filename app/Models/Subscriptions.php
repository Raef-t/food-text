<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    use HasFactory;
    protected $fillable = [
        'package_id',
        'user_id',
        'status',
        'price',
        'payment_method',
    ];
    protected $table = 'subscriptions';
    public function package(){
        return $this->belongsTo(package::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
