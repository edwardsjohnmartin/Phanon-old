<?php
use Illuminate\Http\Request;
use Illuminate\Database\Seeder;
use App\Http\Controllers\Controller;
use App\User;
use App\Exercise;
use App\Lesson;
use App\Project;
use App\Module;
use App\Course;
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
        //TODO: need to create better naming of permissions.
        //  These will be hare to maintain.
        // I recommend object.action as a better structure.
        // Not only will it keep consistent with Laravel modeling,
        // it will make it easier to remember what to do.
        // e.g. course.add
        //      course.edit
        //      course.delete
        //      user.add
        //      user.view
        //      user.edit
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




        $puser_role = Role::create(['name' => 'Power User']);
        //$puser_role->givePermissionTo('Administer roles & permissions');
        $puser_role->givePermissionTo('Create course');
        $puser_role->givePermissionTo('Edit course');
        //$puser_role->givePermissionTo('Delete course'); -- No delete
        $puser_role->givePermissionTo('Create module');
        $puser_role->givePermissionTo('Edit module');
        //$puser_role->givePermissionTo('Delete module'); -- No delete

        // Create admin user
        $aUser = User::create([
                'name' => 'Admin Account',
                'email' => 'admin@test.com',
                'password' => bcrypt('testerer1'),
        ]);

        $aUser->assignRole($admin_role);
        
        // create power user
        $pUser = User::create([
                'name' => 'Power User',
                'email' => 'poweruser@test.com',
                'password' => bcrypt('testerer1'),
        ]);
        $pUser->assignRole($puser_role);

        // create Student user
        $sUser = User::create([
                'name' => 'Test Student 1',
                'email' => 'teststudent1@test.com',
                'password' => bcrypt('testerer1'),
        ]);


        // create admin's exercises and courses.
        Exercise::create([
                'prompt' => 'Test Exercise 1',
                'test_code' => '#test_code',
                'user_id' => $aUser->id,
        ]);
        Exercise::create([
                'prompt' => 'Test Exercise 2',
                'test_code' => '#test_code',
                'user_id' => $aUser->id,
        ]);
        Exercise::create([
                'prompt' => 'Test Exercise 3',
                'test_code' => '#test_code',
                'user_id' => $aUser->id,
        ]);
        Exercise::create([
                'prompt' => 'Test Exercise 4',
                'test_code' => '#test_code',
                'user_id' => $aUser->id,
        ]);
        Exercise::create([
                'prompt' => 'Test Exercise 5',
                'test_code' => '#test_code',
                'user_id' => $aUser->id,
        ]);
        Project::create([
                'name' => 'Test Project 1',
                'open_date' => '2018-05-15 02:01:54',
                'close_date' => '2018-05-15 02:01:54',
                'prompt' => 'Prompt 1',
                'user_id' => $aUser->id,
        ]);
        Project::create([
                'name' => 'Test Project 2',
                'open_date' => '2018-05-15 02:01:54',
                'close_date' => '2018-05-15 02:01:54',
                'prompt' => 'Prompt 2',
                'user_id' => $aUser->id,
        ]);
        Project::create([
                'name' => 'Test Project 3',
                'open_date' => '2018-05-15 02:01:54',
                'close_date' => '2018-05-15 02:01:54',
                'prompt' => 'Prompt 3',
                'user_id' => $aUser->id,
        ]);
        Lesson::create([
                'name' => 'Test Lesson 1',
                'open_date' => '2018-05-15 02:01:54',
                'user_id' => $aUser->id,
        ]);
        Lesson::create([
                'name' => 'Test Lesson 2',
                'open_date' => '2018-05-15 02:01:54',
                'user_id' => $aUser->id,
        ]);
        Module::create([
                'name' => 'Test Module 1',
                'open_date' => '2018-05-15 02:01:54',
                'close_date' => '2018-05-15 02:01:54',
                'user_id' => $aUser->id,
        ]);
        Module::create([
                'name' => 'Test Module 2',
                'open_date' => '2018-05-15 02:01:54',
                'close_date' => '2018-05-15 02:01:54',
                'user_id' => $aUser->id,
        ]);
        Course::create([
                'name' => 'Test Course 1',
                'user_id' => $aUser->id,
        ]);


        // create power users's exercises and courses.
        Course::create([
                'name' => 'Power Course 1',
                'user_id' => $pUser->id,
        ]);

        // modules for course
        Module::create([
                'name' => 'Power Module 1',
                'open_date' => '2018-05-15 02:01:54',
                'close_date' => '2018-05-15 02:01:54',
                'user_id' => $pUser->id,
        ]);
        Module::create([
                'name' => 'Power Module 2',
                'open_date' => '2018-05-15 02:01:54',
                'close_date' => '2018-05-15 02:01:54',
                'user_id' => $pUser->id,
        ]);

        Exercise::create([
                'prompt' => 'Power Exercise 1',
                'test_code' => '#test_code',
                'user_id' => $pUser->id,
        ]);
        Exercise::create([
                'prompt' => 'Power Exercise 2',
                'test_code' => '#test_code',
                'user_id' => $pUser->id,
        ]);
        Exercise::create([
                'prompt' => 'Power Exercise 3',
                'test_code' => '#test_code',
                'user_id' => $pUser->id,
        ]);
        Exercise::create([
                'prompt' => 'Power Exercise 4',
                'test_code' => '#test_code',
                'user_id' => $pUser->id,
        ]);
        Exercise::create([
                'prompt' => 'Power Exercise 5',
                'test_code' => '#test_code',
                'user_id' => $pUser->id,
        ]);
        Project::create([
                'name' => 'Power Project 1',
                'open_date' => '2018-05-15 02:01:54',
                'close_date' => '2018-05-15 02:01:54',
                'prompt' => 'Prompt 1',
                'user_id' => $pUser->id,
        ]);
        Project::create([
                'name' => 'Power Project 2',
                'open_date' => '2018-05-15 02:01:54',
                'close_date' => '2018-05-15 02:01:54',
                'prompt' => 'Prompt 2',
                'user_id' => $pUser->id,
        ]);
        Project::create([
                'name' => 'Power Project 3',
                'open_date' => '2018-05-15 02:01:54',
                'close_date' => '2018-05-15 02:01:54',
                'prompt' => 'Prompt 3',
                'user_id' => $pUser->id,
        ]);
        Lesson::create([
                'name' => 'Power Lesson 1',
                'open_date' => '2018-05-15 02:01:54',
                'user_id' => $pUser->id,
        ]);
        Lesson::create([
                'name' => 'Power Lesson 2',
                'open_date' => '2018-05-15 02:01:54',
                'user_id' => $pUser->id,
        ]);



    }
}
