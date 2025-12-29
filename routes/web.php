<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Models\Scopes\activeScope;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;
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
    return $posts = Post::Filter($keywords)->cursorPaginate(5);
});

Route::get('/order',function(){
    return $posts = Post::query()->where('title','prof.')
            ->orWhere('title','dr.')
            ->oldest('id')
            ->paginate(10);
});

Route::get('/when',function(){
    $keywords = request()->input('key');
    return $posts = Post::query()
        ->when($keywords,
        function ($q, $keywords) {
            $q->where(function($qq) use ($keywords){
                $qq->where('body','LIKE',"%$keywords%")
                ->where('title','prof.');
            } );
        },function ($q) {
            $q->latest('id');
        })->get();
});

Route::get('/groupBy',function(){
    return $title = Post::query()
    ->where('created_at','<','2024-02-03')
    ->select('title',DB::raw('COUNT(*) as total'))
    ->groupBy('title')
    ->having('title','Dr.')
    ->get();
});

Route::get('/globalScope', function(){
    // return Post::withoutGlobalScope(activeScope::class)->get();
    return Post::get();
});

Route::get('/subquery',function(){
    $posts = Post::select('title','body','user_id')
    ->get();
    $posts = $posts->map(function($post){
        $totalpost = Post::where('user_id',$post->user_id)->count();
        $post->totalPost = $totalpost;
        return $post;
    });
    return $posts;
});

Route::get('/selectRaw',function(){
    $posts = Post::query()
    ->selectRaw('user_id,count(*) as totalPost')
    ->groupBy('user_id')
    ->orderBy('user_id','asc')
    ->get();
    return $posts;
});
Route::get('/with',function(){
    $users = User::all();
    foreach($users as $user){
        echo $user->post->count();
    }

});
Route::get('/whereIn',function(){
    $posts = Post::query()
    ->whereIn('user_id',function($query){
        $query->select('id')
        ->from('users')
        ->where('created_at','<','2025-01-01');
    })->get();

    return $posts;

});
