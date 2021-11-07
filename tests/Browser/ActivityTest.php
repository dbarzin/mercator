<?php
namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ActivityTest extends DuskTestCase
{
    public function testIndex()
    {

        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                echo $admin->id . " " . $admin->name . "\n";
                $browser->loginAs($admin);
                $browser->visit(route('admin.activities.index'));
                $browser->waitForText("Mercator");
                $browser->assertRouteIs('admin.activities.index');
            });
        });
    }

    public function testView()
    {
        $admin = \App\User::find(1);
        $data = \App\Activity::first();
        if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/activities/" . $data->id);
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/activities/" . $data->id);
                $browser->assertSee($data->name);
            });
        });
    }

    public function testEdit()
    {
        $admin = \App\User::find(1);
        $data = \App\Activity::first();
        if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/activities/" . $data->id . "/edit");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/activities/" . $data->id . "/edit");
            });
        });
    }

    public function testCreate()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit("/admin/activities/create");                
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/activities/create");
            });        
        });
    }

}
