<?php Popup::head('ADD SECTION '); ?>

<form id="add_section" class="" action="{URL:panel/microsites/add_section}" method="post" enctype="multipart/form-data">
    <div class="form-section">
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="name">Section name</label>
                <input class="form-control" type="text" name="name" id="name" value="<?= post('name', false); ?>" required>
            </div>
        </div>

        <style>
            .scrollbarHide > .scroll-element.scroll-x {
                display: none;
            }

            .scrollbarHide > .scroll-element .scroll-element_track {
                display: none;
            }
        </style>

        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="type">Type</label>

                <div class="radio-pretty">
                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="home">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/home.png" width="40px">
                            <span>Home</span>
                        </span>
                    </label>

                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="text">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/text.png" width="40px">
                            <span>Text</span>
                        </span>
                    </label>

                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="picture_text">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/picture_text.png" width="40px">
                            <span>Image & Text</span>
                        </span>
                    </label>

                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="video">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/video.png" width="40px">
                            <span>Video</span>
                        </span>
                    </label>

                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="video_text">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/video_text.png" width="40px">
                            <span>Video & Text</span>
                        </span>
                    </label>

                    <?php /*
                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="2_blocks">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/2_blocks.png" width="40px">
                            <span>2 Blocks</span>
                        </span>
                    </label>

                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="3_blocks">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/3_blocks.png" width="40px">
                            <span>3 Blocks</span>
                        </span>
                    </label>
                    */ ?>

                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="4_blocks">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/4_blocks.png" width="40px">
                            <span>4 Blocks</span>
                        </span>
                    </label>

                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="how_it_work">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/how_it_work.png" width="40px">
                            <span>How it Work</span>
                        </span>
                    </label>

                    <?php /*
                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="testimonials">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/testimonials.png" width="40px">
                            <span>Testimonials</span>
                        </span>
                    </label>
                    */ ?>

                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="contact_us">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/contact_us.png" width="40px">
                            <span>Contact Us</span>
                        </span>
                    </label>

                    <label class="new-control new-radio radio-classic-warning">
                        <input type="radio" class="new-control-input" name="type" value="map">
                        <span class="radio-pretty-title">
                            <img src="<?= _SITEDIR_ ?>public/images/icon/section_icon/map.png" width="40px">
                            <span>Map</span>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <div class="clr"></div>

        <div style="margin-top: 20px">
            <button type="submit" name="submit" class="btn btn-success"
                    onclick="load('panel/landings/add_section/<?= $this->mid ?>', 'form:#add_section'); return false;">
              Add</button>
            <a onclick="closePopup()" class="btn btn-outline-warning"><i class="fas fa-ban"></i>Cancel</a>
            <div class="clr"></div>
        </div>
    </div>
</form>

<?php Popup::foot(); ?>
<?php Popup::closeListener(); ?>


