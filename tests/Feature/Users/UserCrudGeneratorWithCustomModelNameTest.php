<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserCrudGeneratorWithCustomModelNameTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    function all_files_should_be_generated_with_custom_model(){
        $controllerPath = base_path('App/Http/Controllers/Admin/Auth/UsersController.php');
        $storePath = base_path('App/Http/Requests/Admin/User/StoreUser.php');
        $updatePath = base_path('App/Http/Requests/Admin/User/UpdateUser.php');
        $routesPath = base_path('routes/web.php');
        $indexPath = resource_path('views/admin/user/index.blade.php');
        $indexJsPath = resource_path('assets/js/admin/user/Listing.js');
        $elementsPath = resource_path('views/admin/user/components/form-elements.blade.php');
        $createPath = resource_path('views/admin/user/create.blade.php');
        $editPath = resource_path('views/admin/user/edit.blade.php');
        $formJsPath = resource_path('assets/js/admin/user/Form.js');
        $factoryPath = base_path('database/factories/ModelFactory.php');

        $this->assertFileNotExists($controllerPath);
        $this->assertFileNotExists($storePath);
        $this->assertFileNotExists($updatePath);
        $this->assertFileNotExists($indexPath);
        $this->assertFileNotExists($indexJsPath);
        $this->assertFileNotExists($elementsPath);
        $this->assertFileNotExists($createPath);
        $this->assertFileNotExists($editPath);
        $this->assertFileNotExists($formJsPath);


        $this->artisan('admin:generate:user', [
            '--controller-name' => 'Auth\\UsersController',
            '--model-name' => 'App\\User',
        ]);

        $this->assertFileExists($controllerPath);
        $this->assertFileExists($storePath);
        $this->assertFileExists($updatePath);
        $this->assertFileExists($indexPath);
        $this->assertFileExists($indexJsPath);
        $this->assertFileExists($elementsPath);
        $this->assertFileExists($createPath);
        $this->assertFileExists($editPath);
        $this->assertFileExists($formJsPath);

        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\User\IndexUser;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use App\Http\Requests\Admin\User\DestroyUser;
use Brackets\Admin\AdminListing;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminAuth\Facades\Activation;
use Spatie\Permission\Models\Role;

class UsersController extends Controller', File::get($controllerPath));
        $this->assertStringStartsWith('<?php namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
{', File::get($storePath));
        $this->assertStringStartsWith('<?php namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
{', File::get($updatePath));
        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::get(\'/admin/user\',                                   \'Admin\UsersController@index\');
Route::get(\'/admin/user/create\',                            \'Admin\UsersController@create\');
Route::post(\'/admin/user/store\',                            \'Admin\UsersController@store\');
Route::get(\'/admin/user/edit/{user}\',                       \'Admin\UsersController@edit\')->name(\'admin/user/edit\');
Route::post(\'/admin/user/update/{user}\',                    \'Admin\UsersController@update\')->name(\'admin/user/update\');
Route::delete(\'/admin/user/destroy/{user}\',                 \'Admin\UsersController@destroy\')->name(\'admin/user/destroy\');
Route::get(\'/admin/user/resend-activation/{user}\',          \'Admin\UsersController@resendActivationEmail\')->name(\'admin/user/resendActivationEmail\');', File::get($routesPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.index\')', File::get($indexPath));
        $this->assertStringStartsWith('var base = require(\'../components/Listing/Listing\');

Vue.component(\'user-listing\'', File::get($indexJsPath));
        $this->assertStringStartsWith('<div ', File::get($elementsPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($createPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($editPath));
        $this->assertStringStartsWith('var base = require(\'../components/Form/Form\');

Vue.component(\'user-form\'', File::get($formJsPath));
        $this->assertStringStartsWith('<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class', File::get($factoryPath));
    }

    /** @test */
    function user_factory_generator_should_generate_everything_with_custom_model_name(){
        $filePath = base_path('database/factories/ModelFactory.php');

        $this->artisan('admin:generate:user', [
            '--model-name' => 'Auth\\User',
        ]);

        $this->assertStringStartsWith('<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Auth\User::class', File::get($filePath));
    }

}
