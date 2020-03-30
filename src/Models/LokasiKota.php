<?php

namespace Ombimo\LarawebLokasi\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiKota extends Model
{
    protected $table = 'lokasi_kota';

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

    public function provinsi()
    {
        return $this->belongsTo('Ombimo\LarawebLokasi\Models\LokasiProvinsi', 'provinsi_id');
    }
}
