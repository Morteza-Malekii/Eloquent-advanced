<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use Faker\Factory;

use function PHPUnit\Framework\isNull;

Route::get('/create', function () {
    $post = Factory::create(5);
    return $post;
});
Route::get('/all', function () {
    $posts = Post::all();
    dd($posts->pluck('title'));
});
Route::get('/find/{post}', function ($post) {
    $post = Post::query()->findOrFail($post);
    dd($post->title);
});
Route::get('/', function () {
    return view('welcome');
});


Route::get('/where',function(){
    $keywords = request()->input('query');
    if(is_null($keywords)){
        abort(404);
    }

    $post = Post::select('title','body')
    ->where(function ($query) use ($keywords) {
       return $query->where('title',$keywords)->where('body','LIKE','%%');
    })
    ->where('updated_at','>','2025-01-01')->get();
    return $post;
});
Route::get('/allpost',function(){

    // $posts = Post::all();
    $posts = Post::query()->get();


    return $posts;
});

Route::get('/filter',function(){
    $keywords = request()->input('query');
    if(is_null($keywords)){
        abort(404);
    }
    return $posts = Post::Filter($keywords)->get();
});
