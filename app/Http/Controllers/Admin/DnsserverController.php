<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDnsserverRequest;
use App\Http\Requests\StoreDnsserverRequest;
use App\Http\Requests\UpdateDnsserverRequest;
use App\Models\Cartographer;
use App\Models\Dnsserver;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class DnsserverController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('dnsserver_access') ? null : Cartographer::allowedIdsFor($user, Dnsserver::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $dnsservers = Dnsserver::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (Dnsserver::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.dnsservers.index', compact('dnsservers'));
    }

    public function create()
    {
        abort_if(Gate::denies('dnsserver_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type_list = Dnsserver::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.dnsservers.create', compact('type_list', 'attributes_list'));
    }

    public function store(StoreDnsserverRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        Dnsserver::create($request->all());

        return redirect()->route('admin.dnsservers.index');
    }

    public function edit(Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('edit-object', $dnsserver), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type_list = Dnsserver::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.dnsservers.edit', compact('dnsserver', 'type_list', 'attributes_list'));
    }

    public function update(UpdateDnsserverRequest $request, Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('edit-object', $dnsserver), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $dnsserver->update($request->all());

        return redirect()->route('admin.dnsservers.index');
    }

    public function show(Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('show-object', $dnsserver), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.dnsservers.show', compact('dnsserver'));
    }

    public function destroy(Dnsserver $dnsserver)
    {
        abort_if(Gate::denies('dnsserver_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dnsserver->delete();

        return redirect()->route('admin.dnsservers.index');
    }

    public function massDestroy(MassDestroyDnsserverRequest $request)
    {
        Dnsserver::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = Dnsserver::query()
            ->select('attributes')
            ->where('attributes', '<>', null)
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

        return array_unique($res);
    }
}
