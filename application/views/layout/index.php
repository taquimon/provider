<!DOCTYPE html>
<html>

<head>
     <title><?=isset($titleForLayout) ? $titleForLayout : 'Distribuidora'?></title>
        <meta charset="utf-8">                               
        <meta name="description" content="Distribuidora">
        <meta name="author" content="Edwin Taquichiri">

        <!-- The styles -->
        <?= link_tag(site_url().'css/bootstrap-cerulean.min.css')?>

        <?= link_tag(site_url().'css/charisma-app.css')?>
        <?= link_tag(site_url().'bower_components/fullcalendar/dist/fullcalendar.css')?>
        <?= link_tag(site_url().'bower_components/fullcalendar/dist/fullcalendar.print.css')?>
        <?= link_tag(site_url().'bower_components/chosen/chosen.min.css')?>
        <?= link_tag(site_url().'bower_components/colorbox/example3/colorbox.css')?>
        <?= link_tag(site_url().'bower_components/responsive-tables/responsive-tables.css')?>
        <?= link_tag(site_url().'bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css')?>
        <?= link_tag(site_url().'bower_components/bootstrap/dist/css/bootstrap-select.min.css')?>
        <?= link_tag(site_url().'bower_components/bootstrap/dist/css/bootstrap-dialog.min.css')?>                
        <?= link_tag(site_url().'bower_components/bootstrap/dist/css/bootstrap-toggle.min.css')?>
        <?= link_tag(site_url().'bower_components/bootstrap/dist/css/daterangepicker.css')?>
        <?= link_tag(site_url().'css/jquery.noty.css')?>
        <?= link_tag(site_url().'css/dataTables.bootstrap.min.css')?>
        <?= link_tag(site_url().'css/noty_theme_default.css')?>
        <?= link_tag(site_url().'css/elfinder.min.css')?>
        <?= link_tag(site_url().'css/elfinder.theme.css')?>
        <?= link_tag(site_url().'css/jquery.iphone.toggle.css')?>
        <?= link_tag(site_url().'css/uploadify.css')?>
        <?= link_tag(site_url().'css/animate.min.css')?>
        

        <!-- jQuery -->
        <?= script_tag (site_url()."bower_components/jquery/jquery.min.js")?>

        <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- The fav icon -->
        <?= link_tag(site_url().'img/favicon.ico','SHORTCUT ICON', NULL)?>

        <?= script_tag (site_url().'bower_components/bootstrap/dist/js/bootstrap.min.js')?>

        <!-- library for cookie management -->
        <?= script_tag (site_url().'js/jquery.cookie.js')?>
        <!-- calender plugin -->
        <?= script_tag (site_url().'bower_components/moment/min/moment.min.js')?>
        <?= script_tag (site_url().'bower_components/bootstrap/dist/js/bootstrap-select.min.js')?>    
        <?= script_tag (site_url().'bower_components/fullcalendar/dist/fullcalendar.js')?>
        <?= script_tag (site_url().'bower_components/bootstrap/dist/js/moment-with-locales.js')?>        
        <?= script_tag (site_url().'bower_components/bootstrap/dist/js/bootstrap-datetimepicker.js')?>        
        <?= script_tag (site_url().'bower_components/bootstrap/dist/js/bootstrap-dialog.min.js')?>  
        <?= script_tag (site_url().'bower_components/bootstrap/dist/js/bootstrap-toggle.min.js')?>  
        <?= script_tag (site_url().'bower_components/bootstrap/dist/js/moment.js')?>  
        <?= script_tag (site_url().'bower_components/bootstrap/dist/js/daterangepicker.js')?>          
        <!-- data table plugin -->
        <?= script_tag (site_url().'js/jquery.dataTables.min.js')?>
        <?= script_tag (site_url().'js/dataTables.bootstrap.min.js')?>

        <!-- select or dropdown enhancer -->
        <?= script_tag (site_url()."bower_components/chosen/chosen.jquery.min.js")?>
        <!-- plugin for gallery image view -->
        <?= script_tag (site_url()."bower_components/colorbox/jquery.colorbox-min.js")?>
        <!-- notification plugin -->
        <?= script_tag (site_url()."js/jquery.noty.js")?>
        <!-- library for making tables responsive -->
        <?= script_tag (site_url()."bower_components/responsive-tables/responsive-tables.js")?>
        <!-- tour plugin -->
        <?= script_tag (site_url()."bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js")?>
        <!-- star rating plugin -->
        <?= script_tag (site_url()."js/jquery.raty.min.js")?>
        <!-- for iOS style toggle switch -->
        <?= script_tag (site_url()."js/jquery.iphone.toggle.js")?>
        <!-- autogrowing textarea plugin -->
        <?= script_tag (site_url()."js/jquery.autogrow-textarea.js")?>
        <!-- multiple file upload plugin -->
        <?= script_tag (site_url()."js/jquery.uploadify-3.1.min.js")?>
        <!-- history.js for cross-browser state change on ajax -->
        <?= script_tag (site_url()."js/jquery.history.js")?>
        <!-- application script for Charisma demo -->
        <?= script_tag (site_url()."js/charisma.js")?>


</head>

<body>
    <?php if($header) echo $header ;?>
    <?php //if($left) echo $left ;?>
    <?php if($middle) echo $middle ;?>
    <?php if($footer) echo $footer ;?>
</body>

</html>