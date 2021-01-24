<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessLog extends Model
{
    public $timestamps = true;
    public $table = 'access_log';
    protected $appends = ['type_name'];
    
    use SoftDeletes;

    const TYPES = [
        '1' => 'ENTER',
        '0' => 'EXIT',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'building_id',
        'block',
        'date',
        'type',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function getTypeNameAttribute()
    {
        return $this::TYPES[$this->type];
    }
}
