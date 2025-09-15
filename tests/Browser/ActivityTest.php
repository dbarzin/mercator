<?php

namespace Tests\Browser;

use App\Models\Activity;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ActivityTest extends DuskTestCase
{
    protected function withAdminBrowser(callable $callback)
    {
        $admin = User::find(1);
        retry(5, function () use ($admin, $callback) {
            $this->browse(function (Browser $browser) use ($admin, $callback) {
                $browser->loginAs($admin);
                $callback($browser);
            });
        }, 100);
    }

    public function test_can_visit_activity_index()
    {
        $this->withAdminBrowser(function (Browser $browser) {
            $browser->visit(route('admin.activities.index'))
                ->waitForText('Mercator')
                ->assertRouteIs('admin.activities.index');
        });
    }

    public function test_can_view_activity()
    {
        $activity = Activity::first();
        if (! $activity) {
            $this->markTestSkipped('Aucune activité disponible pour le test de visualisation.');
        }

        $this->withAdminBrowser(function (Browser $browser) use ($activity) {
            $browser->visit("/admin/activities/{$activity->id}")
                ->waitForText('Mercator')
                ->assertPathIs("/admin/activities/{$activity->id}")
                ->assertSee($activity->name);
        });
    }

    public function test_can_edit_activity()
    {
        $activity = Activity::first();
        if (! $activity) {
            $this->markTestSkipped('Aucune activité disponible pour le test d’édition.');
        }

        $this->withAdminBrowser(function (Browser $browser) use ($activity) {
            $browser->visit("/admin/activities/{$activity->id}/edit")
                ->waitForText('Mercator')
                ->assertPathIs("/admin/activities/{$activity->id}/edit");
        });
    }

    public function test_can_open_create_activity_page()
    {
        $this->withAdminBrowser(function (Browser $browser) {
            $browser->visit('/admin/activities/create')
                ->waitForText('Mercator')
                ->assertPathIs('/admin/activities/create');
        });
    }
}
