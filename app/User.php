<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Enums\Roles;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * Relationship function
     * Returns an array of courses this user has created
     */
    public function courses()
    {
        return $this->hasMany('App\Course');
    }

    /**
     * Relationship function
     * Returns an array of courses this user is in
     */
    public function inCourses()
    {
        return $this->belongsToMany(Course::class);
    }

    /**
     * Relationship function
     * Returns an array of courses this user is a student in
     */
    public function takingCourses()
    {
        return $this->belongsToMany(Course::class)->wherePivot('role_id', DB::table('roles')->where('name', Roles::STUDENT)->first()->id);
    }

    /**
     * Relationship function
     * Returns an array of courses this user is a teaching assistant in
     */
    public function assistingCourses()
    {
        return $this->belongsToMany(Course::class)->wherePivot('role_id', DB::table('roles')->where('name', Roles::TEACHING_ASSISTANT)->first()->id);
    }

    /**
     * Relationship function
     * Returns an array of courses this user is a teacher in
     */
    public function teachingCourses()
    {
        return $this->belongsToMany(Course::class)->wherePivot('role_id', DB::table('roles')->where('name', Roles::TEACHER)->first()->id);
    }
}
