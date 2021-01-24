<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }
    
    public function latestAccess()
    {
        return $this->hasOne(AccessLog::class)->latest('id');
    }

    public function scopeFilters($query, Request $request)
    {
        return $query
        ->when($request->has('sortColumn') && $request->has('sortDirection'), function ($query) use ($request)
        {
            $query->orderBy($request->sortColumn, $request->sortDirection);
        })
        ->when($request->has('name') , function ($query) use ($request)
        {
            $query->where('name', 'like', $request->name);
        })
        ->when($request->has('email') , function ($query) use ($request)
        {
            $query->where('email', 'like', $request->email);
        })
        ->paginate();
    }
}
