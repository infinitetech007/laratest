<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;

    public static function getCategory($category) {
        $data = Category::whereIn('id',$category)->pluck('title')->toArray();
        return $data;
    }
}