<?php

namespace App\Services;

use App\Contracts\CounterContract;

class ExampleCounter implements CounterContract {
   
  public function increment(string $key, array $tags = null): int {
    dd('example counter not implemented yet');
    return 0;
  } 

}