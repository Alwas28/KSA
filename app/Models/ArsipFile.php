<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipFile extends Model
{
    protected $table = 'arsip_file';
    protected $primaryKey = 'id_arsip';

    protected $fillable = [
        'nama_file',
        'file_path',
        'file_type',
        'file_size',
        'keterangan',
        'uploaded_by'
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
