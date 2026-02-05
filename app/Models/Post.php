<?php

namespace App\Models;

use App\Enum\ImageType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Enum;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
    ];
    public function scopeFilter($query,$keywords)
    {
        return $post = Post::select('title', 'body')
            ->where(function ($query) use ($keywords) {
                return $query->where('title', $keywords)->where('body', 'LIKE', '%%');
            })
            ->where('updated_at', '>', '2025-01-01');
    }

    // protected static function booted():void
    // {
    //     static::addGlobalScope(new activeScope);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }
    public function avatarImage()
    {
        return $this->morphOne(Image::class,'imageable')->where('type', ImageType::Avatar->value);
    }
    public function coverImage()
    {
        return $this->morphOne(Image::class,'imageable')->where('type', ImageType::Cover->value);
    }
    public function regularImage()
    {
        return $this->morphMany(Image::class,'imageable')->where('type', ImageType::Regular->value);
    }
}
