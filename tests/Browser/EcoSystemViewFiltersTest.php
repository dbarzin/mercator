<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EcoSystemViewFiltersTest extends DuskTestCase
{
    public function testPerimeter() {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.ecosystem'));
                $browser->assertRouteIs('admin.report.view.ecosystem');
                $browser->assertSee('Mercator');
                $browser->assertSee('Toutes');
                $browser->assertSelected('perimeter', 'All');
                /*
                $digraph = $browser->element('#dot-input');
                $this->assertEquals(1, substr_count($digraph,'digraph'),"toto".$digraph);
                echo $digraph;
                $browser->with('#dot-input', function ($elt) use ($digraph){
                    $elt->assertSee('digraph',$digraph);
                });
                */


                $browser->select('perimeter', 'Internes');
                $browser->assertRouteIs('admin.report.view.ecosystem');
                $browser->assertSelected('perimeter', 'Internes');
                $browser->assertSee('Internes');

                $browser->select('perimeter', 'Externes');
                $browser->assertRouteIs('admin.report.view.ecosystem');
                $browser->assertSelected('perimeter', 'Externes');
                $browser->assertSee('Externes');
            });
        });
    }

    public function testEntityType() {

        $entity = \App\Entity::where('name','Acme corp.')->first();
        $this->assertTrue($entity!=null);
        $this->assertEquals('Producer',$entity->entity_type);

        $entity = \App\Entity::where('name','MegaNet System')->first();
        $this->assertTrue($entity!=null);
        $this->assertEquals('Producer',$entity->entity_type);

        $entity = \App\Entity::where('name','World company')->first();
        $this->assertTrue($entity!=null);
        $this->assertEquals('Producer',$entity->entity_type);

        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.ecosystem'));
                $browser->assertRouteIs('admin.report.view.ecosystem');
                $browser->assertSee('Mercator');
                $browser->assertSelected('perimeter', 'All');
                $browser->assertSee('MegaNet System');
                $browser->assertSee('Acme corp.');
                $browser->assertSee('World company');

                $browser->visit('/admin/report/ecosystem?entity_type=Producer');
                $browser->assertRouteIs('admin.report.view.ecosystem');
                $browser->assertSee('Mercator');
                $browser->assertSelected('perimeter', 'All');
                $browser->assertSee('Acme corp.');
                $browser->assertSee('MegaNet System');
                $browser->assertSee('World company');

                $browser->visit('/admin/report/ecosystem?entity_type=Revendeur');
                $browser->assertRouteIs('admin.report.view.ecosystem');
                $browser->assertSee('Mercator');
                $browser->assertSelected('perimeter', 'All');
                $browser->assertDontSee('Acme corp.');
                $browser->assertDontSee('MegaNet System');
                $browser->assertDontSee('World company');
                });
            });
        }

    public function testEntityFilterOnType() {
        $admin = \App\User::find(1);
        retry($times = 5,  function () use ($admin) {
            $this->browse(function (Browser $browser) use ($admin) {
                $browser->loginAs($admin);
                $browser->visit(route('admin.report.view.ecosystem'));
                $browser->assertRouteIs('admin.report.view.ecosystem');
                $browser->assertSee('Mercator');
                $browser->assertSelected('perimeter', 'All');
                $browser->assertSee('Tous les types');

                $browser->assertSelected('entity_type', 'All');


                $allEntities = DB::table('entities');
                $allTypes = $allEntities->groupBy('entity_type')->pluck('entity_type','entity_type');
                foreach($allTypes as $entity_type){
                    if ($entity_type ==null ) continue;

                    $browser->select('entity_type', $entity_type);

                    $browser->assertSee('Mercator');
                    $browser->assertSelected('perimeter', 'All');
                    $browser->with('#graph', function ($elt) use($entity_type){
                        foreach (DB::table('entities')->whereNull('deleted_at')->get() as $entity){
                            if ($entity->entity_type == $entity_type){
                                $elt->assertSee($entity->name);
                            } else{
                                $elt->assertDontSee($entity->name);
                            }
                        }
                    });
                }
                $browser->select('entity_type', 'All');

                $browser->assertSee('Mercator');
                foreach (DB::table('entities')->whereNull('deleted_at')->get() as $entity)
                    $browser->assertSee($entity->name);
            });
        });
    }
}
