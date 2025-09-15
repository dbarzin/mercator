<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ClusterTest extends DuskTestCase
{
    public function test_index()
    {

        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.clusters.index'));
                $browser->waitForText('Mercator');
                $browser->assertRouteIs('admin.clusters.index');
            });
        });
    }

    public function test_view()
    {
        $admin = \App\Models\User::find(1);
        $data = \DB::table('clusters')->first();
        retry($times = 5, function () use ($admin, $data) {
            $this->browse(function (Browser $browser) use ($admin, $data) {
                $browser->loginAs($admin);
                $browser->visit('/admin/clusters/'.$data->id);
                $browser->waitForText('Mercator');
                $browser->assertPathIs('/admin/clusters/'.$data->id);
                $browser->assertSee($data->name);
            });
        });
    }

    public function test_edit()
    {
        $admin = \App\Models\User::find(1);
        $data = \DB::table('clusters')->first();
        retry($times = 5, function () use ($admin, $data) {
            $this->browse(function (Browser $browser) use ($admin, $data) {
                $browser->loginAs($admin);
                $browser->visit('/admin/clusters/'.$data->id.'/edit');
                $browser->waitForText('Mercator');
                $browser->assertPathIs('/admin/clusters/'.$data->id.'/edit');
            });
        });
    }

    public function test_create()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit('/admin/clusters/create');
                $browser->waitForText('Mercator');
                $browser->assertPathIs('/admin/clusters/create');
            });
        });
    }
}
