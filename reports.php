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

    ini_set('session.gc_maxlifetime', 24*60*60);                 // MIN SESSION
    ini_set('session.gc_probability', 1);                        // GC RATES
    ini_set('session.gc_divisor', 100);                          // TIMES
    session_save_path('.tmp');                                   // TEMP
    session_start();                                             // START
    require_once __DIR__ . '/autoload.php';                      // AUTOLOAD
    $StaticFunctions = new \App\LobbySIO\Misc\StaticFunctions(); // CLASSES
    $SiteInfo = new \App\LobbySIO\Database\SiteInfo();
    $VisitTypeInfo = new \App\LobbySIO\Database\VisitTypeInfo();
    $IDTypeInfo = new \App\LobbySIO\Database\IDTypeInfo();
    $VisitInfo = new \App\LobbySIO\Database\VisitInfo();
    if(!isset($_COOKIE['app_disp_lang'])) { $app_disp_lang = $StaticFunctions->getDefaultLanguage(); } else { $app_disp_lang = $_COOKIE['app_disp_lang']; };
    $Translate = new \App\LobbySIO\Language\Translate($app_disp_lang);
    $transLang =  $Translate->userLanguage();                    // SETUP TRANSLATOR
    $app_current_pagename = $transLang['REPORTS'];               // PAGE FUNCTION
    $app_current_pageicon = '<i class="fas fa-chart-pie"></i> '; // PAGE ICON
    require_once("inc/header.inc.php");                          // SHOW HEADER
    if ($StaticFunctions->getSessionStatus() == false) {         // CHECK STATUS
        echo $StaticFunctions->killSession();                    // ELSE DIE
    } else { ?>
<!-- CONTENT START -->

        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <h2><i class="fas fa-chart-pie"></i> <?php echo $transLang['REPORTS']; ?></h2>
                    <p class="lead"><?php echo $transLang['REPORTS_DESC']; ?></p>
                </div>
            </div>
            <form action="reports.php" method="post">
                <fieldset>
                    <div class="form-group row">
                        <div class="col-sm">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><?php echo $transLang['REPORTS']; ?></span>
                                </div>
                                <select name="reporttype" class="form-control">
                                    <?php if (isset($_POST['reporttype'])): ?>
                                    <option value="<?php echo $_POST['reporttype']; ?>" placeholder="<?php echo $transLang['REPORTS']; ?>"><?php echo $_POST['reporttype']; ?></option>
                                    <?php else: ?>
                                    <option value="Default" selected><?php echo $transLang['DEFAULT']; ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3"><?php echo $transLang['SITE']; ?>:</span>
                                </div>
                                <?php if (isset($_POST['repsite'])) { $currentrepsite = $_POST['repsite']; } else { $currentrepsite = "0"; }; ?>
                                <select name="repsite" class="form-control">
                                    <option value="all"<?php if ($currentrepsite == "all") {echo " selected";}; ?>><?php echo $transLang['ALL']; ?></option>
                                    <?php foreach($SiteInfo->getSiteInfo("%") as $row): ?>
                                    <option value="<?php echo $row['sites_id']; ?>"<?php if ($currentrepsite == $row['sites_id']) {echo " selected";}; ?>><?php echo $row['sites_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class='col-sm'>
                            <div class="input-group date" id="datetimepicker-1" data-target-input="#datetimepicker-1">
                                <div class="input-group-prepend" data-target=".datetimepicker-1" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i>&nbsp <?php echo $transLang['START']; ?></div>
                                </div>
                                <input value="<?php if (isset($_POST['starttime'])) { echo $_POST['starttime']; }; ?>" name="starttime" type="text" class="form-control datetimepicker-1" id="datetimepicker-1" data-target=".datetimepicker-1" autocomplete="new-password" required />
                            </div>
                        </div>
                        <div class='col-sm'>
                            <div class="input-group date" id="datetimepicker-2" data-target-input="#datetimepicker-2">
                                <div class="input-group-prepend" data-target=".datetimepicker-2" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i>&nbsp <?php echo $transLang['END']; ?></div>
                                </div>
                                <input value="<?php if (isset($_POST['endtime'])) { echo $_POST['endtime']; }; ?>" name="endtime" type="text" class="form-control datetimepicker-2" id="datetimepicker-2" data-target=".datetimepicker-2" autocomplete="new-password" required />
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function () {
                                $('.datetimepicker-1').datetimepicker({'sideBySide':true, 'format':'YYYY-MM-DD HH:mm:ss'});
                            });
                        </script>
                        <script type="text/javascript">
                            $(function () {
                                $('.datetimepicker-2').datetimepicker({'sideBySide':true, 'format':'YYYY-MM-DD HH:mm:ss'});
                            });
                        </script>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm">
                            <button type="submit" class="form-control btn btn-block btn-primary"><i class="glyphicon glyphicon-play"></i> <?php echo $transLang['SAVE']; ?></button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <?php if (isset($_POST['reporttype'])): ?>
        <?php if ($_POST['reporttype'] == "Default"): ?>
        <div class="container-fluid">
            <table id="report" class="table table-striped table-bordered">
                <thead><tr><th><?php echo $transLang['IN']; ?></th><th><?php echo $transLang['OUT']; ?></th><th><?php echo $transLang['SITE']; ?></th><th><?php echo $transLang['COMPANY']; ?></th><th><?php echo $transLang['REASON']; ?></th><th><?php echo $transLang['NAME']; ?></th><th><?php echo $transLang['ESCORT']; ?></th><th><?php echo $transLang['BADGE']; ?></th><th><?php echo $transLang['INITIALS']; ?></th><th><?php echo $transLang['CITIZEN']; ?></th><th><?php echo $transLang['ID_TYPE']; ?></th><th><?php echo $transLang['ID_CHECKED']; ?></th></tr></thead>
                <tbody>
                    <?php
                        $approval = "2";
                        if ($_POST['repsite'] == "all") { $selsite="%"; } else { $selsite=$_POST['repsite'];}; 
                        foreach ($VisitInfo->getVisitInfo($selsite, $approval, "%", "%", "%", $_POST['starttime'], $_POST['endtime'], "%", "%") as $row):
                        $timein = new DateTime($row['visits_intime'], new DateTimeZone('UTC'));
                        $timeout = new DateTime($row['visits_outtime'], new DateTimeZone('UTC'));
                        $timein->setTimezone(new DateTimeZone("$timezone"));
                        $timeout->setTimezone(new DateTimeZone("$timezone"));
                        $timein_disp = $timein->format('Y-m-d H:i:s');
                        $timeout_disp = $timeout->format('Y-m-d H:i:s');
                    ?>
                    <tr>
                        <td><?php echo $timein_disp; ?></td>
                        <td><?php if (!empty($row['visits_outtime'])) {echo $timeout_disp; } else {echo $transLang['IN'];}; ?></td>
                        <td><?php echo $SiteInfo->getSiteInfo($row['visits_site_id'])[0]["sites_name"]; ?></td>
                        <td><?php echo $row['visits_company']; ?></td>
                        <td><?php echo $transLang[$VisitTypeInfo->getVisitTypeInfo($row['visits_reason'])[0]['visittypes_name']]; ?></td>
                        <td><?php echo $row['visits_lastname'] . ", " . $row['visits_firstname']; ?><br /><img src="<?php echo $row['visits_signature']; ?>" width="200" height="50" alt="Signature" /></td>
                        <td><?php if (!empty($row['visits_escort'])) {echo $row['visits_escort'] . '<br /><img src="' . $row['visits_escort_signature'] . '" width="200" height="50" alt="Escort Signature" />'; } ?></td>
                        <td><?php echo $row['visits_badge']; ?></td>
                        <td><?php echo $row['visits_initials']; ?></td>
                        <td><?php if($row['visits_citizen']==1) { echo $transLang['YESYES']; } else { echo $transLang['NONO']; }; ?></td>
                        <td><?php echo $transLang[$IDTypeInfo->getIDTypeInfo($row['visits_id_type'])[0]['idtypes_name']]; ?></td>
                        <td><?php if($row['visits_id_checked']==1) { echo $transLang['YESYES']; } else { echo $transLang['NONO']; }; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="container">
            <div class="row">
                <p>- - - - - - - - - - -</p>
            </div>
        </div>
        <?php endif; ?>
        <script>
            $(document).ready(function() {
                $('#report').DataTable( {
                    "order": [[ 0, "desc" ]],
                    "lengthMenu": [[50, 100, -1], [50, 100, "All"]],
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'collection',
                            text: '<?php echo $transLang['EXPORT']; ?>',
                            buttons: [
                                {
                                    extend: 'print',
                                    text: '<?php echo $transLang['PRINT']; ?>',
                                    orientation: 'landscape',
                                    autoPrint: false,
                                    exportOptions: {
                                        stripNewlines: false,
                                        stripHtml: false,
                                    },
                                },
                                {
                                    extend: 'pdf',
                                    text: '<?php echo $transLang['PDF']; ?>',
                                    orientation: 'landscape',
                                    exportOptions: {
                                        stripNewlines: false,
                                    },
                                    customize: function (doc) {
                                        doc['content']['1'].layout = 'lightHorizontalLines';
                                    },
                                },
                                {
                                    extend: 'excel',
                                    text: '<?php echo $transLang['EXCEL']; ?>',
                                    orientation: 'landscape'
                                },
                            ]
                        }
                    ],
                } );
            } );
        </script>

<!-- CONTENT END -->
<?php }; require_once("inc/footer.inc.php");
