<?php

namespace Brackets\AdminGenerator\Tests\Feature\Users;

use Brackets\AdminGenerator\Tests\UserTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;

class DefaultUserCrudGeneratorTest extends UserTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function all_files_should_be_generated_under_default_namespace(): void
    {
        $controllerPath = base_path('app/Http/Controllers/Admin/UsersController.php');
        $indexRequestPath = base_path('app/Http/Requests/Admin/User/IndexUser.php');
        $storePath = base_path('app/Http/Requests/Admin/User/StoreUser.php');
        $updatePath = base_path('app/Http/Requests/Admin/User/UpdateUser.php');
        $destroyPath = base_path('app/Http/Requests/Admin/User/DestroyUser.php');
        $routesPath = base_path('routes/web.php');
        $indexPath = resource_path('views/admin/user/index.blade.php');
        $listingJsPath = resource_path('js/admin/user/Listing.js');
        $indexJsPath = resource_path('js/admin/user/index.js');
        $elementsPath = resource_path('views/admin/user/components/form-elements.blade.php');
        $createPath = resource_path('views/admin/user/create.blade.php');
        $editPath = resource_path('views/admin/user/edit.blade.php');
        $formJsPath = resource_path('js/admin/user/Form.js');
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

        $this->artisan('admin:generate:user');

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
use App\Http\Requests\Admin\User\DestroyUser;
use App\Http\Requests\Admin\User\IndexUser;
use App\Http\Requests\Admin\User\StoreUser;
use App\Http\Requests\Admin\User\UpdateUser;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class UsersController extends Controller', File::get($controllerPath));
        $this->assertStringStartsWith('<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class IndexUser extends FormRequest
{', File::get($indexRequestPath));
        $this->assertStringStartsWith('<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
{', File::get($storePath));
        $this->assertStringStartsWith('<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
{', File::get($updatePath));
        $this->assertStringStartsWith('<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DestroyUser extends FormRequest
{', File::get($destroyPath));
        $this->assertStringStartsWith('<?php



/* Auto-generated admin routes */
Route::middleware([\'auth:\' . config(\'admin-auth.defaults.guard\'), \'admin\'])->group(static function () {
    Route::prefix(\'admin\')->namespace(\'App\Http\Controllers\Admin\')->name(\'admin/\')->group(static function() {
        Route::prefix(\'users\')->name(\'users/\')->group(static function() {
            Route::get(\'/\',                                             \'UsersController@index\')->name(\'index\');
            Route::get(\'/create\',                                       \'UsersController@create\')->name(\'create\');
            Route::post(\'/\',                                            \'UsersController@store\')->name(\'store\');
            Route::get(\'/{user}/edit\',                                  \'UsersController@edit\')->name(\'edit\');
            Route::post(\'/{user}\',                                      \'UsersController@update\')->name(\'update\');
            Route::delete(\'/{user}\',                                    \'UsersController@destroy\')->name(\'destroy\');
            Route::get(\'/{user}/resend-activation\',                     \'UsersController@resendActivationEmail\')->name(\'resendActivationEmail\');
        });
    });
});',
            File::get($routesPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($indexPath));
        $this->assertStringStartsWith('import AppListing from \'../app-components/Listing/AppListing\';

Vue.component(\'user-listing\'', File::get($listingJsPath));
        $this->assertStringStartsWith('<div ', File::get($elementsPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($createPath));
        $this->assertStringStartsWith('@extends(\'brackets/admin-ui::admin.layout.default\')', File::get($editPath));
        $this->assertStringStartsWith('import AppForm from \'../app-components/Form/AppForm\';

Vue.component(\'user-form\'', File::get($formJsPath));
        $this->assertStringStartsWith('import \'./Listing\';', File::get($indexJsPath));
        $this->assertStringStartsWith('<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class', File::get($factoryPath));
    }
}
