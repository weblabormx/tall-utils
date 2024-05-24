<?php

namespace WeblaborMx\TallUtils\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Adds ActivityLog with an opinionated configuration
 * 
 * @property string[] $dont_log Array of model attributes to exclude from activity log
 */
trait WithActivityLog
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        $dontLog = ['updated_at', ...($this?->dont_log ?? [])];

        return LogOptions::defaults()
            ->logUnguarded()
            ->dontLogIfAttributesChangedOnly($dontLog)
            ->logExcept($dontLog)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
