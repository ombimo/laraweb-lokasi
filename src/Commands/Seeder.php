<?php

namespace Ombimo\LarawebLokasi\Commands;

use Illuminate\Console\Command;
use Ombimo\LarawebLokasi\Models\LokasiProvinsi;
use Ombimo\LarawebLokasi\Models\LokasiKota;
use Ombimo\LarawebLokasi\Models\LokasiKecamatan;
use Ombimo\LarawebLokasi\Models\LokasiKelurahan;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class Seeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'md-lokasi:seeder {--R|rajaongkir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lokasi Seeder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rajaongkir = $this->option('rajaongkir');

        if (!$rajaongkir) {
            $this->info('seeder local');
            $data = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data.json');
            $data = json_decode($data);

            foreach ($data as $value) {
                $kode = explode('.', $value->kode);
                $db = null;
                switch (count($kode)) {
                    case 1:
                        $db = new LokasiProvinsi;
                        break;
                    case 2:
                        $db = new LokasiKota;
                        $db->provinsi_id = $kode[0];
                        break;
                    case 3:
                        $db = new LokasiKecamatan;
                        $db->kota_id = $kode[0].$kode[1];
                        break;
                    case 4:
                        $db = new LokasiKelurahan;
                        $db->kecamatan_id = $kode[0].$kode[1].$kode[2];
                        break;
                }

                if (!is_null($db)) {
                    $this->info(implode('', $kode) . ' - ' . $value->nama);
                    $db->nama = $value->nama;
                    $db->slug = Str::slug($value->nama);
                    $db->id = implode('', $kode);
                    $db->save();
                }
            }
        } else {
            $this->info('seeder rajaongkir');

            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'https://pro.rajaongkir.com/api/',
                'headers' => [
                    'key' => config('rajaongkir.api_key')
                ]
            ]);

            //seeder provinsi
            $response = $client->request('GET', 'province');
            if ($response->getStatusCode() == 200) {
                $body = json_decode($response->getBody());
                $result = $body->rajaongkir->results;

                $this->info('seeder provinsi');
                $bar = $this->output->createProgressBar(count($result));

                foreach ($result as $value) {
                    $db = LokasiProvinsi::firstOrNew([
                        'id' => $value->province_id
                    ]);
                    $db->id = $value->province_id;
                    $db->nama = $value->province;
                    $db->save();
                    $bar->advance();
                }
                $bar->finish();
                $this->info('');
            }


            //seeder kota
            $response = $client->request('GET', 'city');
            if ($response->getStatusCode() == 200) {
                $body = json_decode($response->getBody());
                $result = $body->rajaongkir->results;

                $this->info('seeder kota');
                $bar = $this->output->createProgressBar(count($result));

                foreach ($result as $value) {
                    $db = LokasiKota::firstOrNew([
                        'id' => $value->city_id
                    ]);
                    $db->id = $value->city_id;
                    $db->provinsi_id = $value->province_id;
                    $db->nama = $value->type .' '. $value->city_name;
                    $db->save();
                    $bar->advance();
                }
                $bar->finish();
                $this->info('');
            }

            //seeder kecamatan
            $dataKota = LokasiKota::get();
            $this->info('seeder kecamatan');
            $bar = $this->output->createProgressBar($dataKota->count());

            foreach ($dataKota as $kota) {
                $response = $client->request('GET', 'subdistrict?city=' . $kota->id);
                if ($response->getStatusCode() == 200) {
                    $body = json_decode($response->getBody());
                    $result = $body->rajaongkir->results;

                    foreach ($result as $value) {
                        $db = LokasiKecamatan::firstOrNew([
                            'id' => $value->subdistrict_id
                        ]);
                        $db->id = $value->subdistrict_id;
                        $db->kota_id = $value->city_id;
                        $db->nama = $value->subdistrict_name;
                        $db->save();
                    }
                }//end if
                $bar->advance();
            }//end foreach

            $bar->finish();
            $this->info('');
        }
    }
}
