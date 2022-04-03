<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pegawai extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'id_role',
        'nama_pegawai',
        'alamat_pegawai',
        'email_pegawai',
        'tanggal_lahir_pegawai',
        'jenis_kelamin_pegawai',
        'no_telp_pegawai',
        'password_pegawai',
        'url_foto_pegawai',
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

    public function Role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }
}