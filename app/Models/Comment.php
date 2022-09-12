<?php

namespace App\Models;

use App\Scopes\LatestScope;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
  use SoftDeletes, Taggable;
  
    use HasFactory;

    protected $fillable = ['user_id', 'content'];

    // public function blogPost() {
    //   return $this->belongsTo('App\Models\BlogPost');
    // }

    protected $hidden = [
       // hides these when converted to array (e.g. JSON returned through publicly visible API)
      'deleted_at', 'commentable_type', 'commentable_id', 'user_id'
    ];

    public function commentable() {
      return $this->morphTo();
    }

    public function user() {
      return $this->belongsTo('App\Models\User');
    }

    // public function tags() {  // moved to Traits\Taggable
    //   return $this->morphToMany('App\Models\Tag', 'taggable')->withTimestamps();
    // }

    public function scopeLatest(Builder $query) { // scope action name: 'latest'
      return $query->orderBy(static::CREATED_AT, 'desc');
    }

    // moved to CommentObserver
    // public static function boot() {
    //   parent::boot();
      // static::addGlobalScope(new LatestScope);

    //   static::creating(function (Comment $comment) {
    //     if ($comment->commentable_type == BlogPost::class) {
    //       Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
    //       Cache::tags(['blog-post'])->forget("mostCommented");
    //     }
    //   });
    // }
}
