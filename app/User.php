<?php

namespace App;

use App\Models\City;
use App\Models\Country;
use App\Models\Employee;
use App\Models\IdentificationType;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    const ROL_VENDEDOR = 'Vendedor';

    const ACTIVE = 1;
    const INACTIVE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $fillable = [
        'name', 'last_name', 'email', 'password', 'slug', 'address',
        'phone', 'identification', 'identification_type_id',
        'picture'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function identificationType()
    {
        return $this->belongsTo(IdentificationType::class, 'identification_type_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function employes(){
        return $this->hasOne(Employee::class);
    }

    static public function roleAuth(){
        $user = User::where('id', auth()->user()->id)->with('roles')->first();
        return $user;
    }

    static public function roleUserVendedor(){
        return auth()->user()->hasRole('Vendedor');
    }

    static public function user(){
        return auth()->user();
    }
}
