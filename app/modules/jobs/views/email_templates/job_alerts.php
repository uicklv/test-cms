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
                                    <img src="{URL:/}app/public/images/panel/email_logo.png" alt="logo" style="width: 240px;"/>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" class="title">
                                    <h3 class="title" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Jobs for you</h3>
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
                                    <table>
                                        <tr>
                                            <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Title</td>
                                            <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Location</td>
                                            <td class="label" style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">Sector</td>
                                        </tr>
                                        <?php if ($this->jobs) { ?>
                                            <?php foreach ($this->jobs as $job) { ?>
                                                <tr>
                                                    <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';"><a href="{URL:job/<?= $job->slug ?>}" target="_blank" style="text-decoration: none; color: #FF8300"><?= $job->title; ?></a></td>
                                                    <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">
                                                        <?= implode(", ", array_map(function ($location) {
                                                                return $location->name;
                                                            }, $job->locations)
                                                        ); ?>
                                                    </td>
                                                    <td style="font-family: Arial, Helvetica, sans-serif, 'Open Sans';">
                                                        <?= implode(", ", array_map(function ($sector) {
                                                                return $sector->name;
                                                            }, $job->sectors)
                                                        ); ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </table>
                                    <p><a href="{URL:unsubscribe/<?= $this->token ?>}">Unsubscribe</a></p>
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
