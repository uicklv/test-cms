<?php if (!Request::isAjax()) { ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <?php echo reFilter(Request::getParam('include_code_top')); // Top JS code ?>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title><?= Request::getTitle(); ?> | <?= Request::getParam('title_prefix') ?></title>

        <?php if (Request::getKeywords()) { ?>
            <meta content="<?= Request::getKeywords(); ?>" name="keywords">
        <?php } ?>
        <?php if (Request::getDescription()) { ?>
            <meta content="<?= Request::getDescription(); ?>" name="description">
        <?php } ?>
        <?php if (Request::getCanonical() !== false) { ?>
            <link rel="canonical" href="<?= SITE_URL . Request::getCanonical() ?>"/>
        <?php } ?>

        <?php /*
        $ogImg = 'og_1.png';
        if (CONTROLLER === 'jobs')
            $ogImg = 'og_3.png';
        else if (CONTROLLER === 'blogs')
            $ogImg = 'og_2.png';
        ?>
        <meta property="og:title" content="<?= Request::getTitle(); ?>">
        <meta property="og:url" content="<?= SITE_URL . _URI_; ?>">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="<?= SITE_NAME; ?>">
        <meta property="og:description" content="<?= Request::getDescription(); ?>">
        <meta property="og:image" content="<?= SITE_URL ?>app/public/images/<?= $ogImg; ?>">
        */ ?>

        <!-- Favicon -->
        <link href="<?= _SITEDIR_ ?>data/setting/<?= Request::getParam('favicon'); ?>" rel="shortcut icon" />

        <!-- Connect CSS files -->
        <link href="<?= _SITEDIR_ ?>public/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?= _SITEDIR_ ?>public/css/slick.css" type="text/css" rel="stylesheet" />
        <link href="<?= _SITEDIR_ ?>public/css/slick-theme.css" type="text/css" rel="stylesheet" />
        <link href="<?= _SITEDIR_ ?>public/css/aos.css" type="text/css" rel="stylesheet" />
        <link href="<?= _SITEDIR_ ?>public/css/style.css?(cache)" type="text/css" rel="stylesheet" />
        <link href="<?= _SITEDIR_ ?>public/css/additional_styles.css" type="text/css" rel="stylesheet" />
        <link href="<?= _SITEDIR_ ?>public/plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />

        <!-- Connect JS files -->
        <script>var site_url = '<?= SITE_URL ?>';</script>
        <script>var site_dir = '<?= _SITEDIR_ ?>';</script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://kit.fontawesome.com/aaa56c7348.js" crossorigin="anonymous"></script>
        <script src="<?= _SITEDIR_ ?>public/js/backend/function.js?(cache)"></script>
        <script src="<?= _SITEDIR_ ?>public/js/backend/event.js?(cache)"></script>

        <script src="<?= _SITEDIR_ ?>public/js/bootstrap.min.js"></script>
        <script src="<?= _SITEDIR_ ?>public/js/backend/slick.min.js"></script>
        <script src="<?= _SITEDIR_ ?>public/js/aos.js"></script>
        <script src="<?= _SITEDIR_ ?>public/js/main.js?(cache)"></script>
        <script src="<?= _SITEDIR_ ?>public/plugins/notification/snackbar/snackbar.min.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
        <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>
    <div id="site" class="home">
        <!-- POPUPS -->
        <?php if (Request::getParam('cookie_enable')) { ?>
            <!--  Cookie Popup  -->
            <?php include _SYSDIR_ . 'modules/page/views/templates/cookie_popup.php' ?>
            <!--  Cookie Popup  -->
        <?php } ?>
        <!-- POPUPS -->
        <div>
            <div class="popup-fon" onclick="closePopup();"></div>
            <div id="popup" class="popup"></div>
        </div>
        <div id="notice"></div>
        <!-- /POPUPS -->

        <!-- Header & Menu -->
        <?php /*
        <header class="header <?= activeIF('page', ['edison','tech_community', 'services']) ? 'header-v2' : (activeIF('about_us', ['index', 'work_for_us', 'our_people']) ? 'header-v2' : ''); ?>">
            <a class="logo <?= activeIF('page', ['edison']) ? 'logo_2' : (activeIF('page', ['services']) ? 'logo_3' : ''); ?>" href="{URL:/}">
                <svg xmlns="http://www.w3.org/2000/svg" width="158.254" height="43.079" viewBox="0 0 158.254 43.079"><g transform="translate(0)"><path d="M88.7,46.636a22.056,22.056,0,0,1,2.264-10.492,33.691,33.691,0,0,1,1.838-3.293c.314-.5.637-.993.966-1.482.428-.637,1.138-1.325,1.1-2.155a.9.9,0,0,1,.864-.166c.543.24.835,1.258.919,2.035C96.6,36.848,94.477,44.325,88.7,46.636M99.15,29.351c-.137-.136-.335-.2-.458-.353a6.631,6.631,0,0,0-.9-.918,4.772,4.772,0,0,0-2.632-1.122,4.956,4.956,0,0,0-1.305.068,3.028,3.028,0,0,0-1.872-.364,2.527,2.527,0,0,0-1.934,1.3,40.852,40.852,0,0,0-2.205,3.453,27.681,27.681,0,0,0-2.451,5.717,25.184,25.184,0,0,0-1.022,6.433,4.251,4.251,0,0,0,1.475,3.644c.173.151.177.479.337.664a5.988,5.988,0,0,0,1.809,1.362,4.1,4.1,0,0,0,2.147.637,6.971,6.971,0,0,0,1.605-.35,5.147,5.147,0,0,0,2.4-1.7l.035-.047a.239.239,0,0,0-.071-.32,16.463,16.463,0,0,0,1.534-1.793,19.1,19.1,0,0,0,3.31-6.062,18.233,18.233,0,0,0,.494-9.456,1.758,1.758,0,0,0-.265-.759Zm14.573,22.605c.23,1.113-1.217.846-1.793.439a3.616,3.616,0,0,1-1.071-1.367c-1.094-2.167-1.2-4.675-1.274-7.1a13.942,13.942,0,0,1-2.007,2.147,4.732,4.732,0,0,1-2.679,1.126,4.551,4.551,0,0,1-4.16-2.96,9.345,9.345,0,0,1-.234-5.327,43.191,43.191,0,0,1,3.769-10.049,2.764,2.764,0,0,1,.88-1.215,2.381,2.381,0,0,1,1.8-.158,2.863,2.863,0,0,1,2.032,1.291c.492.974-.026,2.129-.524,3.1-1.951,3.8-3.706,7.811-4.07,12.062a15.429,15.429,0,0,0,6.2-6.671c1.449-2.712,2.424-5.642,3.677-8.448a2.207,2.207,0,0,1,.921-1.187,2.262,2.262,0,0,1,1.829.22,2.776,2.776,0,0,1,1.648,1.339,2.7,2.7,0,0,1-.174,1.8c-1.156,3.342-2.727,6.533-3.84,9.889a20.6,20.6,0,0,0-1.154,9.537c.078.507.125,1.027.229,1.528M66.18,49.225a1.032,1.032,0,0,1-.009-.294,1.009,1.009,0,0,0-.053-.49,29.784,29.784,0,0,1,.27-4.017c.189-1.461.46-2.911.786-4.347.4-1.776.9-3.556,1.454-5.3q.7-2.2,1.542-4.35c.469-1.206.985-2.44,1.542-3.627.27-.575.546-1.148.846-1.708a8.476,8.476,0,0,0,.838-1.682,2.05,2.05,0,0,0-.655-2.217,2.156,2.156,0,0,0-1.208-.369,3.188,3.188,0,0,0-2.85,1.529q-.706,1.229-1.388,2.472c-2.091,3.815-4.566,7.437-6.923,11.093-.85,1.318-1.7,2.638-2.527,3.967-.2.32-.514.887-.568.914s-.1-.006-.088-.167a27.876,27.876,0,0,1,3.232-10.6c.2-.36.639-.833.663-1.249a1.262,1.262,0,0,0-.022-.268c-.267-1.716-2.792-2.492-4.151-1.57-.85.577-3.443,4.269-4.1,5.287-1.09,1.693-2.782,4.765-2.85,4.872s-.1.055-.038-.081c.218-.469.392-.97.592-1.437q.753-1.759,1.5-3.519c.47-1.1.907-2.229,1.446-3.3a1.674,1.674,0,0,0,.225-.848c-.048-.72-.841-1.252-1.423-1.555a3.072,3.072,0,0,0-3.208.026,3.72,3.72,0,0,0-1,1.775,84.265,84.265,0,0,0-3.351,10.273.61.61,0,0,0-.216.056l-.107.052v.118a29.425,29.425,0,0,1-.6,4.931,22.948,22.948,0,0,0-.37,4.973,1.406,1.406,0,0,0,.341.94,2.645,2.645,0,0,0,2.074.48,1.992,1.992,0,0,0,1.5-1.214c.6-1.369,1.143-2.779,1.643-4.186a44.594,44.594,0,0,1,3.572-7.335s1.014-1.636,1.064-1.679c-.018.108-.037.218-.055.332-.419,2.527-.993,5.987.717,7.735a3.993,3.993,0,0,0,3.945.905,2.207,2.207,0,0,0,.831-.766c1.836-2.6,2.967-5.612,4.835-8.179a1.559,1.559,0,0,0-.078.506q-.043.287-.082.575a39.16,39.16,0,0,0-.359,9.076c.183,1.821.74,5.126,3.165,5.126.019,0,.2,0,.277-.25s-.185-.579-.357-.8a.916.916,0,0,1-.271-.62M37.857,30.234c.571-1.642,2.273-5.344,2.767-6.785a111.825,111.825,0,0,0-10.742,12.5l5.274-.23a1.192,1.192,0,0,0,.728-.193,1.164,1.164,0,0,0,.308-.569m9.663-12.192a34.5,34.5,0,0,0-2.733,6.306c-.415,1.237-1.631,5.057-1.842,5.7-.195.59-.486,1.154-.625,1.761a5.715,5.715,0,0,1,2.789,2.661,2.9,2.9,0,0,1-.08,2.543,1.35,1.35,0,0,1-.574.4c-.809.323-1.327-.145-2-.5a4.723,4.723,0,0,0-1.285-.526c0,.013-1.021,4.9-1.176,7.249-.14,2.112-.3,4.5.958,6.348a2.994,2.994,0,0,1,.572,1.206c.151,1.037-1,1.6-1.885,1.671a3.543,3.543,0,0,1-3.144-1.539,10.081,10.081,0,0,1-1.317-5.927,69.117,69.117,0,0,1,1.242-9.559,43.072,43.072,0,0,0-8.094.417,39.051,39.051,0,0,0-3.054,5.6,18.767,18.767,0,0,0-1.23,4.336,7.247,7.247,0,0,1-.243,1.707,1.693,1.693,0,0,1-1.606.973,3.474,3.474,0,0,1-3.165-2.179,3.393,3.393,0,0,1,.312-2.252c.341-1.085.681-2.172,1.1-3.23a36.645,36.645,0,0,1,1.881-3.782c-1.836.358-4.436,2.085-5.678,2.943-1.106.764-2.651,1.708-3.144,3.019a1.793,1.793,0,0,1-1.113,1.164c-1.085.223-2.071-1.083-2.431-1.925a2.95,2.95,0,0,1,.133-2.926,39.416,39.416,0,0,1,5.191-4.034c1.673-1.121,5.5-2.35,8.767-3.076a3.475,3.475,0,0,0,1.8-.819,10.842,10.842,0,0,0,1.354-1.668c.8-1.114,1.5-2.3,2.34-3.394a93.817,93.817,0,0,1,8.57-9.585,26.348,26.348,0,0,1,7.072-5.362,3.232,3.232,0,0,0,.309-.438,2.917,2.917,0,0,1,1.093-1.329,2.272,2.272,0,0,1,1.884,0c.963.377,2.146,1.146,2.3,2.245.116.808-.9,2.035-.9,2.035M72.284,49.658l0-.01h.038l-.034.01M84.2,28.27c1.171-.457,2.373-.888,3.519-1.4.139-.063.554-.2.613-.346.051-.13-.1-.459-.147-.584a3.542,3.542,0,0,0-1.67-1.91,3.994,3.994,0,0,0-3.3,0,31.2,31.2,0,0,0-7.8,3.43,13.661,13.661,0,0,0-3.05,2.468,4.425,4.425,0,0,0-.184,4.755c.928,1.683,2.7,2.668,4.326,3.576a15.165,15.165,0,0,1,3.142,2.109c.434.417-1.346,2.809-1.639,3.153a9.881,9.881,0,0,1-5.247,2.98,4.3,4.3,0,0,1-1.693.141c-.766-.126-1.109-.619-1.6-1.142-.11-.119-.873-1.022-.971-.544a4.582,4.582,0,0,0,1.093,3.6,5.192,5.192,0,0,0,2.489,2.124,5.92,5.92,0,0,0,3.461-.287c2.965-1.019,6.3-3.5,7.285-6.581.859-2.7-1.035-5.016-3.029-6.629a39.832,39.832,0,0,0-5.113-3.308l-.144-.086c2.4-2.68,6.084-4.119,9.653-5.513m51.459-.846c-.97.392-1.811.955-2.73,1.432a9.418,9.418,0,0,0-1.991,1.645,32.648,32.648,0,0,0-3.6,4.06,31.959,31.959,0,0,0-4.928,9.584,29.5,29.5,0,0,0-1.192,5.752.775.775,0,0,1-.267.617c-.528.384-1.583-.1-2.057-.392-.975-.592-2.1-1.585-1.879-2.831a225.053,225.053,0,0,1,5.522-22.4,1.99,1.99,0,0,1,.593-1.106c.562-.423,1.365-.158,2,.14a3.625,3.625,0,0,1,2.018,1.7,3.1,3.1,0,0,1-.185,2.228,10.141,10.141,0,0,0-.769,2.152,17.8,17.8,0,0,1,4.742-4.127,6.865,6.865,0,0,1,6.1-.414c.512.249,1.044.741.888,1.284-.24.838-1.6.771-2.266.671M154.2,37.561c1.529-2.741,3.109-5.574,6.285-7.107a7.787,7.787,0,0,1-2.1,5.746,5.988,5.988,0,0,1-4.555,2.032q.189-.334.376-.671M166.1,38.914c-.555-.629-1.329.266-1.615.663a17.084,17.084,0,0,1-1.711,2.022,17.718,17.718,0,0,1-4.251,3.163A17.315,17.315,0,0,1,153.62,46.5a2.149,2.149,0,0,1-1.744-.2,1.841,1.841,0,0,1-.526-1.538,10.452,10.452,0,0,1,1.577-4.44,5.093,5.093,0,0,0,5.337.561,9.794,9.794,0,0,0,4.057-3.814,10.077,10.077,0,0,0,1.725-4.429,5.265,5.265,0,0,0-1.508-4.377,5.622,5.622,0,0,0-6.062-.559,12.389,12.389,0,0,0-4.62,4.367,27.458,27.458,0,0,0-3.675,7.271,6.088,6.088,0,0,1-.476,1.221,5.437,5.437,0,0,1-.583.776,28.7,28.7,0,0,1-4.512,4.564,11.6,11.6,0,0,1-2.812,1.571,9.013,9.013,0,0,1-1.58.444,3.636,3.636,0,0,1-1.592.083c-.926-.3-.749-1.906-.649-2.637.817-5.982,4.128-11.406,8.275-15.833a1.619,1.619,0,0,1,.074,1.674,13.614,13.614,0,0,0-.764,1.593,1.328,1.328,0,0,0,.49,1.557,1.4,1.4,0,0,0,.857.088,3.773,3.773,0,0,0,2.874-3.281,5.236,5.236,0,0,0-3.933-5.621,3.392,3.392,0,0,0-2.19.164,4.653,4.653,0,0,0-1.4,1.189,34.387,34.387,0,0,0-6.876,11.579,14.007,14.007,0,0,0-.8,7.408,6.93,6.93,0,0,0,4.765,5.381,7.4,7.4,0,0,0,6.285-1.793,21.517,21.517,0,0,0,4.164-5.27,8.283,8.283,0,0,0,1.848,4.28,6.171,6.171,0,0,0,4.1,2.162,8.609,8.609,0,0,0,5.769-2.2,20.138,20.138,0,0,0,5.316-6.343c.313-.586.577-1,.909-1.574.2-.349.715-1.207.365-1.6" transform="translate(-7.968 -14.512)" fill="#64c2c8"/></g></svg>
            </a>
            <div class="tl-right">
                <ul class="tl-menu">
                    <li><a href="{URL:blogs}" onclick="load('blogs');">Our Blog</a></li>
                </ul>
                <div class="search-block">
                    <input class="search-field" type="text" id="header_search" placeholder="Quick Job Search">
                    <span class="icon-search pointer" onclick="startSearch('#header_search');"></span>
                </div>
                <div class="menu-icon" onclick="openMenu();">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </header>
        <script>
            // $(document).ready(function() {
            //     // Enter listener
            //     $('#header_search').keydown(function(e){
            //         if (e.keyCode === 13) {
            //             startSearch('#header_search');
            //             e.preventDefault();
            //         }
            //     });
            // });
        </script>
        */ ?>
        <!-- /Header & Menu -->

        <div id="content" class="content arrive">
<?php } ?>

<?php
echo $this->Load('contentPart'); // Content from view page
?>

<?php if (!Request::isAjax()) { ?>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div style="text-align: center;">Copyright © <?= date("Y"); ?> CMS</div>
        </footer>
        <!-- /FOOTER -->

        <?php if (Request::getParam('tracker') == 'yes') { ?>
            <!-- Bug Tracker -->
            <div id="api_content"></div>

            <div class="scan__block">
                <a class="report__button" onclick="load('issue_manager/create_task', 'project=<?= Request::getParam('tracker_api') ?>', 'url=' + window.location.href);">Report<br>an issue</a>
                <?php /*<a class="report__button" onclick="load('https://donemen.com/api/create_task', 'project=<?= CONF_PROJECT ?>', 'url=' + window.location.href);">Report<br>an issue</a>*/?>
            </div>
            <!-- /Bug Tracker -->
        <?php } ?>
    </div>

<?php echo reFilter(Request::getParam('include_code_bottom')); // Bottom JS code ?>

    </body>
    </html>
<?php } ?>
