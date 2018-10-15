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
 * Get visit type info as array by visit type id.  Pass % for all.
 *
 * @author josh.north
 */

class VisitTypeInfo {
    public function getVisitTypeInfo ($visittypeid){
        $query = "
        SELECT
        " . Registry::DB_PRFX . "visittypes.id as visittypes_id,
        " . Registry::DB_PRFX . "visittypes.name as visittypes_name
        FROM " . Registry::DB_PRFX . "visittypes
        WHERE " . Registry::DB_PRFX . "visittypes.id LIKE \"$visittypeid\"";
        $database = new \App\LobbySIO\Database\Connect();
        $rows = $database->getQuery($query);
        return $rows;
    }

}
