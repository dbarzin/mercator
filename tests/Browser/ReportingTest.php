<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ReportingTest extends DuskTestCase
{
    /**
     * Maturity Levels testing
     *
     * @return void
     */

    public function testMaturityLevels()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
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

    public function testReports()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
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

    public function testCartographyReport()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);

                $browser->visit(route('admin.report.cartography'));
                $browser->assertDontSee('ErrorException');
            });
        });
    }

    public function testAuditReports()
    {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
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
