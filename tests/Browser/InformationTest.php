<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class InformationTest extends DuskTestCase
{
    public function testIndex()
    {

        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.information.index'));
                $browser->waitForText("Mercator");
                $browser->assertRouteIs('admin.information.index');
            });
        });
    }

    public function testView()
    {
        $admin = \App\User::find(1);
		$data = \DB::table('information')->first();
		if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/information/" . $data->id);
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/information/" . $data->id);
                $browser->assertSee($data->name);
            });
        });
    }

    public function testEdit()
    {
        $admin = \App\User::find(1);
		$data = \DB::table('information')->first();
		if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/information/" . $data->id . "/edit");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/information/" . $data->id . "/edit");
            });
        });
    }

    public function testCreate()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit("/admin/information/create");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/information/create");
            });        
        });
    }

}

