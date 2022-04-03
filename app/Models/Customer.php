<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_customer';
    public $incrementing = false;

    protected $fillable = [
        'id_customer',
        'nama_customer',
        'alamat_customer',
        'tanggal_lahir_customer',
        'jenis_kelamin',
        'email_customer',
        'password',
        'no_telp_customer',
        'berkas_customer',
        'status_berkas',
        'no_tanda_pengenal',
        'no_sim',
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