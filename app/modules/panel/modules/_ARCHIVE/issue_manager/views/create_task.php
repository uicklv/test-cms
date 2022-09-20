<!--<link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet">-->
<link href="<?= SITE_URL ?>app/public/css/popup.css" type="text/css" rel="stylesheet" />
<script>
    function closeIssuePopup(el) {
        if (el)
            $(el).html('');
        else
            $('#popup').html('');
    }
</script>

<div class="popup__fon pointer" onclick="closeIssuePopup('#api_content');"></div>
<div class="popup_z3">
    <div class="popup-api-create">
        <span class="close-popup" onclick="closeIssuePopup('#api_content');"></span>
        <div class="title-api-popup">Create Task <span class="fs16">to <i style="color: #15A450;"><?= $this->project->name ?></i></span></div>

        <div class="popup-create-scroll">

            <form id="comment_form">
                <div class="flex-btw flex-center fs12 mb12">
                    <span class="label">Page link:</span>
                    <select name="where">
                        <option value="<?= $this->url ?>"><?= $this->url ?></option>
                        <option value="All pages">All pages</option>
                    </select>
                </div>

                <textarea id="action" name="action" placeholder="Task text..."></textarea>

                <div class="flex-btw mlr12" style="margin-top: 8px;">
                    <div class="flex w75p">
                        <?php /*
                        <div id="logo_block" class="select-file circle">
                            <input type="hidden" id="comment_file" name="comment_file" value="">
                            <input type="hidden" id="real_name" name="real_name" value="">
                            <input type="file" id="files" class="pointer" accept=""
                                   onchange="load('upload/file', 'form:#comment_form', 'field=#comment_file', 'preview=.up-file-val', 'name=<?= User::get('id'); ?>');"/>
                            <a class="file-fake icon-clip"></a>
                        </div>
                        <div class="up-file-val ml6"><div class="tip fs12">or <b>Ctrl + V</b> to paste</div></div>
                        */ ?>
                    </div>
                    <div>
                        <input class="btn_type" type="submit" onclick="load('https://donemen.com/api/create_task', 'project=<?= $this->project_hash ?>', 'submit=yes', 'form:#comment_form'); return false;" value="Send">
                    </div>
                </div>
            </form>

        </div>

        <div class="clear"></div>
    </div>
</div>