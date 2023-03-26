<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpe_vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->char('part', 1);
            $table->string('name');
        });

        Schema::table('cpe_vendors', function (Blueprint $table) {
            $table->unique(['part','name']);
        });

        Schema::create('cpe_products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cpe_vendor_id')->index('cpe_product_fk_1485479');
            $table->string('name');
        });

        Schema::table('cpe_products', function (Blueprint $table) {
            $table->foreign('cpe_vendor_id', 'cpe_vendor_fk_1454431')->references('id')->on('cpe_vendors')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->unique(['cpe_vendor_id', 'name']);
        });

        Schema::create('cpe_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cpe_product_id')->index('cpe_version_fk_1485479');
            $table->string('name');
        });

        Schema::table('cpe_versions', function (Blueprint $table) {
            $table->foreign('cpe_product_id', 'cpe_product_fk_1447431')->references('id')->on('cpe_products')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->unique(['cpe_product_id', 'name']);
        });

        Schema::table('m_applications', function (Blueprint $table) {
            $table->string('vendor')->after("description")->nullable();
            $table->string('product')->after("vendor")->nullable();
        });

        Schema::table('physical_switches', function (Blueprint $table) {
            $table->string('vendor')->after("description")->nullable();
            $table->string('product')->after("vendor")->nullable();
            $table->string('version')->after("product")->nullable();
        });

        Schema::table('physical_routers', function (Blueprint $table) {
            $table->string('vendor')->after("description")->nullable();
            $table->string('product')->after("vendor")->nullable();
            $table->string('version')->after("product")->nullable();
        });

        Schema::table('peripherals', function (Blueprint $table) {
            $table->string('vendor')->after("description")->nullable();
            $table->string('product')->after("vendor")->nullable();
            $table->string('version')->after("product")->nullable();
        });

        Schema::table('wifi_terminals', function (Blueprint $table) {
            $table->string('vendor')->after("description")->nullable();
            $table->string('product')->after("vendor")->nullable();
            $table->string('version')->after("product")->nullable();
        });

        Schema::table('phones', function (Blueprint $table) {
            $table->string('vendor')->after("description")->nullable();
            $table->string('product')->after("vendor")->nullable();
            $table->string('version')->after("product")->nullable();
        });

        Schema::table('security_devices', function (Blueprint $table) {
            $table->string('vendor')->after("description")->nullable();
            $table->string('product')->after("vendor")->nullable();
            $table->string('version')->after("product")->nullable();
        });

        Schema::table('workstations', function (Blueprint $table) {
            $table->string('vendor')->after("description")->nullable();
            $table->string('product')->after("vendor")->nullable();
            $table->string('version')->after("product")->nullable();
        });

        Schema::table('physical_servers', function (Blueprint $table) {
            $table->string('vendor')->after("description")->nullable();
            $table->string('product')->after("vendor")->nullable();
            $table->string('version')->after("product")->nullable();
        });

        Schema::table('storage_devices', function (Blueprint $table) {
            $table->string('vendor')->after("description")->nullable();
            $table->string('product')->after("vendor")->nullable();
            $table->string('version')->after("product")->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpe_versions');
        Schema::dropIfExists('cpe_products');
        Schema::dropIfExists('cpe_vendors');

        Schema::table('m_applications', function (Blueprint $table) {
            $table->dropColumn('vendor');
            $table->dropColumn('product');
        });

        Schema::table('physical_switches', function (Blueprint $table) {
            $table->dropColumn('vendor');
            $table->dropColumn('product');
            $table->dropColumn('version');
        });

        Schema::table('physical_routers', function (Blueprint $table) {
            $table->dropColumn('vendor');
            $table->dropColumn('product');
            $table->dropColumn('version');
        });

        Schema::table('peripherals', function (Blueprint $table) {
            $table->dropColumn('vendor');
            $table->dropColumn('product');
            $table->dropColumn('version');
        });

        Schema::table('wifi_terminals', function (Blueprint $table) {
            $table->dropColumn('vendor');
            $table->dropColumn('product');
            $table->dropColumn('version');
        });

        Schema::table('phones', function (Blueprint $table) {
            $table->dropColumn('vendor');
            $table->dropColumn('product');
            $table->dropColumn('version');
        });

        Schema::table('security_devices', function (Blueprint $table) {
            $table->dropColumn('vendor');
            $table->dropColumn('product');
            $table->dropColumn('version');
        });

        Schema::table('workstations', function (Blueprint $table) {
            $table->dropColumn('vendor');
            $table->dropColumn('product');
            $table->dropColumn('version');
        });

        Schema::table('physical_servers', function (Blueprint $table) {
            $table->dropColumn('vendor');
            $table->dropColumn('product');
            $table->dropColumn('version');
        });

        Schema::table('storage_devices', function (Blueprint $table) {
            $table->dropColumn('vendor');
            $table->dropColumn('product');
            $table->dropColumn('version');
        });
    }
}
