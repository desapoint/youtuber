<!DOCTYPE html>
<html>
    <head>
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

        <title><?=(!empty($_GET["page"])?$_GET["page"]:"Accueil")?></title>

        <style>
            body {
                /*display: flex;
                width: 100%;
                height: 100vh;
                align-items: center;
                justify-content: center;
                overflow-y:auto;*/
            }
        </style>
    </head>

    <body>
