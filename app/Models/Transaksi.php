<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    protected $fillable = ['id_transaksi', 'id_member', 'tgl', 'batas_waktu', 'tgl_bayar', 'status', 'dibayar', 'id_user'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $table = "transaksi";
    protected $primaryKey = 'id_transaksi';
    public function member()
    {
        return $this->hasOne(Member::class,'id_member','id_member');
    }
    public function paket()
    {
        return $this->hasOne(Paket::class,'id_paket','id_paket');
    }
    public function user()
    {
        return $this->hasOne(User::class,'id_user','id_user');
    }
}
