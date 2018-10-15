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
 * Perform visit actions (approve/void/end/new)
 *
 * @author josh.north
 */

class VisitActions {
    public function endVisit ($visitid, $outtime) {
        $query = "
            UPDATE " . Registry::DB_PRFX . "visits
            SET " . Registry::DB_PRFX . "visits.outtime = \"$outtime\"
            WHERE " . Registry::DB_PRFX . "visits.id = \"$visitid\"
        ";
        $database = new \App\LobbySIO\Database\Connect();
        $count = $database->runQuery($query);
    }

    public function voidVisit ($visitid, $approved) {
        $query = "
            UPDATE " . Registry::DB_PRFX . "visits
            SET " . Registry::DB_PRFX . "visits.approved = \"$approved\"
            WHERE " . Registry::DB_PRFX . "visits.id = \"$visitid\"
        ";
        $database = new \App\LobbySIO\Database\Connect();
        $count = $database->runQuery($query);
    }
    
    public function approveVisit ($approvevisit, $id_type, $id_checked, $citizen, $badge, $initials, $approved) {
        $query = "
            UPDATE " . Registry::DB_PRFX . "visits
            SET
            " . Registry::DB_PRFX . "visits.initials = \"$initials\",
            " . Registry::DB_PRFX . "visits.approved = \"$approved\",
            " . Registry::DB_PRFX . "visits.id_type = \"$id_type\",
            " . Registry::DB_PRFX . "visits.id_checked = \"$id_checked\",
            " . Registry::DB_PRFX . "visits.badge = \"$badge\",
            " . Registry::DB_PRFX . "visits.citizen = \"$citizen\"
            WHERE " . Registry::DB_PRFX . "visits.id = \"$approvevisit\"
        ";
        $database = new \App\LobbySIO\Database\Connect();
        $count = $database->runQuery($query);
    }

    public function newVisit ($firstname, $lastname, $company, $reason, $intime, $signature, $siteid, $approved, $escort_signature, $escort) {
        $query = "
            INSERT INTO " . Registry::DB_PRFX . "visits (" . Registry::DB_PRFX . "visits.firstname, " . Registry::DB_PRFX . "visits.lastname,
            " . Registry::DB_PRFX . "visits.company, " . Registry::DB_PRFX . "visits.reason, " . Registry::DB_PRFX . "visits.intime,
            " . Registry::DB_PRFX . "visits.signature, " . Registry::DB_PRFX . "visits.site_id, " . Registry::DB_PRFX . "visits.approved,
            " . Registry::DB_PRFX . "visits.escort_signature, " . Registry::DB_PRFX . "visits.escort)
            VALUES (\"$firstname\", \"$lastname\", \"$company\", \"$reason\", \"$intime\", \"$signature\", \"$siteid\",
            \"$approved\", \"$escort_signature\", \"$escort\")
        ";
        $database = new \App\LobbySIO\Database\Connect();
        $count = $database->runQuery($query);
    }
}
