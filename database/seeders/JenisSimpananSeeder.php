<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSimpananSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_simpanan')->upsert([
            ['id_jenis_simpanan'=>1,'nama_jenis'=>'Simpanan Wajib',   'deskripsi'=>'Simpanan wajib setiap anggota','created_at'=>now(),'updated_at'=>now()],
            ['id_jenis_simpanan'=>2,'nama_jenis'=>'Simpanan Pokok',   'deskripsi'=>'Simpanan pokok anggota',       'created_at'=>now(),'updated_at'=>now()],
            ['id_jenis_simpanan'=>3,'nama_jenis'=>'Simpanan Sukarela','deskripsi'=>'-',                            'created_at'=>now(),'updated_at'=>now()],
        ], ['id_jenis_simpanan'], ['nama_jenis','deskripsi']);
    }
}
