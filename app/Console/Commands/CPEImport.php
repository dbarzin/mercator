<?php

namespace App\Console\Commands;

use App\CPEProduct;
use App\CPEVendor;
use App\CPEVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CPEImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mercator:cpe-import {cpe-dictionary-file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import CPE dictionary file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('CPEImport - Start.');

        $start = microtime(true);

        // $file = "storage/official-cpe-dictionary_v2.3.xml";
        $file = $this->argument('cpe-dictionary-file');
        if ($file === null) {
            $this->error('dictionary file must be specified');
            return;
        }
        if (! file_exists($file)) {
            $this->error('dictionary file not found');
            return;
        }

        // Delete all previous data
        DB::table('cpe_versions')->delete();
        DB::table('cpe_products')->delete();
        DB::table('cpe_vendors')->delete();

        // count items
        $items = substr_count(file_get_contents($file), '<cpe-23:cpe23-item');
        $this->info("CPEImport - {$items} CPE items to import");

        // progress bar
        $this->output->progressStart($items);

        // Start parsing
        $this->xml_parser = xml_parser_create();
        xml_set_object($this->xml_parser, $this);

        xml_set_element_handler($this->xml_parser, 'startElement', 'endElement');
        $fp = fopen($file, 'r');
        if (! $fp) {
            $this->error('could not open XML input');
            return;
        }

        while ($data = fread($fp, 4096)) {
            if (! xml_parse($this->xml_parser, $data, feof($fp))) {
                $this->error("XML error: {xml_error_string(xml_get_error_code({$xml_parser}))} at line {xml_get_current_line_number({$xml_parser})}");
                return;
            }
        }
        xml_parser_free($this->xml_parser);

        // Prograss done
        $this->output->progressFinish();

        // Log time
        $end = microtime(true);
        $time = number_format($end - $start, 2);
        $this->info('CPEImport - elapsed time: ', $time, ' seconds');

        // Done
        $this->info('CPEImport - DONE.');
    }

    public function startElement($parser, $name, $attribs)
    {
        if ($name === 'CPE-23:CPE23-ITEM') {
            $this->output->progressAdvance();

            $value = explode(':', $attribs['NAME']);
            // $this->info($value[2] . " " . $value[3] . ' ' . $value[4] . ' ' . $value[5]);

            // check vendor exixts
            $vendor = DB::table('cpe_vendors')
                ->where('part', '=', $value[2])
                ->where('name', '=', $value[3])
                ->get()->first();
            // add vendor
            if ($vendor === null) {
                $vendor = CPEVendor::create(['part' => $value[2], 'name' => $value[3]]);
            }

            // check product exists
            $product = DB::table('cpe_products')
                ->where('cpe_vendor_id', '=', $vendor->id)
                ->where('name', '=', $value[4])
                ->get()->first();
            // add product
            if ($product === null) {
                $product = CPEProduct::create(['cpe_vendor_id' => $vendor->id, 'name' => $value[4]]);
            }

            // check version exists
            $version = DB::table('cpe_versions')
                ->where('cpe_product_id', '=', $product->id)
                ->where('name', '=', $value[5])
                ->get()->first();
            // Add version
            if ($version === null) {
                CPEVersion::create(['cpe_product_id' => $product->id, 'name' => $value[5]]);
            }
        }
    }

    public function endElement($parser, $name)
    {
    }
}
