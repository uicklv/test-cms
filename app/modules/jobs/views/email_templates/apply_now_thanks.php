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
                                    <h3 class="title" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Vacancy Application Received</h3>
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
                                <td>
                                    <p style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Thank you <?= $this->data['name'] ?> for applying for <a href="{URL:job/<?= $this->job->slug ?>}" target="_blank"><?= $this->job->title ?></a>.</p>
                                    <p style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">A member of our team will now review your application and be in touch shortly.</p>
                                    <p style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Thanks!<br><?= SITE_NAME ?></p>
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
