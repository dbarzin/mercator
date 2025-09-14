<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ViewsTest extends DuskTestCase
{
    public function test_view_ecosystem()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.ecosystem'));
                $browser->assertRouteIs('admin.report.view.ecosystem');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function test_view_information_system()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.informaton-system'));
                $browser->assertRouteIs('admin.report.view.informaton-system');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function test_view_administration()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.administration'));
                $browser->assertRouteIs('admin.report.view.administration');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function test_view_applications()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.applications'));
                $browser->assertRouteIs('admin.report.view.applications');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function test_view_application_flows()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.application-flows'));
                $browser->assertRouteIs('admin.report.view.application-flows');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function test_view_logical_infrastructure()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.logical-infrastructure'));
                $browser->assertRouteIs('admin.report.view.logical-infrastructure');
                $browser->assertSee('Mercator');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function test_view_physical_infrastructure()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
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
