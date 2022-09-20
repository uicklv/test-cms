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
                                <td align="center" class="title">
                                    <h3 class="title" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Vacancy Application</h3>
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
                                <td><p style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">The following person has applied for a vacancy.</p></td>
                            </tr>
                            <tr>
                                <td>
                                    <table>
                                        <?php if ($this->job->id) { ?>
                                            <tr>
                                                <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Job Vacancy:</td>
                                                <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';"><a href="{URL:job/<?= $this->job->slug ?>}" target="_blank" style="text-decoration: none; color: #1F5DB4"><?= $this->job->title; ?></a></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($this->data['name']) { ?>
                                            <tr>
                                                <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Full name:</td>
                                                <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';"><?= $this->data['name'] ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($this->data['tel']) { ?>
                                            <tr>
                                                <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Telephone:</td>
                                                <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';"><a href="tel:<?= $this->data['tel'] ?>" style="text-decoration: none; color: #1F5DB4"><?= $this->data['tel'] ?></a></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($this->data['email']) { ?>
                                            <tr>
                                                <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Email:</td>
                                                <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';"><a href="mailto:<?= $this->data['email'] ?>" style="text-decoration: none; color: #1F5DB4"><?= $this->data['email'] ?></a></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($this->data['linkedin']) { ?>
                                            <tr>
                                                <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">LinkedIn:</td>
                                                <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';"><a href="<?= $this->data['linkedin'] ?>" style="text-decoration: none; color: #1F5DB4"><?= $this->data['linkedin'] ?></a></td>
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