<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiaryLike extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $incrementing = false;
    
    // protected $primarKey = ['user_id', 'diary_id'];
    
    protected $fillable = [
        'user_id',
        'diary_id',
        ];
        
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function diary()
    {
        return $this->belongsTo(Diary::class);
    }
    
}
