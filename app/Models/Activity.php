<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['name', 'user_id', 'category_id', 'spent_time', 'created_date'];
    use HasFactory;

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
