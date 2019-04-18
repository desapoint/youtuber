<?php
define("ROOT", $_SERVER["DOCUMENT_ROOT"]);

include_once(ROOT."/includes/functions.php");
include_once(ROOT."/includes/head.php");
if (!empty($_GET["page"]))
    include_once(ROOT."/pages/".$_GET["page"].".php");
else
    include_once(ROOT."/pages/accueil.php");
include_once(ROOT."/includes/foot.php");
?>
