<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Judgement extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'has_starter',
        'has_soup',
        'has_fish',
        'has_meat',
        'has_main',
        'has_salad',
        'has_dessert',
        'has_drink',
        ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
