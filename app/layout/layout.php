<?php if (!Request::isAjax()) { ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo reFilter(Request::getParam('include_code_top')); // Top JS code ?>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= Request::getTitle() ?> | <?= Request::getParam('title_prefix') ?></title>

    <?php if (Request::getKeywords()) { ?>
        <meta content="<?= Request::getKeywords() ?>" name="keywords">
    <?php } ?>
    <?php if (Request::getDescription()) { ?>
        <meta content="<?= Request::getDescription() ?>" name="description">
    <?php } ?>
    <?php if (Request::getCanonical() !== false) { ?>
        <link rel="canonical" href="<?= SITE_URL . Request::getCanonical() ?>"/>
    <?php } ?>


    <meta property="og:title" content="<?= Request::getTitle() ?>">
    <meta property="og:url" content="<?= SITE_URL . trim(_URI_,'/') ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= SITE_NAME ?>">
    <meta property="og:description" content="<?= Request::getDescription() ?>">
    <meta property="og:image" content="<?= Request::getImageOG() ?: SITE_URL . 'app/data/og/' . Request::getParam('og_image') ?>">

    <!-- Twitter open-graph tags -->
    <meta name="twitter:title" content="<?= Request::getTitle(); ?>">
    <meta name="twitter:description" content="<?= Request::getDescription(); ?>">
    <meta name="twitter:image" content="<?= Request::getImageOG() ?: SITE_URL . 'app/data/og/' . Request::getParam('og_image') ?>">

    <!-- Favicon -->
    <link href="<?= _SITEDIR_ ?>data/setting/<?= Request::getParam('favicon') ?>" rel="shortcut icon" />

    <meta name="theme-color" content="#2f2f2f">
    <meta name="apple-mobile-web-app-title" content="Amsource">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#2f2f2f">

    <link href="<?= _SITEDIR_ ?>public/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= _SITEDIR_ ?>public/css/slick.css" type="text/css" rel="stylesheet" />
    <link href="<?= _SITEDIR_ ?>public/css/slick-theme.css" type="text/css" rel="stylesheet" />
    <link href="<?= _SITEDIR_ ?>public/css/jquery-ui.min.css" type="text/css" rel="stylesheet" />
    <link href="<?= _SITEDIR_ ?>public/css/aos.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css"/>

    <!-- Om JavaScript Range-Slider https://github.com/Objement/om-javascript-range-slider -->
    <link href="<?= _SITEDIR_ ?>public/css/om-javascript-range-slider.css" type="text/css" rel="stylesheet" />

    <link href="<?= _SITEDIR_ ?>public/css/style.css?(cache)" type="text/css" rel="stylesheet" />
    <link href="<?= _SITEDIR_ ?>public/css/additional_styles.css?(cache)" type="text/css" rel="stylesheet" />
    <link href="<?= _SITEDIR_ ?>public/plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />

    <script>var site_url = '<?= SITE_URL ?>';</script>
    <script>var site_dir = '<?= _SITEDIR_ ?>';</script>
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>-->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"  type="text/javascript"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/js.cookie.min.js"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/function.js?(cache)"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/event.js?(cache)"></script>

    <script src="https://kit.fontawesome.com/aaa56c7348.js" crossorigin="anonymous"></script>
    <script src="<?= _SITEDIR_ ?>public/plugins/notification/snackbar/snackbar.min.js"></script>
    <script src="<?= _SITEDIR_ ?>public/js/bootstrap.min.js"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/slick.min.js"></script>
<!--    <script src="--><?//= _SITEDIR_ ?><!--public/js/jquery-ui.min.js"></script>-->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="<?= _SITEDIR_ ?>public/js/menu-accordion.js"></script>
    <script src="<?= _SITEDIR_ ?>public/js/aos.js"></script>
    <script src="<?= _SITEDIR_ ?>public/js/sticky-sidebar.js"></script>

    <!-- JavaScript Double Range Slider -->
    <!-- Serverside PHP for ^ https://github.com/Objement/om-javascript-range-slider -->
    <script src="<?= _SITEDIR_ ?>public/js/om-javascript-range-slider.js"></script>

    <!-- Scroll Magic -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/ScrollMagic.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/debug.addIndicators.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/animation.gsap.min.js"></script>

    <script type="module" src="<?= _SITEDIR_ ?>public/js/main.js?(cache)"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<?php
$homeClass = true;
if (CONTROLLER == 'learning_development' && ACTION == 'view')
    $homeClass = false;
?>

<body>
<div id="site" class="<?= ($homeClass ? 'home' : '') ?>">
    <!-- POPUPS -->
    <?php if (Request::getParam('cookie_enable')) { ?>
    <!--  Cookie Popup  -->
        <?php include _SYSDIR_ . 'modules/page/views/templates/cookie_popup.php' ?>
    <!--  Cookie Popup  -->
    <?php } ?>

    <div>
        <div class="popup-fon" onclick="closePopup();"></div>
        <div id="popup" class="popup popup-center"></div> <!-- popup-center -->
    </div>
    <div id="notice"></div>
    <!-- /POPUPS -->

    <div class="menu-block">
        <span class="close-menu icon-close" onclick="closeMenu();"></span>
        <div class="mb-cont">
            <div class="mb-left">
                <ul class="menu">
                    <li><a href="{URL:/}">Home</a></li>
                    <li><a href="#">AbOut US</a>
                        <ul>
                            <li><a href="{URL:about-us}">What we do</a></li>
                            <li><a href="{URL:about-us/our-people}">Our people</a></li>
                            <li><a href="{URL:about-us/work-for-us}">Work for us</a></li>
                        </ul>
                    </li>
                    <li><a href="{URL:jobs}">Search Jobs</a></li>
                    <li><a href="{URL:salary-survey}">Salary Survey</a></li>
                    <li><a href="{URL:specialisms}">Specialisms</a></li>
                    <li><a href="#">LOOking fOr Talent</a>
                        <ul>
                            <li><a href="{URL:c-suite}">C-suite Advisory</a></li>
                            <li><a href="{URL:tactical-solutions}">Tactical Solutions for building teams</a></li>
                            <li><a href="{URL:operational-solutions}">Operational Solutions for scaling businesses</a></li>
                        </ul>
                    </li>
                    <li><a href="{URL:services}">Candidate Services</a></li>
                    <li><a href="{URL:tech-community}">Tech COmmunity</a></li>
                    <li><a href="{URL:blogs}">OUr Blog</a></li>
                    <li><a href="{URL:members-growth-club}">Members Growth Club</a></li>
                    <li><a href="{URL:talent}">Talent</a></li>
                    <li><a href="{URL:contact-us}">COntAct us</a></li>
                </ul>
                <ul class="menu-btn">
                    <li><a class="pointer" onclick="closeMenu(); load('page/arrange_call');">Arrange call back</a></li>
                    <!--<li><a href="#">Apply using <span class="icon-linked"></span></a></li>-->
                    <li><a class="pointer" onclick="closeMenu(); load('about_us/upload_cv');">Upload CV</a></li>
                </ul>
            </div>
            <div class="menu-cont">
                <div>
                    <h3 class="mc-title"><img src="<?php echo _SITEDIR_; ?>public/images/edison.png" height="75" width="275" alt=""/></h3>
                    <div class="mc-text">Edison is an intelligent talent acquisition solution that empowers fast-growing tech firms to build high-performing teams capable of remarkable things.</div>
                    <a class="btn-transparent" href="{URL:edison}">Meet Edison</a>
                    <a class="tel-block icon-mail" href="mailto:info@amsource.io">info@amsource.io</a>
                    <a class="tel-block" href="tel://01134686700">0113 468 6700</a>
                </div>
            </div>
            <div class="social-block">
                <?php if (Request::getParam('twitter')) { ?>
                    <a href="<?= Request::getParam('twitter') ?>" target="_blank" rel="noreferrer"><span class="icon-Twitter"></span></a>
                <?php } ?>

                <?php if (Request::getParam('facebook')) { ?>
                    <a href="<?= Request::getParam('facebook') ?>" target="_blank"><span class="icon-Facebook"></span></a>
                <?php } ?>

                <?php if (Request::getParam('instagram')) { ?>
                    <a href="<?= Request::getParam('instagram') ?>" target="_blank"><span class="icon-Instagram"></span></a>
                <?php } ?>

                <?php if (Request::getParam('linkedin')) { ?>
                    <a href="<?= Request::getParam('linkedin') ?>" target="_blank" rel="noreferrer"><span class="icon-LinkedIn"></span></a>
                <?php } ?>

                <?php if (Request::getParam('youtube')) { ?>
                    <a href="<?= Request::getParam('youtube') ?>" target="_blank" rel="noreferrer"><span class="icon-youtube"></span></a>
                <?php } ?>
            </div>
        </div>
        <span class="pattern_menu"><img src="<?php echo _SITEDIR_; ?>public/images/menu-logo.png" height="751" width="205" alt=""/></span>
    </div>

    <header class="header <?= activeIF('page', ['edison','tech_community', 'services']) ? 'header-v2' : (activeIF('about_us', ['index', 'work_for_us', 'our_people']) ? 'header-v2' : ''); ?>">
        <a class="logo <?= activeIF('page', ['edison']) ? 'logo_2' : (activeIF('page', ['services']) ? 'logo_3' : ''); ?>" href="{URL:/}">
            <svg xmlns="http://www.w3.org/2000/svg" width="158.254" height="43.079" viewBox="0 0 158.254 43.079"><g transform="translate(0)"><path d="M88.7,46.636a22.056,22.056,0,0,1,2.264-10.492,33.691,33.691,0,0,1,1.838-3.293c.314-.5.637-.993.966-1.482.428-.637,1.138-1.325,1.1-2.155a.9.9,0,0,1,.864-.166c.543.24.835,1.258.919,2.035C96.6,36.848,94.477,44.325,88.7,46.636M99.15,29.351c-.137-.136-.335-.2-.458-.353a6.631,6.631,0,0,0-.9-.918,4.772,4.772,0,0,0-2.632-1.122,4.956,4.956,0,0,0-1.305.068,3.028,3.028,0,0,0-1.872-.364,2.527,2.527,0,0,0-1.934,1.3,40.852,40.852,0,0,0-2.205,3.453,27.681,27.681,0,0,0-2.451,5.717,25.184,25.184,0,0,0-1.022,6.433,4.251,4.251,0,0,0,1.475,3.644c.173.151.177.479.337.664a5.988,5.988,0,0,0,1.809,1.362,4.1,4.1,0,0,0,2.147.637,6.971,6.971,0,0,0,1.605-.35,5.147,5.147,0,0,0,2.4-1.7l.035-.047a.239.239,0,0,0-.071-.32,16.463,16.463,0,0,0,1.534-1.793,19.1,19.1,0,0,0,3.31-6.062,18.233,18.233,0,0,0,.494-9.456,1.758,1.758,0,0,0-.265-.759Zm14.573,22.605c.23,1.113-1.217.846-1.793.439a3.616,3.616,0,0,1-1.071-1.367c-1.094-2.167-1.2-4.675-1.274-7.1a13.942,13.942,0,0,1-2.007,2.147,4.732,4.732,0,0,1-2.679,1.126,4.551,4.551,0,0,1-4.16-2.96,9.345,9.345,0,0,1-.234-5.327,43.191,43.191,0,0,1,3.769-10.049,2.764,2.764,0,0,1,.88-1.215,2.381,2.381,0,0,1,1.8-.158,2.863,2.863,0,0,1,2.032,1.291c.492.974-.026,2.129-.524,3.1-1.951,3.8-3.706,7.811-4.07,12.062a15.429,15.429,0,0,0,6.2-6.671c1.449-2.712,2.424-5.642,3.677-8.448a2.207,2.207,0,0,1,.921-1.187,2.262,2.262,0,0,1,1.829.22,2.776,2.776,0,0,1,1.648,1.339,2.7,2.7,0,0,1-.174,1.8c-1.156,3.342-2.727,6.533-3.84,9.889a20.6,20.6,0,0,0-1.154,9.537c.078.507.125,1.027.229,1.528M66.18,49.225a1.032,1.032,0,0,1-.009-.294,1.009,1.009,0,0,0-.053-.49,29.784,29.784,0,0,1,.27-4.017c.189-1.461.46-2.911.786-4.347.4-1.776.9-3.556,1.454-5.3q.7-2.2,1.542-4.35c.469-1.206.985-2.44,1.542-3.627.27-.575.546-1.148.846-1.708a8.476,8.476,0,0,0,.838-1.682,2.05,2.05,0,0,0-.655-2.217,2.156,2.156,0,0,0-1.208-.369,3.188,3.188,0,0,0-2.85,1.529q-.706,1.229-1.388,2.472c-2.091,3.815-4.566,7.437-6.923,11.093-.85,1.318-1.7,2.638-2.527,3.967-.2.32-.514.887-.568.914s-.1-.006-.088-.167a27.876,27.876,0,0,1,3.232-10.6c.2-.36.639-.833.663-1.249a1.262,1.262,0,0,0-.022-.268c-.267-1.716-2.792-2.492-4.151-1.57-.85.577-3.443,4.269-4.1,5.287-1.09,1.693-2.782,4.765-2.85,4.872s-.1.055-.038-.081c.218-.469.392-.97.592-1.437q.753-1.759,1.5-3.519c.47-1.1.907-2.229,1.446-3.3a1.674,1.674,0,0,0,.225-.848c-.048-.72-.841-1.252-1.423-1.555a3.072,3.072,0,0,0-3.208.026,3.72,3.72,0,0,0-1,1.775,84.265,84.265,0,0,0-3.351,10.273.61.61,0,0,0-.216.056l-.107.052v.118a29.425,29.425,0,0,1-.6,4.931,22.948,22.948,0,0,0-.37,4.973,1.406,1.406,0,0,0,.341.94,2.645,2.645,0,0,0,2.074.48,1.992,1.992,0,0,0,1.5-1.214c.6-1.369,1.143-2.779,1.643-4.186a44.594,44.594,0,0,1,3.572-7.335s1.014-1.636,1.064-1.679c-.018.108-.037.218-.055.332-.419,2.527-.993,5.987.717,7.735a3.993,3.993,0,0,0,3.945.905,2.207,2.207,0,0,0,.831-.766c1.836-2.6,2.967-5.612,4.835-8.179a1.559,1.559,0,0,0-.078.506q-.043.287-.082.575a39.16,39.16,0,0,0-.359,9.076c.183,1.821.74,5.126,3.165,5.126.019,0,.2,0,.277-.25s-.185-.579-.357-.8a.916.916,0,0,1-.271-.62M37.857,30.234c.571-1.642,2.273-5.344,2.767-6.785a111.825,111.825,0,0,0-10.742,12.5l5.274-.23a1.192,1.192,0,0,0,.728-.193,1.164,1.164,0,0,0,.308-.569m9.663-12.192a34.5,34.5,0,0,0-2.733,6.306c-.415,1.237-1.631,5.057-1.842,5.7-.195.59-.486,1.154-.625,1.761a5.715,5.715,0,0,1,2.789,2.661,2.9,2.9,0,0,1-.08,2.543,1.35,1.35,0,0,1-.574.4c-.809.323-1.327-.145-2-.5a4.723,4.723,0,0,0-1.285-.526c0,.013-1.021,4.9-1.176,7.249-.14,2.112-.3,4.5.958,6.348a2.994,2.994,0,0,1,.572,1.206c.151,1.037-1,1.6-1.885,1.671a3.543,3.543,0,0,1-3.144-1.539,10.081,10.081,0,0,1-1.317-5.927,69.117,69.117,0,0,1,1.242-9.559,43.072,43.072,0,0,0-8.094.417,39.051,39.051,0,0,0-3.054,5.6,18.767,18.767,0,0,0-1.23,4.336,7.247,7.247,0,0,1-.243,1.707,1.693,1.693,0,0,1-1.606.973,3.474,3.474,0,0,1-3.165-2.179,3.393,3.393,0,0,1,.312-2.252c.341-1.085.681-2.172,1.1-3.23a36.645,36.645,0,0,1,1.881-3.782c-1.836.358-4.436,2.085-5.678,2.943-1.106.764-2.651,1.708-3.144,3.019a1.793,1.793,0,0,1-1.113,1.164c-1.085.223-2.071-1.083-2.431-1.925a2.95,2.95,0,0,1,.133-2.926,39.416,39.416,0,0,1,5.191-4.034c1.673-1.121,5.5-2.35,8.767-3.076a3.475,3.475,0,0,0,1.8-.819,10.842,10.842,0,0,0,1.354-1.668c.8-1.114,1.5-2.3,2.34-3.394a93.817,93.817,0,0,1,8.57-9.585,26.348,26.348,0,0,1,7.072-5.362,3.232,3.232,0,0,0,.309-.438,2.917,2.917,0,0,1,1.093-1.329,2.272,2.272,0,0,1,1.884,0c.963.377,2.146,1.146,2.3,2.245.116.808-.9,2.035-.9,2.035M72.284,49.658l0-.01h.038l-.034.01M84.2,28.27c1.171-.457,2.373-.888,3.519-1.4.139-.063.554-.2.613-.346.051-.13-.1-.459-.147-.584a3.542,3.542,0,0,0-1.67-1.91,3.994,3.994,0,0,0-3.3,0,31.2,31.2,0,0,0-7.8,3.43,13.661,13.661,0,0,0-3.05,2.468,4.425,4.425,0,0,0-.184,4.755c.928,1.683,2.7,2.668,4.326,3.576a15.165,15.165,0,0,1,3.142,2.109c.434.417-1.346,2.809-1.639,3.153a9.881,9.881,0,0,1-5.247,2.98,4.3,4.3,0,0,1-1.693.141c-.766-.126-1.109-.619-1.6-1.142-.11-.119-.873-1.022-.971-.544a4.582,4.582,0,0,0,1.093,3.6,5.192,5.192,0,0,0,2.489,2.124,5.92,5.92,0,0,0,3.461-.287c2.965-1.019,6.3-3.5,7.285-6.581.859-2.7-1.035-5.016-3.029-6.629a39.832,39.832,0,0,0-5.113-3.308l-.144-.086c2.4-2.68,6.084-4.119,9.653-5.513m51.459-.846c-.97.392-1.811.955-2.73,1.432a9.418,9.418,0,0,0-1.991,1.645,32.648,32.648,0,0,0-3.6,4.06,31.959,31.959,0,0,0-4.928,9.584,29.5,29.5,0,0,0-1.192,5.752.775.775,0,0,1-.267.617c-.528.384-1.583-.1-2.057-.392-.975-.592-2.1-1.585-1.879-2.831a225.053,225.053,0,0,1,5.522-22.4,1.99,1.99,0,0,1,.593-1.106c.562-.423,1.365-.158,2,.14a3.625,3.625,0,0,1,2.018,1.7,3.1,3.1,0,0,1-.185,2.228,10.141,10.141,0,0,0-.769,2.152,17.8,17.8,0,0,1,4.742-4.127,6.865,6.865,0,0,1,6.1-.414c.512.249,1.044.741.888,1.284-.24.838-1.6.771-2.266.671M154.2,37.561c1.529-2.741,3.109-5.574,6.285-7.107a7.787,7.787,0,0,1-2.1,5.746,5.988,5.988,0,0,1-4.555,2.032q.189-.334.376-.671M166.1,38.914c-.555-.629-1.329.266-1.615.663a17.084,17.084,0,0,1-1.711,2.022,17.718,17.718,0,0,1-4.251,3.163A17.315,17.315,0,0,1,153.62,46.5a2.149,2.149,0,0,1-1.744-.2,1.841,1.841,0,0,1-.526-1.538,10.452,10.452,0,0,1,1.577-4.44,5.093,5.093,0,0,0,5.337.561,9.794,9.794,0,0,0,4.057-3.814,10.077,10.077,0,0,0,1.725-4.429,5.265,5.265,0,0,0-1.508-4.377,5.622,5.622,0,0,0-6.062-.559,12.389,12.389,0,0,0-4.62,4.367,27.458,27.458,0,0,0-3.675,7.271,6.088,6.088,0,0,1-.476,1.221,5.437,5.437,0,0,1-.583.776,28.7,28.7,0,0,1-4.512,4.564,11.6,11.6,0,0,1-2.812,1.571,9.013,9.013,0,0,1-1.58.444,3.636,3.636,0,0,1-1.592.083c-.926-.3-.749-1.906-.649-2.637.817-5.982,4.128-11.406,8.275-15.833a1.619,1.619,0,0,1,.074,1.674,13.614,13.614,0,0,0-.764,1.593,1.328,1.328,0,0,0,.49,1.557,1.4,1.4,0,0,0,.857.088,3.773,3.773,0,0,0,2.874-3.281,5.236,5.236,0,0,0-3.933-5.621,3.392,3.392,0,0,0-2.19.164,4.653,4.653,0,0,0-1.4,1.189,34.387,34.387,0,0,0-6.876,11.579,14.007,14.007,0,0,0-.8,7.408,6.93,6.93,0,0,0,4.765,5.381,7.4,7.4,0,0,0,6.285-1.793,21.517,21.517,0,0,0,4.164-5.27,8.283,8.283,0,0,0,1.848,4.28,6.171,6.171,0,0,0,4.1,2.162,8.609,8.609,0,0,0,5.769-2.2,20.138,20.138,0,0,0,5.316-6.343c.313-.586.577-1,.909-1.574.2-.349.715-1.207.365-1.6" transform="translate(-7.968 -14.512)" fill="#64c2c8"/></g></svg>
        </a>
        <div class="tl-right">
            <ul class="tl-menu">
                <?php if (User::get('id', 'candidate')) { ?>
                    <li><a href="{URL:profile}">Profile</a></li>
                    <li><a href="{URL:profile/logout}">Logout</a></li>
                <?php } else { ?>
                    <li><a href="{URL:login}">Login</a></li>
                <?php } ?>
                <li><a href="{URL:blogs}" onclick="load('blogs');">Our Blog</a></li>
                <li><a href="{URL:edison}">Edison</a></li>
            </ul>
            <a class="tel-block mail-ic" href="mailto:info@amsource.io">info@amsource.io</a>
            <a class="tel-block" href="tel://+4401134686700">+44 0113 468 6700</a>
            <div class="social-block">
                <?php if (Request::getParam('twitter')) { ?>
                    <a href="<?= Request::getParam('twitter') ?>" target="_blank" rel="noreferrer"><span class="icon-Twitter"></span></a>
                <?php } ?>

                <?php if (Request::getParam('facebook')) { ?>
                    <a href="<?= Request::getParam('facebook') ?>" target="_blank" rel="noreferrer"><span class="icon-Facebook"></span></a>
                <?php } ?>

                <?php if (Request::getParam('instagram')) { ?>
                    <a href="<?= Request::getParam('instagram') ?>" target="_blank" rel="noreferrer"><span class="icon-Instagram"></span></a>
                <?php } ?>

                <?php if (Request::getParam('linkedin')) { ?>
                    <a href="<?= Request::getParam('linkedin') ?>" target="_blank" rel="noreferrer"><span class="icon-LinkedIn"></span></a>
                <?php } ?>

                <?php if (Request::getParam('youtube')) { ?>
                    <a href="<?= Request::getParam('youtube') ?>" target="_blank" rel="noreferrer"><span class="icon-youtube"></span></a>
                <?php } ?>
            </div>
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
        $(document).ready(function() {
            // Enter listener
            $('#header_search').keydown(function(e){
                if (e.keyCode === 13) {
                    startSearch('#header_search');
                    e.preventDefault();
                }
            });
        });
    </script>

    <div id="content" class="content arrive">
<?php } ?>

    <?php
    echo $this->Load('contentPart'); // Content from view page
    ?>

