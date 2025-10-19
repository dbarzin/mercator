<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CPEProduct;
use App\Models\CPEVendor;
use App\Models\CPEVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CPEImport extends Command
{
    protected $signature = 'mercator:cpe-import {cpe-dictionary-file}';

    protected $description = 'Import CPE dictionary file';

    protected array $vendors = [];

    protected array $products = [];

    protected array $versions = [];

    public function handle(): void
    {
        $this->info('CPEImport - Start.');
        $start = microtime(true);

        $file = $this->argument('cpe-dictionary-file');
        if (! file_exists($file)) {
            $this->error("File not found: {$file}");

            return;
        }

        // Clean DB
        DB::table('cpe_versions')->delete();
        DB::table('cpe_products')->delete();
        DB::table('cpe_vendors')->delete();

        // Précharger les éléments existants
        $this->vendors = [];
        $this->products = [];
        $this->versions = [];

        // Init lecteur XML
        $reader = new \XMLReader();
        if (! $reader->open($file)) {
            $this->error('Could not open XML file');

            return;
        }

        // count items
        $items = substr_count(file_get_contents($file), '<cpe-23:cpe23-item');
        $this->info("CPEImport - {$items} CPE items to import");

        // progress bar
        $this->output->progressStart($items);

        $counter = 0;

        while ($reader->read()) {
            if ($reader->nodeType === \XMLReader::ELEMENT && $reader->localName === 'cpe23-item') {
                $name = $reader->getAttribute('name');
                if ($name === null) {
                    continue;
                }

                $this->processItem($name);

                $counter++;
            }
        }

        $reader->close();

        // Progress done
        $this->output->progressFinish();

        $time = number_format(microtime(true) - $start, 2);
        $this->info("CPEImport - DONE in {$time} seconds. Total items: {$counter}");
    }

    protected function processItem(string $name): void
    {
        $this->output->progressAdvance();

        $value = explode(':', $name);
        if (count($value) < 6) {
            return;
        }

        [$unused, $cpeVersion, $part, $vendorName, $productName, $versionName] = $value;

        $vendorKey = "{$part}:{$vendorName}";
        $productKey = null;
        $versionKey = null;

        // VENDOR
        if (! isset($this->vendors[$vendorKey])) {
            $vendor = CPEVendor::withoutEvents(function () use ($part, $vendorName) {
                return CPEVendor::create(['part' => $part, 'name' => $vendorName]);
            });
            $this->vendors[$vendorKey] = $vendor;
        } else {
            $vendor = $this->vendors[$vendorKey];
        }

        // PRODUCT
        $productKey = "{$vendor->id}:{$productName}";
        if (! isset($this->products[$productKey])) {
            $product = CPEProduct::withoutEvents(function () use ($vendor, $productName) {
                return CPEProduct::create(['cpe_vendor_id' => $vendor->id, 'name' => $productName]);
            });
            $this->products[$productKey] = $product;
        } else {
            $product = $this->products[$productKey];
        }

        // VERSION
        $versionKey = "{$product->id}:{$versionName}";
        if (! isset($this->versions[$versionKey])) {
            CPEVersion::withoutEvents(function () use ($product, $versionName): void {
                CPEVersion::create(['cpe_product_id' => $product->id, 'name' => $versionName]);
            });
            $this->versions[$versionKey] = true;
        }
    }
}
