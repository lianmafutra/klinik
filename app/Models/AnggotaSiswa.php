<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaSiswa extends Model
{
   use HasFactory;
   protected $table = 'anggota_siswa';
   protected $guarded = [];
   protected $casts = [
     'created_at' => 'date:d-m-Y H:m:s',
     'updated_at' => 'date:d-m-Y H:m:s',
     'tgl_lahir' => 'date:d-m-Y',
 ];
}
