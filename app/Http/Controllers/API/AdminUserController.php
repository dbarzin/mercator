<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAdminUserRequest;
use App\Http\Requests\StoreAdminUserRequest;
use App\Http\Requests\UpdateAdminUserRequest;
use Mercator\Core\Models\AdminUser;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class AdminUserController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('admin_user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = AdminUser::all();

        return response()->json($users);
    }

    public function store(StoreAdminUserRequest $request)
    {
        abort_if(Gate::denies('admin_user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adminUser = AdminUser::create($request->all());

        return response()->json($adminUser, 201);
    }

    public function show(AdminUser $adminUser)
    {
        abort_if(Gate::denies('admin_user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($adminUser);
    }

    public function update(UpdateAdminUserRequest $request, AdminUser $adminUser)
    {
        abort_if(Gate::denies('admin_user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adminUser->update($request->all());

        return response()->json();
    }

    public function destroy(AdminUser $adminUser)
    {
        abort_if(Gate::denies('admin_user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $adminUser->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyAdminUserRequest $request)
    {
        abort_if(Gate::denies('admin_user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        AdminUser::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
