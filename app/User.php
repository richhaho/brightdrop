<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MailResetPasswordToken;

use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait { restore as private restore_entrust; }
     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','worker_id'
    ];
    public $account;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function restore()
    {
        $this->restore_entrust();
         
    }
    // NOTE: Future implementation for time stamp
    public function roles()
    {
        return $this
            ->belongsToMany('App\Role')
            ->withTimestamps();
    }
    public function worker()
    {
        return $this->hasMany('App\Workers')->where('deleted_at',null)->first();
    }
    public function accountmanager()
    {
        return $this->hasMany('App\AccountManagers')->where('deleted_at',null)->first();
    }
    public function payrollmanager()
    {
        return $this->hasMany('App\PayrollManagers')->where('deleted_at',null)->first();
    }
    public function contact()
    {
        return $this->hasMany('App\Contacts')->where('deleted_at',null)->first();
    }
    public function admin()
    {
        return $this->hasMany('App\Admins')->where('deleted_at',null)->first();
    }

    // Function to call all function for checking against a bunch of roles
    public function checkRoles($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    Session()->put('Role', $role);
                    return $role;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                Session()->put('Role', $roles);
                return $roles;
            }
        }
        return false;
    }

    // Implement hasRole if checking against a single role

    public function hasRole($role)
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }

    /**
     * Send a password reset email to the user
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token));
    }

     
}
