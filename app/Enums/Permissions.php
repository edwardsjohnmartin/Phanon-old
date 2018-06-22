<?php
namespace App\Enums;

use App\Enums\Enum;

class Permissions extends Enum
{
    //TODO: Rename the admin permission to something better
    const ADMIN = 'Administer roles & permissions';

    const COURSE_VIEW = 'course.view';
    const COURSE_CREATE = 'course.create';
    const COURSE_EDIT = 'course.edit';
    const COURSE_DELETE = 'course.delete';

    const CONCEPT_VIEW = 'concept.view';
    const CONCEPT_CREATE = 'concept.create';
    const CONCEPT_EDIT = 'concept.edit';
    const CONCEPT_DELETE = 'concept.delete';

    const MODULE_VIEW = 'module.view';
    const MODULE_CREATE = 'module.create';
    const MODULE_EDIT = 'module.edit';
    const MODULE_DELETE = 'module.delete';

    const LESSON_VIEW = 'lesson.view';
    const LESSON_CREATE = 'lesson.create';
    const LESSON_EDIT = 'lesson.edit';
    const LESSON_DELETE = 'lesson.delete';

    const EXERCISE_VIEW = 'exercise.view';
    const EXERCISE_CREATE = 'exercise.create';
    const EXERCISE_EDIT = 'exercise.edit';
    const EXERCISE_DELETE = 'exercise.delete';
    const EXERCISE_AUTOCOMPLETE = "exercise.autocomplete";

    const PROJECT_VIEW = 'project.view';
    const PROJECT_CREATE = 'project.create';
    const PROJECT_EDIT = 'project.edit';
    const PROJECT_DELETE = 'project.delete';
}