<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Enums\Permissions;
use App\Enums\Roles;
use App\User;

class FreshDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::createPermissions();
        self::createRoles();
        self::createAdminUser();
        self::createTeacherUser();
        self::createStudentUser();
    }

    public function createPermissions()
    {
        // Get all permissions that exist in the enum as an array
        $all_permissions = Permissions::toArray();

        // Create all permissions in the database
        foreach ($all_permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function createRoles()
    {
        // Admin role creation
        $admin_role = Role::create(['name' => Roles::ADMIN]);
        foreach (Permission::all() as $permission) {
            $admin_role->givePermissionTo($permission);
        }

        // Power User role creation
        $puser_role = Role::create(['name' => Roles::POWER_USER]);
        $puser_role->givePermissionTo(Permissions::COURSE_VIEW);
        $puser_role->givePermissionTo(Permissions::COURSE_CREATE);
        $puser_role->givePermissionTo(Permissions::COURSE_EDIT);
        $puser_role->givePermissionTo(Permissions::CONCEPT_VIEW);
        $puser_role->givePermissionTo(Permissions::CONCEPT_CREATE);
        $puser_role->givePermissionTo(Permissions::CONCEPT_EDIT);
        $puser_role->givePermissionTo(Permissions::MODULE_VIEW);
        $puser_role->givePermissionTo(Permissions::MODULE_CREATE);
        $puser_role->givePermissionTo(Permissions::MODULE_EDIT);
        $puser_role->givePermissionTo(Permissions::LESSON_VIEW);
        $puser_role->givePermissionTo(Permissions::LESSON_CREATE);
        $puser_role->givePermissionTo(Permissions::LESSON_EDIT);
        $puser_role->givePermissionTo(Permissions::EXERCISE_VIEW);
        $puser_role->givePermissionTo(Permissions::EXERCISE_CREATE);
        $puser_role->givePermissionTo(Permissions::EXERCISE_EDIT);
        $puser_role->givePermissionTo(Permissions::PROJECT_VIEW);
        $puser_role->givePermissionTo(Permissions::PROJECT_CREATE);
        $puser_role->givePermissionTo(Permissions::PROJECT_EDIT);

        // Teacher role creation
        $teacher_role = Role::create(['name' => Roles::TEACHER]);
        $teacher_role->givePermissionTo(Permissions::COURSE_VIEW);
        $teacher_role->givePermissionTo(Permissions::COURSE_CREATE);
        $teacher_role->givePermissionTo(Permissions::COURSE_EDIT);
        $teacher_role->givePermissionTo(Permissions::COURSE_DELETE);
        $teacher_role->givePermissionTo(Permissions::CONCEPT_VIEW);
        $teacher_role->givePermissionTo(Permissions::CONCEPT_CREATE);
        $teacher_role->givePermissionTo(Permissions::CONCEPT_EDIT);
        $teacher_role->givePermissionTo(Permissions::CONCEPT_DELETE);
        $teacher_role->givePermissionTo(Permissions::MODULE_VIEW);
        $teacher_role->givePermissionTo(Permissions::MODULE_CREATE);
        $teacher_role->givePermissionTo(Permissions::MODULE_EDIT);
        $teacher_role->givePermissionTo(Permissions::MODULE_DELETE);
        $teacher_role->givePermissionTo(Permissions::LESSON_VIEW);
        $teacher_role->givePermissionTo(Permissions::LESSON_CREATE);
        $teacher_role->givePermissionTo(Permissions::LESSON_EDIT);
        $teacher_role->givePermissionTo(Permissions::LESSON_DELETE);
        $teacher_role->givePermissionTo(Permissions::EXERCISE_VIEW);
        $teacher_role->givePermissionTo(Permissions::EXERCISE_CREATE);
        $teacher_role->givePermissionTo(Permissions::EXERCISE_EDIT);
        $teacher_role->givePermissionTo(Permissions::EXERCISE_DELETE);
        $teacher_role->givePermissionTo(Permissions::EXERCISE_AUTOCOMPLETE);
        $teacher_role->givePermissionTo(Permissions::PROJECT_VIEW);
        $teacher_role->givePermissionTo(Permissions::PROJECT_CREATE);
        $teacher_role->givePermissionTo(Permissions::PROJECT_EDIT);
        $teacher_role->givePermissionTo(Permissions::PROJECT_DELETE);
        $teacher_role->givePermissionTo(Permissions::TEAM_VIEW);
        $teacher_role->givePermissionTo(Permissions::TEAM_CREATE);
        $teacher_role->givePermissionTo(Permissions::TEAM_EDIT);
        $teacher_role->givePermissionTo(Permissions::TEAM_DELETE);

        // Teaching Assistant role creation
        $teaching_assistant_role = Role::create(['name' => Roles::TEACHING_ASSISTANT]);
        $teaching_assistant_role->givePermissionTo(Permissions::COURSE_VIEW);
        $teaching_assistant_role->givePermissionTo(Permissions::COURSE_EDIT);
        $teaching_assistant_role->givePermissionTo(Permissions::CONCEPT_VIEW);
        $teaching_assistant_role->givePermissionTo(Permissions::CONCEPT_EDIT);
        $teaching_assistant_role->givePermissionTo(Permissions::MODULE_VIEW);
        $teaching_assistant_role->givePermissionTo(Permissions::MODULE_EDIT);
        $teaching_assistant_role->givePermissionTo(Permissions::LESSON_VIEW);
        $teaching_assistant_role->givePermissionTo(Permissions::LESSON_EDIT);
        $teaching_assistant_role->givePermissionTo(Permissions::EXERCISE_VIEW);
        $teaching_assistant_role->givePermissionTo(Permissions::EXERCISE_EDIT);
        $teaching_assistant_role->givePermissionTo(Permissions::PROJECT_VIEW);
        $teaching_assistant_role->givePermissionTo(Permissions::PROJECT_EDIT);
        $teaching_assistant_role->givePermissionTo(Permissions::TEAM_VIEW);
        $teaching_assistant_role->givePermissionTo(Permissions::TEAM_CREATE);
        $teaching_assistant_role->givePermissionTo(Permissions::TEAM_EDIT);
        $teaching_assistant_role->givePermissionTo(Permissions::TEAM_DELETE);

        // Student role creation
        $student_role = Role::create(['name' => Roles::STUDENT]);
        $student_role->givePermissionTo(Permissions::COURSE_VIEW);
        $student_role->givePermissionTo(Permissions::CONCEPT_VIEW);
        $student_role->givePermissionTo(Permissions::MODULE_VIEW);
        $student_role->givePermissionTo(Permissions::LESSON_VIEW);
        $student_role->givePermissionTo(Permissions::EXERCISE_VIEW);
        $student_role->givePermissionTo(Permissions::PROJECT_VIEW);
        $student_role->givePermissionTo(Permissions::TEAM_VIEW);

        // Observer role creation
        $observer_role = Role::create(['name' => Roles::OBSERVER]);
        $observer_role->givePermissionTo(Permissions::COURSE_VIEW);
        $observer_role->givePermissionTo(Permissions::CONCEPT_VIEW);
        $observer_role->givePermissionTo(Permissions::MODULE_VIEW);
        $observer_role->givePermissionTo(Permissions::LESSON_VIEW);
        $observer_role->givePermissionTo(Permissions::EXERCISE_VIEW);
        $observer_role->givePermissionTo(Permissions::PROJECT_VIEW);
    }

    public function createAdminUser()
    {
        $aUser = User::create([
            'name' => 'Admin Account',
            'email' => 'admin@test.com',
            'password' => bcrypt('tester'),
        ]);

        $aUser->assignRole(Role::where('name', Roles::ADMIN)->first());
    }

    public function createTeacherUser()
    {
        $tUser = User::create([
            'name' => 'Teacher 1',
            'email' => 'teacher1@test.com',
            'password' => bcrypt('tester'),
        ]);
         $tUser->assignRole(Role::where('name', Roles::TEACHER)->first());
    }
     public function createStudentUser()
    {
        $sUser = User::create([
            'name' => 'Student 1',
            'email' => 'teststudent1@test.com',
            'password' => bcrypt('tester'),
        ]);
         $sUser->assignRole(Role::where('name', Roles::STUDENT)->first());
    }
}
