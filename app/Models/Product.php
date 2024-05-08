<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'color_id',
        'size_id',
        'unit',
        'unit_value',
        'selling_price',
        'purchase_price',
        'discount',
        'tax',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function getPrice()
    {
        $attributes = collect($this->attributes);
        $avg = $attributes->sum('selling_price') / $attributes->count();
        return $avg ?? 0;
    }

}
