<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiaryImage extends Model
{
    public $timestamps = false;
    
    use HasFactory;
    protected $fillable = [
        'image_url',
        'diary_id',
        ];
        
        public function diary()
        {
            return $this->blongsTo(Diary::class);
        }
}
