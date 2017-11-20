@php echo "<?php"
@endphp namespace {{ $controllerNamespace }};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public $user;

    public function __construct()
    {
        // TODO add authorization
    }

    /**
     * Get logged user before each method
     *
     * @param Request $request
     */
    protected function setUser($request) {
        if (empty($request->user())) {
            abort(404, 'User not found');
        }

        $this->user = $request->user();
    }

    /**
     * Show the form for editing logged user profile.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function editProfile(Request $request)
    {
        $this->setUser($request);

        return view('admin.profile.edit-profile', [
            'user' => $this->user,
        ]);
    }
@php
    $columnsProfile = $columns->reject(function($column) {
        return in_array($column['name'], ['password', 'activated', 'forbidden']);
    });
@endphp

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response|array
     */
    public function updateProfile(Request $request)
    {
        $this->setUser($request);
        $user = $this->user;

        // Validate the request
        $this->validate($request, [
            @foreach($columnsProfile as $column)'{{ $column['name'] }}' => [{!! implode(', ', (array) $column['serverUpdateRules']) !!}],
            @endforeach

        ]);

        // Sanitize input
        $sanitized = $request->only([
            @foreach($columnsProfile as $column)'{{ $column['name'] }}',
            @endforeach

        ]);

        // Update changed values {{ $modelBaseName }}
        $this->user->update($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/profile'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/profile');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function editPassword(Request $request)
    {
        $this->setUser($request);

        return view('admin.profile.edit-password', [
            'user' => $this->user,
        ]);
    }

@php
    $columnsPassword = $columns->reject(function($column) {
        return !in_array($column['name'], ['password']);
    });
@endphp

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response|array
     */
    public function updatePassword(Request $request)
    {
        $this->setUser($request);
        $user = $this->user;

        // Validate the request
        $this->validate($request, [
            @foreach($columnsPassword as $column)'{{ $column['name'] }}' => [{!! implode(', ', (array) $column['serverUpdateRules']) !!}],
            @endforeach

        ]);

        // Sanitize input
        $sanitized = $request->only([
            @foreach($columnsPassword as $column)'{{ $column['name'] }}',
            @endforeach

        ]);

        //Modify input, set hashed password
        $sanitized['password'] = Hash::make($sanitized['password']);

        // Update changed values {{ $modelBaseName }}
        $this->user->update($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/password'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/password');
    }

}
