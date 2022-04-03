<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_transaksi';
    public $incrementing = false;

    protected $fillable = [
        'id_transaksi',
        'id_driver',
        'id_customer',
        'id_mobil',
        'id_pegawai',
        'id_promo',
        'tanggal_transaksi',
        'tanggal_pengembalian',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_transaksi',
        'metode_pembayaran',
        'bukti_bayar',
        'total_harga',
        'total_sewa_mobil',
        'total_sewa_driver',
        'total_denda',
        'potongan_promo',
        'rating_driver',
        'rating_ajr',
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

    public function Driver()
    {
        return $this->belongsTo(Driver::class, 'id_driver', 'id_driver');
    }

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function Mobil()
    {
        return $this->belongsTo(Mobil::class, 'id_mobil', 'id_mobil');
    }

    public function Pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function Promo()
    {
        return $this->belongsTo(Promo::class, 'id_promo', 'id_promo');
    }
}