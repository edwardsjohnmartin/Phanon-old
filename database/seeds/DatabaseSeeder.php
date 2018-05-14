<?php

use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Http\Controllers\Controller;

use App\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        Permission::create(['name' => 'Administer roles & permissions']);
        Permission::create(['name' => 'Create course']);
        Permission::create(['name' => 'Edit course']);
        Permission::create(['name' => 'Delete course']);
        Permission::create(['name' => 'Create module']);
        Permission::create(['name' => 'Edit module']);
        Permission::create(['name' => 'Delete module']);

        $admin_role = Role::create(['name' => 'Admin']);
        $admin_role->givePermissionTo('Administer roles & permissions');
        $admin_role->givePermissionTo('Create course');
        $admin_role->givePermissionTo('Edit course');
        $admin_role->givePermissionTo('Delete course');
        $admin_role->givePermissionTo('Create module');
        $admin_role->givePermissionTo('Edit module');
        $admin_role->givePermissionTo('Delete module');

        $user = User::create([
            'name' => 'Admin Account',
            'email' => 'admin@test.com',
            'password' => bcrypt('testerer1'),
        ]);

        $user->assignRole($admin_role);

        $user = User::create([
            'name' => 'Test Student 1',
            'email' => 'teststudent1@test.com',
            'password' => bcrypt('testerer1'),
        ]);
    }
}
