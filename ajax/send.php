<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]);
    include_once(ROOT."/includes/functions.php");

    if (!empty($_POST) || !empty($_GET)) {

        if (!empty($_GET)) {
            $_POST = $_GET;
        }

        $cmd = 'python '.ROOT.'/python/tag.py "'.$_POST["file"].'" "'.trim($_POST["song"]).'" "'.trim($_POST["artist"]).'" "'.trim($_POST["album"]).'" "'.$_POST["thumbnail"].'"';

        $data = array();

        exec($cmd, $data);

        $name = trim($_POST["artist"])." - ".trim($_POST["song"]);

        if (isset($_POST["download"])) {

            if(file_exists($_POST["file"]))
            {
                header("Content-Disposition: attachment; filename=".$name.".mp3");
                header("Content-Type: audio/mpeg");
                header("Content-Length: " . filesize($_POST["file"]));
                header("Connection: close");
                echo file_get_contents($_POST["file"]);
            }
        } else {
            header("Location: /");
        }

    }
?>
