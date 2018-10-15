<?php
$StaticFunctions = new \App\LobbySIO\Misc\StaticFunctions();        // INSTANTIATE CLASSES
?>
        <div class="container">
            <div class="row">
                <div class="col-sm text-muted"><?php echo $transLang['SERVER_TIME'] . ": " . $StaticFunctions->getUTC(); ?></div>
                <div class="col-sm text-muted"><?php echo $transLang['LOCAL_TIME'] . ": " . $timenow; ?></div>
                <div class="col-sm text-muted"><?php echo $StaticFunctions->getVersion($app_disp_lang); ?></div>
            </div>
        </div>
        <script src="js/ie10-viewport-bug-workaround.js"></script>
        <script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
