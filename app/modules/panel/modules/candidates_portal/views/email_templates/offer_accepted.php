<table border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">
                <!-- START CENTERED WHITE CONTAINER -->
                <table class="main">
                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper" align="center">
                            <img src="<?= SITE_URL ?>app/public/images/logo.png" alt="" width="250" height="350">
                        </td>
                    </tr>

                    <tr>
                        <td class="wrapper">
                            <p>Hi <?= $this->customer->firstname ?>,</p>
                            <p>Congratulations, <?= $this->user->firstname ?> has accepted the verbal offer you
                                have made and you can now log into your client portal to see all of the contact information
                                and any identification, so that you can make this offer official to them.</p>
                            <p>Please click <a class="calc_btn"  href="<?= SITE_URL ?>login">Client Portal</a>
                                to access your offered and placed candidates page.</p>
                            <p>Can you please send an email copy of your offer to  <?= $this->user->firstname ?> and CC me into this?</p>
                            <p>This is so I can manage the official acceptance once they have reviewed the paperwork,
                                their resignation and any counter offer situation that may arise. (If employed)</p>
                            <p>Can you also advise me of when they can expect their offer letter, contract and any other documentation, please?</p>
                            <p>Please bear in mind that until paperwork is reviewed and agreed, there is always a slim chance of something changing,
                                the best way to mitigate this risk is to move the offer process forward as swiftly and professionally as possible.</p>
                            <p>Is there any other information that you need from <?= $this->user->firstname ?> or myself to move this forward quickly?</p>
                            <br>
                            <p>Regards,<br>
                                Simon Monaghan</p>
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

