<?php

namespace App\Http\Controllers\Admin;

use App\AdminUser;
use App\DomaineAd;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAdminUSerRequest;
use App\Http\Requests\StoreAdminUserRequest;
use App\Http\Requests\UpdateAdminUserRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class AdminUserController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('admin_user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = AdminUser::orderBy('user_id')->get();

        return view('admin.adminUser.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('admin_user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type_list = AdminUser::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $domains = DomaineAd::all()->sortBy('name')->pluck('name', 'id');

        return view('admin.adminUser.create', compact('domains', 'type_list'));
    }

    public function store(StoreAdminUserRequest $request)
    {
        $request['local'] = $request->has('local');
        $request['privileged'] = $request->has('privileged');
        $adminUser = AdminUser::create($request->all());

        return redirect()->route('admin.admin-users.index');
    }

    public function edit(AdminUser $adminUser)
    {
        abort_if(Gate::denies('activity_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type_list = AdminUser::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $domains = DomaineAd::all()->sortBy('name')->pluck('name', 'id');

        return view(
            'admin.adminUser.edit',
            compact('type_list', 'domains', 'adminUser'),
        );
    }

    public function update(UpdateAdminUserRequest $request, AdminUser $adminUser)
    {
        $request['local'] = $request->has('local');
        $request['privileged'] = $request->has('privileged');
        $adminUser->update($request->all());

        return redirect()->route('admin.admin-users.index');
    }

    public function show(AdminUser $adminUser)
    {
        abort_if(Gate::denies('activity_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.adminUser.show', compact('adminUser'));
    }

    public function destroy(AdminUser $user)
    {
        abort_if(Gate::denies('admin_user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return redirect()->route('admin.adminUser.index');
    }

    public function massDestroy(MassDestroyAdminUserRequest $request)
    {
        AdminUser::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
