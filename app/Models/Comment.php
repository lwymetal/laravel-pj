<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
  use SoftDeletes;
  
    use HasFactory;

    public function blogPost() {
      return $this->belongsTo('App\Models\BlogPost');
    }

    public function user() {
      return $this->belongsTo('App\Models\User');
    }

    public function scopeLatest(Builder $query) { // scope action name: 'latest'
      return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot() {
      parent::boot();
      static::addGlobalScope(new LatestScope);
    }
}
