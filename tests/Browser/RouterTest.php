<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RouterTest extends DuskTestCase
{
    public function test_index()
    {

        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.routers.index'));
                $browser->waitForText('Mercator');
                $browser->assertRouteIs('admin.routers.index');
            });
        });
    }

    public function test_view()
    {
        $admin = \App\Models\User::find(1);
        $data = \DB::table('routers')->first();
        if ($data != null) {
            retry($times = 5, function () use ($admin, $data) {
                $this->browse(function (Browser $browser) use ($admin, $data) {
                    $browser->loginAs($admin);
                    $browser->visit('/admin/routers/'.$data->id);
                    $browser->waitForText('Mercator');
                    $browser->assertPathIs('/admin/routers/'.$data->id);
                    $browser->assertSee($data->name);
                });
            });
        }
    }

    public function test_edit()
    {
        $admin = \App\Models\User::find(1);
        $data = \DB::table('routers')->first();
        if ($data != null) {
            retry($times = 5, function () use ($admin, $data) {
                $this->browse(function (Browser $browser) use ($admin, $data) {
                    $browser->loginAs($admin);
                    $browser->visit('/admin/routers/'.$data->id.'/edit');
                    $browser->waitForText('Mercator');
                    $browser->assertPathIs('/admin/routers/'.$data->id.'/edit');
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
                $browser->visit('/admin/routers/create');
                $browser->waitForText('Mercator');
                $browser->assertPathIs('/admin/routers/create');
            });
        });
    }
}
