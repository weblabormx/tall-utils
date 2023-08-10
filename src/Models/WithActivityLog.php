<?php

namespace WeblaborMx\TallUtils\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Adds ActivityLog with an opinionated configuration
 * and a `$dont_log` option
 */
trait WithActivityLog
{
    use LogsActivity;

    /** 
     * Array of model attributes to not Log by ActivityLog
     * 
     * @var array 
     */
    protected $dont_log = [];

    public function getActivitylogOptions(): LogOptions
    {
        $dontLog = ['updated_at', ...$this->dont_log];

        return LogOptions::defaults()
            ->dontLogIfAttributesChangedOnly($dontLog)
            ->logExcept($dontLog)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
