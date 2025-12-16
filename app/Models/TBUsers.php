<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TBUsers extends Model
{
    protected $connection = "surveyhub";
    public $table = "systemusers";
    public $primaryKey = "id";
    protected $guarded = [];
    public $timestamps = false;

    /**
     *
     * Get using filter
     * @param array $filter
     * @param bool $single
     * @return TBUsers[]|Collection
     */
    public static function getWhere(array $filter,$single = false)
    {
        $criteria = 'get';
        if ($single)
            $criteria = 'first';

        if ($filter)
            return DB::connection((new self())->connection)
                ->table((new self())->table)->select('*')
                ->where($filter)->$criteria();

        return self::all();
    }

     public function country()
    {
       
        return $this->belongsTo(TBCountries::class,'countryid','id');
    }



}