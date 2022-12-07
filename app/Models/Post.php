<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
    ];

    protected $casts = [
        'body' => 'array',
    ];

    protected $appends = [
        'title_uppercase'
    ];

    public function titleUppercase(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return strtoupper($attributes['title']);
            }
        );
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id');
    }
}
