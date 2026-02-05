<?php

namespace App\Http\Traits;
use App\Models\ActivityLog;

trait LeadActivityTrait
{
    public function leadactivity($logArray=array())
    {
        $log = ActivityLog::create($logArray);
    }
}


?>