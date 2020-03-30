<?php

namespace Ombimo\LarawebLokasi\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiProvinsi extends Model
{
    protected $table = 'lokasi_provinsi';

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
        return $this->hasMany('App\LokasiKota', 'provinsi_id')->orderBy('nama');
    }

}
