<?php

include_once _SYSDIR_ . 'modules/shop/system/inc/Cart.php';

class ShopController extends Controller
{
    use Validator;

    private static $numElements = 6;

    public function indexAction()
    {
        Model::import('panel/shops/types');

        $this->view->highlights = ShopModel::getHighlights();
        $this->view->popular = ShopModel::getPopular(4);
        $this->view->types = TypesModel::getAll();
    }

    public function searchAction()
    {
        $type = get('type');
        $this->view->keywords = $keywords = get('keywords') ? [get('keywords')] : false;

        $products = ShopModel::search($keywords, false, $type);

        Pagination::calculate(post('page'), self::$numElements, count($products));
        $this->view->products = array_slice($products, Pagination::$start, Pagination::$end);

        Model::import('panel/shops/brands');
        Model::import('panel/shops/types');

        $brands = BrandsModel::getAll();
        $types = TypesModel::getAll();

        $productsForCounters = ShopModel::search($keywords); //todo mb use only php without SQL
        //add product counters to filters
        $this->view->brands = $this->getCounter($brands, 'brands', $productsForCounters);
        $this->view->types = $this->getCounter($types, 'types', $productsForCounters);
    }

    public function search_processAction()
    {
        Request::ajaxPart();

        Model::import('panel/shops/brands');
        Model::import('panel/shops/types');

        $type = post('type');
        $brand =  post('brand');
        $keywords = post('keywords');
        $this->view->price = $price = post('price');
        $sort = post('sort') ?: 'recent';

        if ($keywords && is_string($keywords))
            $keywords = stringToArray($keywords);

        //remove tag (keyword) from all keywords array
        if (post('tag_type') == 'unset' && $keywords) {
            foreach ($keywords as $k => $keyword) {
                if ($keyword === post('value'))
                    unset($keywords[$k]);
            }
        }

        //remove same keywords and empty elements when research
        if (is_array($keywords))
            $keywords = array_filter(array_unique($keywords));

        if ($type && is_string($type))
            $type = stringToArray($type);

        if ($brand && is_string($brand))
            $brand = stringToArray($brand);

        $this->view->keywords = $keywords;
        $this->view->brand = $brand;
        $this->view->type = $type;

        $this->view->products = ShopModel::search($keywords, $brand, $type, $price, $sort);
        $productsForCounters = ShopModel::search($keywords, false, false, $price, $sort); //todo mb use only php without SQL
        $brands = BrandsModel::getAll();
        $types = TypesModel::getAll();

        //add product counters to filters
        $this->view->brands = $this->getCounter($brands, 'brands', $productsForCounters);
        $this->view->types = $this->getCounter($types, 'types', $productsForCounters);

        Pagination::calculate(post('page'), self::$numElements, count($this->view->products));
        $this->view->products = array_slice($this->view->products, Pagination::$start, Pagination::$end);


        if (post('tag_type'))
            unset($_POST['tag_type']);


        $post = allPost();
        if ($type && is_array($type))
            $type = arrayToString($type); $post['type'] = $type;

        if ($brand && is_array($brand))
            $brand = arrayToString($brand); $post['brand'] = $brand;

            //todo beed recheck
        if (is_array($keywords)) {
            if (count($keywords) > 0) {
                $keywords = arrayToString($keywords);
                $post['keywords'] = $keywords;
            } else {
                unset($post['keywords']);
            }
        }

        Request::addResponse('html', '#pagination-block', Pagination::ajax('shop/search_process', $post));
        Request::addResponse('html', '#sidebar', $this->getView('modules/shop/views/parts/_filters.php'));
        Request::addResponse('html', '#search_results_box', $this->getView('modules/shop/views/parts/_search_process.php'));
    }

    public function viewAction()
    {
        $slug = Request::getUri(0);
        Model::import('panel/shops/brands');
        Model::import('panel/shops/types');

        $this->view->product = ShopModel::getBySlug($slug);

        if (!$this->view->product)
            redirect(url('shop/search'));

        $media = Model::fetchAll(Model::select('shop_products_media',
            "`product_id` = {$this->view->product->id}"));

        $this->view->images = [];
        $this->view->video = [];
        foreach ($media as $item) {
            if ($item->type == 'image') {
                $this->view->images[] = $item;
            } else {
                $this->view->videos[] = $item;
            }
        }

        $similar = ShopModel::search(false, false, $this->view->product->type_ids, false, false,
        " `id` != {$this->view->product->id}");

        $this->view->similar = array_slice($similar, 0, 3);

        //get reviews
        $this->view->reviews = ShopModel::getReviews($this->view->product->id);

        if ($this->view->reviews)
           $this->view->avg_rating = array_sum($tmpArray = array_column($this->view->reviews, 'rating')) / count($tmpArray);

        // Set stat
        $data_views['views'] = '++';
        Model::update('shop_products', $data_views, "`id` = '" . $this->view->product->id . "'");

        Request::setTitle('Product - ' . $this->view->product->meta_title);
        Request::setKeywords($this->view->product->meta_keywords);
        Request::setDescription($this->view->product->meta_desc);
    }

