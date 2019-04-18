<?php
    define("ROOT", $_SERVER["DOCUMENT_ROOT"]);
    include_once(ROOT."/includes/functions.php");

    $currentDate = date("Y-m-d_H-i");
    $cmd = 'youtube-dl --extract-audio --print-json --audio-quality 0 --prefer-ffmpeg --audio-format mp3 -o "'.ROOT.'/download/%(title)s - '.$currentDate.'.mp3" '.$_POST["video"].'"';
    exec($cmd, $data);
    if (!empty($data)) {
        $data = json_decode($data[0]);
        $filename = $data->_filename;
        $pathinfo = pathinfo($filename);
        $filename2 = $pathinfo["dirname"]."\\".$pathinfo["filename"]."-tmp".".".$pathinfo["extension"];
        $cmd = 'ffmpeg -threads 4 -i "'.$filename.'" "'.$filename2.'"';
        exec($cmd, $data2);
        rename($filename2, $filename);
        $title = $data->fulltitle;
        $album = "https://youtu.be/v=".$data->id;
        $thumbnail = getThumbnail($_POST["video"]);
        $data = array();
        saveFromUrl($thumbnail, $pathinfo["filename"], "/download/thumbnails/");
        exec('python '.ROOT.'/python/getinfo.py "'.$title.'"', $data);

        if (!empty($data)) {
            echo '
                <h3 class="header center teal-text">Confirm?</h3>
                <div class="row center">
                  <h5 class="header col s12 light">Edit your tags and choose album-art</h5>
                </div>
                <br>
                <form class="row" method="post" action="/ajax/send.php">
                    <div class="col s12">
                      <div class="row">
                        <div class="input-field col s12 l4">
                          <input autocomplete="off" spellcheck="false" autocorrect="off" onchange="validateInput();" onkeyup="validateInput();" onpaste="validateInput();" oninput="validateInput();" name="song" id="song" type="text" value="'.$data[0].'">
                          <label class="active" for="song">Titre de la chançon</label>
                        </div>

                        <div class="input-field col s12 l4 switch">
                          <img src="images/switch.png" />
                        </div>

                        <div class="input-field col s12 l4">
                          <input autocomplete="off" spellcheck="false" autocorrect="off" name="artist" id="artist" type="text" value="'.$data[1].'">
                          <label class="active" for="artist">Artistes</label>
                        </div>

                        <div class="input-field col s12 l6">
                          <input autocomplete="off" spellcheck="false" autocorrect="off" name="album" id="album" type="text" value="'.$album.'">
                          <label class="active" for="album">Album</label>
                        </div>
                        <div class="input-field col s12 l6">
                          <div style="height:40px"></div>
                          <input name="download" id="download" type="checkbox" value="1" checked>
                          <label class="active" for="download">Télécharger</label>
                        </div>
                      </div>
                      <br>
                      <div class="row">';
            echo        '<div class = "col s12 l4 center" style="float:unset;margin:auto;">
                            <img class ="responsive" src="'.$thumbnail.'" height="70%" width="70%" style="border: 2px solid #26a69a"><br>
                            <input type="hidden" name="thumbnail" id="thumbnail" value="'.$thumbnail.'" />
                            <input type="hidden" name="file" id="file" value="'.$filename.'" />
                          </div>';
            echo          '</div>
                  <br><br>
                  <div class="row center">
                    <button type="submit" id="submit-button" class="btn waves-effect waves-light teal lighten-1 ">Télécharger <i class="mdi-content-send right"></i></button>
                  </div>
                </div>
              </form>
        ';
        }
    } else {
        echo '<br><br>
            <h2 class="header center teal-text">
              URL Invalide!
            </h2>
          <div class="row center">
            <h5 class="header col s12 light">
              Essayer à nouveau <a style="color:teal" href="/" >ICI</a>!
            </h5>
          </div>';
    }

?>
