<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([ 'name' => 'admin']);
        Role::create([ 'name' => 'vendor']);
        Role::create([ 'name' => 'customer']);
    }
}
