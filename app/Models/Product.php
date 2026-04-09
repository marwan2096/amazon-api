<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
     use HasFactory, SoftDeletes;
     protected $appends = ['image_url'];
    protected $fillable = [
    'name', 'slug', 'description', 'price', 'stock', 'sku', 'is_active','image'
];

public function inStock()
{
    return $this->stock > 0;
}

public function categories()
{
    return $this->belongsToMany(Category::class);
}


public static function booted()
{
    static::addGlobalScope('active',function ($query) {
        $query->where('is_active',true);
    });
}
// local scope
public function ScopePrice($query,$min,$max)
{
      $query->whereBetween('price',[$min,$max]);
}
// Accessor - reading
// public function getFormattedNameAttribute()
// {
//     return ucfirst($this->name);
// }
// Mutator - writing
public function setNameAttribute($value)
{
    $this->attributes['name'] = ucfirst($value);
}

public function getImageUrlAttribute()
{
     return $this->image ? asset('storage/' . $this->image) : null;
}
}
