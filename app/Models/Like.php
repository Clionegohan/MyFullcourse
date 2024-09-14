<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public $timestamps = false;
    use HasFactory;
    
    public $incrementing = false;
    protected $primarKey = ['user_id', 'post_id'];
    
    protected $fillable = [
        'user_id',
        'post_id',
        ];
        
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
