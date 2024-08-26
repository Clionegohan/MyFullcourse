<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
        'category_id',
        'image_url',
        'address',
        'latitude',
        'longitude',
    ];
    public function scopeOrderByCategory($query)
    {
        return $query->orderBy('category_id', 'asc');
    }
    public function getPaginateByLimit(int $limit_count = 10)
    {
        return $this::with('category')->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function isLikedByUser($user)
{
    return $this->likes()->where('user_id', $user->id)->exists();
}
}