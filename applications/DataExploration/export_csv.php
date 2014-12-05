<?php

header('Content-Type: application/force-download');
header('Content-disposition: attachment; filename='.$_POST['exportdatafilename']);

print $_POST['exportdata'];
?>
