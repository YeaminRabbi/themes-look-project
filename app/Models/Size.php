<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = ['name', 'slug'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
