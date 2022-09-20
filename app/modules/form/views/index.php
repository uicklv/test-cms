<link href="<?= _SITEDIR_ ?>public/css/formbuilder.css" type="text/css" rel="stylesheet" />
<div class="main-area">
    <div class="white-block form-builder">
        <form action="" id="form_sections">
            <h1><?= $this->form->title ?></h1>

            <div class="fb-main">
                <div class="fb-row title">
                    <div class="fb-input-area">
                        <h3>Form Title</h3>
                        <input type="text" name="title" placeholder="Form Title" value="<?= $this->progress->title ?>" class="fb-input">
                    </div>

                    <div class="fb-input-area">
                        <h3>Date Created</h3>
                        <p><?= date('d.m.Y', $this->form->time) ?></p>
                    </div>
                </div>


                <?php if ($this->sections) { ?>
                    <?php foreach ($this->sections as $section) { ?>
                        <div class="fb-section">
                            <h3 class="fb-section__title"><?= $section->title ?></h3>
                            <?php if ($section->fields) { ?>
                                <?php foreach ($section->fields as $field) { ?>
                                    <?php if ($field->gray == 'no') { ?>
                                        <?php if ($field->type === 'input') { ?>
                                            <div class="fb-elem" id="elem-file-<?= $field->id ?>_<?= $section->id ?>">
                                                <div class="fb-input-area red">
                                                    <h3><?= $field->title ?></h3>
                                                    <?php
                                                    if ($images = $field->images)
                                                        include _SYSDIR_ .'modules/form/views/_field_images.php'; ?>
                                                    <input type="text" placeholder="Name" class="fb-input"
                                                           name="field_<?= $field->id ?>_<?= $section->id ?>"
                                                           value="<?= $field->answer ?>">

                                                    <!-- upload images -->
                                                    <?php include _SYSDIR_ .'modules/form/views/_upload_images.php'; ?>
                                                </div>
                                            </div>
                                        <?php } elseif ($field->type === 'radio') { ?>
                                            <!-- Type radio -->
                                            <div class="fb-elem" id="elem-file-<?= $field->id ?>_<?= $section->id ?>">
                                                <div class="fb-input-area red">
                                                    <h3><?= $field->title ?></h3>
                                                    <?php
                                                    if ($images = $field->images)
                                                        include _SYSDIR_ .'modules/form/views/_field_images.php'; ?>
                                                    <div class="fb-row">
                                                        <?php foreach (stringToArray($field->answer_options) as $k => $answer) { ?>
                                                            <label class="custom-check-radio">
                                                                <input type="radio"
                                                                       name="field_<?= $field->id ?>_<?= $section->id ?>"
                                                                       value="<?= $answer ?>"
                                                                    <?= checkCheckboxValue(post('field_' . $field->id . '_' . $section->id), $answer, stringToArray($field->answer)) ?>>
                                                                <span><?= $answer ?></span>
                                                            </label>
                                                        <?php } ?>
                                                    </div>

                                                    <!-- upload images -->
                                                    <?php include _SYSDIR_ .'modules/form/views/_upload_images.php'; ?>
                                                </div>
                                            </div>
                                        <?php } elseif ($field->type === 'checkbox') { ?>
                                            <!-- Type checkbox -->
                                            <div class="fb-elem">
                                                <div class="fb-input-area red" id="elem-file-<?= $field->id ?>_<?= $section->id ?>">
                                                    <h3><?= $field->title ?></h3>
                                                    <?php
                                                    if ($images = $field->images)
                                                        include _SYSDIR_ .'modules/form/views/_field_images.php'; ?>
                                                    <div class="fb-col mt20">
                                                        <?php foreach (stringToArray($field->answer_options) as $answer) { ?>
                                                            <label class="custom-check-radio">
                                                                <input type="checkbox"
                                                                       name="field_<?= $field->id ?>_<?= $section->id ?>[]"
                                                                       value="<?= $answer ?>"
                                                                    <?= checkCheckboxValue(post('field_' . $field->id . '_' . $section->id), $answer, stringToArray($field->answer)) ?>>
                                                                <span><?= $answer ?></span>
                                                            </label>
                                                        <?php } ?>
                                                    </div>

                                                    <!-- upload images -->
                                                    <?php include _SYSDIR_ .'modules/form/views/_upload_images.php'; ?>
                                                </div>
                                            </div>
                                            <!-- End type checkbox -->
                                        <?php } elseif ($field->type === 'textarea') { ?>
                                            <div class="fb-elem full" id="elem-file-<?= $field->id ?>_<?= $section->id ?>">
                                                <div class="fb-input-area red">
                                                    <h3><?= $field->title ?></h3>
                                                    <?php
                                                    if ($images = $field->images)
                                                        include _SYSDIR_ .'modules/form/views/_field_images.php'; ?>
                                                    <textarea placeholder="Name"
                                                              name="field_<?= $field->id ?>_<?= $section->id ?>"
                                                              class="fb-input textarea">
                                                        <?= $field->answer ?>
                                                    </textarea>

                                                    <!-- upload images -->
                                                    <?php include _SYSDIR_ .'modules/form/views/_upload_images.php'; ?>
                                                </div>
                                            </div>
                                            <!-- End type textarea -->
                                        <?php } elseif ($field->type === 'info') { ?>
                                            <!-- Type info -->
                                            <div class="fb-elem full">
                                                <?= reFilter($field->answer_options); ?>
                                            </div>
                                            <!-- End type info -->
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php /*
                <!-- Field with images -->
                <div class="fb-elem">
                    <div class="fb-input-area red">
                        <h3>Question 7 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sem nulla lacus fringilla</h3>

                        <div class="fb-row images mb20">
                            <img src="<?= _SITEDIR_ ?>public/images/testkit/images/4.png" alt="">
                            <img src="<?= _SITEDIR_ ?>public/images/testkit/images/4.png" alt="">
                        </div>

                        <div class="fb-row">
                            <label class="custom-check-radio">
                                <input type="radio" value="yes">
                                <span>Yes</span>
                            </label>

                            <label class="custom-check-radio">
                                <input type="radio" value="No">
                                <span>No</span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- End field with images -->

                <!-- Field options with images -->
                <div class="fb-elem">
                    <div class="fb-input-area red">
                        <h3>Question 7 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi sem nulla lacus fringilla</h3>

                        <div class="fb-col">
                            <div class="fb-col images-small">
                                <label class="custom-check-radio">
                                    <input type="checkbox" value="opt-1">
                                    <span>Option 1</span>
                                </label>

                                <img src="<?= _SITEDIR_ ?>public/images/testkit/images/4.png" alt="">
                            </div>

                            <div class="fb-col images-small">
                                <label class="custom-check-radio">
                                    <input type="checkbox" value="opt-1">
                                    <span>Option 1</span>
                                </label>

                                <img src="<?= _SITEDIR_ ?>public/images/testkit/images/4.png" alt="">
                            </div>

                            <div class="fb-col images-small">
                                <label class="custom-check-radio">
                                    <input type="checkbox" value="opt-1">
                                    <span>Option 1</span>
                                </label>

                                <img src="<?= _SITEDIR_ ?>public/images/testkit/images/4.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End field options with images -->
 */ ?>
            </div>

            <div class="btns-list">
                <?php if (get('mode') == 'answers') { ?>
                <a href="" onclick="load('form-pdf/<?= $this->progress->id ?>');" class="btn new primary"><i class="ico-download"></i> PDF</a>
                <?php } ?>
                <a href="" onclick="load('form-save', 'form:#form_sections', 'form_id=<?= $this->form->id ?>', 'progress_id=<?= $this->progress->id ?>');" class="btn new primary">Save</a>
            </div>
        </form>
    </div>
</div>

<script>
    jQuery(document).ready(function($){
        $('.main-table').addClass('scrollbarHide');
        $('.main-table').scrollbar({
            "ignoreOverlay": false,
            "ignoreMobile": false
        });
    });
</script>
