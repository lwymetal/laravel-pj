<?php

namespace App\Services;

// use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session;
use App\Contracts\CounterContract;

class Counter implements CounterContract {

  private $timeout;
  private $cache;
  private $session;
  private $supportsTags;

  public function __construct(Cache $cache, Session $session, int $timeout) {
    $this->cache = $cache;
    $this->timeout = $timeout;
    $this->session = $session;
    $this->supportsTags = method_exists($cache, 'tags');
  }
   
  public function increment(string $key, array $tags = null): int {
    $sessionId = $this->session->getId();
    $counterKey = "{$key}-counter";
    $usersKey = "{$key}-users";

    $cache = $this->supportsTags && $tags !== null ? $this->cache->tags($tags) : $this->cache;

    // $users = Cache::tags(['blog-post'])->get($usersKey, []);
    $users = $cache->get($usersKey, []);
    $usersUpdate = [];
    $diff = 0;
    $now = now();

    foreach ($users as $session => $lastVisit) {
      if ($now->diffInMinutes($lastVisit) >= $this->timeout) {
        $diff--;
      } else {
        $usersUpdate[$session] = $lastVisit;
      }
    }

    if (!array_key_exists($sessionId, $users) || $now->diffInMinutes($users[$sessionId]) >= $this->timeout) {
      $diff++;
    }

    $usersUpdate[$sessionId] = $now;
    // Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);
    $cache->forever($usersKey, $usersUpdate);
    if ($cache->has($counterKey)) {
      $cache->forever($counterKey, 1); // count first user 
    } else {
      $cache->increment($counterKey, $diff); // diff is 1 only if user is new/refreshed
    // cache increment can be negative
    }

    $counter = $cache->get($counterKey);

    return $counter;
  }

}