<div class="section no-pad-bot" id="index-banner">
    <div class="container">

        <br><br>
        <h1 class="header center teal-text">YouTuber</h1>


        <div class="row center">
            <h5 class="header col s12 light">Une application WEB pour télécharger votre musique de youtube avec les métadonnées</h5>
        </div>

        <br><br>

    </div>

    <div class="container">
        <div class="row">
            <form id="youtube-form" action="confirm" class="col s12" method="post">
                <div class="row">
                    <div class="input-field col s12 l6 offset-l3">
                        <input spellcheck="false" autocomplete="off" autocorrect="off" onchange="validateInput();" onkeyup="validateInput();" onpaste="validateInput();" oninput="validateInput();" id="video" name="video" type="text" class="validate">
                        <label for="video">YouTube URL</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<script type="text/javascript" src="js/materialize.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#video").focus();
    });

    function validateInput()
    {
        var isYoutube = false;

        var regexYoutube = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/
        isYoutube = regexYoutube.test($("input[name=video]").val());
        if (isYoutube)
            $("form").submit();
    }

    $('html').bind('keypress', function(e) {
        if (e.keyCode == 13) {
            return false;
        }
    });
</script>
