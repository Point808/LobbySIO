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

namespace App\LobbySIO\Misc;
use App\LobbySIO\Config\Registry;

/**
 * Miscellaneous junk probably not even deserving of a class but whatever
 *
 * @author josh.north
 */
class StaticFunctions {
    public function getVersion ($app_disp_lang) {
        $Translate = new \App\LobbySIO\Language\Translate($app_disp_lang);
        $transLang =  $Translate->userLanguage();
        echo $transLang['SOFTWARE_VERSION'] . ': lobbysio_v0.16-beta';
    }

    public function getUTC () {
        return gmdate('Y-m-d H:i:s');
    }

    public function getTitle ($app_current_pagename, $app_disp_lang) {
        $Translate = new \App\LobbySIO\Language\Translate($app_disp_lang);
        $transLang =  $Translate->userLanguage();
        echo Registry::ORGANIZATION . " > " . $transLang['APP_NAME'] . " > " . $app_current_pagename;
    }

    public function getDefaultLanguage () {
        return Registry::DEFAULTLANGUAGE;
    }

    public function getDefaultTZ () {
        return Registry::DEFAULTTZ;
    }

    public function getLogo () {
        if(file_exists('assets/logo-small.png')) {
            return 'assets/logo-small.png';
        } else {
            return 'assets/logo-small.example.png';
        }
    }

    public function getRules () {
        if(file_exists('assets/Rules.pdf')) {
            return 'assets/Rules.pdf';
        } else {
            return 'assets/Rules.example.pdf';
        }
    }

    public function getLogoText () {
        if(file_exists('assets/logo-text.png')) {
            return 'assets/logo-text.png';
        } else {
            return 'assets/logo-text.example.png';
        }
    }

    public function getPageRows () {
        return Registry::ROWSPERPAGE;
    }
    
    public function getMinPass () {
        return Registry::MINPASS;
    }
    
    public function killSession() {
        session_unset();
        session_destroy();
        session_write_close();
        header("Location: index.php");
    }

    public function getFooter () {
        echo Registry::DEFAULTLANGUAGE;
    }

    public function getSessionStatus () {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['signature']) || !isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] != true || $_SESSION['signature'] != md5($_SESSION['user_id'] . $_SERVER['HTTP_USER_AGENT'])) {
            return false;
        } else {
            return true;
        }
    }


}
