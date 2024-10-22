<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DhcpServerTest extends DuskTestCase
{
    public function testIndex()
    {
/* Deprecated
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.dhcp-servers.index'));
                $browser->waitForText("Mercator");
                $browser->assertRouteIs('admin.dhcp-servers.index');
            });
        });
*/
    }

    public function testView()
    {
/* Deprecated
        $admin = \App\User::find(1);
		$data = \DB::table('dhcp_servers')->first();
		if ($data!=null)
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/dhcp-servers/" . $data->id);
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/dhcp-servers/" . $data->id);
                $browser->assertSee($data->name);
            });
        });
*/
    }

    public function testEdit()
    {
/* Deprecated
        $admin = \App\User::find(1);
		$data = \DB::table('dhcp_servers')->first();
		if ($data!=null)
        retry($times = 5,  function () use ($admin,$data) {
            $this->browse(function (Browser $browser) use ($admin,$data) {
                $browser->loginAs($admin);
                $browser->visit("/admin/dhcp-servers/" . $data->id . "/edit");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/dhcp-servers/" . $data->id . "/edit");
            });
        });
*/
    }

    public function testCreate()
    {
/* Deprecated
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit("/admin/dhcp-servers/create");
                $browser->waitForText("Mercator");
                $browser->assertPathIs("/admin/dhcp-servers/create");
            });
        });
*/
    }

}
