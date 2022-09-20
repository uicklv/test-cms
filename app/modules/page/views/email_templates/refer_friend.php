<table border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">
                <!-- START CENTERED WHITE CONTAINER -->
                <table class="main">
                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper"><p><strong>New friend referring</strong></p>
                            <p>A refer a friend submission has been made via the website.</p>

                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:14px; color:#636363; font-family: Tahoma,sans-serif;">
                                <?php if ($this->data['your_name']) { ?>
                                <tr>
                                    <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Full name</strong></td>
                                    <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->data['your_name']; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($this->data['your_email']) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Email</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->data['your_email']; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($this->data['your_tel']) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Telephone number</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->data['your_tel']; ?></td>
                                    </tr>
                                <?php } ?>

                                <?php if ($this->data['friend_name']) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Friend full name</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->data['friend_name']; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($this->data['friend_email']) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Friend email</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->data['friend_email']; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($this->data['friend_tel']) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Friend telephone number</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->data['friend_tel']; ?></td>
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
