<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;

class ProfileGeneratorWithCustomControllerNameTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function profile_controller_name_can_be_namespaced(): void
    {
        $filePathController = base_path('app/Http/Controllers/Admin/Auth/ProfileController.php');
        $filePathRoute = base_path('routes/web.php');

        $this->assertFileNotExists($filePathController);

        $this->artisan('admin:generate:admin-user:profile', [
            '--controller-name' => 'Auth\\ProfileController',
        ]);

        $this->assertFileExists($filePathController);
        $this->assertStringStartsWith('<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{', File::get($filePathController));
        $this->assertStringStartsWith('<?php



/* Auto-generated profile routes */
Route::middleware([\'auth:\' . config(\'admin-auth.defaults.guard\'), \'admin\'])->group(static function () {
    Route::get(\'/admin/profile\',                                \'Admin\Auth\ProfileController@editProfile\');
    Route::post(\'/admin/profile\',                               \'Admin\Auth\ProfileController@updateProfile\');
    Route::get(\'/admin/password\',                               \'Admin\Auth\ProfileController@editPassword\');
    Route::post(\'/admin/password\',                              \'Admin\Auth\ProfileController@updatePassword\');',
            File::get($filePathRoute));
    }

    /** @test */
    public function profile_controller_name_can_be_outside_default_directory(): void
    {
        $filePath = base_path('app/Http/Controllers/Auth/ProfileController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:admin-user:profile', [
            '--controller-name' => 'App\\Http\\Controllers\\Auth\\ProfileController',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{', File::get($filePath));
    }
}
