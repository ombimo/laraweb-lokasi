<?php

namespace Ombimo\LarawebLokasi\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ombimo\LarawebLokasi\Models\LokasiDusun;
use Ombimo\LarawebLokasi\Models\LokasiProvinsi;
use Ombimo\LarawebLokasi\Models\LokasiKota;
use Ombimo\LarawebLokasi\Models\LokasiKecamatan;
use Ombimo\LarawebLokasi\Models\LokasiKelurahan;

class LokasiController extends Controller
{
    public function provinsi(Request $request)
    {
        $data = [];
        $limit = $request->query('limit', 20);
        $query = LokasiProvinsi::defaultSort();
        if (!is_null($request->query('keyword'))) {
            $key = $request->query('keyword');
            $query = $query->where('nama', 'like', "%". $key ."%");
        }

        $data = $query->take($limit)->get();
        return response()->json($data);
    }

    public function kota(Request $request)
    {
        $data = [];
        $limit = $request->query('limit', 20);
        $query = LokasiKota::defaultSort();

        if (!is_null($request->query('provinsi'))) {
            $query = $query->where('provinsi_id', intval($request->query('provinsi')));
        }

        if (!is_null($request->query('keyword'))) {
            $key = $request->query('keyword');
            $query = $query->where('nama', 'like', "%". $key ."%");
        }

        if (!is_null($request->query('id'))) {
            $key = $request->query('id');
            $query = $query->where('id', $key);
        }

        $temp = $query->take($limit)->get();
        foreach ($temp as $value) {
            //nama masih perlu sepertinya
            $data[] = [
                'id' => $value->id,
                'nama' => $value->nama,
                'kota' => $value->nama,
                'slug' => $value->slug,
                'provinsi_id' => $value->provinsi->id ?? '',
                'provinsi' => $value->provinsi->nama ?? ''
            ];
        }

        return response()->json($data);
    }

    public function kecamatan(Request $request)
    {
        $data = [];
        $limit = $request->query('limit', 20);
        $query = LokasiKecamatan::with(['kota.provinsi'])->defaultSort();

        if (!is_null($request->query('kota'))) {
            $query = $query->where('kota_id', intval($request->query('kota')));
        }

        if (!is_null($request->query('provinsi'))) {
            $query = $query->where('provinsi_id', intval($request->query('provinsi')));
        }

        if (!is_null($request->query('keyword'))) {
            $key = $request->query('keyword');
            $query = $query->where(function($query) use ($key) {
                $query->where('nama', 'like', "%". $key ."%")
                      ->orWhereHas('kota', function ($query) use ($key) {
                        $query->where('lokasi_kota.nama', 'like', "%". $key ."%");
                      })->orWhereHas('kota.provinsi', function ($query) use ($key) {
                        $query->where('lokasi_provinsi.nama', 'like', "%". $key ."%");
                      });
            });
        }

        if (!is_null($request->query('id'))) {
            $key = $request->query('id');
            $query = $query->where('id', $key);
        }

        $temp = $query->take($limit)->get();
        foreach ($temp as $value) {
            //nama masih perlu sepertinya
            $data[] = [
                'id' => $value->id,
                'nama' => $value->nama,
                'kecamatan' => $value->nama,
                'slug' => $value->slug,
                'kota_id' => $value->kota->id ?? '',
                'kota' => $value->kota->nama ?? '',
                'provinsi_id' => $value->kota->provinsi->id ?? '',
                'provinsi' => $value->kota->provinsi->nama ?? ''
            ];
        }

        return response()->json($data);
    }

