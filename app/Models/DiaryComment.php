<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiaryComment extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'diary_id',
        'comment'
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
