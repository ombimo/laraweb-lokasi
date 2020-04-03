<?php

namespace Ombimo\LarawebLokasi\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiKelurahan extends Model
{
    protected $table = 'lokasi_kelurahan';

    protected $visible = ['id', 'nama', 'slug'];

    protected $fillable = ['id'];

    public function scopePublish($query)
    {
        return $query->where('publish', 1);
    }

    public function scopeDefaultSort($query)
    {
        return $query->orderBy('nama');
    }

    public function kecamatan()
    {
        return $this->belongsTo('Ombimo\LarawebLokasi\Models\LokasiKecamatan', 'kecamatan_id');
    }
}
