<body class="">
<table border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">
                <!-- START CENTERED WHITE CONTAINER -->
                <table class="main">
                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper"><p><strong>New contact</strong></p>
                            <p>The following person has completed the website contact form</p>

                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:14px; color:#636363; font-family: Tahoma,sans-serif;">
                                <?php if (post('name')) { ?>
                                <tr>
                                    <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Name</strong></td>
                                    <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= post('name'); ?></td>
                                </tr>
                                <?php } ?>
                                <?php if (post('email')) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Email</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= post('email'); ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (post('message')) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Message</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= post('message'); ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                    <!-- END MAIN CONTENT AREA -->
                </table>
                <!-- START FOOTER --><!-- END FOOTER -->
                <!-- END CENTERED WHITE CONTAINER -->
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
