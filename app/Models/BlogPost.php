<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{

    use SoftDeletes, Taggable;

    use HasFactory;

    protected $fillable = [
      'title',
      'content',
      'user_id'
    ];

    public function comments() {
      // return $this->hasMany('App\Models\Comment')->latest();
      return $this->morphMany('App\Models\Comment', 'commentable')->latest();
    }

    public function user() {
      return $this->belongsTo('App\Models\User');
    }

    // public function tags() {    // moved to Traits\Taggable
    //   return $this->morphToMany('App\Models\Tag', 'taggable')->withTimestamps();
    // }

    public function image() {
      return $this->morphOne('App\Models\Image', 'imageable');
    }

    public function scopeLatest(Builder $query) { // scope action name: 'latest'
      return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query) {
      return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopeLatestWithRelations(Builder $query) {
      return $query->latest()
        ->withCount('comments')
        ->with('user')
        ->with('tags');
    }

    public static function boot() {
      static::addGlobalScope(new DeletedAdminScope); // put before parent as use of SoftDeletes would override this scope 
      parent::boot();
      // static::addGlobalScope(new LatestScope);

      // below: moved to BlogPostObserver
      // static::deleting(function (BlogPost $blogPost) {
      //   $blogPost->comments()->delete();
      //   $blogPost->image()->delete();
      //   Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
      // });
      // static::updating(function (BlogPost $blogPost) {
      //   Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
      // });
      // static::restoring(function (BlogPost $blogPost) {
      //   $blogPost->comments()->restore();
      // });

    }
}
