<?php

namespace Ombimo\LarawebLokasi\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lokasi\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $limit = $request->query('limit', config('laraweb-lokasi.limit'));

        $query = Kecamatan::defaultSort()->take($limit);

        if ($request->query('kota_id') != null) {
            $kotaID = intval($request->query('kota_id'));
            $query = $query->where('kota_id', $kotaID);
        }

        if ($request->query('provinsi_id') != null) {
            $provinsiID = intval($request->query('provinsi_id'));
            $query = $query->whereHas('kota', function ($query) use ($provinsiID) {
                $query->where('provinsi_id', $provinsiID);
            });
        }

        if ($request->query('keyword') != null) {
            $keyword = $request->query('keyword');
            $query = $query->where('nama', 'like', "%" . $keyword . "%");
        }

        if ($request->query('with') != null) {
            $with = $request->query('with');
            $query = $query->with($with);
        }

        if ($id === null) {
            $data = $query->get();
        } else {
            $data = $query->where('id', $id)->first();
        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
