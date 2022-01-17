<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('model_has_roles')->insert([
            [
                'role_id'       => 1,
                'model_type'    => 'App\Models\User',
                'model_id'      => 1,
                'created_by'    => 1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);

        $this->command->info('Users has roles berhasil disimpan');
    }
}
