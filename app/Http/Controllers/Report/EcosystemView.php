<?php

declare(strict_types=1);

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Relation;
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
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $perimeter = in_array($request->perimeter, $this::ALLOWED_PERIMETERS) ?
                   $request->perimeter : $this::SANITIZED_PERIMETER;
        $typeFilter = $request->entity_type ??= 'All';

        $entitiesGroups = Entity::All()->groupBy('entity_type');
        $entities = collect([]);
        $entityTypes = collect([]);
        $isTypeExists = false; /* sanitize entity_type: si type inconnu pas d'entités */
        foreach ($entitiesGroups as $entity_type => $entOfGroup) {
            $entities = $entities->concat($entOfGroup);
            if ($entity_type !== null) {
                $isTypeExists = $isTypeExists || ($entity_type === $typeFilter);
                $entityTypes->push($entity_type);
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
                                       $item->is_external : ! $item->is_external;
                });
        }

        $relations = Relation::All()->sortBy('name');
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
        $request->session()->put('entity_type', $typeFilter);

        return view('admin/reports/ecosystem')
            ->with('entityTypes', $entityTypes)
            ->with('entities', $entities)
            ->with('relations', $relations);
    }
}
