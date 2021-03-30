<?php

namespace Ombimo\LarawebLokasi\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lokasi\Provinsi;
use Illuminate\Http\Request;

class ProvinsiController extends Controller
{
    public function get(Request $request, $id = null)
    {
        $limit = $request->query('limit', config('laraweb-lokasi.limit'));

        $query = Provinsi::defaultSort()->take($limit);

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
