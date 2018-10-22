<?php

/*
 * Copyright (C) 2018 josh.north
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\LobbySIO\Database;
use App\LobbySIO\Config\Registry;

/**
 * Get visit info as array by visit id.  Pass % for all.
 * TODO - break into select sections for speed by pagination
 * Pass NULL for nulls, % for any not null
 * 
 * @author josh.north
 */

class VisitInfo {
    // Pass "empty" to get unset or empty valued rows, pass "%" for all rows, or pass int/string for 1 row.
    public function getVisitInfo ($siteid, $approved, $outtime, $visitid, $intime, $starttime, $endtime, $rowsperpage, $offset){
        if ($outtime === "empty") { 
            $cond_outtime = Registry::DB_PRFX . "visits.outtime IS NULL AND "; 
        } elseif ($outtime == "%") { 
            $cond_outtime = NULL;
        } else {
            $cond_outtime = Registry::DB_PRFX . "visits.outtime LIKE \"$outtime\" AND "; 
        };
        if ($rowsperpage == "%") { $cond_rowsperpage = NULL; } else { $cond_rowsperpage = " LIMIT " . Registry::ROWSPERPAGE; };
        if ($offset == "%") { $cond_offset = NULL; } else { $cond_offset = " OFFSET " . $offset; };
        if ($intime == "%") { $cond_intime = NULL; } else { $cond_intime = Registry::DB_PRFX . "visits.intime=\"$intime\" AND "; };
        if ($siteid == "%") { $cond_siteid = NULL; } else { $cond_siteid = Registry::DB_PRFX . "visits.site_id IN (\"$siteid\") AND "; };
        if ($visitid == "%") { $cond_visitid = NULL; } else { $cond_visitid = Registry::DB_PRFX . "visits.id LIKE \"$visitid\" AND "; };
        if ($starttime == "%") { $cond_intime = NULL; } else { $cond_intime = Registry::DB_PRFX . "visits.intime BETWEEN \"$starttime\" and \"$endtime\" AND "; };
        $query = "
        SELECT
        " . Registry::DB_PRFX . "visits.id as visits_id,
        " . Registry::DB_PRFX . "visits.intime as visits_intime,
        " . Registry::DB_PRFX . "visits.outtime as visits_outtime,
        " . Registry::DB_PRFX . "visits.firstname as visits_firstname,
        " . Registry::DB_PRFX . "visits.lastname as visits_lastname,
        " . Registry::DB_PRFX . "visits.signature as visits_signature,
        " . Registry::DB_PRFX . "visits.escort as visits_escort,
        " . Registry::DB_PRFX . "visits.escort_signature as visits_escort_signature,
        " . Registry::DB_PRFX . "visits.reason as visits_reason,
        " . Registry::DB_PRFX . "visits.citizen as visits_citizen,
        " . Registry::DB_PRFX . "visits.id_type as visits_id_type,
        " . Registry::DB_PRFX . "visits.id_checked as visits_id_checked,
        " . Registry::DB_PRFX . "visits.initials as visits_initials,
        " . Registry::DB_PRFX . "visits.badge as visits_badge,
        " . Registry::DB_PRFX . "visits.site_id as visits_site_id,
        " . Registry::DB_PRFX . "visits.company as visits_company,
        " . Registry::DB_PRFX . "visits.approved as visits_approved,
        " . Registry::DB_PRFX . "visits.approved as visits_carnum,
        " . Registry::DB_PRFX . "visits.approved as visits_regnum
        FROM " . Registry::DB_PRFX . "visits
        WHERE " . $cond_siteid . Registry::DB_PRFX . "visits.approved>=\"$approved\" AND " . $cond_outtime  . $cond_intime  . Registry::DB_PRFX . "visits.id LIKE \"$visitid\"" . $cond_rowsperpage . $cond_offset;
        $database = new \App\LobbySIO\Database\Connect();
        $rows = $database->getQuery($query);
        return $rows;
    }

    
}
