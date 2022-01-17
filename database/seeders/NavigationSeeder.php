<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('navigations')->insert([
            [
                'parent_id'     => NULL,
                'name'          => 'Setting',
                'folder'        => 'pages',
                'url'           => 'setting',
                'icon'          => 'fas fa-cogs',
                'position'      => 30,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => NULL,
                'name'          => 'Privileges',
                'folder'        => NULL,
                'url'           => NULL,
                'icon'          => 'fas fa-user-shield',
                'position'      => 29,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => 2,
                'name'          => 'Guest',
                'folder'        => 'pages',
                'url'           => 'guest',
                'icon'          => NULL,
                'position'      => 1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => 2,
                'name'          => 'User',
                'folder'        => 'pages',
                'url'           => 'user',
                'icon'          => NULL,
                'position'      => 2,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => 2,
                'name'          => 'Role',
                'folder'        => 'pages',
                'url'           => 'role',
                'icon'          => NULL,
                'position'      => 3,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => 2,
                'name'          => 'Role Has Permission',
                'folder'        => 'pages',
                'url'           => 'role-has-permission',
                'icon'          => NULL,
                'position'      => 4,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => 2,
                'name'          => 'User Has Permission',
                'folder'        => 'pages',
                'url'           => 'user-has-permission',
                'icon'          => NULL,
                'position'      => 5,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => NULL,
                'name'          => 'Master',
                'folder'        => NULL,
                'url'           => NULL,
                'icon'          => 'fas fa-database',
                'position'      => 1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => 8,
                'name'          => 'Satuan',
                'folder'        => 'pages',
                'url'           => 'satuan',
                'icon'          => NULL,
                'position'      => 3,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => 8,
                'name'          => 'Jenis',
                'folder'        => 'pages',
                'url'           => 'jenis',
                'icon'          => NULL,
                'position'      => 4,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => 8,
                'name'          => 'Barang',
                'folder'        => 'pages',
                'url'           => 'barang',
                'icon'          => NULL,
                'position'      => 2,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => 8,
                'name'          => 'Supplier',
                'folder'        => 'pages',
                'url'           => 'supplier',
                'icon'          => NULL,
                'position'      => 1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => NULL,
                'name'          => 'Transaksi',
                'folder'        => NULL,
                'url'           => NULL,
                'icon'          => 'fas fa-dolly',
                'position'      => 2,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => 13,
                'name'          => 'Barang Masuk',
                'folder'        => 'pages',
                'url'           => 'barang-masuk',
                'icon'          => NULL,
                'position'      => 1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => 13,
                'name'          => 'Barang Keluar',
                'folder'        => 'pages',
                'url'           => 'barang-keluar',
                'icon'          => NULL,
                'position'      => 2,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'parent_id'     => NULL,
                'name'          => 'Laporan',
                'folder'        => 'pages',
                'url'           => 'laporan',
                'icon'          => 'fas fa-file-alt',
                'position'      => 3,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);

        $this->command->info('Navigation berhasil disimpan');
    }
}
