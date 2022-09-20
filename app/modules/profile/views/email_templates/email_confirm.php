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
                                <td>
                                    <p style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Thank you for registering.</p>
                                    <p style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">To activate your account and begin the search for your next career move, please click <a href="<?= SITE_URL ?>email-confirmation?email=<?= $this->email ?>&token=<?= $this->token ?>">here</a>.</p>
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
