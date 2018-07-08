<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Enums\Roles;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id user's name 
 * @property string $name user's name 
 * @property string $email user's name 
 * @property string $pasword user's name 
 * @property \datetime $created_at when this object was added to the database
 * @property \datetime $updated_at when this object was last changed in the database
 */

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
        return $this->hasMany('App\Course', 'owner_id');
    }

    /**
     * Relationship function
     * Returns an array of courses this user is in
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class)->withPivot('role_id');
    }

    /**
     * Returns an array of all courses this user is participating in with the specified role
     */
    public function getCoursesByRole($role_id)
    {
        // Validate the role being requested exists
        if(Role::where('id', '=', $role_id)->exists()){
            return $this->enrolledCourses()->wherePivot('role_id', $role_id)->get();
        }
    }

    public function teams()
    {
        return $this->belongsToMany('App\Team');
    }

    public function isAdmin()
    {
        foreach ($this->roles as $role){
            if ($role->name == Roles::ADMIN){
                return true;
            }
        }

        return false;
    }
}
