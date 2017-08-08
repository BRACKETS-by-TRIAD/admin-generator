<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileGeneratorWithCustomModelNameTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    function profile_controller_should_be_generated_with_custom_model(){
        $filePath = base_path('App/Http/Controllers/Admin/Auth/ProfileController.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user:profile', [
            '--controller-name' => 'Auth\\ProfileController',
            '--model-name' => 'App\\User',
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
    function profile_form_should_get_generated_with_custom_model_name(){
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
    function password_form_should_get_generated_with_custom_model_name(){
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
