<section class="head-block mar head-block-small"><!-- style="background-image: url('<?= _SITEDIR_?>public/images/head-inner_bg5.jpg')"-->
    <div class="fixed">
        <div class="head-cont">
            <div>
                <div class="gen-title"><span>Create Your Free Account</span></div>
            </div>
        </div>
    </div>
</section>

<section class="section-login" data-aos="fade-up">
    <div class="fixed">
        <div class="account-reg mar">
            <div class="ar-right">
                <div class="title-small">Log In</div>
                <form class="first-search" id="form_login">
                    <div class="first__inputs">
                        <div class="fs-flex">
                            <div class="fs-cell"><input type="text" name="email" placeholder="Email Address" class="first__input"></div>
                            <div class="fs-cell"><input type="password" name="password" placeholder="Password" class="first__input"></div>
                        </div>
                        <div class="fs-flex fs-flex-sub">
                            <div><a href="{LINK:restore}">forgot password</a></div>
                            <button type="submit" class="more-link" onclick="load('profile/login', 'form:#form_login'); return false;">Log In</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

<section class="section-register" data-aos="fade-up">
    <div class="fixed" >
        <div class="account-reg mar">
            <div class="ar-right">
                <div class="title-small">Register</div>
                <p>Take advantage of the benefits by creating your account.</p>
                <form id="form_register" class="sr-search">
                    <div class="sr__inputs">
                        <div class="fs-flex">
                            <div class="fs-cell"><input type="text" placeholder="First Name" name="firstname" class="sr__input"></div>
                            <div class="fs-cell"><input type="text" placeholder="Last Name" name="lastname" class="sr__input"></div>
                            <div class="fs-cell"><input type="text" placeholder="Email Address" name="email" class="sr__input"></div>
                            <div class="fs-cell"><input type="text" placeholder="Phone Number" name="tel" class="sr__input"></div>
                            <div class="fs-cell"><input type="password" placeholder="Create Password" name="password" class="sr__input"></div>
                            <div class="fs-cell"><input type="password" placeholder="Confirm Password" name="password2" class="sr__input"></div>
                        </div>
                        <div class="fs-flex fs-flex-sub">
                            <div class="value">
                                <label class="checked__label">
                                    <input type="checkbox" class="value__check" name="check">
                                    <div class="value__name">I have read and agree to the Privacy Policy
                                        including GDPR guidelines</div>
                                </label>
                            </div>
                            <button type="submit" class="more-link" onclick="load('register', 'form:#form_register'); return false;">Create Account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>





                <?php /*
                <input type="text" placeholder="Current Job Title" name="job_title" class="sr__input">

                <select name="currency"  class="sr__input">
                    <option value="">Currency</option>
                    <option value="£">pound £</option>
                    <option value="€">euro €</option>
                    <option value="$">dollar $</option>
                </select>

                <select name="pay_type"  class="sr__input">
                    <option value="">Pay Type</option>
                    <option value="Annually">Annually</option>
                    <option value="Monthly">Monthly</option>
                </select>
                <select name="pay_rate"  class="sr__input">
                    <option value="">Pay rate</option>
                    <option value="500">500</option>
                    <option value="600">600</option>
                    <option value="700">700</option>
                    <option value="800">800</option>
                    <option value="900">900</option>
                    <option value="1000">1000</option>
                </select>
                <select name="salary_range"  class="sr__input">
                    <option value="">Salary Range</option>
                    <option value="0">0 – 10,000</option>
                    <option value="10000">10,001 – 20,000</option>
                    <option value="20000">20,001 – 30,000</option>
                    <option value="30000">30,001 – 40,000</option>
                    <option value="40000">40,001 – 50,000</option>
                    <option value="50000">50,001 – 60,000</option>
                    <option value="60000">60,001 – 80,000</option>
                    <option value="80000">80,001 – 100,000</option>
                    <option value="100000">100,001 – 120,000</option>
                    <option value="120000">120,001 – 140,000</option>
                    <option value="140000">140,001 – 160,000</option>
                    <option value="160000">160,001 – 180,000</option>
                    <option value="180000">180,001 – 200,000</option>
                    <option value="200000">200,001 +</option>
                </select>
                <input type="text" placeholder="Current Salary" name="current_salary" class="sr__input">
                <input type="text" placeholder="Current Location" name="location" class="sr__input">
  */ ?>




    </div>
</section>