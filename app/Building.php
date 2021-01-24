<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    // public $timestamps = true;
    // public $table = 'buildings';
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        
    ];


    public function accesLogs()
    {
        return $this->hasMany(AccessLog::class);
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
        ->paginate();
    }
}
