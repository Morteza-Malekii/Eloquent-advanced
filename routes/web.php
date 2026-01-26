<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Models\Scopes\activeScope;
use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;
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
    ->select('id','title','body','user_id')
    ->selectRaw('user_id,count(*) as totalPost')
    ->groupBy('user_id')
    ->orderBy('user_id','asc')
    ->get();
    return $posts;
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
Route::get('/havinRaw',function(){
    $posts = Post::query()
    ->selectRaw('user_id, count(*) as totalPost')
    ->groupBy('user_id')
    ->havingRaw('totalPost < ?',[22])
    ->get();
    return $posts;
});
//لیست کاربران +تاریخ آخرین پست هر کاربر
Route::get('/selectSub',function(){
    $users = User::select('name')
    ->selectSub(
        Post::select('created_at')
        ->whereColumn('posts.user_id','users.id')
        ->latest()
        ->limit(1)
        ,'last_post_at'
        )
    ->get();
    return $users;
});
//لیست پستها همراه با تعداد پستهای نویسنده
Route::get('/selectSub2',function(){
    $posts = Post::query()
    ->select('id', 'title', 'body', 'user_id')
    ->selectSub(
        Post::query()
            ->selectRaw('COUNT(*)')
            ->whereColumn('posts.user_id', 'outer.user_id'),
        'author_posts_count'
    )
    ->from('posts as outer')
    ->get();
    return $posts;
});
//لیست یوزرها همراه با تاریخ آخرین پستشان
Route::get('/selectSub3',function(){
    $users = User::query()
    ->select('id','name')
    ->selectSub(
        Post::select('created_at')
        ->whereColumn('posts.user_id','users.id')
        ->latest()
        ->limit(1),'last_post'
    )
    ->get();
    return $users;
});
//تعداد پست‌های هر کاربر است
Route::get('/N+1',function(){
    $users = User::all();
    foreach($users as $user){
        echo $user->posts->count().'<br>';
    }
});
//تعداد پست‌های هر کاربر است
Route::get('/with',function(){
    $users = User::with('posts')->get();
    foreach($users as $user){
        echo $user->posts->count().'<br>';
    }
});
//لیست کاربران به همراه تعداد پست هایشان
Route::get('/with2',function(){
    $users = User::with('posts')->get();
    foreach($users as $user){
        echo $user->name . 'counts of post = ';
        echo $user->posts->count().'<br>';
    }
});
//گرفتن همهٔ پست‌ها به‌همراه نویسنده‌شان
Route::get('/with3',function(){
    $posts = Post::with('user')->get();
    foreach($posts as $post){
        echo $post->title . 'wrote as = ';
        echo $post->user->name .'<br>';
    }
});
//گرفتن همهٔ پست‌ها به‌همراه نویسنده و کامنت‌ها
Route::get('/with4',function(){
    $posts = Post::with(['user','comments'])
    ->get();
    foreach($posts as $post){
        echo $post->title . 'wrote as = ';
        echo $post->user->name.'<br>' ;
        foreach($post->comments as $comment)
        {
            echo 'COMMENTS :::::'.$comment->body.'<br>';
        }
        echo '<br>';
    }
});

Route::get('/with4',function(){
    $posts = Post::with(['user','comments'])
    ->withCount('comments')
    ->get();
    foreach($posts as $post){
        echo $post->title . 'wrote as = ';
        echo $post->user->name.'<br>' ;
        foreach($post->comments as $comment)
        {
            echo 'COMMENTS :::::'.$comment->body.'<br>';
        }
        echo $post->comments_count;
        echo '<br>';
        echo '<br>';
    }
});





