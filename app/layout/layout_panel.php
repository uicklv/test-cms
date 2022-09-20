<?php if (!Request::isAjax()) { ?>
<!--

/*
  ____        _     _    ____ __  __ ____
 | __ )  ___ | | __| |  / ___|  \/  / ___|
 |  _ \ / _ \| |/ _` | | |   | |\/| \___ \
 | |_) | (_) | | (_| | | |___| |  | |___) |
 |____/ \___/|_|\__,_|  \____|_|  |_|____/   .v4 2020

 Copyright Bold Identities Ltd - All Rights Reserved

 Author: Bold Identities Ltd

*/

-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?= Request::getTitle(); ?></title>

    <?php
    if (Request::getParam('favicon'))
        $favicon = _SITEDIR_ . 'data/setting/' . Request::getParam('favicon');
    else
        $favicon = _SITEDIR_ . 'assets/img/favicon.png';
    ?>
    <link rel="icon" type="image/x-icon" href="<?= $favicon ?>"/>
    <link href="<?= _SITEDIR_ ?>assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="<?= _SITEDIR_ ?>assets/js/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="<?= _SITEDIR_ ?>public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= _SITEDIR_ ?>assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="<?= _SITEDIR_ ?>public/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="<?= _SITEDIR_ ?>assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css">
    <link href="<?= _SITEDIR_ ?>assets/css/elements/avatar.css" rel="stylesheet" type="text/css">
    <link href="<?= _SITEDIR_ ?>public/css/backend/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= _SITEDIR_ ?>public/css/backend/jquery.scroll.css" rel="stylesheet" />
    <link href="<?= _SITEDIR_ ?>public/css/backend/jquery.jcrop.css" rel="stylesheet" type="text/css" />
    <link href="<?= _SITEDIR_ ?>public/css/additional_styles.css?(cache)" rel="stylesheet" type="text/css" />
    <!-- toastr -->
    <link href="<?= _SITEDIR_ ?>public/plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <!-- Datatable -->
    <link rel="stylesheet" type="text/css" href="<?= _SITEDIR_ ?>public/plugins/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="<?= _SITEDIR_ ?>public/plugins/datatable/dt-global_style.css">
    <!-- Datatable -->

    <link href="<?= _SITEDIR_ ?>public/css/more.css?(cache)" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->


    <script>var site_url = '<?= SITE_URL ?>';</script>
    <script>var site_dir = '<?= _SITEDIR_ ?>';</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!-- Copy to clipboard -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
    <script src="https://kit.fontawesome.com/aaa56c7348.js" crossorigin="anonymous"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/jquery.scrollbar.min.js"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/function.js?(cache)"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/event.js?(cache)"></script>
    <script src="<?= _SITEDIR_ ?>public/plugins/notification/snackbar/snackbar.min.js"></script>
    <script src="<?= _SITEDIR_ ?>public/js/backend/jquery.jcrop.min.js"></script>

    <!-- Datepicker -->
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <?php /*
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

<!-- BEGIN LOADER -->
<div id="load_screen">
    <div class="loader">
        <div class="loader-content">
            <div class="spinner-grow align-self-center"></div>
        </div>
    </div>
</div>
<!--  END LOADER -->

<!--  BEGIN NAVBAR  -->
<div class="header-container fixed-top">
    <header class="header navbar navbar-expand-sm">
        <ul class="navbar-item theme-brand flex-row text-center">
            <li style="margin-right: 10px">
                <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </a>
            </li>

            <li class="nav-item theme-logo">
                <a href="{URL:/panel}">
                    <img src="<?= Request::getParam('admin_logo') ?>" class="navbar-logo" alt="logo">
                </a>
            </li>
<!--            <li class="nav-item theme-text">-->
<!--                <a href="{URL:/panel}" class="nav-link"> BOLD </a>-->
<!--            </li>-->
        </ul>

<!--        <ul class="navbar-item flex-row ml-md-0 ml-auto">-->
<!--            <li class="nav-item align-self-center search-animated">-->
<!--                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search toggle-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>-->
<!--                <form class="form-inline search-full form-inline search" role="search">-->
<!--                    <div class="search-bar">-->
<!--                        <input type="text" class="form-control search-form-control  ml-lg-auto" placeholder="Search...">-->
<!--                    </div>-->
<!--                </form>-->
<!--            </li>-->
<!--        </ul>-->

        <ul class="navbar-item flex-row ml-sm-auto ml-md-auto">
            <?php /*
            <li class="nav-item dropdown language-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="language-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="<?= _SITEDIR_ ?>assets/img/ca.png" class="flag-width" alt="flag">
                </a>
                <div class="dropdown-menu position-absolute" aria-labelledby="language-dropdown">
                    <a class="dropdown-item d-flex" href="javascript:void(0);"><img src="<?= _SITEDIR_ ?>assets/img/de.png" class="flag-width" alt="flag"> <span class="align-self-center">&nbsp;German</span></a>
                    <a class="dropdown-item d-flex" href="javascript:void(0);"><img src="<?= _SITEDIR_ ?>assets/img/jp.png" class="flag-width" alt="flag"> <span class="align-self-center">&nbsp;Japanese</span></a>
                    <a class="dropdown-item d-flex" href="javascript:void(0);"><img src="<?= _SITEDIR_ ?>assets/img/fr.png" class="flag-width" alt="flag"> <span class="align-self-center">&nbsp;French</span></a>
                    <a class="dropdown-item d-flex" href="javascript:void(0);"><img src="<?= _SITEDIR_ ?>assets/img/ca.png" class="flag-width" alt="flag"> <span class="align-self-center">&nbsp;English</span></a>
                </div>
            </li>
            <li class="nav-item dropdown message-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="messageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </a>
                <div class="dropdown-menu p-0 position-absolute" aria-labelledby="messageDropdown">
                    <div class="">
                        <a class="dropdown-item">
                            <div class="">

                                <div class="media">
                                    <div class="user-img">
                                        <img class="usr-img rounded-circle" src="<?= _SITEDIR_ ?>assets/img/90x90.jpg" alt="profile">
                                    </div>
                                    <div class="media-body">
                                        <div class="">
                                            <h5 class="usr-name">Kara Young</h5>
                                            <p class="msg-title">ACCOUNT UPDATE</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </a>
                        <a class="dropdown-item">
                            <div class="">

                                <div class="media">
                                    <div class="user-img">
                                        <img class="usr-img rounded-circle" src="<?= _SITEDIR_ ?>assets/img/90x90.jpg" alt="profile">
                                    </div>
                                    <div class="media-body">
                                        <div class="">
                                            <h5 class="usr-name">Daisy Anderson</h5>
                                            <p class="msg-title">ACCOUNT UPDATE</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </a>
                        <a class="dropdown-item">
                            <div class="">

                                <div class="media">
                                    <div class="user-img">
                                        <img class="usr-img rounded-circle" src="<?= _SITEDIR_ ?>assets/img/90x90.jpg" alt="profile">
                                    </div>
                                    <div class="media-body">
                                        <div class="">
                                            <h5 class="usr-name">Oscar Garner</h5>
                                            <p class="msg-title">ACCOUNT UPDATE</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown notification-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
                </a>
                <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                    <div class="notification-scroll">

                        <div class="dropdown-item">
                            <div class="media">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                <div class="media-body">
                                    <div class="notification-para"><span class="user-name">Shaun Park</span> likes your photo.</div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-item">
                            <div class="media">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                                <div class="media-body">
                                    <div class="notification-para"><span class="user-name">Kelly Young</span> shared your post</div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-item">
                            <div class="media">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7" y2="7"></line></svg>
                                <div class="media-body">
                                    <div class="notification-para"><span class="user-name">Kelly Young</span> mentioned you in comment.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            */ ?>
            <li class="user-profile-info">
                <h3><?= User::get('firstname') . ' ' . User::get('lastname') ?></h3>
                <div><?= User::get('email') ?></div>
            </li>
            <li class="nav-item dropdown user-profile-dropdown">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?php if (file_exists(_SYSDIR_ . 'data/users/mini_' . User::get('image'))) { ?>
                        <img src="<?= _SITEDIR_ ?>data/users/mini_<?= User::get('image'); ?>" alt="avatar">
                    <?php } else { ?>
                        <img src="<?= _SITEDIR_ ?>assets/img/90x90.jpg" alt="avatar">
                    <?php } ?>
                </a>
                <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                    <div class="">
                        <div class="dropdown-item">
                            <a class="" href="{URL:panel/team/edit}/<?= User::get('id'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                My Profile
                            </a>
                        </div>
<!--                        <div class="dropdown-item">-->
<!--                            <a class="" href="apps_mailbox.html">-->
<!--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>-->
<!--                                Inbox-->
<!--                            </a>-->
<!--                        </div>-->
<!--                        <div class="dropdown-item">-->
<!--                            <a class="" href="auth_lockscreen.html">-->
<!--                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>-->
<!--                                Lock Screen-->
<!--                            </a>-->
<!--                        </div>-->
                        <div class="dropdown-item">
                            <a class="" href="{URL:panel/logout}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                Sign Out
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>
<!--  END NAVBAR  -->

<!--  BEGIN NAVBAR  -->
<?php /*
<div class="sub-header-container">
    <header class="header navbar navbar-expand-sm">
        <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </a>

        <ul class="navbar-nav flex-row">
            <li>
                <div class="page-header">
                    <nav class="breadcrumb-one" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{URL:panel}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><span>Sales</span></li>
                        </ol>
                    </nav>
                </div>
            </li>
        </ul>

        <ul class="navbar-nav flex-row ml-auto ">
            <li class="nav-item more-dropdown">
                <div class="dropdown  custom-dropdown-icon">
                    <a class="dropdown-toggle btn" href="#" role="button" id="customDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>Settings</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="customDropdown">
                        <a class="dropdown-item" data-value="Settings" href="{URL:panel/setting}">Settings</a>
<!--                        <a class="dropdown-item" data-value="Mail" href="javascript:void(0);">Mail</a>-->
<!--                        <a class="dropdown-item" data-value="Print" href="javascript:void(0);">Print</a>-->
<!--                        <a class="dropdown-item" data-value="Download" href="javascript:void(0);">Download</a>-->
<!--                        <a class="dropdown-item" data-value="Share" href="javascript:void(0);">Share</a>-->
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>
*/ ?>
<!--  END NAVBAR  -->

<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
    <div class="overlay"></div>
    <div class="search-overlay"></div>

    <!--  BEGIN SIDEBAR  -->
    <div class="sidebar-wrapper sidebar-theme">
        <nav id="sidebar">

            <?php echo View::get('panel/index', 'left'); // !!! LEFT-MENU !!! ?>

        </nav>
    </div>
    <!--  END SIDEBAR  -->

    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
<!--        <div id="content_place" class="content arrive">-->
        <?php } ?>

        <?php echo $this->Load('contentPart'); // Content wrapper ?>

        <?php if (!Request::isAjax()) { ?>

        <div class="footer-wrapper">
            <div class="footer-section f-section-1">
                <p class="">© <?= date("Y") ?> Bold Identities Ltd, registered in England & Wales (<a href="tel:09777426">09777426</a>), All rights reserved.</p>
            </div>
            <div class="footer-section f-section-2">
                <!--<p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>-->
            </div>
        </div>

<!--        </div>-->
    </div>
    <!--  END CONTENT AREA  -->
</div>
<!-- END MAIN CONTAINER -->

<div>
    <div class="popup-fon" onclick="closePopup();"></div>
    <div id="popup" class="popup popup_height_full"></div>
</div>

<div id="api_content"></div>

<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="<?= _SITEDIR_ ?>public/plugins/bootstrap/js/popper.min.js"></script>
<script src="<?= _SITEDIR_ ?>public/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= _SITEDIR_ ?>public/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?= _SITEDIR_ ?>assets/js/app.js"></script>

<script>
    $(document).ready(function() {
        App.init();
    });
</script>
<script src="<?= _SITEDIR_ ?>assets/js/custom.js"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->

</body>
</html>
<?php } ?>
