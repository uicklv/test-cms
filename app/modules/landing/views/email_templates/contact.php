<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
    <tr>
        <td>
            <table class="main table-600" cellpadding="0" cellspacing="0" width="600" align="center">
                <tr>
                    <td height="30" width="100%"></td>
                </tr>
                <tr>
                    <td class="header-td">
                        <table class="table-460" cellpadding="0" cellspacing="0" width="460" align="center">
                            <tr>
                                <td align="center" class="logo">
                                    <img src="{URL:/}app/public/images/panel/email_logo.png" alt="logo" />
                                </td>
                            </tr>
                            <tr>
                                <td height="28"></td>
                            </tr>
                            <tr>
                                <td bgcolor="#F1F1F1" height="1"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="main-tb" width="380"  align="center">
                            <tr>
                                <td><p style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">New Message</p></td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
                                        <?php if (post('firstname')) { ?>
                                            <tr>
                                                <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">First Name</td>
                                                <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';"><?= post('firstname') ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (post('lastname')) { ?>
                                            <tr>
                                                <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Last Name</td>
                                                <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';"><?= post('lastname') ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (post('email')) { ?>
                                            <tr>
                                                <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Email</td>
                                                <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';"><?= post('email') ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (post('tel')) { ?>
                                            <tr>
                                                <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Phone Number</td>
                                                <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';"><?= post('tel') ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (post('message')) { ?>
                                            <tr>
                                                <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Message</td>
                                                <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';"><?= post('message') ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="30"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>