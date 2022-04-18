<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Driver extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_driver';
    public $incrementing = false;

    protected $fillable = [
        'id_driver',
        'nama_driver',
        'alamat_driver',
        'email_driver',
        'status_ketersediaan_driver',
        'status_berkas',
        'isEnglish',
        'tanggal_lahir_driver',
        'jenis_kelamin',
        'no_telp_driver',
        'url_foto_driver',
        'password',
        'tarif_sewa_driver',
        'berkas_bebas_napza',
        'berkas_sim',
        'berkas_sehat_jiwa',
        'berkas_sehat_jasmani',
        'berkas_skck',
        'rerata_rating_driver',
        'isAktif',
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
}