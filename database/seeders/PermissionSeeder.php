<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'navigation_id' => 1,
                'parent_id'     => NULL,
                'name'          => 'setting',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 3,
                'parent_id'     => NULL,
                'name'          => 'guest',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 2,
                'name'          => 'guest-create',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 2,
                'name'          => 'guest-edit',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 2,
                'name'          => 'guest-delete',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 2,
                'name'          => 'guest-roles',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 4,
                'parent_id'     => NULL,
                'name'          => 'user',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 7,
                'name'          => 'user-create',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 7,
                'name'          => 'user-edit',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 7,
                'name'          => 'user-delete',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 5,
                'parent_id'     => NULL,
                'name'          => 'role',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 11,
                'name'          => 'role-create',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 11,
                'name'          => 'role-edit',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 11,
                'name'          => 'role-delete',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 6,
                'parent_id'     => NULL,
                'name'          => 'role-has-permission',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 15,
                'name'          => 'role-has-permission-create',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 15,
                'name'          => 'role-has-permission-edit',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 7,
                'parent_id'     => NULL,
                'name'          => 'user-has-permission',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 18,
                'name'          => 'user-has-permission-create',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 18,
                'name'          => 'user-has-permission-edit',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 9,
                'parent_id'     => NULL,
                'name'          => 'satuan',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 21,
                'name'          => 'satuan-create',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 21,
                'name'          => 'satuan-edit',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 21,
                'name'          => 'satuan-delete',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 10,
                'parent_id'     => NULL,
                'name'          => 'jenis',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 25,
                'name'          => 'jenis-create',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 25,
                'name'          => 'jenis-edit',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 25,
                'name'          => 'jenis-delete',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 11,
                'parent_id'     => NULL,
                'name'          => 'barang',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 29,
                'name'          => 'barang-create',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 29,
                'name'          => 'barang-edit',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 29,
                'name'          => 'barang-delete',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 12,
                'parent_id'     => NULL,
                'name'          => 'supplier',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 33,
                'name'          => 'supplier-create',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 33,
                'name'          => 'supplier-edit',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 33,
                'name'          => 'supplier-delete',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 14,
                'parent_id'     => NULL,
                'name'          => 'barang-masuk',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 37,
                'name'          => 'barang-masuk-create',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 37,
                'name'          => 'barang-masuk-edit',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 37,
                'name'          => 'barang-masuk-delete',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 15,
                'parent_id'     => NULL,
                'name'          => 'barang-keluar',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 41,
                'name'          => 'barang-keluar-create',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 41,
                'name'          => 'barang-keluar-edit',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => NULL,
                'parent_id'     => 41,
                'name'          => 'barang-keluar-delete',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'navigation_id' => 16,
                'parent_id'     => NULL,
                'name'          => 'laporan',
                'guard_name'    => 'web',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);

        $this->command->info('Permissions berhasil disimpan');
    }
}
