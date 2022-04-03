<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Mobil extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_mobil';

    protected $fillable = [
        'id_mitra',
        'nama_mobil',
        'tipe_mobil',
        'jenis_transmisi',
        'jenis_bahan_bakar',
        'warna_mobil',
        'volume_bahan_bakar',
        'kategori_aset',
        'kapasitas_penumpang',
        'harga_sewa_mobil',
        'plat_nomor',
        'nomor_stnk',
        'status_ketersediaan',
        'url_foto_mobil',
        'fasilitas',
        'mulai_kontrak',
        'selesai_kontrak',
        'tanggal_servis_terakhir',
    ];

    public function getCreatedAtAttribute()
    {
        if (!is_null($this->attributes['created_at'])) {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute()
    {
        if (!is_null($this->attributes['updated_at'])) {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }

    public function Mitra()
    {
        return $this->belongsTo(Mitra::class, 'id_mitra', 'id_mitra');
    }
}