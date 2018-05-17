<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
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
    public function courses(){
        //TODO: Really need a better permission set for this.
        if ($this->hasPermissionTo("Administer roles & permissions")){
            return $this->hasMany('App\Course');
        }            else{
            return $this->hasMany('App\Course');
        }
    }
}
