<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoLogicalServersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('logical_servers')->delete();

        \DB::table('logical_servers')->insert([
            0 => [
                'id' => 1,
                'name' => 'SRV-1',
                'description' => '<p>Description du serveur 1</p>',
                'net_services' => 'DNS, HTTP, HTTPS',
                'configuration' => '<p>Configuration du serveur 1</p>',
                'created_at' => '2020-07-12 18:57:42',
                'updated_at' => '2021-08-17 15:13:21',
                'deleted_at' => null,
                'operating_system' => 'Windows 3.1',
                'address_ip' => '10.10.1.1, 10.10.10.1',
                'cpu' => '2',
                'memory' => '8',
                'environment' => 'PROD',
                'disk' => 60,
                'install_date' => null,
                'update_date' => null,
            ],
            1 => [
                'id' => 2,
                'name' => 'SRV-2',
                'description' => '<p>Description du serveur 2</p>',
                'net_services' => 'HTTPS, SSH',
                'configuration' => '<p>Configuration par défaut</p>',
                'created_at' => '2020-07-30 12:00:16',
                'updated_at' => '2021-08-17 20:17:41',
                'deleted_at' => null,
                'operating_system' => 'Windows 10',
                'address_ip' => '10.50.1.2',
                'cpu' => '2',
                'memory' => '5',
                'environment' => 'PROD',
                'disk' => 100,
                'install_date' => null,
                'update_date' => null,
            ],
            2 => [
                'id' => 3,
                'name' => 'SRV-3',
                'description' => '<p>Description du serveur 3</p>',
                'net_services' => 'HTTP, HTTPS',
                'configuration' => null,
                'created_at' => '2021-08-26 16:33:03',
                'updated_at' => '2021-08-26 16:33:38',
                'deleted_at' => null,
                'operating_system' => 'Ubuntu 20.04',
                'address_ip' => '10.70.8.3',
                'cpu' => '4',
                'memory' => '16',
                'environment' => 'PROD',
                'disk' => 80,
                'install_date' => null,
                'update_date' => null,
            ],
            3 => [
                'id' => 4,
                'name' => 'SRV-42',
                'description' => '<p><i>The Ultimate Question of Life, the Universe and Everything</i></p>',
                'net_services' => null,
                'configuration' => '<p>Full configuration</p>',
                'created_at' => '2021-11-15 17:03:59',
                'updated_at' => '2022-03-20 12:39:54',
                'deleted_at' => null,
                'operating_system' => 'OS 42',
                'address_ip' => '10.0.0.42',
                'cpu' => '42',
                'memory' => '42 G',
                'environment' => 'PROD',
                'disk' => 42,
                'install_date' => null,
                'update_date' => null,
            ],
            4 => [
                'id' => 5,
                'name' => 'SRV-4',
                'description' => '<p>Description du serveur 4</p>',
                'net_services' => null,
                'configuration' => null,
                'created_at' => '2022-05-02 18:43:02',
                'updated_at' => '2022-05-02 18:49:34',
                'deleted_at' => null,
                'operating_system' => 'Ubunti 22.04 LTS',
                'address_ip' => '10.10.3.2',
                'cpu' => '4',
                'memory' => '2',
                'environment' => 'Dev',
                'disk' => null,
                'install_date' => '2022-05-01 20:47:41',
                'update_date' => '2022-05-02 20:47:47',
            ],
        ]);

    }
}
