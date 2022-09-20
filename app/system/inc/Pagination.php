<?php
/**
 * PAGINATION
 */

class Pagination
{
    static public $page = 1;
    static public $countPage = 0;
    static public $start = 0;
    static public $end = 20;
    static public $allRecords = 0;


    /**
     * @param int $page
     * @param int $end
     * @param bool $allRecords
     */
    static public function calculate($page, $end = 20, $allRecords = false)
    {
        self::$page = intval($page);
        self::$countPage = ceil($allRecords / $end);
        self::$end = $end;
        self::$allRecords = $allRecords;

        if (self::$page <= 1 OR self::$page > self::$countPage) {
            self::$page = 1;
            self::$start = 0;
        } else
            self::$start = (self::$page-1) * self::$end;
    }

    /**
     * page - will be GET param
     * ex: http://cms-admin.loc/blogs/club?order=newest&page=3 => Pagination::printPagination('blogs/club', ['order' => 'newest']) // page=3 - will be added automatically
     * @param string $partUrl
     * @param array $get
     * @param int $count - display count of pages between do
     * @return string
     */
    static public function printPagination($partUrl = '', $get = [], $count = 2)
    {
        // Short pagination ex: < >
        if ($count === false) {
            if (self::$page > 1)
                $prev = self::$page - 1;
            else
                $prev = 1;

            $html = '<div class="nav-links">';
            $html .= '<a class="prev page-numbers" href="' . buildUrl($partUrl, $get, ['page' => $prev]) . '"><span class="icon-chevron-left"></span></a>';
            $html .= '<a class="next page-numbers" href="' . buildUrl($partUrl, $get, ['page' => self::$page + 1]) . '"><span class="icon-chevron-right"></span></a>';
            $html .= '</div>';

            return $html;
        }

        // Full pagination First ex: < 1 .. 34(5)67 .. 11 > Last
        $html = '<div class="nav-links">';

        if (self::$page > 1)
            $prev = self::$page - 1;
        else
            $prev = 1;

//        if (self::$page > 1)
        $html .= '<a class="prev text page-numbers" href="' . buildUrl($partUrl, $get, ['page' => 1]) . '" title="">First</a>';
        $html .= '<a class="prev page-numbers" href="' . buildUrl($partUrl, $get, ['page' => $prev]) . '" title=""><span class="icon-chevron-left"></span></a>';

        if (self::$page > $count)
            $from = self::$page - $count;
        else
            $from = 1;

        if (self::$countPage - self::$page >= $count)
            $to = self::$page + $count;
        else
            $to = self::$countPage;

        if (self::$page > $count + 1)
            $html .= '<a class="page-numbers" href="' . buildUrl($partUrl, $get, ['page' => 1]) . '">1</a>';
        if (self::$page > $count + 2)
            $html .= '<b style="margin: 0 8px;">...</b>';


        for ($from; $from <= $to; $from++) {
            if (self::$page == $from)
                $html .= '<span aria-current="page" class="page-numbers current">' . $from . '.</span>';
            else
                $html .= '<a class="page-numbers" href="' . buildUrl($partUrl, $get, ['page' => $from]) . '">' . $from . '.</a>';
        }

        if (self::$page + $count < self::$countPage - 1)
            $html .= '<b style="margin: 0 8px;">...</b>';
        if (self::$page + $count < self::$countPage)
            $html .= '<a class="page-numbers" href="' . buildUrl($partUrl, $get, ['page' => self::$countPage]) . '">' . self::$countPage . '</a>';


        if (self::$page < self::$countPage)
            $html .= '<a class="next page-numbers" href="' . buildUrl($partUrl, $get, ['page' => self::$page + 1]) . '" title=""><span class="icon-chevron-right"></span></a>';

        $html .= '<a class="next text page-numbers" href="' . buildUrl($partUrl, $get, ['page' => self::$countPage]) . '" title="">Last</a>';

        $html .= '</div>';

        return $html;
    }

    /**
     * page - will be POST param
     * ex: http://cms-admin.loc/blogs/club?order=newest&page=3 => Pagination::ajax('blogs/club', ['order' => 'newest']) // page=3 - will be added automatically
     * @param string $partUrl
     * @param array $post
     * @param int $count - display count of pages between do
     * @return string
     */
    static public function ajax($partUrl = '', $post = [], $count = 2)
    {

        // Full pagination First ex: < 1 .. 34(5)67 .. 11 > Last
        $html = '';

        if (self::$page > 1)
            $prev = self::$page - 1;
        else
            $prev = 1;

        if (self::$page > 1)
            $html .= '<a class="icon-shop-arrow-right btn-shop btn-shop--line prev" onclick="' . buildAjax($partUrl, $post, ['page' => $prev]) . '" title=""></a>'; // onclick="'.ajaxLoad(''.$partUrl.'page='.(self::$page-1)).'"

        if (self::$page > $count)
            $from = self::$page - $count;
        else
            $from = 1;

        if (self::$countPage - self::$page >= $count)
            $to = self::$page + $count;
        else
            $to = self::$countPage;

        $html .= '<div class="num-pages">';

        if (self::$page > $count + 1)
            $html .= '<a class="btn-shop btn-shop--line" onclick="' . buildAjax($partUrl, $post, ['page' => 1]) . '">1</a>'; // onclick="'.ajaxLoad(''.$partUrl.'page=1').'"
        if (self::$page > $count + 2)
            $html .= '<b style="margin: 0 8px;">...</b>';


        for ($from; $from <= $to; $from++) {
            if (self::$page == $from)
                $html .= '<a href="#" class="btn-shop btn-shop--line active">' . $from . '</a>';
            else
                $html .= '<a class="btn-shop btn-shop--line" onclick="' . buildAjax($partUrl, $post, ['page' => $from]) . '">' . $from . '</a>'; // onclick="'.ajaxLoad(''.$partUrl.'page='.$from).'"
        }

        if (self::$page + $count < self::$countPage - 1)
            $html .= '<b style="margin: 0 8px;">...</b>';
        if (self::$page + $count < self::$countPage)
            $html .= '<a class="btn-shop btn-shop--line" onclick="' . buildAjax($partUrl, $post, ['page' => self::$countPage]) . '">' . self::$countPage . '</a>'; // onclick="'.ajaxLoad(''.$partUrl.'page='.self::$countPage).'"



        $html .= '</div>';

        if (self::$page < self::$countPage)
            $html .= '<a class="icon-shop-arrow-right btn-shop btn-shop--line next" onclick="' . buildAjax($partUrl, $post, ['page' => self::$page + 1]) . '" title=""></a>'; // onclick="'.ajaxLoad(''.$partUrl.'page='.(self::$page+1)).'"


        return $html;
    }
}
/* End of file */