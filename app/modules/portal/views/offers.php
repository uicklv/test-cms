<section class="section-inner" style="margin: 100px 0 100px 0">
    <div class="fixed">
        <div class="gt-flex">
            <h1 class="gen-title" data-aos="zoom-in" data-aos-duration="1500">
                <p>Offers and Placed Candidates</p>
            </h1>
            <a class="portal-link" href="{URL:portal}"  data-aos="fade-left" data-aos-duration="1500">Back to Portal</a>
        </div>


        <?php  if (is_array($this->candidates) && count($this->candidates) > 0) { ?>
        <ul class="offers-list">
            <?php foreach ($this->candidates as $item){ ?>
            <li>
                <?php /*<h3 class="specialty-title"><?= $item->vacancy_title ?></h3>*/ ?>
                <div class="specialty-name-flex" onclick="toggleClass('#<?= $item->id ?>', 'collapse_part_open')">
                    <h3 class="specialty-name"><?= $item->firstname . ' ' . $item->lastname ?></h3>
                    <div class="specialty-details">
                        <?php if ($item->time) { ?>
                            <div>
                                <h4 style="color: #ffcc24;">Start Date</h4>
                                <?= date('d-m-Y', $item->start_date) ?>
                            </div>
                        <?php } ?>
                        <span class="sn-arrow"></span>
                    </div>
                </div>

                <div id="<?= $item->id ?>" class="collapse_part ">
                    <div class="ol-info">
                        <?php if ($item->salary) { ?>
                            <div>
                                <h4>Salary Offered</h4>
                                <?= $item->hired_salary ?>
                            </div>
                        <?php } ?>
                        <?php if ($item->tel) { ?>
                            <div>
                                <h4>Telephone Number</h4>
                                <?= $item->tel ?>
                            </div>
                        <?php } ?>
                        <?php if ($item->email) { ?>
                            <div>
                                <h4>Email Address</h4>
                                <?= $item->email ?>
                            </div>
                        <?php } ?>
                        <?php if ($item->dob) { ?>
                            <div>
                                <h4>DOB</h4>
                                <?= date('d-m-Y', $item->dob) ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if ($item->address) { ?>
                        <div class="ol-text">
                            <h4>Home Address</h4>
                            <?= $item->address ?>
                        </div>
                    <?php } ?>
                    <div class="ol-download-flex">
                    <?php if ($item->cv) { ?>
                        <div>
                            <h4>CV Download</h4>
                            <a class="sb-download" href="<?= _SITEDIR_ ?>data/candidates/<?= $item->cv ?>"
                               download="cv-<?= $item->firstname . '-' . $item->lastname . '.' . File::format($this->user->cv) ?>"><i class="fas fa-download"></i></a>
                        </div>
                    <?php } ?>
                    <?php if ($item->id_file) { ?>
                        <div>
                            <h4>ID Download</h4>
                            <a class="sb-download" href="<?= _SITEDIR_ ?>data/candidates/<?= $item->id_file ?>"
                               download="id-<?= $item->firstname . '-' . $item->lastname . '.' . File::format($this->user->id_file) ?>"><i class="fas fa-download"></i></a>
                        </div>
                    <?php } ?>
                    <?php if ($item->passport) { ?>
                        <div>
                            <h4>Passport Download</h4>
                            <a class="sb-download" href="<?= _SITEDIR_ ?>data/candidates/<?= $item->passport ?>"
                               download="passport-<?= $item->firstname . '-' . $item->lastname . '.' . File::format($this->user->passport) ?>"><i class="fas fa-download"></i></a>
                        </div>
                    <?php } ?>
                </div>
                </div>
            </li>
            <?php } ?>
        </ul>
        <?php } else { ?>
            <p>The list of candidates is empty</p>
        <?php } ?>
    </div>
</section>

<section class="separator"></section>
