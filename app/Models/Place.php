<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'file_id',
        'author_id',
        'visibility_id',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    
    public function author()
    {
        return $this->belongsTo(User::class);
    }
    public function favorited()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
    
    public function favoritedByUser(User $user)
    {
        $count = Favorite::where([
            ['user_id',  '=', $user->id],
            ['place_id', '=', $this->id],
        ])->count();

        return $count > 0;
    }

    public function favoritedByAuthUser()
    {
        $user = auth()->user();
        return $this->favoritedByUser($user);
    }
    public function visibility()
    {
        return $this->belongsTo(visibility::class);
    }

    /*public function reviews()
    {
        return $this->hasMany(Review::class);
    }*/

}


