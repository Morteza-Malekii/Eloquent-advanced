<?php

namespace App\Models;

use App\Models\Scopes\activeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
