<?php

namespace Ombimo\LarawebLokasi\Commands;

use Illuminate\Console\Command;
use Ombimo\LarawebLokasi\Models\LokasiNegara;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class SeederNegara extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'md-lokasi:seeder-negara';

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
        $this->info('seeder local');

        $fh = fopen(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data-negara.txt','r');
        while ($value = fgets($fh)) {
            $this->info($value);
            $db = new LokasiNegara;
            $db->nama = $value;
            $db->slug = Str::slug($value);
            $db->save();
        }
        fclose($fh);
    }
}
