<?php
use App\Concept;
use App\Course;
use App\Exercise;
use App\Lesson;
use App\Module;
use App\Project;
use App\User;
use App\Enums\Permissions;
use App\Enums\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all permissions that exist in the enum as an array
        $all_permissions = Permissions::toArray();

        // Create all permissions in the database
        foreach ($all_permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Admin role creation
        // The admin role will have the power to do everything on the website
        $admin_role = Role::create(['name' => Roles::ADMIN]);
        foreach ($all_permissions as $permission) {
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

        // Create roles
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
        $teacher_role->givePermissionTo(Permissions::PROJECT_VIEW);
        $teacher_role->givePermissionTo(Permissions::PROJECT_CREATE);
        $teacher_role->givePermissionTo(Permissions::PROJECT_EDIT);
        $teacher_role->givePermissionTo(Permissions::PROJECT_DELETE);

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

        $student_role = Role::create(['name' => Roles::STUDENT]);
        $student_role->givePermissionTo(Permissions::COURSE_VIEW);
        $student_role->givePermissionTo(Permissions::CONCEPT_VIEW);
        $student_role->givePermissionTo(Permissions::MODULE_VIEW);
        $student_role->givePermissionTo(Permissions::LESSON_VIEW);
        $student_role->givePermissionTo(Permissions::EXERCISE_VIEW);
        $student_role->givePermissionTo(Permissions::PROJECT_VIEW);

        $observer_role = Role::create(['name' => Roles::OBSERVER]);
        $observer_role->givePermissionTo(Permissions::COURSE_VIEW);
        $observer_role->givePermissionTo(Permissions::CONCEPT_VIEW);
        $observer_role->givePermissionTo(Permissions::MODULE_VIEW);
        $observer_role->givePermissionTo(Permissions::LESSON_VIEW);
        $observer_role->givePermissionTo(Permissions::EXERCISE_VIEW);
        $observer_role->givePermissionTo(Permissions::PROJECT_VIEW);

        // Create admin user
        $aUser = User::create([
            'name' => 'Admin Account',
            'email' => 'admin@test.com',
            'password' => bcrypt('testerer1'),
        ]);

        $aUser->assignRole($admin_role);

        // Create power user
        $pUser = User::create([
            'name' => 'Power User',
            'email' => 'poweruser@test.com',
            'password' => bcrypt('testerer1'),
        ]);
        $pUser->assignRole($puser_role);

        // Create two teacher users
        $teacher1User = User::create([
            'name' => 'Teacher 1',
            'email' => 'teacher1@test.com',
            'password' => bcrypt('testerer1'),
        ]);
        $teacher1User->assignRole($teacher_role);

        $teacher2User = User::create([
            'name' => 'Teacher 2',
            'email' => 'teacher2@test.com',
            'password' => bcrypt('testerer1'),
        ]);
        $teacher2User->assignRole($teacher_role);

        // Create two teaching_assistant users
        $ta1User = User::create([
            'name' => 'Teaching Assistant 1',
            'email' => 'ta1@test.com',
            'password' => bcrypt('testerer1'),
        ]);
        $ta1User->assignRole($student_role);
        $ta1User->assignRole($teaching_assistant_role);

        $ta2User = User::create([
            'name' => 'Teaching Assistant 2',
            'email' => 'ta2@test.com',
            'password' => bcrypt('testerer1'),
        ]);
        $ta2User->assignRole($student_role);
        $ta2User->assignRole($teaching_assistant_role);

        // Create student user
        $s1User = User::create([
            'name' => 'Test Student 1',
            'email' => 'teststudent1@test.com',
            'password' => bcrypt('testerer1'),
        ]);
        $s1User->assignRole($student_role);

        // Create student user
        $s2User = User::create([
            'name' => 'Test Student 2',
            'email' => 'teststudent2@test.com',
            'password' => bcrypt('testerer1'),
        ]);
        $s2User->assignRole($student_role);

        // Create student user
        $s3User = User::create([
            'name' => 'Test Student 3',
            'email' => 'teststudent3@test.com',
            'password' => bcrypt('testerer1'),
        ]);
        $s3User->assignRole($student_role);

        // Create an obersver user
        $o1User = User::create([
            'name' => 'Observer 1',
            'email' => 'oberver1@test.com',
            'password' => bcrypt('testerer1'),
        ]);
        $o1User->assignRole($observer_role);

        //TODO: Update these objects to use the new structure (ie. concepts in courses, modules in concepts, etc...)
        // // create admin's exercises and courses.
        // Exercise::create([
        //     'prompt' => 'Test Exercise 1',
        //     'test_code' => '#test_code',
        //     'user_id' => $aUser->id,
        // ]);
        // Exercise::create([
        //     'prompt' => 'Test Exercise 2',
        //     'test_code' => '#test_code',
        //     'user_id' => $aUser->id,
        // ]);
        // Exercise::create([
        //     'prompt' => 'Test Exercise 3',
        //     'test_code' => '#test_code',
        //     'user_id' => $aUser->id,
        // ]);
        // Exercise::create([
        //     'prompt' => 'Test Exercise 4',
        //     'test_code' => '#test_code',
        //     'user_id' => $aUser->id,
        // ]);
        // Exercise::create([
        //     'prompt' => 'Test Exercise 5',
        //     'test_code' => '#test_code',
        //     'user_id' => $aUser->id,
        // ]);
        // Project::create([
        //     'name' => 'Test Project 1',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        //     'prompt' => 'Prompt 1',
        //     'user_id' => $aUser->id,
        // ]);
        // Project::create([
        //     'name' => 'Test Project 2',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        //     'prompt' => 'Prompt 2',
        //     'user_id' => $aUser->id,
        // ]);
        // Project::create([
        //     'name' => 'Test Project 3',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        //     'prompt' => 'Prompt 3',
        //     'user_id' => $aUser->id,
        // ]);
        // Lesson::create([
        //     'name' => 'Test Lesson 1',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'user_id' => $aUser->id,
        // ]);
        // Lesson::create([
        //     'name' => 'Test Lesson 2',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'user_id' => $aUser->id,
        // ]);
        // Module::create([
        //     'name' => 'Test Module 1',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        //     'user_id' => $aUser->id,
        // ]);
        // Module::create([
        //     'name' => 'Test Module 2',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        //     'user_id' => $aUser->id,
        // ]);
        // Course::create([
        //     'name' => 'Test Course 1',
        //     'user_id' => $aUser->id,
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        // ]);

        // // create power users's exercises and courses.
        // Course::create([
        //     'name' => 'Power Course 1',
        //     'user_id' => $pUser->id,
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        // ]);

        // // modules for course
        // Module::create([
        //     'name' => 'Power Module 1',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        //     'user_id' => $pUser->id,
        // ]);
        // Module::create([
        //     'name' => 'Power Module 2',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        //     'user_id' => $pUser->id,
        // ]);

        // Exercise::create([
        //     'prompt' => 'Power Exercise 1',
        //     'test_code' => '#test_code',
        //     'user_id' => $pUser->id,
        // ]);
        // Exercise::create([
        //     'prompt' => 'Power Exercise 2',
        //     'test_code' => '#test_code',
        //     'user_id' => $pUser->id,
        // ]);
        // Exercise::create([
        //     'prompt' => 'Power Exercise 3',
        //     'test_code' => '#test_code',
        //     'user_id' => $pUser->id,
        // ]);
        // Exercise::create([
        //     'prompt' => 'Power Exercise 4',
        //     'test_code' => '#test_code',
        //     'user_id' => $pUser->id,
        // ]);
        // Exercise::create([
        //     'prompt' => 'Power Exercise 5',
        //     'test_code' => '#test_code',
        //     'user_id' => $pUser->id,
        // ]);
        // Project::create([
        //     'name' => 'Power Project 1',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        //     'prompt' => 'Prompt 1',
        //     'user_id' => $pUser->id,
        // ]);
        // Project::create([
        //     'name' => 'Power Project 2',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        //     'prompt' => 'Prompt 2',
        //     'user_id' => $pUser->id,
        // ]);
        // Project::create([
        //     'name' => 'Power Project 3',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'close_date' => '2018-05-15 02:01:54',
        //     'prompt' => 'Prompt 3',
        //     'user_id' => $pUser->id,
        // ]);
        // Lesson::create([
        //     'name' => 'Power Lesson 1',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'user_id' => $pUser->id,
        // ]);
        // Lesson::create([
        //     'name' => 'Power Lesson 2',
        //     'open_date' => '2018-05-15 02:01:54',
        //     'user_id' => $pUser->id,
        // ]);

        self::createTestCourse();
    }

    /**
     * Create a fully populated course with its object relationships defined.
     */
    public function createTestCourse()
    {
        // The course owner will be the user with an id of 1
        $user = User::find(1);

        // Create all the objects
        $course = Course::create([
            'name' => 'Test Course 1',
            'open_date' => '2018-05-15 02:01:54',
            'close_date' => '2018-10-15 02:01:54',
            'user_id' => $user->id,
        ]);

        $concept1 = Concept::create([
            'name' => 'Test Concept 1',
            'course_id' => $course->id,
            'user_id' => $user->id,
        ]);

        $module1 = Module::create([
            'name' => 'Test Module 1',
            'open_date' => '2018-05-15 02:01:54',
            'concept_id' => $concept1->id,
            'user_id' => $user->id,
        ]);

        $lesson1 = Lesson::create([
            'name' => 'Test Lesson 1',
            'module_id' => $module1->id,
            'user_id' => $user->id,
        ]);

        $exercise1 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 1',
            'pre_code' => '#this is the code the user will have access to',
            'start_code' => '#this is the code the user will start with',
            'test_code' => '#test_code',
            'lesson_id' => $lesson1->id,
            'user_id' => $user->id,
        ]);

        $exercise2 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 2',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson1->id,
            'previous_exercise_id' => $exercise1->id,
            'user_id' => $user->id,
        ]);

        $exercise3 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 3',
            'pre_code' => '#this is the code the user will have access to',
            'start_code' => '#this is the code the user will start with',
            'test_code' => '#test_code',
            'lesson_id' => $lesson1->id,
            'previous_exercise_id' => $exercise2->id,
            'user_id' => $user->id,
        ]);

        $exercise4 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 4',
            'pre_code' => '#this is the code the user will have access to',
            'start_code' => '#this is the code the user will start with',
            'test_code' => '#test_code',
            'lesson_id' => $lesson1->id,
            'previous_exercise_id' => $exercise3->id,
            'user_id' => $user->id,
        ]);

        $lesson2 = Lesson::create([
            'name' => 'Test Lesson 2',
            'module_id' => $module1->id,
            'previous_lesson_id' => $lesson1->id,
            'user_id' => $user->id,
        ]);

        $exercise5 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 5',
            'pre_code' => '#this is the code the user will have access to',
            'start_code' => '#this is the code the user will start with',
            'test_code' => '#test_code',
            'lesson_id' => $lesson2->id,
            'user_id' => $user->id,
        ]);

        $exercise6 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 6',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson2->id,
            'previous_exercise_id' => $exercise5->id,
            'user_id' => $user->id,
        ]);

        $lesson30 = Lesson::create([
            'name' => 'Test Lesson 1-3',
            'module_id' => $module1->id,
            'previous_lesson_id' => $lesson2->id,
            'user_id' => $user->id,
        ]);

        $exercise31 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 3-1',
            'pre_code' => '#this is the code the user will have access to',
            'start_code' => '#this is the code the user will start with',
            'test_code' => '#test_code',
            'lesson_id' => $lesson30->id,
            'user_id' => $user->id,
        ]);

        $exercise32 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 3-2',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson30->id,
            'previous_exercise_id' => $exercise31->id,
            'user_id' => $user->id,
        ]);
        $exercise33 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 3-3',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson30->id,
            'previous_exercise_id' => $exercise32->id,
            'user_id' => $user->id,
        ]);
        $exercise34 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 3-4',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson30->id,
            'previous_exercise_id' => $exercise33->id,
            'user_id' => $user->id,
        ]);
        $exercise35 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 3-5',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson30->id,
            'previous_exercise_id' => $exercise34->id,
            'user_id' => $user->id,
        ]);

        $lesson40 = Lesson::create([
            'name' => 'Test Lesson 1-4',
            'module_id' => $module1->id,
            'previous_lesson_id' => $lesson30->id,
            'user_id' => $user->id,
        ]);

        $exercise41 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 4-1',
            'pre_code' => '#this is the code the user will have access to',
            'start_code' => '#this is the code the user will start with',
            'test_code' => '#test_code',
            'lesson_id' => $lesson40->id,
            'user_id' => $user->id,
        ]);

        $exercise42 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 4-2',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson40->id,
            'previous_exercise_id' => $exercise41->id,
            'user_id' => $user->id,
        ]);
        $exercise43 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 4-3',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson40->id,
            'previous_exercise_id' => $exercise42->id,
            'user_id' => $user->id,
        ]);
        $exercise44 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 4-4',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson40->id,
            'previous_exercise_id' => $exercise43->id,
            'user_id' => $user->id,
        ]);
        $exercise45 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 4-5',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson40->id,
            'previous_exercise_id' => $exercise44->id,
            'user_id' => $user->id,
        ]);

        $lesson50 = Lesson::create([
            'name' => 'Test Lesson 1-5',
            'module_id' => $module1->id,
            'previous_lesson_id' => $lesson40->id,
            'user_id' => $user->id,
        ]);

        $exercise51 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 5-1',
            'pre_code' => '#this is the code the user will have access to',
            'start_code' => '#this is the code the user will start with',
            'test_code' => '#test_code',
            'lesson_id' => $lesson50->id,
            'user_id' => $user->id,
        ]);

        $exercise52 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 5-2',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson50->id,
            'previous_exercise_id' => $exercise51->id,
            'user_id' => $user->id,
        ]);
        $exercise53 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 5-3',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson50->id,
            'previous_exercise_id' => $exercise52->id,
            'user_id' => $user->id,
        ]);
        $exercise54 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 5-4',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson50->id,
            'previous_exercise_id' => $exercise53->id,
            'user_id' => $user->id,
        ]);
        $exercise55 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 5-5',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson50->id,
            'previous_exercise_id' => $exercise54->id,
            'user_id' => $user->id,
        ]);
        $exercise56 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 5-6',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson50->id,
            'previous_exercise_id' => $exercise55->id,
            'user_id' => $user->id,
        ]);
        $exercise57 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 5-7',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson50->id,
            'previous_exercise_id' => $exercise56->id,
            'user_id' => $user->id,
        ]);

        $project1 = Project::create([
            'name' => 'Test Project 1',
            'open_date' => '2018-05-17 02:01:54',
            'close_date' => '2018-05-19 02:01:54',
            'prompt' => 'Prompt 1',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module1->id,
            'previous_lesson_id' => $lesson2->id,
            'user_id' => $user->id,
        ]);

        $module2 = Module::create([
            'name' => 'Test Module 2',
            'open_date' => '2018-05-21 02:01:54',
            'concept_id' => $concept1->id,
            'previous_module_id' => $module1->id,
            'user_id' => $user->id,
        ]);

        $project2 = Project::create([
            'name' => 'Test Project 2',
            'open_date' => '2018-05-21 02:01:54',
            'close_date' => '2018-05-26 02:01:54',
            'prompt' => 'Prompt 2',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module2->id,
            'user_id' => $user->id,
        ]);

        $module3 = Module::create([
            'name' => 'Test Module 3',
            'open_date' => '2018-05-27 02:01:54',
            'concept_id' => $concept1->id,
            'previous_module_id' => $module2->id,
            'user_id' => $user->id,
        ]);

        $lesson3 = Lesson::create([
            'name' => 'Test Lesson 3',
            'module_id' => $module3->id,
            'user_id' => $user->id,
        ]);

        $exercise7 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 7',
            'pre_code' => '#this is the code the user will have access to',
            'start_code' => '#this is the code the user will start with',
            'test_code' => '#test_code',
            'lesson_id' => $lesson3->id,
            'user_id' => $user->id,
        ]);

        $exercise8 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 8',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson3->id,
            'previous_exercise_id' => $exercise7->id,
            'user_id' => $user->id,
        ]);

        $exercise9 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 9',
            'pre_code' => '#this is the code the user will have access to',
            'start_code' => '#this is the code the user will start with',
            'test_code' => '#test_code',
            'lesson_id' => $lesson3->id,
            'previous_exercise_id' => $exercise8->id,
            'user_id' => $user->id,
        ]);

        $project3 = Project::create([
            'name' => 'Test Project 3',
            'open_date' => '2018-06-01 02:01:54',
            'close_date' => '2018-06-07 02:01:54',
            'prompt' => 'Prompt 3',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module3->id,
            'previous_lesson_id' => $lesson3->id,
            'user_id' => $user->id,
        ]);

        $project4 = Project::create([
            'name' => 'Test Project 4',
            'open_date' => '2018-06-05 02:01:54',
            'close_date' => '2018-06-07 02:01:54',
            'prompt' => 'Prompt 4',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module3->id,
            'previous_lesson_id' => $lesson3->id,
            'user_id' => $user->id,
        ]);

        $concept2 = Concept::create([
            'name' => 'Test Concept 2',
            'course_id' => $course->id,
            'previous_concept_id' => $concept1->id,
            'user_id' => $user->id,
        ]);

        $module4 = Module::create([
            'name' => 'Test Module 4',
            'open_date' => '2018-06-11 02:01:54',
            'concept_id' => $concept2->id,
            'user_id' => $user->id,
        ]);

        $project5 = Project::create([
            'name' => 'Test Project 5',
            'open_date' => '2018-06-15 02:01:54',
            'close_date' => '2018-06-16 02:01:54',
            'prompt' => 'Prompt 5',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module4->id,
            'user_id' => $user->id,
        ]);

        $lesson4 = Lesson::create([
            'name' => 'Test Lesson 4',
            'module_id' => $module4->id,
            'user_id' => $user->id,
        ]);

        $exercise10 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 10',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson4->id,
            'user_id' => $user->id,
        ]);

        $exercise11 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 11',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson4->id,
            'previous_exercise_id' => $exercise10->id,
            'user_id' => $user->id,
        ]);

        $exercise12 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 12',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson4->id,
            'previous_exercise_id' => $exercise11->id,
            'user_id' => $user->id,
        ]);

        $exercise13 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 13',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson4->id,
            'previous_exercise_id' => $exercise12->id,
            'user_id' => $user->id,
        ]);

        $exercise14 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 14',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson4->id,
            'previous_exercise_id' => $exercise13->id,
            'user_id' => $user->id,
        ]);

        $lesson5 = Lesson::create([
            'name' => 'Test Lesson 5',
            'module_id' => $module4->id,
            'previous_lesson_id' => $lesson4->id,
            'user_id' => $user->id,
        ]);

        $exercise15 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 15',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson5->id,
            'user_id' => $user->id,
        ]);

        $exercise16 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 16',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson5->id,
            'previous_exercise_id' => $exercise15->id,
            'user_id' => $user->id,
        ]);

        $project6 = Project::create([
            'name' => 'Test Project 6',
            'open_date' => '2018-06-16 02:01:54',
            'close_date' => '2018-06-17 02:01:54',
            'prompt' => 'Prompt 6',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module4->id,
            'previous_lesson_id' => $lesson5->id,
            'user_id' => $user->id,
        ]);

        $module5 = Module::create([
            'name' => 'Test Module 5',
            'open_date' => '2018-06-19 02:01:54',
            'concept_id' => $concept2->id,
            'previous_module_id' => $module4->id,
            'user_id' => $user->id,
        ]);

        $lesson6 = Lesson::create([
            'name' => 'Test Lesson 6',
            'module_id' => $module5->id,
            'user_id' => $user->id,
        ]);

        $exercise17 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 17',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson6->id,
            'user_id' => $user->id,
        ]);

        $exercise18 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 18',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson6->id,
            'previous_exercise_id' => $exercise17->id,
            'user_id' => $user->id,
        ]);

        $exercise19 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 19',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson6->id,
            'previous_exercise_id' => $exercise18->id,
            'user_id' => $user->id,
        ]);

        $exercise20 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 20',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson6->id,
            'previous_exercise_id' => $exercise19->id,
            'user_id' => $user->id,
        ]);

        $exercise21 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 21',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson6->id,
            'previous_exercise_id' => $exercise20->id,
            'user_id' => $user->id,
        ]);

        $project7 = Project::create([
            'name' => 'Test Project 7',
            'open_date' => '2018-06-23 02:01:54',
            'close_date' => '2018-06-25 02:01:54',
            'prompt' => 'Prompt 7',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module5->id,
            'previous_lesson_id' => $lesson6->id,
            'user_id' => $user->id,
        ]);

        $module6 = Module::create([
            'name' => 'Test Module 6',
            'open_date' => '2018-06-26 02:01:54',
            'concept_id' => $concept2->id,
            'previous_module_id' => $module5->id,
            'user_id' => $user->id,
        ]);

        $project8 = Project::create([
            'name' => 'Test Project 8',
            'open_date' => '2018-06-30 02:01:54',
            'close_date' => '2018-07-01 02:01:54',
            'prompt' => 'Prompt 8',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module6->id,
            'user_id' => $user->id,
        ]);

        $concept3 = Concept::create([
            'name' => 'Test Concept 3',
            'course_id' => $course->id,
            'previous_concept_id' => $concept2->id,
            'user_id' => $user->id,
        ]);

        $module7 = Module::create([
            'name' => 'Test Module 7',
            'open_date' => '2018-07-03 02:01:54',
            'concept_id' => $concept3->id,
            'user_id' => $user->id,
        ]);

        $lesson7 = Lesson::create([
            'name' => 'Test Lesson 7',
            'module_id' => $module7->id,
            'user_id' => $user->id,
        ]);

        $exercise22 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 22',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson7->id,
            'user_id' => $user->id,
        ]);

        $lesson8 = Lesson::create([
            'name' => 'Test Lesson 8',
            'module_id' => $module7->id,
            'previous_lesson_id' => $lesson7->id,
            'user_id' => $user->id,
        ]);

        $exercise23 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 23',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson8->id,
            'user_id' => $user->id,
        ]);

        $project9 = Project::create([
            'name' => 'Test Project 9',
            'open_date' => '2018-07-06 02:01:54',
            'close_date' => '2018-07-08 02:01:54',
            'prompt' => 'Prompt 9',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module7->id,
            'previous_lesson_id' => $lesson8->id,
            'user_id' => $user->id,
        ]);

        $module8 = Module::create([
            'name' => 'Test Module 8',
            'open_date' => '2018-07-11 02:01:54',
            'concept_id' => $concept3->id,
            'previous_module_id' => $module7->id,
            'user_id' => $user->id,
        ]);

        $project10 = Project::create([
            'name' => 'Test Project 10',
            'open_date' => '2018-07-15 02:01:54',
            'close_date' => '2018-07-17 02:01:54',
            'prompt' => 'Prompt 10',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module8->id,
            'user_id' => $user->id,
        ]);

        $lesson9 = Lesson::create([
            'name' => 'Test Lesson 9',
            'module_id' => $module8->id,
            'user_id' => $user->id,
        ]);

        $exercise24 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 24',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson9->id,
            'user_id' => $user->id,
        ]);

        $project11 = Project::create([
            'name' => 'Test Project 11',
            'open_date' => '2018-07-20 02:01:54',
            'close_date' => '2018-07-26 02:01:54',
            'prompt' => 'Prompt 11',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module8->id,
            'previous_lesson_id' => $lesson9->id,
            'user_id' => $user->id,
        ]);

        $module9 = Module::create([
            'name' => 'Test Module 9',
            'open_date' => '2018-07-28 02:01:54',
            'concept_id' => $concept3->id,
            'previous_module_id' => $module8->id,
            'user_id' => $user->id,
        ]);

        $lesson10 = Lesson::create([
            'name' => 'Test Lesson 10',
            'module_id' => $module9->id,
            'user_id' => $user->id,
        ]);

        $exercise25 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 25',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson10->id,
            'user_id' => $user->id,
        ]);

        $lesson11 = Lesson::create([
            'name' => 'Test Lesson 11',
            'module_id' => $module9->id,
            'previous_lesson_id' => $lesson10->id,
            'user_id' => $user->id,
        ]);

        $exercise26 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 26',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson11->id,
            'user_id' => $user->id,
        ]);

        $module10 = Module::create([
            'name' => 'Test Module 10',
            'open_date' => '2018-08-01 02:01:54',
            'concept_id' => $concept3->id,
            'previous_module_id' => $module9->id,
            'user_id' => $user->id,
        ]);

        $project12 = Project::create([
            'name' => 'Test Project 12',
            'open_date' => '2018-08-04 02:01:54',
            'close_date' => '2018-08-05 02:01:54',
            'prompt' => 'Prompt 12',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module10->id,
            'user_id' => $user->id,
        ]);

        $project13 = Project::create([
            'name' => 'Test Project 13',
            'open_date' => '2018-08-05 02:01:54',
            'close_date' => '2018-08-06 02:01:54',
            'prompt' => 'Prompt 13',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module10->id,
            'user_id' => $user->id,
        ]);

        $module11 = Module::create([
            'name' => 'Test Module 11',
            'open_date' => '2018-08-08 02:01:54',
            'concept_id' => $concept3->id,
            'previous_module_id' => $module10->id,
            'user_id' => $user->id,
        ]);

        $project14 = Project::create([
            'name' => 'Test Project 14',
            'open_date' => '2018-08-10 02:01:54',
            'close_date' => '2018-08-12 02:01:54',
            'prompt' => 'Prompt 14',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module11->id,
            'user_id' => $user->id,
        ]);

        $project15 = Project::create([
            'name' => 'Test Project 15',
            'open_date' => '2018-08-13 02:01:54',
            'close_date' => '2018-08-14 02:01:54',
            'prompt' => 'Prompt 15',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module11->id,
            'user_id' => $user->id,
        ]);

        $lesson12 = Lesson::create([
            'name' => 'Test Lesson 12',
            'module_id' => $module11->id,
            'user_id' => $user->id,
        ]);

        $exercise27 = Exercise::create([
            'prompt' => 'This is the prompt of Exercise 27',
            'pre_code' => 'def myFunc():\n    print("hello world")',
            'start_code' => '#this is the code the user will start with',
            'test_code' => 'test_out("hello world")',
            'lesson_id' => $lesson12->id,
            'user_id' => $user->id,
        ]);

        $project16 = Project::create([
            'name' => 'Test Project 16',
            'open_date' => '2018-08-15 02:01:54',
            'close_date' => '2018-08-16 02:01:54',
            'prompt' => 'Prompt 16',
            'pre_code' => '#this is project pre code',
            'start_code' => '#this is project start code',
            'module_id' => $module11->id,
            'previous_lesson_id' => $lesson12->id,
            'user_id' => $user->id,
        ]);

        // Get Teacher role id
        $teacher_role_id = DB::table('roles')->where('name', Roles::TEACHER)->first()->id;
        // Get Teaching Assistant role id
        $assist_role_id = DB::table('roles')->where('name', Roles::TEACHING_ASSISTANT)->first()->id;
        // Get Student role id
        $student_role_id = DB::table('roles')->where('name', Roles::STUDENT)->first()->id;

        // Add the owner of the course as a teacher in the course
        $course->users()->attach([
            $course->user->id => ['role_id' => $teacher_role_id]
        ]);

        // Add users to the course as teaching assistants
        $course->users()->attach([
            DB::table('users')->where('name', 'Teaching Assistant 1')->first()->id => ['role_id' => $assist_role_id]
        ]);

        // Add users to the course as students
        $course->users()->attach([
            DB::table('users')->where('name', 'Test Student 1')->first()->id => ['role_id' => $student_role_id],
            DB::table('users')->where('name', 'Test Student 2')->first()->id => ['role_id' => $student_role_id],
            DB::table('users')->where('name', 'Test Student 3')->first()->id => ['role_id' => $student_role_id]
        ]);
    }
}
