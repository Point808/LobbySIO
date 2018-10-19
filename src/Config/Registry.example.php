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

namespace App\LobbySIO\Config;

/**
 * Configuration registry for the application
 * Customize this file to your environment.  Directory paths should
 * end in slash and be absolute.
 *
 * @author josh.north
 */

class Registry { const
    DIRPATH             = '/var/www/html/lsio/',                                // Filesystem base dir
    DB_DRVR             = 'mysql',                                              // PDO Driver
    DB_HOST             = 'localhost',                                          // DB Host
    DB_USER             = 'lsio_user',                                          // DB Username
    DB_PASS             = 'yoursecret',                                         // DB Password
    DB_NAME             = 'lsio',                                               // DB Name
    DB_PRFX             = 'lsio_',                                              // DB table prefix
    ORGANIZATION        = 'Widgets, Inc',                                       // Organization name
    DEFAULTLANGUAGE     = 'en',                                                 // Default language - make sure a translation file exists
    ROWSPERPAGE         = '10',                                                 // Rows per page on tables (does not include reports)
    MINPASS             = '8',                                                  // Minimum password length
    DEFAULTTZ           = 'America/New_York'                                    // DEFAULT TIME ZONE
;}
