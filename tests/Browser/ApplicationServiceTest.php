<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ApplicationServiceTest extends DuskTestCase
{
    public function testIndex()
    {

        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.application-services.index'));
                $browser->waitForText("Mercator");
                $browser->assertRouteIs('admin.application-services.index');
            });
        });
    }

    public function testView()
    {
        $admin = \App\User::find(1);
        $data = \App\ApplicationService::first();
        if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/application-services/" . $data->id);
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/application-services/" . $data->id);
                // $browser->assertSee($data->name);
            });
        });
    }

    public function testEdit()
    {
        $admin = \App\User::find(1);
        $data = \App\ApplicationService::first();
        if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/application-services/" . $data->id . "/edit");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/application-services/" . $data->id . "/edit");
            });
        });
    }

    public function testCreate()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit("/admin/application-services/create");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/application-services/create");
            });        
        });
    }

}
