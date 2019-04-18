<?php
  if(!empty($_POST['video'])) {
        ?>
        <style>
            .switch {
                text-align: center;
            }

            .switch img {
                height: 50px;
                cursor: pointer;
            }
        </style>
        <div class="section no-pad-bot" id="spinner">
            <div class="container">
                <div class="row">
                    <div class="col s12 l6 offset-l3">
                        <div class="progress">
                            <div class="indeterminate"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section no-pad-bot">
            <div class="container" id="index-banner">
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function() {

                $.ajax({
                    url: '/ajax/ajax.php',
                    type: 'POST',
                    data: {
                        video: '<?=$_POST["video"]?>'
                    },
                    success: function(data) {
                        $('#index-banner').html(data);
                        $('#spinner').hide();
                    },
                    error: function() {
                        alert("Une erreur est survenue");
                    }
                });
            });

            /*$(document).on("click", "#submit-button", function (e) {
                e.preventDefault();
                $("<a></a>").attr("href",$("form").attr("action")+"?"+$("form").serialize()).attr("download", "")[0].click();
                setTimeout(function() {location.href="/";},600);
            });*/

            $(document).on("submit", "form", function () {
                setTimeout(function() {location.href="/";},1000);
            });

            $(document).on("click", ".switch img", function() {
                $tmp = $("#song").val();
                $("#song").val($("#artist").val());
                $("#artist").val($tmp);
            });

            function validateInput() {

                s = $("#song").val();

                if (s.length > 0) {
                    $("#submit-button").fadeIn(0);
                } else {
                    $("#submit-button").fadeOut();
                }
            }

            $('html').bind('keypress', function(e) {
                if (e.keyCode == 13) {
                    return false;
                }
            });
        </script>
        <?php
    } else {
        ?>
        <div class="section no-pad-bot" id="index-banner">
            <div class="container">
                <br><br>
                <h2 class="header center teal-text">
                    Oups!
                </h2>
                <div class="row center">
                    <h5 class="header col s12 light">
                        Je crois que vous devriez aller <a style='color:teal' href='/'>ICI</a>!
                    </h5>
                </div>
            </div>
        </div>
        <?php
    }
?>
