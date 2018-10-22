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
 * Get site info as array by site id.  Pass % for all.
 *
 * @author josh.north
 */

class SiteInfo {

// Return Site Information array.  0 eliminates argument
    public function getSite ($sites_id, $users_id, $rowsperpage, $offset) {
        if ($sites_id == "0") { $c_sites_id = NULL; }
            else { $c_sites_id = Registry::DB_PRFX . "sites.id LIKE \"$sites_id\""; }
        if ($users_id == "0") { $c_users_id = NULL; }
            else { $c_users_id = Registry::DB_PRFX . "users_sites.users_id LIKE \"$users_id\""; }
        
        if ($c_sites_id === NULL AND $c_users_id === NULL) {
            $c_where = NULL;
        } elseif ($c_sites_id !== NULL AND $c_users_id !== NULL) {
            $c_where = "WHERE " . $c_sites_id . " AND " . $c_users_id;
        } elseif ($c_sites_id === NULL AND $c_users_id !== NULL) {
            $c_where = "WHERE " . $c_users_id;
        } elseif ($c_sites_id !== NULL AND $c_users_id === NULL) {
            $c_where = "WHERE " . $c_sites_id;
        }
        
        if ($rowsperpage == "0") { $c_rowsperpage = NULL; }
            else { $c_rowsperpage = " LIMIT " . Registry::ROWSPERPAGE; }
        if ($offset == "0") { $c_offset = NULL; }
            else { $c_offset = " OFFSET " . $offset; }
        $query = "
            SELECT
            " . Registry::DB_PRFX . "sites.id as sites_id,
            " . Registry::DB_PRFX . "sites.name as sites_name,
            " . Registry::DB_PRFX . "sites.region as sites_region,
            " . Registry::DB_PRFX . "sites.timezone as sites_timezone,
            " . Registry::DB_PRFX . "users_sites.users_id as users_sites_users_id
            FROM " . Registry::DB_PRFX . "sites
            JOIN " . Registry::DB_PRFX . "users_sites ON " . Registry::DB_PRFX . "sites.id=" . Registry::DB_PRFX . "users_sites.sites_id
            " . $c_where . "
            ORDER BY " . Registry::DB_PRFX . "sites.name ASC" . $c_rowsperpage . $c_offset;
        $database = new \App\LobbySIO\Database\Connect();
        $rows = $database->getQuery($query);
        return $rows;
    }

    public function deleteSite ($siteid) {
        $query = "
            DELETE FROM " . Registry::DB_PRFX . "sites WHERE " . Registry::DB_PRFX . "sites.id=\"$siteid\"
        ";
        $database = new \App\LobbySIO\Database\Connect();
        $count = $database->runQuery($query);
        return $count;
    }

    public function addSite ($sitename, $timezone, $region) {
        $query = "
            INSERT INTO " . Registry::DB_PRFX . "sites (" . Registry::DB_PRFX . "sites.name, " . Registry::DB_PRFX . "sites.timezone, " . Registry::DB_PRFX . "sites.region)
            VALUES (\"$sitename\", \"$timezone\", \"$region\")
        ";
        $database = new \App\LobbySIO\Database\Connect();
        $count = $database->runQuery($query);
        return $count;
    }

}
