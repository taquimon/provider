<head>
<title><?=isset($titleForLayout) ? $titleForLayout : 'RHC'?></title>
                <meta charset="utf-8">        
        <?= link_tag(base_url().'images/favicon.ico', 'SHORTCUT ICON', NULL)?>
        <?= script_tag (base_url().'js/jquery-2.1.3.min.js')?>                              
        <?= link_tag(base_url().'css/docs.css')?>
        <?= link_tag(base_url().'css/metro.css')?>
        <?= link_tag(base_url().'css/metro-icons.css')?>                                     
        <?= link_tag(base_url().'css/rhc.css')?>
        <?= script_tag (base_url().'js/metro.js')?>
        <?= script_tag (base_url().'js/basic.js')?>
        <?= script_tag (base_url().'js/select2.min.js')?>
        <!--<?= script_tag (base_url().'js/ga.js')?>-->
        <!--<?= script_tag (base_url().'js/prettify/run_prettify.js')?>-->
        <?= script_tag (base_url().'js/select2.min.js')?>
        <?= script_tag (base_url().'js/jquery.dataTables.min.js')?> 
<style>
        .login-form {
            width: 400px;
            height: 300px;
            position: fixed;
            top: 50%;
            margin-top: -150px;
            left: 50%;
            margin-left: -200px;
            background-color: #ffffff;
            opacity: 0;
            -webkit-transform: scale(.8);
            transform: scale(.8);
        }
    </style>

    <script>

        // /*
        // * Do not use this is a google analytics fro Metro UI CSS
        // * */
        // if (window.location.hostname !== 'localhost') {

        //     (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        //         (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        //             m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        //     })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        //     ga('create', 'UA-58849249-3', 'auto');
        //     ga('send', 'pageview');

        // }

        $(function() {
            var form = $(".login-form");

            form.css({
                opacity: 1,
                "-webkit-transform": "scale(1)",
                "transform": "scale(1)",
                "-webkit-transition": ".5s",
                "transition": ".5s"
            });
                $ ('#logon').click(function(){  
                    var bValid = true;
                    bValid = bValid && checkLength($( "#username" ));
                    bValid = bValid && checkLength($( "#password" ));

                    if (bValid)
                        return true;
                    else return false;
                });
            })

    </script>
</head>
<body class="bg-darkCobalt">
    <div class="login-form padding20 block-shadow">
        <?=form_open(site_url("home/login"))?>
            <h1 class="text-light">Login</h1>
            <hr class="thin"/>
            <br />
            <div class="input-control text full-size" data-role="input">
                <label for="user_login">User Name:</label>
                <input type="text" name="username" id="username">
                <button class="button helper-button clear"><span class="mif-cross"></span></button>
            </div>
            <br />
            <br />
            <div class="input-control password full-size" data-role="input">
                <label for="user_password">User password:</label>
                <input type="password" name="password" id="password">
                <button class="button helper-button reveal"><span class="mif-looks"></span></button>
            </div>
            <br />
            <br />
            <div class="form-actions">
                <button type="submit" class="button primary" id="logon">Login</button>
                <button type="button" class="button link">Cancel</button>
            </div>
        <?=form_close()?>
    </div>
</body>