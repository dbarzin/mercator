<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAdminUserRequest;
use App\Http\Requests\StoreAdminUserRequest;
use App\Http\Requests\UpdateAdminUserRequest;
use App\Models\AdminUser;
use App\Models\DomaineAd;
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

        // Get Attributes
        $attributes_list = AdminUser::select('attributes')
            ->whereNotNull('attributes')
            ->distinct()
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);
        $attributes_list = array_unique($res);

        return view('admin.adminUser.create', compact('domains', 'type_list', 'attributes_list'));
    }

    public function store(StoreAdminUserRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);
        $adminUser = AdminUser::create($request->all());

        return redirect()->route('admin.admin-users.index');
    }

    public function edit(AdminUser $adminUser)
    {
        abort_if(Gate::denies('admin_user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type_list = AdminUser::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $domains = DomaineAd::all()->sortBy('name')->pluck('name', 'id');

        // Get Attributes
        $attributes_list = AdminUser::select('attributes')
            ->whereNotNull('attributes')
            ->distinct()
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);
        $attributes_list = array_unique($res);

        return view(
            'admin.adminUser.edit',
            compact('type_list', 'domains', 'adminUser', 'attributes_list'),
        );
    }

    public function update(UpdateAdminUserRequest $request, AdminUser $adminUser)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);
        $adminUser->update($request->all());

        return redirect()->route('admin.admin-users.index');
    }

    public function show(AdminUser $adminUser)
    {
        abort_if(Gate::denies('admin_user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.adminUser.show', compact('adminUser'));
    }

    public function destroy(AdminUser $adminUser)
    {
        abort_if(Gate::denies('admin_user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adminUser->delete();

        return redirect()->route('admin.admin-users.index');
    }

    public function massDestroy(MassDestroyAdminUserRequest $request)
    {
        AdminUser::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
