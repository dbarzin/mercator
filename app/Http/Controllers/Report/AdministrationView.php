<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Mercator\Core\Models\AdminUser;
use Mercator\Core\Models\Annuaire;
use Mercator\Core\Models\DomaineAd;
use Mercator\Core\Models\ForestAd;
use Mercator\Core\Models\ZoneAdmin;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class AdministrationView extends Controller
{
    /**
     * Build the administration report view populated with zones, annuaires, forests, domains, and admin users.
     *
     * Aborts with HTTP 403 Forbidden if the current user is denied the 'reports_access' gate.
     *
     * @return \Illuminate\View\View The rendered 'admin/reports/administration' view with keys:
     *                               - 'zones' => collection of ZoneAdmin
     *                               - 'annuaires' => collection of Annuaire
     *                               - 'forests' => collection of ForestAd
     *                               - 'domains' => collection of DomaineAd
     *                               - 'adminUsers' => collection of AdminUser
     */
    public function generate(): View
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zones = ZoneAdmin::All();
        $annuaires = Annuaire::All();
        $forests = ForestAd::All();
        $domains = DomaineAd::All();
        $adminUsers = AdminUser::All();

        return view('admin/reports/administration')
            ->with('zones', $zones)
            ->with('annuaires', $annuaires)
            ->with('forests', $forests)
            ->with('domains', $domains)
            ->with('adminUsers', $adminUsers);
    }
}
