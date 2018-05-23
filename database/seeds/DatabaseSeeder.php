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
use App\Concept;
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

		$project1 = Project::create([
			'name' => 'Test Project 1',
			'open_date' => '2018-05-15 02:01:54',
            'close_date' => '2018-05-15 02:01:54',
            'prompt' => 'Prompt 1',
			'pre_code' => '#this is project pre code',
			'start_code' => '#this is project start code',
            'module_id' => $module1->id,
            'previous_lesson_id' => $lesson2->id,
			'user_id' => $user->id,
        ]);
        
        $module2 = Module::create([
			'name' => 'Test Module 2',
            'open_date' => '2018-05-17 02:01:54',
            'concept_id' => $concept1->id,
            'previous_module_id' => $module1->id,
			'user_id' => $user->id,
        ]);
        
        $project2 = Project::create([
			'name' => 'Test Project 2',
			'open_date' => '2018-05-15 02:01:54',
            'close_date' => '2018-05-15 02:01:54',
            'prompt' => 'Prompt 2',
			'pre_code' => '#this is project pre code',
			'start_code' => '#this is project start code',
            'module_id' => $module2->id,
			'user_id' => $user->id,
        ]);

        $module3 = Module::create([
			'name' => 'Test Module 3',
            'open_date' => '2018-05-17 02:01:54',
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
			'open_date' => '2018-05-15 02:01:54',
            'close_date' => '2018-05-15 02:01:54',
            'prompt' => 'Prompt 3',
			'pre_code' => '#this is project pre code',
			'start_code' => '#this is project start code',
            'module_id' => $module3->id,
            'previous_lesson_id' => $lesson3->id,
			'user_id' => $user->id,
        ]);

        $project4 = Project::create([
			'name' => 'Test Project 4',
			'open_date' => '2018-05-15 02:01:54',
            'close_date' => '2018-05-15 02:01:54',
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
            'open_date' => '2018-05-15 02:01:54',
            'concept_id' => $concept2->id,
			'user_id' => $user->id,
        ]);

        $project5 = Project::create([
			'name' => 'Test Project 5',
			'open_date' => '2018-05-15 02:01:54',
            'close_date' => '2018-05-15 02:01:54',
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
			'open_date' => '2018-05-15 02:01:54',
            'close_date' => '2018-05-15 02:01:54',
            'prompt' => 'Prompt 6',
			'pre_code' => '#this is project pre code',
			'start_code' => '#this is project start code',
            'module_id' => $module4->id,
            'previous_lesson_id' => $lesson5->id,
			'user_id' => $user->id,
        ]);
        
        $module5 = Module::create([
			'name' => 'Test Module 5',
            'open_date' => '2018-05-15 02:01:54',
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
			'open_date' => '2018-05-15 02:01:54',
            'close_date' => '2018-05-15 02:01:54',
            'prompt' => 'Prompt 7',
			'pre_code' => '#this is project pre code',
			'start_code' => '#this is project start code',
            'module_id' => $module5->id,
            'previous_lesson_id' => $lesson6->id,
			'user_id' => $user->id,
        ]);
        
        $module6 = Module::create([
			'name' => 'Test Module 6',
            'open_date' => '2018-05-15 02:01:54',
            'concept_id' => $concept2->id,
            'previous_module_id' => $module5->id,
			'user_id' => $user->id,
        ]);
        
        $project8 = Project::create([
			'name' => 'Test Project 8',
			'open_date' => '2018-05-15 02:01:54',
            'close_date' => '2018-05-15 02:01:54',
            'prompt' => 'Prompt 8',
			'pre_code' => '#this is project pre code',
			'start_code' => '#this is project start code',
            'module_id' => $module6->id,
			'user_id' => $user->id,
        ]);
	}
}
