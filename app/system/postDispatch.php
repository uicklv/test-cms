<?php
setSession('sys_note_tik', 0);

// Generating sitemap links
sitemapProcess();

if (Request::getParam('test_mode') == 'yes') {
    echo 'Time: ' . round(microtime(1) - _START_TIME_, 5) . 's<br/>';
    echo 'Memory: ' . format_bytes(memory_get_usage() - _START_MEMORY_) . '<br/>';
}

/* End of file */