<?php

use Illuminate\Support\Facades\Route;
use App\Acl\Acl;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::get('/locale/{locale}', [\App\Http\Controllers\LocaleController::class, 'switch'])->name('locale.switch');
    Route::domain(config('subdomain.school') . '.' . config('app.url'))->group(function () {
        Route::get('/', function () {
            if (!auth()->check()) {
                return redirect()->route('auth.login.show-form');
            }

            $user = auth()->user();

            if ($user->hasAnyRole([Acl::ROLE_SUPER_ADMIN, Acl::ROLE_ADMIN])) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->hasAnyRole([Acl::ROLE_STAFF, Acl::ROLE_TEACHER])) {
                return redirect()->route('staff.dashboard');
            }
            return response()->view('auth.blocked');
        })->name('auth.index');

        include 'v1/web/auth.php';
    });

    Route::domain(config('subdomain.admin') . '.' . config('subdomain.school') . '.' . config('app.url'))
        ->scopeBindings()
        ->middleware(['check_user_role_redirect', 'auth.admin', 'role:' . Acl::ROLE_SUPER_ADMIN . '|' . Acl::ROLE_ADMIN])
        ->group(function () {
            include 'v1/web/admin.php';
        });

    Route::domain(config('subdomain.staff') . '.' . config('subdomain.school') . '.' . config('app.url'))
        ->scopeBindings()
        ->middleware(['check_user_role_redirect', 'auth', 'role:' . Acl::ROLE_STAFF. '|' . Acl::ROLE_TEACHER])
        ->group(function () {
            include 'v1/web/staff.php';
        });
});

// Get url for CORS handling in FormUpload component
Route::get('/cors-image/{media_id}/{filename}', function ($media_id, $filename) {
    $path = storage_path("app/public/{$media_id}/{$filename}");
    
    if (!File::exists($path)) {
        abort(404, 'Không tìm thấy ảnh');
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    return Response::make($file, 200)
        ->header('Content-Type', $type)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
        ->header('Cache-Control', 'public, max-age=86400');
});
