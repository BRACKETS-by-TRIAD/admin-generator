<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileGeneratorWithCustomModelNameTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    function profile_controller_should_be_generated_with_custom_model(){
        $filePath = base_path('app/Http/Controllers/Admin/Auth/ProfileController.php');

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
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{', File::get($filePath));
    }
}
