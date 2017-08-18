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
        $filePathController = base_path('App/Http/Controllers/Admin/Auth/ProfileController.php');
        $filePathRoute = base_path('routes/web.php');

        $this->assertFileNotExists($filePathController);

        $this->artisan('admin:generate:user:profile', [
            '--controller-name' => 'Auth\\ProfileController',
        ]);

        $this->assertFileExists($filePathController);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{', File::get($filePathController));
        $this->assertStringStartsWith('<?php



/* Auto-generated profile routes */
Route::get(\'/admin/profile\',                                \'Admin\Auth\ProfileController@editProfile\')->name(\'admin/profile/edit\');
Route::post(\'/admin/profile\',                               \'Admin\Auth\ProfileController@updateProfile\')->name(\'admin/profile/update\');
Route::get(\'/admin/password\',                               \'Admin\Auth\ProfileController@editPassword\')->name(\'admin/password/edit\');
Route::post(\'/admin/password\',                              \'Admin\Auth\ProfileController@updatePassword\')->name(\'admin/password/update\');', File::get($filePathRoute));
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

}
