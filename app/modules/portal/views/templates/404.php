<?php if (!Request::isAjax()) { ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0, maximum-scale=5.0"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <title>404</title>
        <link rel="shortcut icon" href="<?= _SITEDIR_ ?>public/images/favicon.png">
        <link rel="stylesheet" href="<?= _SITEDIR_ ?>public/css/style.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>
        <script src="<?= _SITEDIR_ ?>public/js/backend/jquery.scrollbar.min.js"></script>
        <script src="<?= _SITEDIR_ ?>public/js/backend/function.js"></script>
        <script src="<?= _SITEDIR_ ?>public/js/backend/event.js"></script>
    </head>

    <body>
    <div class="wrap">
        <div class="content">
<?php } ?>

<?php
// Error for ajax
if (Request::isAjax()) {
    http_response_code(200); // to process response
    Request::addResponse('func', 'alert', '404 - Page not found');
    Request::endAjax();
}
?>

<!-- 404 page content -->

<div class="page404">
    <div class="title center" style="margin-top: 100px;">Page not found</div>
    <div class="center rose_light">The page may have changed the address or never existed</div>

    <div class="center mt_24">
        <a href="<?php echo url(); ?>" class="btn gray">Home page</a>
    </div>
</div>

<!-- /404 page content -->


<?php if (!Request::isAjax()) { ?>
        </div>
    </div>

    </body>
    </html>
<?php } ?>