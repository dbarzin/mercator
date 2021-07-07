<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PermissionsTest extends DuskTestCase
{
    public function testIndex()
    {

        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.permissions.index'));
                $browser->waitForText("Mercator");
                $browser->assertRouteIs('admin.permissions.index');
            });
        });
    }

    public function testView()
    {
        $admin = \App\User::find(1);
		$data = \DB::table('permissions')->first();
		if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/permissions/" . $data->id);
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/permissions/" . $data->id);
                $browser->assertSee($data->id);
                $browser->assertSee($data->title);
            });
        });
    }

    public function testEdit()
    {
        $admin = \App\User::find(1);
		$data = \DB::table('permissions')->first();
		if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/permissions/" . $data->id . "/edit");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/permissions/" . $data->id . "/edit");
            });
        });
    }

    public function testCreate()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit("/admin/permissions/create");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/permissions/create");
            });        
        });
    }

}

