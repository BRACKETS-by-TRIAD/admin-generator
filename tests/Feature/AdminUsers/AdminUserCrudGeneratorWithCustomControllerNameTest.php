<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;

class AdminUserCrudGeneratorWithCustomControllerNameTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function admin_user_controller_name_can_be_namespaced(): void
    {
        $filePathController = base_path('app/Http/Controllers/Admin/Auth/AdminUsersController.php');
        $filePathRoutes = base_path('routes/web.php');

        $this->assertFileNotExists($filePathController);

        $this->artisan('admin:generate:admin-user', [
            '--controller-name' => 'Auth\\AdminUsersController',
        ]);

        $this->assertFileExists($filePathController);
        $this->assertStringStartsWith('<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUser\DestroyAdminUser;
use App\Http\Requests\Admin\AdminUser\IndexAdminUser;
use App\Http\Requests\Admin\AdminUser\StoreAdminUser;
use App\Http\Requests\Admin\AdminUser\UpdateAdminUser;
use Brackets\AdminAuth\Models\AdminUser;
use Spatie\Permission\Models\Role;
use Brackets\AdminAuth\Activation\Facades\Activation;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class AdminUsersController extends Controller', File::get($filePathController));

        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::middleware([\'auth:\' . config(\'admin-auth.defaults.guard\'), \'admin\'])->group(static function () {
    Route::get(\'/admin/admin-users\',                            \'Admin\Auth\AdminUsersController@index\');
    Route::get(\'/admin/admin-users/create\',                     \'Admin\Auth\AdminUsersController@create\');
    Route::post(\'/admin/admin-users\',                           \'Admin\Auth\AdminUsersController@store\');
    Route::get(\'/admin/admin-users/{adminUser}/edit\',           \'Admin\Auth\AdminUsersController@edit\')->name(\'admin/admin-users/edit\');
    Route::post(\'/admin/admin-users/{adminUser}\',               \'Admin\Auth\AdminUsersController@update\')->name(\'admin/admin-users/update\');
    Route::delete(\'/admin/admin-users/{adminUser}\',             \'Admin\Auth\AdminUsersController@destroy\')->name(\'admin/admin-users/destroy\');
    Route::get(\'/admin/admin-users/{adminUser}/resend-activation\',\'Admin\Auth\AdminUsersController@resendActivationEmail\')->name(\'admin/admin-users/resendActivationEmail\');',
            File::get($filePathRoutes));
    }

    /** @test */
    public function admin_user_controller_name_can_be_outside_default_directory(): void
    {
        $filePath = base_path('app/Http/Controllers/Auth/AdminUsersController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:admin-user', [
            '--controller-name' => 'App\\Http\\Controllers\\Auth\\AdminUsersController',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUser\DestroyAdminUser;
use App\Http\Requests\Admin\AdminUser\IndexAdminUser;
use App\Http\Requests\Admin\AdminUser\StoreAdminUser;
use App\Http\Requests\Admin\AdminUser\UpdateAdminUser;
use Brackets\AdminAuth\Models\AdminUser;
use Spatie\Permission\Models\Role;
use Brackets\AdminAuth\Activation\Facades\Activation;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;

class AdminUsersController extends Controller', File::get($filePath));
    }
}
