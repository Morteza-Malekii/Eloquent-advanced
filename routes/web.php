<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;

Route::get('/create', function () {
    $post = Post::create([
        'title'=>'advanced elequent 2',
        'body'=>'this is good project for learning elequent laravel'
    ]);
    return $post;
});
Route::get('/all', function () {
    $posts = Post::all();
    dd($posts->pluck('title'));
});
Route::get('/find', function () {
    $post = Post::findOrFail(3);
    dd($post->title);
});
Route::get('/', function () {
    return view('welcome');
});
