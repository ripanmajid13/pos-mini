<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        for ($i = 21; $i <= 45; $i++) {
            $data[] = [
                'role_id'       => 1,
                'permission_id' => $i,
                'created_by'    => 1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ];
        }

        DB::table('role_has_permissions')->insert($data);

        $this->command->info('Roles has permissions berhasil disimpan');
    }
}
