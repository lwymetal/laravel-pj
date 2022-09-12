<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUser;
// use App\Services\Counter; // change service to contract
// use App\Contracts\CounterContract; // for dependency injection - changing to facade
use App\Facades\CounterFacade;

class UserController extends Controller
{

  // private $counter;

  // public function __construct(CounterContract $counter) { // dependency injection
  public function __construct() {
    $this->middleware('auth');
    $this->authorizeResource(User::class, 'user');
    // $this->counter = $counter; // for dependency injection
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
      // $counter = resolve(Counter::class);
      return view('users.show', [
        'user' => $user, 
        // 'counter' => $this->counter->increment("user-{$user->id}") // dependency injection
        'counter' => CounterFacade::increment("user-{$user->id}") // static operator
      ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
      return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
      if ($request->hasFile('avatar')) {
        $path = $request->file('avatar')->store('avatars');
        if ($user->image) {
          $user->image->path = $path;
          $user->image->save(); // update existing
        } else {
          $user->image()->save(
            Image::make(['path' => $path])
          ); // new
        }
      }

      $user->locale = $request->get('locale');
      $user->save();

      return redirect()->back()->withStatus(__('Changes saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
