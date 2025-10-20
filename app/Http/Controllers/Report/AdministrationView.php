<?php


namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\Annuaire;
use App\Models\DomaineAd;
use App\Models\ForestAd;
use App\Models\ZoneAdmin;

class AdministrationView extends Controller
{
    public function generate()
    {
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
