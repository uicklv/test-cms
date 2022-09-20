<section class="section-inner" style="margin-top: 200px;">
    <div class="fixed">
        <div class="account-reg mar">
            <div class="ar-right">
                <h3 class="title-small">Client Login</h3>
                <form id="form_login" class="log-form">
                    <div class="fs-flex">
                        <div class="fs-cell"><input class="cf-text-field" type="text" name="email" placeholder="Email" value="<?= $this->email ?>"></div>
                        <div class="fs-cell"><input class="cf-text-field" type="password" name="password" placeholder="Password" value="<?= $this->pass ?>"></div>
                    </div>
                    <div class="fs-flex fs-flex-sub">
                        <div class="value">
                            <label class="checked__label">
                                <input type="checkbox" name="remember">
                                <div class="value__name">remember me</div>
                            </label>
                        </div>
                        <button class="more-link" type="submit" onclick="load('portal/login', 'form:#form_login'); return false;">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="separator"></div>