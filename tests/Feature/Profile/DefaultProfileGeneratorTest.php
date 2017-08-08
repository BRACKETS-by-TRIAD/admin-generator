<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DefaultProfileGeneratorTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    function profile_controller_should_be_generated_under_default_namespace(){
        $filePath = base_path('App/Http/Controllers/Admin/ProfileController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user:profile', [
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{', File::get($filePath));
    }

    /** @test */
    function profile_generated_routes_append(){
        $filePath = base_path('routes/web.php');

        $this->artisan('admin:generate:user:profile', [
        ]);

        $this->assertStringStartsWith('<?php



/* Auto-generated profile routes */
Route::get(\'/admin/profile\',                                \'Admin\ProfileController@editProfile\')->name(\'admin/profile/edit\');
Route::post(\'/admin/profile/update\',                        \'Admin\ProfileController@updateProfile\')->name(\'admin/profile/update\');
Route::get(\'/admin/profile/password\',                       \'Admin\ProfileController@editPassword\')->name(\'admin/password/edit\');
Route::post(\'/admin/profile/password/update\',               \'Admin\ProfileController@updatePassword\')->name(\'admin/password/update\');', File::get($filePath));
    }

    /** @test */
    function profile_form_should_get_generated() {
        $editPath = resource_path('views/admin/profile/edit-profile.blade.php');
        $formJsPath = resource_path('assets/js/admin/profile-edit-profile/Form.js');

        $this->assertFileNotExists($editPath);
        $this->assertFileNotExists($formJsPath);

        $this->artisan('admin:generate:user:profile', [
        ]);

        $this->assertFileExists($editPath);
        $this->assertFileExists($formJsPath);
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')

@section(\'body\')

    <div class="container-xl">

        <div class="card">

            <profile-edit-profile-form', File::get($editPath));
        $this->assertStringStartsWith('var base = require(\'../components/Form/Form\');

Vue.component(\'profile-edit-profile-form\'', File::get($formJsPath));
    }

    /** @test */
    function password_form_should_get_generated(){
        $editPath = resource_path('views/admin/profile/edit-password.blade.php');
        $formJsPath = resource_path('assets/js/admin/profile-edit-password/Form.js');

        $this->assertFileNotExists($editPath);
        $this->assertFileNotExists($formJsPath);

        $this->artisan('admin:generate:user:profile', [
        ]);

        $this->assertFileExists($editPath);
        $this->assertFileExists($formJsPath);
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')

@section(\'body\')

    <div class="container-xl">

        <div class="card">

            <profile-edit-password-form', File::get($editPath));
        $this->assertStringStartsWith('var base = require(\'../components/Form/Form\');

Vue.component(\'profile-edit-password-form\'', File::get($formJsPath));
    }
}
