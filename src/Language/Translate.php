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

namespace App\LobbySIO\Language;
use App\LobbySIO\Config\Registry;

/**
 * Language return
 *
 * @author josh.north
 */
class Translate {

    private $UserLng;
    private $langSelected;
    public $lang = array();
    public function __construct($userLanguage){
        $this->UserLng = $userLanguage;
        //construct lang file
        $langFile = Registry::DIRPATH . 'src/Language/'. $this->UserLng . '.lang.ini';
        if(!file_exists($langFile)){
            throw new \Exception("Language could not be loaded"); //or default to a language
        }
        $this->lang = parse_ini_file($langFile);
    }
    public function userLanguage(){
        return $this->lang;
    }


}
