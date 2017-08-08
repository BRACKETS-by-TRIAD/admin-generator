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
use App\User;

class UpdateUser extends FormRequest
{', File::get($filePath));
    }

}
