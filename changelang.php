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

    $app_disp_lang = filter_input(INPUT_POST, 'app_disp_lang');                 // GET SANITARY LANG CHOICE
    setcookie ( 'app_disp_lang', $app_disp_lang, time() + 60*60*24*90);         // SET LONG COOKIE
    header('Location: index.php');                                              // GO HOME UNTIL WE ADD REFERER LOGIC
