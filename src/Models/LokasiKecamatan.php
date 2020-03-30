<?php

namespace Ombimo\LarawebLokasi\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiKecamatan extends Model
{
    protected $table = 'lokasi_kecamatan';

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

    public function kota()
    {
        return $this->belongsTo('Ombimo\LarawebLokasi\Models\LokasiKota', 'kota_id');
    }
}
