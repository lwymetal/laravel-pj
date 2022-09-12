<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
// use App\Models\BlogPost; // not needed, same namespace

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const LOCALES = ['en' => 'English', 'es' => 'Español', 'de' => 'Deutsch'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email', 
        'email_verified_at',
        'created_at',
        'updated_at',
        'is_admin',
        'locale'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function blogPosts() {
      return $this->hasMany('App\Models\BlogPost');
    }

    public function comments() {
      return $this->hasMany('App\Models\Comment');
    }

    public function commentsOn() {
      return $this->morphMany('App\Models\Comment', 'commentable')->latest();
    }

    public function image() {
      return $this->morphOne('App\Models\Image', 'imageable');
    }

    public function scopeWithMostBlogPosts(Builder $query) {
      return $query->withCount('blogPosts')->orderBy('blog_posts_count', 'desc');
    }

    public function scopeWithMostBlogPostsLastMonth(Builder $query) {
      return $query->withCount(['blogPosts' => function (Builder $query) {
        $query->whereBetween(static::CREATED_AT, [now()->subMonths(1), now()]);
      }])
      ->having('blog_posts_count', '>=', 2)
      ->orderBy('blog_posts_count', 'desc');
    }

    public function scopeThatHasCommentedOnPost(Builder $query, BlogPost $post) {
      $query->whereHas('comments', function($query) use ($post) {
        return $query->where('commentable_id', '=', $post->id)
          ->where('commentable_type', '=', BlogPost::class);
      });
    }

    public function scopeThatIsAdmin(Builder $query) {
      return $query->where('is_admin', true);
    }

}