    public function kelurahan(Request $request)
    {
        $data = [];
        $limit = $request->query('limit', 20);
        $query = LokasiKelurahan::with(['kecamatan.kota.provinsi'])->defaultSort();
        $kotaID = intval($request->query('kota'));

        if (!empty($kotaID)) {
            $query = $query->whereHas('kecamatan', function($query) use ($kotaID) {
                $query->where('kota_id', $kotaID);
            });
        }

        if (!is_null($request->query('provinsi'))) {
            $query = $query->where('provinsi_id', intval($request->query('provinsi')));
        }

        if (!is_null($request->query('keyword'))) {
            $key = $request->query('keyword');
            $query = $query->where(function($query) use ($key) {
                $query->where('nama', 'like', "%". $key ."%")
                      ->orWhereHas('kecamatan.kota', function ($query) use ($key) {
                        $query->where('lokasi_kota.nama', 'like', "%". $key ."%");
                      })->orWhereHas('kecamatan.kota.provinsi', function ($query) use ($key) {
                        $query->where('lokasi_provinsi.nama', 'like', "%". $key ."%");
                      });
            });
        }

        if (!is_null($request->query('id'))) {
            $key = $request->query('id');
            $query = $query->where('id', $key);
        }

        $temp = $query->take($limit)->get();
        foreach ($temp as $value) {
            //nama masih perlu sepertinya
            $data[] = [
                'id' => $value->id,
                'nama' => $value->nama,
                'kelurahan' => $value->nama,
                'slug' => $value->slug,

                'kecamatan' => $value->kecamatan->nama,
                'kecamatan_id' => $value->kecamatan->id ?? '',

                'kota_id' => $value->kecamatan->kota->id ?? '',
                'kota' => $value->kecamatan->kota->nama ?? '',

                'provinsi_id' => $value->kecamatan->kota->provinsi->id ?? '',
                'provinsi' => $value->kecamatan->kota->provinsi->nama ?? ''
            ];
        }

        return response()->json($data);
    }

    public function dusun(Request $request)
    {
        $data = [];
        $limit = $request->query('limit', 20);
        $query = LokasiDusun::with(['kelurahan.kecamatan.kota.provinsi'])->defaultSort();
        $kotaID = intval($request->query('kota'));
        $kelurahanID = intval($request->query('kelurahan'));

        if (!empty($kotaID)) {
            $query = $query->whereHas('kecamatan', function($query) use ($kotaID) {
                $query->where('kota_id', $kotaID);
            });
        }

        if (!empty($kelurahanID)) {
            $query = $query->where('kelurahan_id', $kelurahanID);
        }

        if (!is_null($request->query('provinsi'))) {
            $query = $query->where('provinsi_id', intval($request->query('provinsi')));
        }

        if (!is_null($request->query('keyword'))) {
            $key = $request->query('keyword');
            $query = $query->where(function($query) use ($key) {
                $query->where('nama', 'like', "%". $key ."%")
                      ->orWhereHas('kelurahan.kecamatan.kota', function ($query) use ($key) {
                        $query->where('lokasi_kota.nama', 'like', "%". $key ."%");
                      })->orWhereHas('kelurahan.kecamatan.kota.provinsi', function ($query) use ($key) {
                        $query->where('lokasi_provinsi.nama', 'like', "%". $key ."%");
                      });
            });
        }

        if (!is_null($request->query('id'))) {
            $key = $request->query('id');
            $query = $query->where('id', $key);
        }

        $temp = $query->take($limit)->get();
        foreach ($temp as $value) {
            //nama masih perlu sepertinya
            $data[] = [
                'id' => $value->id,
                'nama' => $value->nama,
                'kelurahan' => $value->nama,
                'slug' => $value->slug,

                'kelurahan' => $value->kelurahan->nama ?? '',
                'kelurahan_id' => $value->kelurahan->id ?? '',

                'kecamatan' => $value->kelurahan->kecamatan->nama ?? '',
                'kecamatan_id' => $value->kelurahan->kecamatan->id ?? '',

                'kota_id' => $value->kelurahan->kecamatan->kota->id ?? '',
                'kota' => $value->kelurahan->kecamatan->kota->nama ?? '',

                'provinsi_id' => $value->kelurahan->kecamatan->kota->provinsi->id ?? '',
                'provinsi' => $value->kelurahan->kecamatan->kota->provinsi->nama ?? ''
            ];
        }

        return response()->json($data);
    }
}
