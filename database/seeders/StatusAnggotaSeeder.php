<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusAnggotaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('status_anggota')->upsert([
            ['id_status_anggota'=>1,'nama_status'=>'Aktif',  'deskripsi'=>'Anggota aktif',   'created_at'=>now(),'updated_at'=>now()],
            ['id_status_anggota'=>2,'nama_status'=>'Suspend','deskripsi'=>'Anggota suspend', 'created_at'=>now(),'updated_at'=>now()],
        ], ['id_status_anggota'], ['nama_status','deskripsi']);
    }
}
