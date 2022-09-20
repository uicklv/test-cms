<?php if (!Request::isAjax()) { ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?= Request::getTitle(); ?></title>

    <link rel="icon" type="image/x-icon" href="<?= _SITEDIR_ ?>assets/img/favicon.png"/>

    <link href="<?= _SITEDIR_ ?>public/css/additional_styles.css" rel="stylesheet" type="text/css" />


    <script>var site_url = '<?= SITE_URL ?>';</script>
    <script>var site_dir = '<?= _SITEDIR_ ?>';</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/function.js"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/event.js"></script>

    <?php /*
    <link rel="stylesheet" href="<?= _SITEDIR_ ?>public/css/additional_styles.css" type="text/css" />
    <link rel="stylesheet" href="<?= _SITEDIR_ ?>public/plugins/data-tables/jquery.datatables.css"/>
    <link rel="stylesheet" href="<?= _SITEDIR_ ?>public/plugins/tooltipster-master/dist/css/tooltipster.bundle.min.css"/>
    <link rel="stylesheet" href="<?= _SITEDIR_ ?>public/plugins/tooltipster-master/dist/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-borderless.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link rel="shortcut icon" href="<?= _SITEDIR_ ?>public/images/backend/dashboard-icons/manage-microsites.png" type="image/png"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/function.js"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/event.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/af-2.3.0/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.4.0/r-2.2.2/rg-1.0.3/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.js"></script>
    <script src="<?= _SITEDIR_ ?>public/plugins/tooltipster-master/dist/js/tooltipster.bundle.min.js"></script>

    <!-- Datepicker -->
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    */ ?>
</head>
<body id="site">

    <!-- HEADER HERE -->

    <?php //echo View::get('panel/talents/index', 'left'); // !!! LEFT-MENU !!! ?>
<?php } ?>

    <?php echo $this->Load('contentPart'); // Content wrapper ?>

<?php if (!Request::isAjax()) { ?>

    <!-- FOOTER HERE -->

</body>
</html>
<?php } ?>

