<?php

namespace Ombimo\LarawebLokasi\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lokasi\Kota;
use Illuminate\Http\Request;

class KotaController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $limit = $request->query('limit', config('laraweb-lokasi.limit'));

        $query = Kota::defaultSort()->take($limit);

        if ($request->query('provinsi_id') != null) {
            $provinsiID = intval($request->query('provinsi_id'));
            $query = $query->where('provinsi_id', $provinsiID);
        }

        if ($request->query('keyword') != null) {
            $keyword = $request->query('keyword');
            $query = $query->where('nama', 'like', "%" . $keyword . "%");
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
