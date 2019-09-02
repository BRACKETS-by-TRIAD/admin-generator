<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;

class UserCrudGeneratorWithCustomControllerNameTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_controller_name_can_be_namespaced(): void
    {
        $filePathController = base_path('app/Http/Controllers/Admin/Auth/UsersController.php');
        $filePathRoutes = base_path('routes/web.php');

        $this->assertFileNotExists($filePathController);

        $this->artisan('admin:generate:user', [
            '--controller-name' => 'Auth\\UsersController',
        ]);

        $this->assertFileExists($filePathController);
        $this->assertStringStartsWith('<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\DestroyUser;
use App\Http\Requests\Admin\User\IndexUser;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class UsersController extends Controller', File::get($filePathController));

        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::middleware([\'auth:\' . config(\'admin-auth.defaults.guard\'), \'admin\'])->group(static function () {
    Route::get(\'/admin/users\',                                  \'Admin\Auth\UsersController@index\');
    Route::get(\'/admin/users/create\',                           \'Admin\Auth\UsersController@create\');
    Route::post(\'/admin/users\',                                 \'Admin\Auth\UsersController@store\');
    Route::get(\'/admin/users/{user}/edit\',                      \'Admin\Auth\UsersController@edit\')->name(\'admin/users/edit\');
    Route::post(\'/admin/users/{user}\',                          \'Admin\Auth\UsersController@update\')->name(\'admin/users/update\');
    Route::delete(\'/admin/users/{user}\',                        \'Admin\Auth\UsersController@destroy\')->name(\'admin/users/destroy\');
    Route::get(\'/admin/users/{user}/resend-activation\',         \'Admin\Auth\UsersController@resendActivationEmail\')->name(\'admin/users/resendActivationEmail\');',
            File::get($filePathRoutes));
    }

    /** @test */
    public function user_controller_name_can_be_outside_default_directory(): void
    {
        $filePath = base_path('app/Http/Controllers/Auth/UsersController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user', [
            '--controller-name' => 'App\\Http\\Controllers\\Auth\\UsersController',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\DestroyUser;
use App\Http\Requests\Admin\User\IndexUser;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class UsersController extends Controller', File::get($filePath));
    }
}
