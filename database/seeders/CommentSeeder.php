<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id');   // لیست id کاربران

        Post::all()->each(function ($post) use ($users) {
            // برای هر پست ۲ تا ۵ کامنت بساز
            Comment::factory(rand(2, 5))
                ->make([
                    // اینجا فقط user_id رو override می‌کنیم
                    'user_id' => $users->random(),
                ])
                ->each(function ($comment) use ($post) {
                    // این خط طلاییه:
                    // commentable_id و commentable_type اتوماتیک پر می‌شه
                    $post->comments()->save($comment);
                });
        });

        //اگر خواستیم برای product هم بصورت اتوماتیک کامنت پر کنیم بایستی بصورت زیر بنویسیم

        // Product::all()->each(function($product) use ($users){
        //     Comment::factory(rand(2,5))
        //     ->make([
        //         'user_id'=>$users->random(),
        //     ])
        //     ->each(function ($comment) use ($product) {
        //         $product->comments->save($comment);
        //     });
        // });
    }
}
