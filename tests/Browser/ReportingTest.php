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

                $browser->visit(route('admin.report.maturity2'));
                $browser->assertRouteIs('admin.report.maturity2');
                $browser->assertSee('%');
                $browser->assertSee('#');

                $browser->visit(route('admin.report.maturity3'));
                $browser->assertRouteIs('admin.report.maturity3');
                $browser->assertSee('%');
                $browser->assertSee('#');
            });
        });
    }
}
