<?php
$date = date('Y-m-d'); 
$dj = new SaeDeferredJob(); 
$taskID = $dj->addTask("export" ,"mysql", "back", "$date.sql.zip" ,"app_darkblueacgstandby" ,"" ,"");
?>