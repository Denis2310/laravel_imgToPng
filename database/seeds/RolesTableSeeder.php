<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    protected $roles = ['Administrator', 'User'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roles as $role) {
            DB::table('roles')->insert([
                'name' => $role,
                'created_at' => DB::raw('CURRENT_TIMESTAMP'),
                'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
            ]);
        }
    }
}
