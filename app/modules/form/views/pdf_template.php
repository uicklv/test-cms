<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    <style>
        html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, menu, nav, output, ruby, section, summary, time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
            box-sizing: border-box
        }

        article, aside, details, figcaption, figure, footer, header, menu, nav, section {
            display: block
        }

        :focus {
            outline: 0
        }

        ol, ul {
            list-style-position: inside
        }

        a {
            text-decoration: underline
        }

        a:hover {
            text-decoration: none
        }

        html, body {
            height: 100%
        }

        label, button, input[type=submit], input[type=button] {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none
        }

        body {
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0)
        }

        article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section {
            display: block
        }

        img {
            max-width: 100%;
            height: auto
        }

        body {
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0)
        }

        button, input[type=submit] {
            -webkit-appearance: none;
            -moz-appearance: none
        }

        body {
            font-family: "neue-haas-grotesk-display", sans-serif !important;
            font-size: 18px;
            line-height: 1.3;
            color: #000;
            background: #f8f6f6;
            min-width: 320px;
            position: relative;
            padding: 26px
        }

        .call-form-section {
            margin-top: 16px;
            font: 400 22px/150% "neue-haas-grotesk-display", sans-serif !important;
            color: #000
        }

        .call-form-section p {
            margin: 5px 0
        }

        .call-form-section ul {
            margin: 5px 0
        }

        .title-lab {
            font: bold 20px/126% "neue-haas-grotesk-display", sans-serif !important;
            color: #459e92
        }

        .cfs-title {
            margin-bottom: 10px;
            font: bold 24px/126% "neue-haas-grotesk-display", sans-serif !important;
            color: #5b3552
        }

        .cfs-row {
            display: block;
            margin-left: -40px;
            font: 400 16px/126% "neue-haas-grotesk-display", sans-serif !important;
            color: #000
        }

        .cfs-row .cfs-cell {
            font: 400 18px/126% "neue-haas-grotesk-display", sans-serif !important;
            color: #000;
            margin-bottom: 8px;
            margin-left: 40px;
            width: 100%
        }

        .cfs-row .cfs-cell.cfs-width-1 {
            min-width: 400px;
            width: auto
        }

        .cfs-row .cfs-cell.cfs-width-2 {
            width: 640px
        }

        .cfs-row .cfs-cell.cfs-width-3 {
            width: 200px
        }

        .cfs-label {
            display: block;
            margin-bottom: 15px;
            font: 500 22px/126% "neue-haas-grotesk-display", sans-serif !important;
            color: #fdc700
        }

        .cfs-text-field {
            display: block;
            box-sizing: border-box;
            width: 100%;
            padding: 0;
            border: none;
            border-bottom: none;
            background: none;
            font: 400 18px/126% "neue-haas-grotesk-display", sans-serif !important;
            color: #000
        }

        .cfs-textarea {
            display: block;
            box-sizing: border-box;
            width: 100%;
            padding: 0;
            min-height: 90px;
            max-height: 200px;
            border: none;
            border-bottom: none;
            background: none;
            font: 400 18px/126% "neue-haas-grotesk-display", sans-serif !important;
            color: #000
        }

        .cfs-answer {
            width: 100%;
            max-width: 800px
        }

        .cfs-answer .txt {
            display: block;
            box-sizing: border-box;
            width: 100%;
            padding: 0;
            min-height: 90px;
            border: none;
            border-bottom: none;
            background: none;
            font: 400 18px/126% "neue-haas-grotesk-display", sans-serif !important;
            color: #000
        }

        .cfs-process {
            display: block
        }

        .cfs-check {
            display: inline-block;
            margin: 0;
            position: relative
        }

        .cfs-check input[type=checkbox], .cfs-check input[type=radio] {
            display: inline-block;
            margin-top: 5px;
            line-height: 26px
        }

        .cfs-check .cfs-check-title {
            position: relative;
            padding-left: 25px;
            margin: 0;
            cursor: pointer;
            font: 500 18px/20px "neue-haas-grotesk-display", sans-serif;
            color: #000
        }

        /*# sourceMappingURL=pdf.css.map */

    </style>
</head>
<body>
<div class="top-line">
    <img src="<?= SITE_URL ?>app/public/images/logo.png" height="260" width="187" alt=""/>
</div>
<section class="section-inner">
    <div class="fixed">
        <h1 class="gen-title" data-aos="zoom-in" data-aos-duration="1500">
            <p><?= $this->view->form->title ?></p>
            <div class="small"><?= date('d.m.Y', $this->view->form->time) ?></div>
        </h1>
        <form id="form_sections">
            <br><br>
            <?php
            if ($this->view->sections) {
                foreach ($this->view->sections as $section) { ?>
                    <div class="call-form-section">
                        <h3 class="cfs-title"><?= $section->title ?></h3>
                        <div class="cfs-row">
                            <?php
                            if ($section->fields) {
                                foreach ($section->fields as $field) { ?>
                                    <?php if ($field->type === 'input') { ?>
                                        <div class="fb-elem">
                                            <div class="fb-input-area red">
                                                <h3><?= $field->title ?></h3>
                                                <input type="text" placeholder="Name" class="fb-input"
                                                       name="field_<?= $field->id ?>_<?= $section->id ?>"
                                                       value="<?= $field->answer ?>">
                                            </div>
                                        </div>
                                    <?php } else if ($field->type === 'radio') { ?>
                                        <!-- Type radio -->
                                        <div class="fb-elem">
                                            <div class="fb-input-area red">
                                                <h3><?= $field->title ?></h3>
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
                                            </div>
                                        </div>
                                    <?php } else if ($field->type === 'checkbox') { ?>
                                        <!-- Type checkbox -->
                                        <div class="fb-elem">
                                            <div class="fb-input-area red">
                                                <h3><?= $field->title ?></h3>
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
                                            </div>
                                        </div>
                                        <!-- End type checkbox -->
                                    <?php } else if ($field->type === 'textarea') { ?>
                                        <div class="fb-elem full">
                                            <div class="fb-input-area red">
                                                <h3><?= $field->title ?></h3>

                                                <textarea placeholder="Name"
                                                          name="field_<?= $field->id ?>_<?= $section->id ?>"
                                                          class="fb-input textarea">
                                                        <?= $field->answer ?>
                                                    </textarea>

                                            </div>
                                        </div>
                                        <!-- End type textarea -->
                                    <?php } else if ($field->type === 'info') { ?>
                                        <!-- Type info -->
                                        <div class="fb-elem full">
                                            <?= reFilter($field->answer_options); ?>
                                        </div>
                                        <!-- End type info -->
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </form>
    </div>
</section>
</body>
</html>
