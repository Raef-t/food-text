<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class order_groups extends Model
{
    use HasFactory;
    protected $table = 'order_groups';
    protected $fillable = [
        'order_group_id',
        'main_order_id',
    ];
    public function mainOrder()
    {
        return $this->belongsTo(Order::class, 'main_order_id',"id");

    }
    public function OrderGroupId()
    {
        return $this->belongsTo(Order::class, 'order_group_id',"id");
    }
}
