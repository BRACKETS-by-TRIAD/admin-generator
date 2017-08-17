<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DefaultProfileGeneratorTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    function all_files_should_be_generated_under_default_namespace(){
        $filePathController = base_path('App/Http/Controllers/Admin/ProfileController.php');
        $filePathRoute = base_path('routes/web.php');
        $editPathProfile = resource_path('views/admin/profile/edit-profile.blade.php');
        $formJsPathProfile = resource_path('assets/js/admin/profile-edit-profile/Form.js');
        $editPathPassword = resource_path('views/admin/profile/edit-password.blade.php');
        $formJsPathPassword = resource_path('assets/js/admin/profile-edit-password/Form.js');

        $this->assertFileNotExists($filePathController);
        $this->assertFileNotExists($editPathProfile);
        $this->assertFileNotExists($formJsPathProfile);
        $this->assertFileNotExists($editPathPassword);
        $this->assertFileNotExists($formJsPathPassword);

        $this->artisan('admin:generate:user:profile', [
        ]);

        $this->assertFileExists($filePathController);
        $this->assertFileExists($editPathProfile);
        $this->assertFileExists($formJsPathProfile);
        $this->assertFileExists($editPathPassword);
        $this->assertFileExists($formJsPathPassword);

        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{', File::get($filePathController));
        $this->assertStringStartsWith('<?php



/* Auto-generated profile routes */
Route::get(\'/admin/profile\',                                \'Admin\ProfileController@editProfile\')->name(\'admin/profile/edit\');
Route::post(\'/admin/profile\',                               \'Admin\ProfileController@updateProfile\')->name(\'admin/profile/update\');
Route::get(\'/admin/password\',                               \'Admin\ProfileController@editPassword\')->name(\'admin/password/edit\');
Route::post(\'/admin/password\',                              \'Admin\ProfileController@updatePassword\')->name(\'admin/password/update\');', File::get($filePathRoute));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')

@section(\'body\')

    <div class="container-xl">

        <div class="card">

            <profile-edit-profile-form', File::get($editPathProfile));
        $this->assertStringStartsWith('var base = require(\'../components/Form/Form\');

Vue.component(\'profile-edit-profile-form\'', File::get($formJsPathProfile));
        $this->assertStringStartsWith('@extends(\'brackets/admin::admin.layout.form\')

@section(\'body\')

    <div class="container-xl">

        <div class="card">

            <profile-edit-password-form', File::get($editPathPassword));
        $this->assertStringStartsWith('var base = require(\'../components/Form/Form\');

Vue.component(\'profile-edit-password-form\'', File::get($formJsPathPassword));
    }
}
