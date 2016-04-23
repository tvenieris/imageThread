<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    public static function increaseViews() {
        $stats = self::find(1);
        if (empty($stats)) {
            $stats = new Stats();
            $stats->id = 1;
            $stats->views = 1;
        } else {
            $stats->views++;
        }
        if ($stats->save()) {
            return $stats;
        }
        abort(500, 'Failed to update views');
    }
    
    public static function getViews() {
        $stats = self::find(1);
        if (empty($stats)) {
            $stats = new Stats();
            $stats->id = 1;
            $stats->views = 0;
        } else {
            $stats->views = intval($stats->views);
        }
        return $stats;

    }
}
