<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Models\Comment;


class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_name',
        'content',
        'file_id',
        'author_id',
    ];

    public function file(){
        return $this->belongsTo(File::class);
    }
    public function user(){
        return $this->belongsTo(User::class, 'author_id');
    }
    public function author(){
        return $this->belongsTo(User::class);
    }
    public function liked(){
        return $this->belongsToMany(User::class, 'likes');
    }

    public function likedByUser(User $user)
    {
        $count = Like::where([
            ['user_id',  '=', $user->id],
            ['post_id', '=', $this->id],
        ])->count();
        
        return $count > 0;
    }

    public function likedByAuthUser()
    {
        $user = auth()->user();
        return $this->likedByUser($user);
    }

    /*public function visibility()
    {
        return $this->belongsTo(Visibility::class);
    }*/

   /* public function comments()
    {
        return $this->hasMany(Comment::class);
    }*/

  




}
