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
 * Database connection class
 *
 * @author josh.north
 */


class Connect {
    public $dbconn;

    // open conn
    public function __construct() {
        $this->openPDO();
    }

    // close conn
    public function __destruct() {
		$this->dbconn = NULL;
	}

    // class-internal to open the connection if not already open
    private function openPDO() {
        if ($this->dbconn == NULL) {
            $connstring = "" . Registry::DB_DRVR . ":host=" . Registry::DB_HOST . ";dbname=" . Registry::DB_NAME;
            $connoptions = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
                ];
            try {
                $this->dbconn = new \PDO( $connstring, Registry::DB_USER, Registry::DB_PASS, $connoptions );
            } catch( \PDOException $e ) {
                echo __LINE__.$e->getMessage();
            }
        }
    }

    // insert or update something
    public function runQuery( $sql ) {
        try {
        	$count = $this->dbconn->exec($sql) or print_r($this->dbconn->errorInfo());
        } catch(\PDOException $e) {
        	echo __LINE__.$e->getMessage();
        }
    }

    // select something
    public function getQuery( $sql ) {
		$stmt = $this->dbconn->query( $sql );
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
		return $stmt->fetchAll();
	}

}
