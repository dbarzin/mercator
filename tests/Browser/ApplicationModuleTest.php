<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ApplicationModuleTest extends DuskTestCase
{
    public function testIndex()
    {

        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.application-modules.index'));
                $browser->waitForText("Mercator");
                $browser->assertRouteIs('admin.application-modules.index');
            });
        });
    }

    public function testView()
    {
        $admin = \App\User::find(1);
		$data = \App\ApplicationModule::first();
		if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/application-modules/" . $data->id);
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/application-modules/" . $data->id);
                $browser->assertSee($data->name);
            });
        });
    }

    public function testEdit()
    {
        $admin = \App\User::find(1);
        $data = \App\ApplicationModule::first();
		if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/application-modules/" . $data->id . "/edit");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/application-modules/" . $data->id . "/edit");
            });
        });
    }

    public function testCreate()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit("/admin/application-modules/create");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/application-modules/create");
            });        
        });
    }

}
