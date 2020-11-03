<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;

class DefaultProfileGeneratorTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function all_files_should_be_generated_under_default_namespace(): void
    {
        $filePathController = base_path('app/Http/Controllers/Admin/ProfileController.php');
        $filePathRoute = base_path('routes/web.php');
        $editPathProfile = resource_path('views/admin/profile/edit-profile.blade.php');
        $formJsPathProfile = resource_path('js/admin/profile-edit-profile/Form.js');
        $editPathPassword = resource_path('views/admin/profile/edit-password.blade.php');
        $formJsPathPassword = resource_path('js/admin/profile-edit-password/Form.js');
        $indexJsPathPassword = resource_path('js/admin/profile-edit-password/index.js');
        $bootstrapJsPath = resource_path('js/admin/index.js');

        $this->assertFileDoesNotExist($filePathController);
        $this->assertFileDoesNotExist($editPathProfile);
        $this->assertFileDoesNotExist($formJsPathProfile);
        $this->assertFileDoesNotExist($editPathPassword);
        $this->assertFileDoesNotExist($formJsPathPassword);
        $this->assertFileDoesNotExist($indexJsPathPassword);

        $this->artisan('admin:generate:admin-user:profile', [
        ]);

        $this->assertFileExists($filePathController);
        $this->assertFileExists($editPathProfile);
        $this->assertFileExists($formJsPathProfile);
        $this->assertFileExists($editPathPassword);
        $this->assertFileExists($formJsPathPassword);
        $this->assertFileExists($indexJsPathPassword);

        $this->assertStringStartsWith('<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{', File::get($filePathController));
        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::middleware([\'auth:\' . config(\'admin-auth.defaults.guard\'), \'admin\'])->group(static function () {
    Route::prefix(\'admin\')->namespace(\'App\Http\Controllers\Admin\')->name(\'admin/\')->group(static function() {
        Route::get(\'/profile\',                                      \'ProfileController@editProfile\')->name(\'edit-profile\');
        Route::post(\'/profile\',                                     \'ProfileController@updateProfile\')->name(\'update-profile\');
        Route::get(\'/password\',                                     \'ProfileController@editPassword\')->name(\'edit-password\');
        Route::post(\'/password\',                                    \'ProfileController@updatePassword\')->name(\'update-password\');
    });
});',
            File::get($filePathRoute));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')

@section(\'title\', trans(\'admin.admin-user.actions.edit_profile\'))

@section(\'body\')

    <div class="container-xl">

        <div class="card">

            <profile-edit-profile-form', File::get($editPathProfile));
        $this->assertStringStartsWith('import AppForm from \'../app-components/Form/AppForm\';

Vue.component(\'profile-edit-profile-form\'', File::get($formJsPathProfile));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')

@section(\'title\', trans(\'admin.admin-user.actions.edit_password\'))

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
