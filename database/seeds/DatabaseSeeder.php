<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run() {
		DB::table('roles')->insert([
			'name' => 'Administrator',
			'created_at' => DB::raw('CURRENT_TIMESTAMP'),
			'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
		]);

		DB::table('roles')->insert([
			'name' => 'User',
			'created_at' => DB::raw('CURRENT_TIMESTAMP'),
			'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
		]);
	}
}
