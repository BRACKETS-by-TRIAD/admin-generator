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

        $this->assertFileDoesNotExist($filePathController);

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
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class UsersController extends Controller', File::get($filePathController));

        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::middleware([\'auth:\' . config(\'admin-auth.defaults.guard\'), \'admin\'])->group(static function () {
    Route::prefix(\'admin\')->namespace(\'App\Http\Controllers\Admin\')->name(\'admin/\')->group(static function() {
        Route::prefix(\'users\')->name(\'users/\')->group(static function() {
            Route::get(\'/\',                                             \'Auth\UsersController@index\')->name(\'index\');
            Route::get(\'/create\',                                       \'Auth\UsersController@create\')->name(\'create\');
            Route::post(\'/\',                                            \'Auth\UsersController@store\')->name(\'store\');
            Route::get(\'/{user}/edit\',                                  \'Auth\UsersController@edit\')->name(\'edit\');
            Route::post(\'/{user}\',                                      \'Auth\UsersController@update\')->name(\'update\');
            Route::delete(\'/{user}\',                                    \'Auth\UsersController@destroy\')->name(\'destroy\');
            Route::get(\'/{user}/resend-activation\',                     \'Auth\UsersController@resendActivationEmail\')->name(\'resendActivationEmail\');
        });
    });
});',
            File::get($filePathRoutes));
    }

    /** @test */
    public function user_controller_name_can_be_outside_default_directory(): void
    {
        $filePath = base_path('app/Http/Controllers/Auth/UsersController.php');

        $this->assertFileDoesNotExist($filePath);

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
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class UsersController extends Controller', File::get($filePath));
    }
}
