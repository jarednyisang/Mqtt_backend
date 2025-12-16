<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TBSurveyResponse extends Model
{
    protected $connection = "chloride";
    public $table = "surveys_response";
    public $primaryKey = "id";
    protected $guarded = [];
    public $timestamps = false;

    /**
     *
     * Get using filter
     * @param array $filter
     * @param bool $single
     * @return TBSurveyResponse[]|Collection
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

   public function survey()
    {
       
        return $this->belongsTo(TBSurvey::class,'survey_id','id');
    }

}