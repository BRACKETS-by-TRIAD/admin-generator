<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;

class DefaultAdminUserCrudGeneratorTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function all_files_should_be_generated_under_default_namespace(): void
    {
        $controllerPath = base_path('app/Http/Controllers/Admin/AdminUsersController.php');
        $indexRequestPath = base_path('app/Http/Requests/Admin/AdminUser/IndexAdminUser.php');
        $storePath = base_path('app/Http/Requests/Admin/AdminUser/StoreAdminUser.php');
        $updatePath = base_path('app/Http/Requests/Admin/AdminUser/UpdateAdminUser.php');
        $destroyPath = base_path('app/Http/Requests/Admin/AdminUser/DestroyAdminUser.php');
        $routesPath = base_path('routes/web.php');
        $indexPath = resource_path('views/admin/admin-user/index.blade.php');
        $listingJsPath = resource_path('js/admin/admin-user/Listing.js');
        $indexJsPath = resource_path('js/admin/admin-user/index.js');
        $elementsPath = resource_path('views/admin/admin-user/components/form-elements.blade.php');
        $createPath = resource_path('views/admin/admin-user/create.blade.php');
        $editPath = resource_path('views/admin/admin-user/edit.blade.php');
        $formJsPath = resource_path('js/admin/admin-user/Form.js');
        $factoryPath = base_path('database/factories/ModelFactory.php');

        $this->assertFileDoesNotExist($controllerPath);
        $this->assertFileDoesNotExist($indexRequestPath);
        $this->assertFileDoesNotExist($storePath);
        $this->assertFileDoesNotExist($updatePath);
        $this->assertFileDoesNotExist($destroyPath);
        $this->assertFileDoesNotExist($indexPath);
        $this->assertFileDoesNotExist($listingJsPath);
        $this->assertFileDoesNotExist($elementsPath);
        $this->assertFileDoesNotExist($createPath);
        $this->assertFileDoesNotExist($editPath);
        $this->assertFileDoesNotExist($formJsPath);
        $this->assertFileDoesNotExist($indexJsPath);


        $this->artisan('admin:generate:admin-user');

        $this->assertFileExists($controllerPath);
        $this->assertFileExists($indexRequestPath);
        $this->assertFileExists($storePath);
        $this->assertFileExists($updatePath);
        $this->assertFileExists($destroyPath);
        $this->assertFileExists($indexPath);
        $this->assertFileExists($listingJsPath);
        $this->assertFileExists($elementsPath);
        $this->assertFileExists($createPath);
        $this->assertFileExists($editPath);
        $this->assertFileExists($formJsPath);
        $this->assertFileExists($indexJsPath);
        $this->assertStringStartsWith('<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUser\DestroyAdminUser;
use App\Http\Requests\Admin\AdminUser\ImpersonalLoginAdminUser;
use App\Http\Requests\Admin\AdminUser\IndexAdminUser;
use App\Http\Requests\Admin\AdminUser\StoreAdminUser;
use App\Http\Requests\Admin\AdminUser\UpdateAdminUser;
use Brackets\AdminAuth\Models\AdminUser;
use Spatie\Permission\Models\Role;
use Brackets\AdminAuth\Activation\Facades\Activation;
use Brackets\AdminAuth\Services\ActivationService;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class AdminUsersController extends Controller', File::get($controllerPath));
        $this->assertStringStartsWith('<?php

namespace App\Http\Requests\Admin\AdminUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class IndexAdminUser extends FormRequest
{', File::get($indexRequestPath));
        $this->assertStringStartsWith('<?php

namespace App\Http\Requests\Admin\AdminUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StoreAdminUser extends FormRequest
{', File::get($storePath));
        $this->assertStringStartsWith('<?php

namespace App\Http\Requests\Admin\AdminUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateAdminUser extends FormRequest
{', File::get($updatePath));
        $this->assertStringStartsWith('<?php

namespace App\Http\Requests\Admin\AdminUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DestroyAdminUser extends FormRequest
{', File::get($destroyPath));
        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::middleware([\'auth:\' . config(\'admin-auth.defaults.guard\'), \'admin\'])->group(static function () {
    Route::prefix(\'admin\')->namespace(\'App\Http\Controllers\Admin\')->name(\'admin/\')->group(static function() {
        Route::prefix(\'admin-users\')->name(\'admin-users/\')->group(static function() {
            Route::get(\'/\',                                             \'AdminUsersController@index\')->name(\'index\');
            Route::get(\'/create\',                                       \'AdminUsersController@create\')->name(\'create\');
            Route::post(\'/\',                                            \'AdminUsersController@store\')->name(\'store\');
            Route::get(\'/{adminUser}/impersonal-login\',                 \'AdminUsersController@impersonalLogin\')->name(\'impersonal-login\');
            Route::get(\'/{adminUser}/edit\',                             \'AdminUsersController@edit\')->name(\'edit\');
            Route::post(\'/{adminUser}\',                                 \'AdminUsersController@update\')->name(\'update\');
            Route::delete(\'/{adminUser}\',                               \'AdminUsersController@destroy\')->name(\'destroy\');
            Route::get(\'/{adminUser}/resend-activation\',                \'AdminUsersController@resendActivationEmail\')->name(\'resendActivationEmail\');
        });
    });
});',
            File::get($routesPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($indexPath));
        $this->assertStringStartsWith('import AppListing from \'../app-components/Listing/AppListing\';

Vue.component(\'admin-user-listing\'', File::get($listingJsPath));
        $this->assertStringStartsWith('<div ', File::get($elementsPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($createPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($editPath));
        $this->assertStringStartsWith('import AppForm from \'../app-components/Form/AppForm\';

Vue.component(\'admin-user-form\'', File::get($formJsPath));
        $this->assertStringStartsWith('import \'./Listing\';', File::get($indexJsPath));
        $this->assertStringStartsWith('<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Brackets\AdminAuth\Models\AdminUser::class', File::get($factoryPath));
    }

}
