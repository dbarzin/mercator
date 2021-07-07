<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AnnuaireTest extends DuskTestCase
{
    public function testIndex()
    {

        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.annuaires.index'));
                $browser->waitForText("Mercator");
                $browser->assertRouteIs('admin.annuaires.index');
            });
        });
    }

    public function testView()
    {
        $admin = \App\User::find(1);
		$data = \DB::table('annuaires')->first();
		if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/annuaires/" . $data->id);
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/annuaires/" . $data->id);
                $browser->assertSee($data->name);
            });
        });
    }

    public function testEdit()
    {
        $admin = \App\User::find(1);
		$data = \DB::table('annuaires')->first();
		if ($data!=null) 
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/annuaires/" . $data->id . "/edit");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/annuaires/" . $data->id . "/edit");
            });
        });
    }

    public function testCreate()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit("/admin/annuaires/create");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/annuaires/create");
            });        
        });
    }

}
