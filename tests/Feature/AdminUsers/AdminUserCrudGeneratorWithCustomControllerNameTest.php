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

        $this->assertFileDoesNotExist($filePathController);

        $this->artisan('admin:generate:admin-user', [
            '--controller-name' => 'Auth\\AdminUsersController',
        ]);

        $this->assertFileExists($filePathController);
        $this->assertStringStartsWith('<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUser\DestroyAdminUser;
use App\Http\Requests\Admin\AdminUser\ImpersonalLoginAdminUser;
use App\Http\Requests\Admin\AdminUser\IndexAdminUser;
use App\Http\Requests\Admin\AdminUser\StoreAdminUser;
use App\Http\Requests\Admin\AdminUser\UpdateAdminUser;
use Brackets\AdminAuth\Models\AdminUser;
use Spatie\Permission\Models\Role;
use Brackets\AdminAuth\Activation\Facades\Activation;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class AdminUsersController extends Controller', File::get($filePathController));

        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::middleware([\'auth:\' . config(\'admin-auth.defaults.guard\'), \'admin\'])->group(static function () {
    Route::prefix(\'admin\')->namespace(\'App\Http\Controllers\Admin\')->name(\'admin/\')->group(static function() {
        Route::prefix(\'admin-users\')->name(\'admin-users/\')->group(static function() {
            Route::get(\'/\',                                             \'Auth\AdminUsersController@index\')->name(\'index\');
            Route::get(\'/create\',                                       \'Auth\AdminUsersController@create\')->name(\'create\');
            Route::post(\'/\',                                            \'Auth\AdminUsersController@store\')->name(\'store\');
            Route::get(\'/{adminUser}/impersonal-login\',                 \'Auth\AdminUsersController@impersonalLogin\')->name(\'impersonal-login\');
            Route::get(\'/{adminUser}/edit\',                             \'Auth\AdminUsersController@edit\')->name(\'edit\');
            Route::post(\'/{adminUser}\',                                 \'Auth\AdminUsersController@update\')->name(\'update\');
            Route::delete(\'/{adminUser}\',                               \'Auth\AdminUsersController@destroy\')->name(\'destroy\');
            Route::get(\'/{adminUser}/resend-activation\',                \'Auth\AdminUsersController@resendActivationEmail\')->name(\'resendActivationEmail\');
        });
    });
});',
            File::get($filePathRoutes));
    }

    /** @test */
    public function admin_user_controller_name_can_be_outside_default_directory(): void
    {
        $filePath = base_path('app/Http/Controllers/Auth/AdminUsersController.php');

        $this->assertFileDoesNotExist($filePath);

        $this->artisan('admin:generate:admin-user', [
            '--controller-name' => 'App\\Http\\Controllers\\Auth\\AdminUsersController',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUser\DestroyAdminUser;
use App\Http\Requests\Admin\AdminUser\ImpersonalLoginAdminUser;
use App\Http\Requests\Admin\AdminUser\IndexAdminUser;
use App\Http\Requests\Admin\AdminUser\StoreAdminUser;
use App\Http\Requests\Admin\AdminUser\UpdateAdminUser;
use Brackets\AdminAuth\Models\AdminUser;
use Spatie\Permission\Models\Role;
use Brackets\AdminAuth\Activation\Facades\Activation;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class AdminUsersController extends Controller', File::get($filePath));
    }
}
