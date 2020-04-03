<?php

namespace Ombimo\LarawebLokasi\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiNegara extends Model
{
    protected $table = 'lokasi_negara';

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

}
