<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserCrudGeneratorWithGenerateModelTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_model_name_should_auto_generate_from_table_name_if_required(){
        $filePath = base_path('app/Models/User.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user', [
            '--generate-model' => true
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Models;

use Brackets\AdminAuth\Auth\Activations\CanActivate;
use Brackets\AdminAuth\Contracts\Auth\CanActivate as CanActivateContract;
use Brackets\AdminAuth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements CanActivateContract
{', File::get($filePath));
    }

    /** @test */
    function user_model_name_should_use_custom_name_if_required(){
        $filePath = base_path('app/Models/Auth/User.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user', [
            '--model-name' => 'Auth\\User',
            '--generate-model' => true
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Models\Auth;

use Brackets\AdminAuth\Auth\Activations\CanActivate;
use Brackets\AdminAuth\Contracts\Auth\CanActivate as CanActivateContract;
use Brackets\AdminAuth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements CanActivateContract
{', File::get($filePath));
    }

    /** @test */
    function user_model_name_should_use_custom_name_outside_default_folder_if_required(){
        $filePath = base_path('app/Auth/User.php');

        $this->assertFileNotExists($filePath);

        $this->artisan('admin:generate:user', [
            '--model-name' => 'App\\Auth\\User',
            '--generate-model' => true
        ]);

        $this->assertFileExists($filePath);
        $this->assertStringStartsWith('<?php namespace App\Auth;

use Brackets\AdminAuth\Auth\Activations\CanActivate;
use Brackets\AdminAuth\Contracts\Auth\CanActivate as CanActivateContract;
use Brackets\AdminAuth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements CanActivateContract
{', File::get($filePath));
    }

}
