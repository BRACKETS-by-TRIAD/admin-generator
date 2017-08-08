<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileGeneratorWithCustomControllerNameTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    function profile_controller_name_can_be_namespaced(){
        $filePath = base_path('App/Http/Controllers/Admin/Auth/ProfileController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user:profile', [
            '--controller-name' => 'Auth\\ProfileController',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{', File::get($filePath));
    }

    /** @test */
    function profile_controller_name_can_be_outside_default_directory(){
        $filePath = base_path('App/Http/Controllers/Auth/ProfileController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user:profile', [
            '--controller-name' => 'App\\Http\\Controllers\\Auth\\ProfileController',
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{', File::get($filePath));
    }

    /** @test */
    function profile_custom_controller_name_routes(){
        $filePath = base_path('routes/web.php');

        $this->artisan('admin:generate:user:profile', [
            '--controller-name' => 'Auth\\ProfileController',
        ]);

        $this->assertStringStartsWith('<?php



/* Auto-generated profile routes */
Route::get(\'/admin/profile\',                                \'Admin\Auth\ProfileController@editProfile\')->name(\'admin/profile/edit\');
Route::post(\'/admin/profile/update\',                        \'Admin\Auth\ProfileController@updateProfile\')->name(\'admin/profile/update\');
Route::get(\'/admin/profile/password\',                       \'Admin\Auth\ProfileController@editPassword\')->name(\'admin/password/edit\');
Route::post(\'/admin/profile/password/update\',               \'Admin\Auth\ProfileController@updatePassword\')->name(\'admin/password/update\');', File::get($filePath));
    }

}
