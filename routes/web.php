<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/w', function() {
//   return view('welcome');
// });

// Route::get('/', function () {
//   return view('home.index', []);
// })->name('home.index');
// Route::view('/', 'home.index')->name('home.index');
Route::get('/', [HomeController::class, 'home'])
  ->name('home.index');
  // ->middleware('auth');

// Route::get('/contact', function() {
//   return view('home.contact', []);
// })->name('home.contact');
// Route::view('/contact', 'home.contact')->name('home.contact');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::get('/secret', [HomeController::class, 'secret'])
  ->name('secret')
  ->middleware('can:home.secret'); // works same as in blade template

Route::get('/single', AboutController::class); // __invoke - single action

Route::resource('posts', PostsController::class)
  // ->only([
  // 'index', 'show', 'create', 'store', 'edit', 'update'
// ])
;
// ->only([])
// ->except([])

// Route::get('/posts', function() use($posts) {
//   dd(request()->input('limit'));
//   // return view('posts.index', ['posts' => $posts]);
//   return view('posts.index', compact('posts'));
// });

// Route::get('/posts/{id}', function($id) use($posts) {

//   abort_if(!isset($posts[$id]), 404);

//   return view('posts.show', ['post' => $posts[$id]]);
// })
//   // ->where(['id' => '[0-9]+'])
//   ->name('posts.show');

Route::get('/recent-posts/{days_ago?}', function($daysAgo = 20) {
  return 'Posts from ' . $daysAgo . ' days ago';
})->name('posts.recent.index')->middleware('auth');

// Route::prefix('/fun')->name('fun.')->group(function() use($posts) {
//   Route::get('responses', function() use($posts) {
//     return response($posts, 201)
//       // ->view()
//       ->header('Content-Type', 'asdf')
//       ->cookie('MY_COOKIE', 'MyName', 3600);
//   })->name('responses');
  
//   Route::get('redirect', function() {
//     return redirect('/contact');
//   })->name('redirect');
  
//   Route::get('back', function() {
//     return back();
//   })->name('back');
  
//   Route::get('named-route', function() {
//     return redirect()->route('posts.show', ['id' => 1]);
//   })->name('named-route');
  
//   Route::get('away', function() {
//     return redirect()->away('https://www.yahoo.com');
//   })->name('away');
  
//   Route::get('json', function() use($posts) {
//     return response()->json($posts);
//   })->name('json');
  
//   Route::get('download', function() use ($posts) {
//     return response()->download(public_path('/images/potato.jpeg'), '1potato.jpg');
//   })->name('download');

Auth::routes();