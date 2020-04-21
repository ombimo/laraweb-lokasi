<?php

namespace Ombimo\LarawebLokasi\Commands;

use Illuminate\Console\Command;
use Ombimo\LarawebLokasi\Models\LokasiProvinsi;
use Ombimo\LarawebLokasi\Models\LokasiKota;
use Ombimo\LarawebLokasi\Models\LokasiKecamatan;
use Ombimo\LarawebLokasi\Models\LokasiKelurahan;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        $bufferProvinsi = [];
        $bufferKota = [];
        $bufferKecamatan = [];
        $bufferKelurahan = [];

        if (!$rajaongkir) {
            $this->info('seeder local');

            $first = true;
            $doInsert = false;
            $i = 0;
            $bufferSize = 2000;

            if (($handle = fopen(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data.csv', "r")) !== FALSE) {
                while (($data = fgetcsv($handle)) !== FALSE) {
                    $i++;
                    $kode = explode('.', $data[0]);
                    $nama = $data[1];
                    if (!empty($kode) && !empty($nama)) {
                        switch (count($kode)) {
                            case 1:
                                $bufferProvinsi[] = [
                                    'id' => implode('', $kode),
                                    'nama' => $nama,
                                    'slug' => Str::slug($nama),
                                ];
                                if ($first) {
                                    $first = false;
                                } else {
                                    $doInsert = true;
                                }
                                break;
                            case 2:
                                $bufferKota[] = [
                                    'id' => implode('', $kode),
                                    'nama' => $nama,
                                    'slug' => Str::slug($nama),
                                    'provinsi_id' => $kode[0],
                                ];
                                break;
                            case 3:
                                $bufferKecamatan[] = [
                                    'id' => implode('', $kode),
                                    'nama' => $nama,
                                    'slug' => Str::slug($nama),
                                    'kota_id' => $kode[0].$kode[1],
                                ];
                                break;
                            case 4:
                                $bufferKelurahan[] = [
                                    'id' => implode('', $kode),
                                    'nama' => $nama,
                                    'slug' => Str::slug($nama),
                                    'kecamatan_id' => $kode[0].$kode[1].$kode[2],
                                ];
                                break;
                        }
                    }

                    if ($i > 1000) {
                        $i = 0;

                        if (!empty($bufferProvinsi)) {
                            LokasiProvinsi::insert($bufferProvinsi);
                            $bufferProvinsi = [];
                        }
                        if (!empty($bufferKota)) {
                            LokasiKota::insert($bufferKota);
                            $bufferKota = [];
                        }
                        if (!empty($bufferKecamatan)) {
                            LokasiKecamatan::insert($bufferKecamatan);
                            $bufferKecamatan = [];
                        }
                        if (!empty($bufferKelurahan)) {
                            LokasiKelurahan::insert($bufferKelurahan);
                            $bufferKelurahan = [];
                        }
                    }
                }

                if (!empty($bufferProvinsi)) {
                    LokasiProvinsi::insert($bufferProvinsi);
                    $bufferProvinsi = [];
                }
                if (!empty($bufferKota)) {
                    LokasiKota::insert($bufferKota);
                    $bufferKota = [];
                }
                if (!empty($bufferKecamatan)) {
                    LokasiKecamatan::insert($bufferKecamatan);
                    $bufferKecamatan = [];
                }
                if (!empty($bufferKelurahan)) {
                    LokasiKelurahan::insert($bufferKelurahan);
                    $bufferKelurahan = [];
                }

                fclose($handle);
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
        $mem = memory_get_usage() / 1024 / 1024;
        $maxMem = memory_get_peak_usage() / 1024 / 1024;
        $this->info('memory : ' . $mem . 'MB / ' . $maxMem . 'MB');
    }
}
