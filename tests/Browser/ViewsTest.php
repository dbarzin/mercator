<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ViewsTest extends DuskTestCase
{
    public function testViewEcosystem()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.ecosystem'));
                $browser->assertRouteIs('admin.report.view.ecosystem');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function testViewInformationSystem()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.informaton-system'));
                $browser->assertRouteIs('admin.report.view.informaton-system');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
                });
        });
    }

    public function testViewAdministration()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.administration'));
                $browser->assertRouteIs('admin.report.view.administration');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function testViewApplications()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.applications'));
                $browser->assertRouteIs('admin.report.view.applications');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function testViewApplicationFlows()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.application-flows'));
                $browser->assertRouteIs('admin.report.view.application-flows');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function testViewLogicalInfrastructure()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.logical-infrastructure'));
                $browser->assertRouteIs('admin.report.view.logical-infrastructure');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function testViewPhysicalInfrastructure()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.physical-infrastructure'));
                $browser->assertRouteIs('admin.report.view.physical-infrastructure');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

}
