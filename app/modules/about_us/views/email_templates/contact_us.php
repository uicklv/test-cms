<table border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">
                <!-- START CENTERED WHITE CONTAINER -->
                <table class="main">
                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper"><p><strong>Submitted Contact Us form</strong></p>
                            <p>The following person has completed the website contact form</p>

                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:14px; color:#636363; font-family: Tahoma,sans-serif;">
                                <?php if ($this->uvid) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Uploaded Vacancy</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><a href="{URL:panel/vacancy_applications/edit/<?= $this->uvid ?>}" target="_blank">Application ID: <?= $this->uvid ?></a></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($this->data['name']) { ?>
                                <tr>
                                    <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Full name</strong></td>
                                    <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->data['name']; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($this->data['tel']) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Telephone</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->data['tel']; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($this->data['email']) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Email</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->data['email']; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($this->data['linkedin']) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>LinkedIn</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->data['linkedin']; ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if ($this->data['message']) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Message</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->data['message']; ?></td>
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
