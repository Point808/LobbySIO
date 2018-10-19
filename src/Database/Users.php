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
 * User management functions
 *
 * @author josh.north
 */
class Users {
    // Get user info as array by user id.  Pass % for all.
    public function getUserInfo($userid, $rowsperpage, $offset) {
        if ($rowsperpage == "%") { $cond_rowsperpage = NULL; } else { $cond_rowsperpage = " LIMIT " . Registry::ROWSPERPAGE; };
        if ($offset == "%") { $cond_offset = NULL; } else { $cond_offset = " OFFSET " . $offset; };
       $query = "
        SELECT
        " . Registry::DB_PRFX . "users.id as users_id,
        " . Registry::DB_PRFX . "users.username as users_username,
        " . Registry::DB_PRFX . "users.email as users_email,
        " . Registry::DB_PRFX . "users.created as users_created,
        " . Registry::DB_PRFX . "users.firstname as users_firstname,
        " . Registry::DB_PRFX . "users.lastname as users_lastname,
        " . Registry::DB_PRFX . "users.usertype as users_usertypeid,
        " . Registry::DB_PRFX . "usertypes.name as users_usertype,
        " . Registry::DB_PRFX . "users.password as users_password
        FROM " . Registry::DB_PRFX . "users
        INNER JOIN " . Registry::DB_PRFX . "usertypes ON " . Registry::DB_PRFX . "users.usertype = " . Registry::DB_PRFX . "usertypes.id
        WHERE " . Registry::DB_PRFX . "users.id LIKE \"$userid\"
        ORDER BY " . Registry::DB_PRFX . "users.lastname ASC" . $cond_rowsperpage . $cond_offset;
        $database = new \App\LobbySIO\Database\Connect();
        $rows = $database->getQuery($query);
        return $rows;
    }

    public function loginUser ($username) {
        $query = "
            SELECT
            " . Registry::DB_PRFX . "users.id as users_id,
            " . Registry::DB_PRFX . "users.password as users_password,
            UNIX_TIMESTAMP(" . Registry::DB_PRFX . "users.created) AS users_salt,
            " . Registry::DB_PRFX . "users.firstname as users_firstname,
            " . Registry::DB_PRFX . "users.lastname as users_lastname
            FROM " . Registry::DB_PRFX . "users
            WHERE " . Registry::DB_PRFX . "users.username = \"$username\"
            ";
        $database = new \App\LobbySIO\Database\Connect();
        $rows = $database->getQuery($query);
        return $rows;
    }

    public function checkUser ($username, $email) {
        $query = "
            SELECT
            " . Registry::DB_PRFX . "users.username as users_username,
            " . Registry::DB_PRFX . "users.email as users_email
            FROM " . Registry::DB_PRFX . "users
            WHERE " . Registry::DB_PRFX . "users.username = \"$username\" OR " . Registry::DB_PRFX . "users.email = \"$email\"
        ";
        $database = new \App\LobbySIO\Database\Connect();
        $rows = $database->getQuery($query);
        return $rows;
    }

    public function addUser ($firstname, $lastname, $username, $timezone, $password, $email, $usertype) {
        $query = "
            INSERT INTO " . Registry::DB_PRFX . "users (" . Registry::DB_PRFX . "users.firstname, " . Registry::DB_PRFX . "users.lastname, " . Registry::DB_PRFX . "users.username, " . Registry::DB_PRFX . "users.timezone, " . Registry::DB_PRFX . "users.password, " . Registry::DB_PRFX . "users.email, " . Registry::DB_PRFX . "users.created, " . Registry::DB_PRFX . "users.usertype)
            VALUES (\"$firstname\", \"$lastname\", \"$username\", \"$timezone\", \"$password\", \"$email\", NOW(), \"$usertype\")
        ";
        $database = new \App\LobbySIO\Database\Connect();
        $count = $database->runQuery($query);
        return $count;
    }

    public function setUserInfo($uid, $firstname, $lastname, $email, $usertypeid, $password) {
        $query = "
            UPDATE
            " . Registry::DB_PRFX . "users
            SET
            " . Registry::DB_PRFX . "users.firstname = \"$firstname\",
            " . Registry::DB_PRFX . "users.lastname = \"$lastname\",
            " . Registry::DB_PRFX . "users.email = \"$email\",
            " . Registry::DB_PRFX . "users.usertype = \"$usertypeid\",
            " . Registry::DB_PRFX . "users.password = \"$password\"
            WHERE " . Registry::DB_PRFX . "users.id = \"$uid\"
            ";
        $database = new \App\LobbySIO\Database\Connect();
        $count = $database->runQuery($query);
        return $count;
    }

    public function getUserType ($usertypeid){
        $query = "
        SELECT
        " . Registry::DB_PRFX . "usertypes.id as usertypes_id,
        " . Registry::DB_PRFX . "usertypes.name as usertypes_name
        FROM " . Registry::DB_PRFX . "usertypes
        WHERE " . Registry::DB_PRFX . "usertypes.id LIKE \"$usertypeid\"";
        $database = new \App\LobbySIO\Database\Connect();
        $rows = $database->getQuery($query);
        return $rows;
    }

    public function deleteUser ($userid) {
        $query = "
            DELETE FROM " . Registry::DB_PRFX . "users WHERE " . Registry::DB_PRFX . "users.id=\"$userid\"
        ";
        $database = new \App\LobbySIO\Database\Connect();
        $count = $database->runQuery($query);
        return $count;
    }

    public function getUserTypeInfo ($usertypeid) {
        $query = "
        SELECT
        " . Registry::DB_PRFX . "usertypes.id as usertypes_id,
        " . Registry::DB_PRFX . "usertypes.name as usertypes_name
        FROM " . Registry::DB_PRFX . "usertypes
        WHERE " . Registry::DB_PRFX . "usertypes.id LIKE \"$usertypeid\"";
        $database = new \App\LobbySIO\Database\Connect();
        $rows = $database->getQuery($query);
        return $rows;
    }


}
