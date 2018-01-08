<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DefaultProfileGeneratorTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    function all_files_should_be_generated_under_default_namespace(){
        $filePathController = base_path('app/Http/Controllers/Admin/ProfileController.php');
        $filePathRoute = base_path('routes/web.php');
        $editPathProfile = resource_path('views/admin/profile/edit-profile.blade.php');
        $formJsPathProfile = resource_path('assets/admin/js/profile-edit-profile/Form.js');
        $editPathPassword = resource_path('views/admin/profile/edit-password.blade.php');
        $formJsPathPassword = resource_path('assets/admin/js/profile-edit-password/Form.js');
        $indexJsPathPassword = resource_path('assets/admin/js/profile-edit-password/index.js');
		$bootstrapJsPath = resource_path('assets/admin/js/index.js');

        $this->assertFileNotExists($filePathController);
        $this->assertFileNotExists($editPathProfile);
        $this->assertFileNotExists($formJsPathProfile);
        $this->assertFileNotExists($editPathPassword);
        $this->assertFileNotExists($formJsPathPassword);
		$this->assertFileNotExists($indexJsPathPassword);

        $this->artisan('admin:generate:user:profile', [
        ]);

        $this->assertFileExists($filePathController);
        $this->assertFileExists($editPathProfile);
        $this->assertFileExists($formJsPathProfile);
        $this->assertFileExists($editPathPassword);
        $this->assertFileExists($formJsPathPassword);
		$this->assertFileExists($indexJsPathPassword);

        $this->assertStringStartsWith('<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{', File::get($filePathController));
        $this->assertStringStartsWith('<?php



/* Auto-generated profile routes */
Route::middleware([\'admin\'])->group(function () {
    Route::get(\'/admin/profile\',                                \'Admin\ProfileController@editProfile\');
    Route::post(\'/admin/profile\',                               \'Admin\ProfileController@updateProfile\');
    Route::get(\'/admin/password\',                               \'Admin\ProfileController@editPassword\');
    Route::post(\'/admin/password\',                              \'Admin\ProfileController@updatePassword\');', File::get($filePathRoute));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')

@section(\'title\', trans(\'admin.user.actions.edit_profile\'))

@section(\'body\')

    <div class="container-xl">

        <div class="card">

            <profile-edit-profile-form', File::get($editPathProfile));
        $this->assertStringStartsWith('import AppForm from \'../app-components/Form/AppForm\';

Vue.component(\'profile-edit-profile-form\'', File::get($formJsPathProfile));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')

@section(\'title\', trans(\'admin.user.actions.edit_password\'))

@section(\'body\')

    <div class="container-xl">

        <div class="card">

            <profile-edit-password-form', File::get($editPathPassword));
        $this->assertStringStartsWith('import AppForm from \'../app-components/Form/AppForm\';

Vue.component(\'profile-edit-password-form\'', File::get($formJsPathPassword));
        $this->assertStringStartsWith("import './profile-edit-profile';
import './profile-edit-password';", File::get($bootstrapJsPath));

		$this->assertStringStartsWith("import './Form';\n", File::get($indexJsPathPassword));
    }
}
