<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',    // ID of the associated order
        'product_id',  // ID of the associated product
        'name',        // Name of the product
        'quantity',    // Quantity of the product in the order
        'image',       // Image of the product
        'price',       // Price of the product
    ];

    public function orders()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