    public function add_shortlistAction()
    {
        Request::ajaxPart();

        if (!User::get('id', 'candidate'))
            Request::returnError('You must be logged in to save the product!');

        $product_id = post('product');
        $favorite = ShopModel::getFavorite(User::get('id', 'candidate'), $product_id);
        if (!$favorite) {
            $data = [
                'user_id' => User::get('id', 'candidate'),
                'product_id'  => $product_id,
                'time'    => time(),
            ];

            Model::insert('shop_shortlist', $data);

            Request::addResponse('addClass', '#save-button-' . $product_id, 'active');
            Request::addResponse('func', 'noticeSuccess', 'Product saved!');
        } else {
            Model::delete('shop_shortlist', "`user_id` = '" . User::get('id', 'candidate') . "' AND `product_id` = $product_id");

            Request::addResponse('removeClass', '#save-button-' . $product_id, 'active');
            Request::addResponse('func', 'noticeError', 'Product removed from your saves!');
        }

    }

    public function add_reviewAction()
    {
        if ($this->startValidation()) {
            $this->validatePost('review',            'Review content',    'required|trim|min_length[1]|max_length[2000]');
            $this->validatePost('rating',            'Rating',            'required|trim|min_length[1]');

            $product_id = post('product_id');
            $content = post('review');
            $rating = post('rating', 'int');

            $product = ShopModel::get($product_id);

            if (!$product || !User::get('id', 'candidate'))
                redirect(url('shop/search'));

            $checkReview = Model::fetch(Model::select('shop_reviews', " `product_id` = $product_id 
            AND `user_id` = " . User::get('id', 'candidate') . " LIMIT 1"));

            if ($checkReview)
                Request::returnError('You have already sent a review for this product');

            if ($this->isValid()) {

                $data = [
                    'user_id' => User::get('id', 'candidate'),
                    'product_id' => $product_id,
                    'content' => $content,
                    'rating' => $rating,
                    'time' => time(),
                ];

                $result = Model::insert('shop_reviews', $data);
                $insertId = Model::insertID();

                if (!$result && $insertId) {
                    Request::addResponse('func', 'noticeSuccess', 'Your review has been successfully submitted!');
                    Request::endAjax();
                } else {
                    Request::returnError('Database error');
                }
            } else {
                if (Request::isAjax())
                    Request::returnErrors($this->validationErrors);
            }
        }

    }

    /**
     * @param $filter
     * @param $filterName
     * @param $data
     * @return mixed
     */
    private function getCounter(array $filter, string $filterName, array $data) : array
    {
        foreach ($filter as $item) {
            $item->counter = 0;
            foreach ($data as $job) {
                if ($job->$filterName) {
                    foreach ($job->$filterName as $b) {
                        if ($b->id === $item->id)
                            $item->counter++;
                    }
                }
            }
        }

        return $filter;
    }

    //cart actions
    public function buy_productAction()
    {
        Request::ajaxPart();

        $productId = post('product_id');

        Model::import('panel/shops');
        $product = ShopsModel::get($productId);

        if (!$product)
            redirectAny(url('shop/search'));

        Cart::setProduct($productId);

        Request::addResponse('attr', '#btn-buy-' . $productId, 'return false;', 'onclick');
        Request::addResponse('html', '#btn-buy-' . $productId, '<span class="icon-shop-check-fill"></span>');
        Request::addResponse('html', '#product-counter', Cart::getCount());
        Request::addResponse('html', '#cart-products', $this->getView('modules/shop/views/parts/_cart_products.php'));
        Request::addResponse('func', 'cartCalculator');
        Request::addResponse('func', 'noticeSuccess', 'Product added to your cart');

        Request::endAjax();
    }

    public function remove_productAction()
    {
        Request::ajaxPart();

        $productId = post('product_id');

        Model::import('panel/shops');
        $product = ShopsModel::get($productId);

        if (!$product)
            redirectAny(url('shop/search'));

        Cart::removeProduct($productId);

        Request::addResponse('remove', '#cart-product-' . $productId);
        Request::addResponse('func', 'noticeError', 'Product removed');
        Request::addResponse('func', 'cartCalculator');

        Request::endAjax();
    }

}
/* End of file */
