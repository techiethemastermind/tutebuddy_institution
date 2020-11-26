<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'role_access']);
        Permission::create(['name' => 'role_create']);
        Permission::create(['name' => 'role_edit']);
        Permission::create(['name' => 'role_view']);
        Permission::create(['name' => 'role_delete']);

        // create roles and assign created permissions
        $role = Role::create([
        	'name' => 'Administrator',
        	'slug' => 'super-admin'
        ]);

        $role->givePermissionTo(Permission::all());
    }
}
