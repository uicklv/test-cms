<!--<script src="--><?//= _SITEDIR_; ?><!--public/js/backend/Sortable.min.js"></script>-->
<!---->
<!--<div id="control-container">-->
<!--    <div id="button-holder">-->
<!--        <a href="{URL:panel/settings/sitemap/add}" onclick="load('panel/settings/sitemap/add');" class="btn add"><i class="fas fa-ban"></i>Cancel</a>-->
<!--        <div class="clr"></div>-->
<!--    </div>-->
<!--    <h1>-->
<!--        <i class="fas fa-users"></i> Site Map <i class="fas fa-caret-right"></i>Save-->
<!--    </h1>-->
<!--    <hr/>-->
<!---->
<!--    --><?php //if (isset($success) && $success) { ?>
<!--        <div class="success">-->
<!--            <i class="fas fa-check-circle"></i>--><?//= $success; ?>
<!--        </div>-->
<!--    --><?php //} ?>
<!--    --><?php //if (isset($error) && $error) { ?>
<!--        <div class="error">-->
<!--            <i class="fas fa-check-circle"></i>--><?//= $error; ?>
<!--        </div>-->
<!--    --><?php //} ?>
<!--    --><?php ////echo validation_errors('<div class="error"><i class="fas fa-check-circle"></i>', '</div>'); ?>
<!---->
<!---->
<!--    <form id="form_box" action="{URL:panel/settings/sitemap/save}" method="post" enctype="multipart/form-data">-->
<!--        <div class="form-section">-->
<!--            <span class="heading">General</span>-->
<!---->
<!--            <div class="col half_column_left">-->
<!--                <label for="title">Table</label>-->
<!--                <input type="text" name="table" id="table" value="--><?//= post('table', false, $this->table) ?><!--" readonly="readonly">-->
<!--            </div>-->
<!---->
<!--            <div class="col half_column_right">-->
<!--                <label for="ref">Where</label>-->
<!--                <input type="text" name="where" id="where" value="--><?//= post('condition', false); ?><!--">-->
<!--            </div>-->
<!--            <div class="clr"></div>-->
<!---->
<!--            <div class="col full_column">-->
<!--                <label>Url</label>-->
<!--                <input type="text" name="url" id="url" value="--><?//= post('url', false); ?><!--">-->
<!--                <input type="hidden" name="base_url" id="url" value="--><?//= $this->base_url ?><!--">-->
<!--            </div>-->
<!--            <div class="col full_column">-->
<!--                <label>Links</label>-->
<!--                <textarea type="text" name="links" id="links"></textarea>-->
<!--            </div>-->
<!--            <div class="clr"></div>-->
<!---->
<!--        </div>-->
<!--        <div class="form-section">-->
<!--            <span class="heading">Result</span>-->
<!--            <div class="col full_column">-->
<!--                --><?php
//                    if($this->result)
//                        foreach ($this->result as $item){
//                            echo $item . '<br>';
//                        }
//                 ?>
<!--            </div>-->
<!--            <div class="clr"></div>-->
<!--        </div>-->
<!---->
<!--        <div class="form-section">-->
<!--            <button type="submit" name="submit" class="btn submit" onclick="load('panel/settings/sitemap/save', 'form:#form_box'); return false;"><i class="fas fa-save"></i>Save Changes</button>-->
<!--            <a href="{URL:panel/settings/sitemap/add}" onclick="load('panel/settings/sitemap/add');" class="btn cancel"><i class="fas fa-ban"></i>Cancel</a>-->
<!--            <div class="clr"></div>-->
<!--        </div>-->
<!--    </form>-->
<!--</div>-->
