<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Cartographer;
use App\Models\Entity;
use App\Models\Relation;
use App\Services\Graph\EcosystemGraphBuilder;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EcosystemView extends Controller
{
    public const ALLOWED_PERIMETERS = ['All', 'Internes', 'Externes'];

    public const SANITIZED_PERIMETER = 'All';

    /*
    * Ecosystem View
    */
    public function generate(Request $request)
    {
        $allowed = Gate::allows('explore_access') || Cartographer::canAccessAny([Entity::class, Relation::class]);
        abort_if(! $allowed, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $perimeter = in_array($request->perimeter, $this::ALLOWED_PERIMETERS) ?
                   $request->perimeter : $this::SANITIZED_PERIMETER;
        $typeFilter = $request->type ??= 'All';

        $entitiesGroups = Cartographer::scopedQuery(Entity::query())->get()->groupBy('type');
        $entities = collect([]);
        $entityTypes = collect([]);
        $isTypeExists = false; /* sanitize type: si type inconnu pas d'entités */
        foreach ($entitiesGroups as $type => $entOfGroup) {
            $entities = $entities->concat($entOfGroup);
            if ($type != null) {
                $isTypeExists = $isTypeExists || ($type === $typeFilter);
                $entityTypes->push($type);
            }
        }

        $has_filter = false;
        if ($typeFilter !== 'All') {
            $has_filter = true;
            $entities = $isTypeExists ? $entitiesGroups[$typeFilter] : collect([]);
        }

        if ($perimeter !== 'All') {
            $has_filter = true;
            $entities = $entities
                ->filter(function ($item) use ($perimeter) {
                    return $perimeter === 'Externes' ?
                                       $item->isExternal() : ! $item->isExternal();
                });
        }

        $relations = Cartographer::scopedQuery(Relation::query())->orderBy('name')->get();
        if ($has_filter) {
            /**
             * Le "group by" semble résoudre les entités on doit travailler avec les ids ..
             */
            $ids = $entities->map(function ($item) {
                return $item->id;
            });
            $relations = $relations
                ->filter(function ($item) use ($ids) {
                    return $ids->contains($item->source_id) &&
                        $ids->contains($item->destination_id);
                });
        }

        $request->session()->put('perimeter', $perimeter);
        $request->session()->put('type', $typeFilter);

        $graphBuilder = new EcosystemGraphBuilder;

        return view('admin/reports/ecosystem')
            ->with('entityTypes', $entityTypes)
            ->with('entities', $entities)
            ->with('relations', $relations)
            ->with('dotSrc', $graphBuilder->buildDot($entities, $relations))
            ->with('imageManifest', $graphBuilder->imageManifest($entities));
    }
}
