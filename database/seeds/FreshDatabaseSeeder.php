<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Enums\Permissions;
use App\Enums\Roles;
use App\User;
use App\Concept;
use App\Course;
use App\Exercise;
use App\Lesson;
use App\Module;
use App\Project;
use App\Code;
use App\Choice;

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
        self::createFilledCourse();
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

    public function createFilledCourse()
    {
        $user = User::where('name', 'Teacher 1')->first();

        $EOL = "\r\n";

        $course = Course::create([
            'name' => 'CS 1181 - Test Course',
            'open_date' => '2018-08-10 02:01:54',
            'close_date' => '2018-11-15 02:01:54',
            'owner_id' => $user->id,
        ]);

        $concept1 = Concept::create([
            'name' => 'Print Statements',
            'course_id' => $course->id,
            'owner_id' => $user->id
        ]);

        $module1 = Module::create([
            'name' => 'Printing',
            'concept_id' => $concept1->id,
            'open_date' => '2018-05-15 0:00:00',
            'owner_id' => $user->id
        ]);

        $lesson1= Lesson::create([
            'name' => 'Print statements',
            'module_id' =>  $module1->id,
            'owner_id' => $user->id
        ]);

        $exercise1Code = Code::create([
            'prompt' => 'Press the run button. "Hello" will be printed to the screen. Change the program to print "Hello World!"',
            'pre_code' => 'def myFunc():' . $EOL.'   print("hello world")',
            'start_code' => 'print("Hello")',
            'test_code' => 'test_out("Hello World!")'
        ]);

        $exercise1 = Exercise::create([
            'lesson_id' => $lesson1->id,
            'owner_id' => $user->id,
            'type_id' => $exercise1Code->id,
            'type_type' => get_class($exercise1Code)
        ]);

        $course->addUserAsRole(User::where('name', 'Student 1')->first()->id, Role::where('name', Roles::STUDENT)->first()->id);
    }
}
