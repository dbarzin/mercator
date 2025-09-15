<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ReportingTest extends DuskTestCase
{
    /**
     * Maturity Levels testing
     *
     * @return void
     */
    public function test_maturity_levels()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);

                // Maturity levels
                $browser->visit(route('admin.report.maturity1'));
                $browser->assertRouteIs('admin.report.maturity1');
                $browser->assertSee('%');
                $browser->assertSee('#');
                $browser->assertDontSee('ErrorException');

                $browser->visit(route('admin.report.maturity2'));
                $browser->assertRouteIs('admin.report.maturity2');
                $browser->assertSee('%');
                $browser->assertDontSee('ErrorException');

                $browser->visit(route('admin.report.maturity3'));
                $browser->assertRouteIs('admin.report.maturity3');
                $browser->assertSee('%');
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function test_reports()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);

                $browser->visit(route('admin.report.entities'));
                $browser->assertDontSee('ErrorException');

                $browser->visit(route('admin.report.entities'));
                $browser->assertDontSee('ErrorException');

                $browser->visit(route('admin.report.applicationsByBlocks'));
                $browser->assertDontSee('ErrorException');

                $browser->visit(route('admin.report.logicalServerConfigs'));
                $browser->assertDontSee('ErrorException');

                $browser->visit(route('admin.report.physicalInventory'));
                $browser->assertDontSee('ErrorException');

                $browser->visit(route('admin.report.securityNeeds'));
                $browser->assertDontSee('ErrorException');

            });
        });
    }

    public function test_cartography_report()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.cartography'));
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function test_audit_reports()
    {
        $admin = \App\Models\User::find(1);
        retry($times = 5, function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.audit.maturity'));
                $browser->assertDontSee('ErrorException');

                $browser->visit(route('admin.audit.changes'));
                $browser->assertDontSee('ErrorException');

            });
        });
    }
}
