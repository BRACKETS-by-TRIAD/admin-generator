<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserCrudGeneratorWithCustomControllerNameTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_controller_name_can_be_namespaced(){
        $filePathController = base_path('App/Http/Controllers/Admin/Auth/UsersController.php');
        $filePathRoutes = base_path('routes/web.php');

        $this->assertFileNotExists($filePathController);

        $this->artisan('admin:generate:user', [
            '--controller-name' => 'Auth\\UsersController',
        ]);

        $this->assertFileExists($filePathController);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\User\IndexUser;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use App\Http\Requests\Admin\User\DestroyUser;
use Brackets\AdminListing\AdminListing;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminAuth\Facades\Activation;
use Spatie\Permission\Models\Role;

class UsersController extends Controller', File::get($filePathController));

        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::get(\'/admin/user\',                                   \'Admin\Auth\UsersController@index\');
Route::get(\'/admin/user/create\',                            \'Admin\Auth\UsersController@create\');
Route::post(\'/admin/user/store\',                            \'Admin\Auth\UsersController@store\');
Route::get(\'/admin/user/edit/{user}\',                       \'Admin\Auth\UsersController@edit\')->name(\'admin/user/edit\');
Route::post(\'/admin/user/update/{user}\',                    \'Admin\Auth\UsersController@update\')->name(\'admin/user/update\');
Route::delete(\'/admin/user/destroy/{user}\',                 \'Admin\Auth\UsersController@destroy\')->name(\'admin/user/destroy\');
Route::get(\'/admin/user/resend-activation/{user}\',          \'Admin\Auth\UsersController@resendActivationEmail\')->name(\'admin/user/resendActivationEmail\');', File::get($filePathRoutes));
    }

    /** @test */
    function user_controller_name_can_be_outside_default_directory(){
        $filePath = base_path('App/Http/Controllers/Auth/UsersController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user', [
            '--controller-name' => 'App\\Http\\Controllers\\Auth\\UsersController',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\User\IndexUser;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use App\Http\Requests\Admin\User\DestroyUser;
use Brackets\AdminListing\AdminListing;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminAuth\Facades\Activation;
use Spatie\Permission\Models\Role;

class UsersController extends Controller', File::get($filePath));
    }
}
