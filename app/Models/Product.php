<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
        use SoftDeletes;

    protected $fillable = [
        'name','category_id','color_id','size_id','qty','price','image'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function color() {
        return $this->belongsTo(Color::class);
    }

    public function size() {
        return $this->belongsTo(Size::class);
    }
}
