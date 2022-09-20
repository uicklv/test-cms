<?php
/**
 * POPUP
 */

class Popup
{

    public static function head($title, $classHelper = false) {
        echo '<span class="close-popup" onclick="closePopup();" title="Close"><i class="fas fa-times"></i></span>
            <h3 class="title-popup">'.$title.'</h3>
            <div class="popup_body">';
    }

    public static function foot() {
        echo '</div>';
    }

    public static function closeListener() {
        echo '<script>
                closePopupListener();
                $(\'.popup_body\').addClass(\'scrollbarHide\').scrollbar();
            </script>';
    }
}

/* End of file */