<table border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">
                <!-- START CENTERED WHITE CONTAINER -->
                <table class="main">
                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper"><p><strong>New Resource Download</strong></p>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:14px; color:#636363; font-family: Tahoma,sans-serif;">
                                <tr>
                                    <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Resource</strong></td>
                                    <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= $this->resource->title . '(#' . $this->resource->id . ')'; ?></td>
                                </tr>
                                <tr>
                                    <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Type</strong></td>
                                    <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= 'For' . getResourceType($this->resource->type); ?></td>
                                </tr>
                                <?php if (post('firstname')) { ?>
                                <tr>
                                    <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>First name</strong></td>
                                    <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= post('firstname'); ?></td>
                                </tr>
                                <?php } ?>
                                <?php if (post('lastname')) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Last name</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= post('lastname'); ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (post('email')) { ?>
                                    <tr>
                                        <td width="40%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><strong>Email</strong></td>
                                        <td width="60%" style="padding: 10px; border-bottom: 1px solid #cccccc;"><?= post('email'); ?></td>
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