<?php if (!Request::isAjax()) { ?>
    </div>

    <footer class="footer">
        <div class="follow-block">
            <h3 class="follow-title">Follow us</h3>
            <div class="social-block">
                <?php if (Request::getParam('facebook')) { ?>
                    <a href="<?= Request::getParam('facebook') ?>" target="_blank" rel="noreferrer"><span class="icon-Facebook"></span></a>
                <?php } ?>

                <?php if (Request::getParam('twitter')) { ?>
                    <a href="<?= Request::getParam('twitter') ?>" target="_blank" rel="noreferrer"><span class="icon-Twitter"></span></a>
                <?php } ?>

                <?php if (Request::getParam('instagram')) { ?>
                    <a href="<?= Request::getParam('instagram') ?>" target="_blank"><span class="icon-Instagram"></span></a>
                <?php } ?>

                <?php if (Request::getParam('linkedin')) { ?>
                    <a href="<?= Request::getParam('linkedin') ?>" target="_blank" rel="noreferrer"><span class="icon-LinkedIn"></span></a>
                <?php } ?>

                <?php if (Request::getParam('youtube')) { ?>
                    <a href="<?= Request::getParam('youtube') ?>" target="_blank" rel="noreferrer"><span class="icon-youtube"></span></a>
                <?php } ?>
            </div>
        </div>
        <div class="footer-block">
            <div class="fixed">
                <div class="footel-logo">
                    <a href="{URL:/}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="165.254" height="45.079" viewBox="0 0 165.254 45.079">
                            <g id="Amsource_Logo" data-name="Amsource Logo" transform="translate(0)">
                                <path id="Path_1" data-name="Path 1" d="M92.275,48.127a23.121,23.121,0,0,1,2.364-10.979A35.276,35.276,0,0,1,96.559,33.7c.327-.523.665-1.039,1.009-1.551.447-.667,1.189-1.386,1.151-2.255a.943.943,0,0,1,.9-.174c.567.251.872,1.316.96,2.129-.059,6.033-2.277,13.857-8.305,16.275M103.183,30.04c-.143-.143-.349-.209-.479-.369a6.932,6.932,0,0,0-.941-.961,4.978,4.978,0,0,0-2.748-1.174,5.164,5.164,0,0,0-1.363.071,3.156,3.156,0,0,0-1.955-.381,2.639,2.639,0,0,0-2.02,1.36,42.759,42.759,0,0,0-2.3,3.614,29,29,0,0,0-2.56,5.983,26.4,26.4,0,0,0-1.067,6.732,4.453,4.453,0,0,0,1.541,3.813c.181.158.185.5.351.7a6.254,6.254,0,0,0,1.889,1.425,4.273,4.273,0,0,0,2.242.667,7.266,7.266,0,0,0,1.676-.366,5.375,5.375,0,0,0,2.51-1.778l.037-.049a.251.251,0,0,0-.074-.335,17.217,17.217,0,0,0,1.6-1.876,20,20,0,0,0,3.457-6.344,19.118,19.118,0,0,0,.516-9.895,1.842,1.842,0,0,0-.277-.795C103.207,30.065,103.195,30.052,103.183,30.04ZM118.4,53.7c.24,1.165-1.271.886-1.872.459a3.782,3.782,0,0,1-1.119-1.43c-1.143-2.268-1.253-4.892-1.331-7.426a14.577,14.577,0,0,1-2.1,2.246,4.936,4.936,0,0,1-2.8,1.179,4.753,4.753,0,0,1-4.344-3.1,9.8,9.8,0,0,1-.244-5.574,45.256,45.256,0,0,1,3.936-10.516,2.892,2.892,0,0,1,.919-1.271,2.481,2.481,0,0,1,1.875-.166,2.989,2.989,0,0,1,2.122,1.351c.514,1.02-.027,2.227-.547,3.244-2.037,3.981-3.87,8.174-4.25,12.622a16.132,16.132,0,0,0,6.471-6.981c1.513-2.838,2.532-5.9,3.839-8.841a2.309,2.309,0,0,1,.962-1.242,2.358,2.358,0,0,1,1.91.23,2.9,2.9,0,0,1,1.721,1.4,2.835,2.835,0,0,1-.182,1.884c-1.207,3.5-2.848,6.836-4.01,10.348a21.6,21.6,0,0,0-1.2,9.98c.082.531.131,1.075.239,1.6M68.755,50.837a1.081,1.081,0,0,1-.01-.308,1.058,1.058,0,0,0-.056-.512,31.233,31.233,0,0,1,.281-4.2c.2-1.529.48-3.046.82-4.549.421-1.858.937-3.721,1.518-5.543q.734-2.3,1.611-4.552c.489-1.262,1.029-2.553,1.61-3.795.281-.6.57-1.2.884-1.787a8.878,8.878,0,0,0,.875-1.76,2.148,2.148,0,0,0-.684-2.319,2.248,2.248,0,0,0-1.261-.386,3.327,3.327,0,0,0-2.976,1.6q-.738,1.286-1.449,2.586C67.735,29.3,65.151,33.09,62.69,36.916c-.887,1.379-1.771,2.76-2.638,4.152-.209.335-.536.928-.593.956s-.109-.006-.092-.175a29.214,29.214,0,0,1,3.375-11.091c.213-.377.667-.871.693-1.307a1.324,1.324,0,0,0-.023-.281c-.279-1.8-2.916-2.608-4.334-1.643-.887.6-3.6,4.467-4.28,5.532-1.138,1.772-2.9,4.986-2.976,5.1s-.106.058-.04-.084c.227-.491.409-1.015.619-1.5q.786-1.84,1.57-3.682c.491-1.152.947-2.332,1.51-3.451a1.755,1.755,0,0,0,.235-.888c-.051-.753-.879-1.31-1.486-1.627a3.2,3.2,0,0,0-3.349.027A3.9,3.9,0,0,0,49.838,28.8a88.31,88.31,0,0,0-3.5,10.75.636.636,0,0,0-.226.058L46,39.668v.123a30.853,30.853,0,0,1-.628,5.16,24.062,24.062,0,0,0-.386,5.2,1.473,1.473,0,0,0,.356.984,2.758,2.758,0,0,0,2.166.5,2.081,2.081,0,0,0,1.565-1.27c.624-1.432,1.194-2.908,1.716-4.38a46.706,46.706,0,0,1,3.73-7.675s1.059-1.712,1.111-1.757c-.019.113-.038.228-.057.347-.437,2.644-1.037,6.265.749,8.094a4.164,4.164,0,0,0,4.119.947,2.306,2.306,0,0,0,.867-.8c1.917-2.717,3.1-5.872,5.049-8.559a1.635,1.635,0,0,0-.082.53q-.045.3-.085.6a41.063,41.063,0,0,0-.375,9.5c.191,1.905.772,5.363,3.3,5.363.02,0,.205,0,.29-.262s-.193-.606-.373-.832a.959.959,0,0,1-.283-.649M39.179,30.964c.6-1.718,2.374-5.592,2.889-7.1A116.951,116.951,0,0,0,30.851,36.947l5.508-.241a1.243,1.243,0,0,0,.76-.2,1.219,1.219,0,0,0,.322-.6M47.531,23.15a36.14,36.14,0,0,0-2.854,6.6c-.433,1.294-1.7,5.292-1.924,5.96-.2.617-.508,1.208-.652,1.842a5.973,5.973,0,0,1,2.913,2.784A3.036,3.036,0,0,1,44.93,43a1.41,1.41,0,0,1-.6.423c-.845.338-1.386-.152-2.093-.528a4.926,4.926,0,0,0-1.342-.55c0,.014-1.066,5.125-1.228,7.586-.146,2.21-.312,4.711,1,6.643a3.136,3.136,0,0,1,.6,1.262c.158,1.085-1.048,1.678-1.968,1.748a3.7,3.7,0,0,1-3.283-1.61,10.567,10.567,0,0,1-1.375-6.2,72.469,72.469,0,0,1,1.3-10,44.884,44.884,0,0,0-8.452.437,40.889,40.889,0,0,0-3.189,5.861A19.67,19.67,0,0,0,23.01,52.6a7.6,7.6,0,0,1-.254,1.786A1.767,1.767,0,0,1,21.079,55.4a3.628,3.628,0,0,1-3.3-2.28,3.558,3.558,0,0,1,.325-2.357c.356-1.135.711-2.273,1.153-3.38a38.379,38.379,0,0,1,1.965-3.957c-1.917.374-4.632,2.181-5.93,3.08-1.155.8-2.769,1.788-3.283,3.16a1.875,1.875,0,0,1-1.162,1.218c-1.133.233-2.162-1.133-2.539-2.015a3.093,3.093,0,0,1,.139-3.062,41.172,41.172,0,0,1,5.42-4.221c1.747-1.173,5.747-2.459,9.155-3.219a3.625,3.625,0,0,0,1.884-.857,11.34,11.34,0,0,0,1.414-1.746c.84-1.166,1.565-2.411,2.443-3.552a98.1,98.1,0,0,1,8.949-10.03,27.519,27.519,0,0,1,7.385-5.611,3.381,3.381,0,0,0,.323-.458,3.051,3.051,0,0,1,1.141-1.391,2.368,2.368,0,0,1,1.968,0c1.005.395,2.241,1.2,2.405,2.349.121.845-.938,2.129-.938,2.129M75.128,51.289l0-.011h.04l-.036.011M87.573,28.908c1.223-.478,2.478-.929,3.674-1.468.145-.065.579-.208.64-.362.053-.136-.106-.48-.154-.611a3.7,3.7,0,0,0-1.744-2,4.162,4.162,0,0,0-3.443,0,32.541,32.541,0,0,0-8.142,3.59,14.271,14.271,0,0,0-3.185,2.582,4.638,4.638,0,0,0-.192,4.976c.969,1.761,2.818,2.791,4.517,3.742a15.834,15.834,0,0,1,3.281,2.207c.454.436-1.405,2.939-1.712,3.3a10.312,10.312,0,0,1-5.479,3.118,4.486,4.486,0,0,1-1.768.148c-.8-.132-1.158-.648-1.667-1.2-.115-.124-.911-1.069-1.014-.57a4.8,4.8,0,0,0,1.142,3.766,5.425,5.425,0,0,0,2.6,2.223,6.169,6.169,0,0,0,3.614-.3c3.1-1.067,6.583-3.658,7.607-6.886.9-2.828-1.081-5.249-3.163-6.937a41.584,41.584,0,0,0-5.339-3.461l-.15-.09c2.5-2.8,6.354-4.31,10.08-5.769m53.735-.885c-1.012.41-1.891,1-2.851,1.5a9.839,9.839,0,0,0-2.079,1.721,34.14,34.14,0,0,0-3.762,4.249,33.468,33.468,0,0,0-5.146,10.029,30.921,30.921,0,0,0-1.245,6.019.811.811,0,0,1-.279.646c-.552.4-1.653-.108-2.148-.41-1.018-.62-2.19-1.658-1.962-2.963A235.911,235.911,0,0,1,127.6,25.375a2.084,2.084,0,0,1,.62-1.157c.586-.443,1.426-.165,2.092.147a3.788,3.788,0,0,1,2.107,1.782,3.245,3.245,0,0,1-.193,2.332,10.628,10.628,0,0,0-.8,2.252,18.6,18.6,0,0,1,4.952-4.319,7.155,7.155,0,0,1,6.368-.433c.535.261,1.09.775.928,1.344-.25.877-1.676.807-2.366.7m19.357,10.608c1.6-2.868,3.247-5.833,6.563-7.437a8.159,8.159,0,0,1-2.2,6.013,6.247,6.247,0,0,1-4.757,2.127q.2-.35.392-.7M173.1,40.047c-.58-.659-1.388.279-1.687.694a17.865,17.865,0,0,1-1.787,2.116,18.5,18.5,0,0,1-4.439,3.31,18.056,18.056,0,0,1-5.122,1.819,2.24,2.24,0,0,1-1.821-.21,1.929,1.929,0,0,1-.549-1.61,10.953,10.953,0,0,1,1.647-4.647,5.309,5.309,0,0,0,5.573.587,10.236,10.236,0,0,0,4.236-3.991,10.558,10.558,0,0,0,1.8-4.635,5.516,5.516,0,0,0-1.575-4.58,5.86,5.86,0,0,0-6.33-.585,12.948,12.948,0,0,0-4.825,4.57,28.756,28.756,0,0,0-3.838,7.608,6.38,6.38,0,0,1-.5,1.277,5.688,5.688,0,0,1-.609.812,30.006,30.006,0,0,1-4.712,4.776A12.1,12.1,0,0,1,145.628,49a9.4,9.4,0,0,1-1.65.465,3.789,3.789,0,0,1-1.662.087c-.967-.317-.783-1.995-.678-2.76.853-6.26,4.31-11.936,8.641-16.568a1.7,1.7,0,0,1,.077,1.752,14.255,14.255,0,0,0-.8,1.667,1.391,1.391,0,0,0,.512,1.629,1.458,1.458,0,0,0,.895.092,3.945,3.945,0,0,0,3-3.433,5.479,5.479,0,0,0-4.107-5.882,3.535,3.535,0,0,0-2.286.171,4.862,4.862,0,0,0-1.46,1.244,36,36,0,0,0-7.18,12.116,14.685,14.685,0,0,0-.839,7.752,7.246,7.246,0,0,0,4.975,5.63,7.711,7.711,0,0,0,6.563-1.876,22.509,22.509,0,0,0,4.348-5.515,8.677,8.677,0,0,0,1.93,4.479,6.44,6.44,0,0,0,4.282,2.263,8.979,8.979,0,0,0,6.024-2.306,21.065,21.065,0,0,0,5.552-6.637c.326-.613.6-1.046.95-1.648.211-.365.747-1.263.381-1.677" transform="translate(-7.968 -14.512)" fill="#fff"/>
                            </g>
                        </svg>
                    </a>
                </div>
                <div class="footer-cont">
                    <div>
                        <div class="footer-flex">
                            <div class="footer-address">
                                <b>Amsource (UK)</b><br>
                                The Leeming Building,<br>
                                Vicar Lane, Leeds, LS2 7JF<br>
                                t: +44 0113 468 6700
                            </div>
                            <div class="footer-address">
                                <b>Amsource (Europe)</b><br>
                                3rd Floor, Pariser Platz  6a,<br>
                                10117, Berlin, Europe<br>
                                t: +49 30 300 149 3266
                            </div>
                        </div>
                        <div class="footer-flex">
                            <ul class="footer-menu">
                                <li><a href="{URL:about-us}">About</a></li>
                                <li><a href="{URL:jobs}">Search Jobs</a></li>
                                <li><a href="{URL:services}">Candidates</a></li>
                                <!--<li><a href="{URL:/}">Employers</a></li>-->
                            </ul>
                            <ul class="footer-menu">
                                <!--<li><a href="{URL:blogs}">Latest News</a></li>-->
                                <li><a href="{URL:blogs}">Blog</a></li>
                                <li><a href="{URL:contact-us}">Contact</a></li>
                            </ul>
                            <ul class="footer-menu">
                                <li><a href="{URL:terms-and-conditions}">Terms & Conditions</a></li>
                                <li><a href="{URL:privacy-policy}">Privacy Policy</a></li>
                                <!--<li><a href="{URL:/}">Site Map</a></li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="footer-btn-block">
                        <button class="btn-yellow" onclick="load('page/arrange_call');">Arrange call back</button>
                        <!--<button class="btn-yellow">Apply using <span class="icon-linked"></span></button>-->
                        <button class="btn-transparent" onclick="load('about_us/upload_cv');">Upload cv</button>
                    </div>
                </div>
                <div class="copyright">Copyright © <?= date("Y"); ?> Amsource Technology Limited (Registered Company Number 7298917)</div>
            </div>
        </div>
    </footer>

    <div class="preloader dark">
        <div class="preloader__inner">
            <div class="preloader__logo">
                <img src="<?= _SITEDIR_ ?>public/images/panel/logo.png" alt="">
            </div>
            <div class="preloader__item first"></div>
            <div class="preloader__item second"></div>
            <div class="preloader__item third"></div>
        </div>
    </div>

    <script>
        // Preloader, do not remove
        const preloader = document.querySelector('.preloader');

        function removePreloader() {
            preloader.classList.add('fade');

            setTimeout(() => {
                preloader.classList.add('loaded');
                preloader.classList.remove('fade');
                preloader.remove();
            }, 500)
        }

        window.addEventListener('load', function () {
            removePreloader();
        })

        setTimeout(() => {
            if (preloader) {
                removePreloader();
            }
        }, 10000)
    </script>

    <?php /* if (Request::getParam('tracker') == 'yes') { ?>
        <!-- Bug Tracker -->
        <div id="api_content"></div>

        <div class="scan__block">
            <a class="report__button" onclick="load('issue_manager/create_task', 'project=<?= Request::getParam('tracker_api') ?>', 'url=' + window.location.href);">Report<br>an issue</a>
            <?php /*<a class="report__button" onclick="load('https://donemen.com/api/create_task', 'project=<?= CONF_PROJECT ?>', 'url=' + window.location.href);">Report<br>an issue</a>
        </div>
        <!-- /Bug Tracker -->
    <?php } */ ?>
</div>

    <?php echo reFilter(Request::getParam('include_code_bottom')); // Bottom JS code ?>

    <script>
        $(window).scroll(function(){
            function getScreenPercentage(percentage) {
                percentage = parseInt(percentage) || 0;
                if (percentage > 100 || percentage < 0)
                    percentage = 0;

                let hWoWin = docH() - winH();

                if (hWoWin <= 0)
                    return 0;

                return hWoWin * percentage / 100;
            }

            $(window).scroll(function(){
                if ($(this).scrollTop() > 20) {
                    $('.header').addClass('scroll-active');
                } else {
                    $('.header').removeClass('scroll-active');
                }

                if ($(this).scrollTop() > getScreenPercentage(33)) {
                    $('.header').addClass('scroll-active_2');
                } else {
                    $('.header').removeClass('scroll-active_2');
                }

                if ($(this).scrollTop() > getScreenPercentage(66)) {
                    $('.header').addClass('scroll-active_3');
                } else {
                    $('.header').removeClass('scroll-active_3');
                }
            });
        });
    </script>

</body>
</html>
<?php } ?>
