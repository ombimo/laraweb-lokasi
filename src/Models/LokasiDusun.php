<?php

namespace Ombimo\LarawebLokasi\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiDusun extends Model
{
    protected $table = 'lokasi_dusun';

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

    public function kelurahan()
    {
        return $this->belongsTo('Ombimo\LarawebLokasi\Models\LokasiKelurahan', 'kelurahan_id');
    }

    public function kecamatan()
    {
        return $this->kelurahan->kecamatan();
    }

    public function kota()
    {
        return $this->kelurahan->kecamatan->kota();
    }

    public function provinsi()
    {
        return $this->kelurahan->kecamatan->kota->provinsi();
    }

}
