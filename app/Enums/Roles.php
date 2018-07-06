<?php
namespace App\Enums;

use App\Enums\Enum;
use Spatie\Permission\Models\Role;

class Roles extends Enum
{
    // Admins will have every permission on the website
    const ADMIN = 'Admin';
    
    // Power User
    const POWER_USER = 'Power User';

    // Teachers will be able to create/edit/delete their own courses
    // They will also have the ability to view other teachers courses to clone if they desire
    const TEACHER = 'Teacher';

    // Teaching Assistants will be a helper to a teacher
    // They will be able to edit courses they are assigned to
    const TEACHING_ASSISTANT = 'Teaching Assistant';

    // Students will be able to participate in courses they are assigned to
    const STUDENT = 'Student';

    // Observers will be able to view content on the website but not be able to interact with anything
    // This will be for people who want to demo the website without needing to be assigned to a course
    const OBSERVER = 'Observer';

    public static function id($name)
    {
        return Role::select('id')->where('name', $name)->first()->id;
    }
}
