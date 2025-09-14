<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DomaineAdTest extends DuskTestCase
{
    public function test_index()
    {

        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.domaine-ads.index'));
                $browser->waitForText('Mercator');
                $browser->assertRouteIs('admin.domaine-ads.index');
            });
        });
    }

    public function test_view()
    {
        $admin = \App\Models\User::find(1);
        $data = \DB::table('domaine_ads')->first();
        if ($data != null) {
            retry($times = 5, function () use ($admin, $data) {
                $this->browse(function (Browser $browser) use ($admin, $data) {
                    $browser->loginAs($admin);
                    $browser->visit('/admin/domaine-ads/'.$data->id);
                    $browser->waitForText('Mercator');
                    $browser->assertPathIs('/admin/domaine-ads/'.$data->id);
                    $browser->assertSee($data->name);
                });
            });
        }
    }

    public function test_edit()
    {
        $admin = \App\Models\User::find(1);
        $data = \DB::table('domaine_ads')->first();
        if ($data != null) {
            retry($times = 5, function () use ($admin, $data) {
                $this->browse(function (Browser $browser) use ($admin, $data) {
                    $browser->loginAs($admin);
                    $browser->visit('/admin/domaine-ads/'.$data->id.'/edit');
                    $browser->waitForText('Mercator');
                    $browser->assertPathIs('/admin/domaine-ads/'.$data->id.'/edit');
                });
            });
        }
    }

    public function test_create()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit('/admin/domaine-ads/create');
                $browser->waitForText('Mercator');
                $browser->assertPathIs('/admin/domaine-ads/create');
            });
        });
    }
}
