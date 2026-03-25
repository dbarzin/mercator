<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DependencyController extends Controller {

    public function index(Request $request) {

        abort_if(Gate::denies('explore_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.reports.dependency');
    }
}
