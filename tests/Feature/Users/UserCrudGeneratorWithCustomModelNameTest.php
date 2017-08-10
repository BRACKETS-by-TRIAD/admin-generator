<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserCrudGeneratorWithCustomModelNameTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_controller_should_be_generated_with_custom_model(){
        $filePath = base_path('App/Http/Controllers/Admin/Auth/UsersController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user', [
            '--controller-name' => 'Auth\\UsersController',
            '--model-name' => 'App\\User',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use Brackets\Admin\AdminListing;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminAuth\Facades\Activation;
use Spatie\Permission\Models\Role;

class UsersController extends Controller', File::get($filePath));
    }

    /** @test */
    function user_store_request_should_be_generate_with_custom_model_name(){
        $filePath = base_path('App/Http/Requests/Admin/User/StoreUser.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user', [
            '--controller-name' => 'Auth\\UsersController',
            '--model-name' => 'App\\User',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
{', File::get($filePath));
    }

    /** @test */
    function user_update_request_should_be_generate_with_custom_model_name(){
        $filePath = base_path('App/Http/Requests/Admin/User/UpdateUser.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user', [
            '--controller-name' => 'Auth\\UsersController',
            '--model-name' => 'App\\User',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Gate;
use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
{', File::get($filePath));
    }

    /** @test */
    function user_index_listing_should_get_generated_with_custom_model_name(){
        $indexPath = resource_path('views/admin/user/index.blade.php');
        $indexJsPath = resource_path('assets/js/admin/user/Listing.js');

        $this->assertFileNotExists($indexPath);
        $this->assertFileNotExists($indexJsPath);

        $this->artisan('admin:generate:user', [
            '--model-name' => 'App\\User',
        ]);

        $this->assertFileExists($indexPath);
        $this->assertFileExists($indexJsPath);
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.index\')', File::get($indexPath));
        $this->assertStringStartsWith('var base = require(\'../components/Listing/Listing\');

Vue.component(\'user-listing\'', File::get($indexJsPath));
    }

    /** @test */
    function user_view_form_should_get_generated_with_custom_model_name(){
        $elementsPath = resource_path('views/admin/user/components/form-elements.blade.php');
        $createPath = resource_path('views/admin/user/create.blade.php');
        $editPath = resource_path('views/admin/user/edit.blade.php');
        $formJsPath = resource_path('assets/js/admin/user/Form.js');

        $this->assertFileNotExists($elementsPath);
        $this->assertFileNotExists($createPath);
        $this->assertFileNotExists($editPath);
        $this->assertFileNotExists($formJsPath);

        $this->artisan('admin:generate:user', [
            '--model-name' => 'App\\User',
        ]);

        $this->assertFileExists($elementsPath);
        $this->assertFileExists($createPath);
        $this->assertFileExists($editPath);
        $this->assertFileExists($formJsPath);
        $this->assertStringStartsWith('<div ', File::get($elementsPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($createPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')', File::get($editPath));
        $this->assertStringStartsWith('var base = require(\'../components/Form/Form\');

Vue.component(\'user-form\'', File::get($formJsPath));
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


    /** @test */
    function user_factory_generator_should_generate_everything_with_custom_model_name_outside_default_folder(){
        $filePath = base_path('database/factories/ModelFactory.php');

        $this->artisan('admin:generate:user', [
            '--model-name' => 'App\\User',
        ]);

        $this->assertStringStartsWith('<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class', File::get($filePath));
    }

}
