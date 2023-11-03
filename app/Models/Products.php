<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $table = 'products';
    protected $fillable = ['product_id', 'name', 'category', 'description', 'new_price', 'old_price'];

    protected $casts = [
        'product_id' => 'string',
    ];
    
}
