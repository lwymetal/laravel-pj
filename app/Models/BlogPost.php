<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{

    use SoftDeletes;

    use HasFactory;

    protected $fillable = [
      'title',
      'content',
      'user_id'
    ];

    public function comments() {
      return $this->hasMany('App\Models\Comment')->latest();
    }

    public function user() {
      return $this->belongsTo('App\Models\User');
    }

    public function tags() {
      return $this->belongsToMany('App\Models\Tag')->withTimestamps();
    }

    public function scopeLatest(Builder $query) { // scope action name: 'latest'
      return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query) {
      return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public static function boot() {
      static::addGlobalScope(new DeletedAdminScope); // put before parent as use of SoftDeletes would override this scope 
      parent::boot();
      // static::addGlobalScope(new LatestScope);
      static::deleting(function (BlogPost $blogPost) {
        $blogPost->comments()->delete();
      });
      static::updating(function (BlogPost $blogPost) {
        Cache::forget("blog-post-{$blogPost->id}");
      });
      static::restoring(function (BlogPost $blogPost) {
        $blogPost->comments()->restore();
      });
    }
}
