<section class="products-reviews">
    <div class="products-reviews__title">
        <h3>
            Reviews
        </h3>
        <button class="btn-shop btn-shop--line"><?= count($this->reviews) ?></button>
    </div>
    <?php if ($this->reviews) { ?>
        <?php foreach ($this->reviews as $review) { ?>
            <div class="products-reviews__container">
                <div class="products-reviews__top">
                    <div class="products-reviews__author">
                        <div class="photo-block">
                            <img src="<?= _SITEDIR_ ?>public/images/shop/author-1.jpg" alt="">
                        </div>
                        <h4>
                            <?= $review->candidate->firstname . ' ' . $review->candidate->lastname ?>
                        </h4>
                    </div>
                    <div class="products-reviews__rating">
                        <time><?= date('d M Y', $review->time) ?></time>
                        <div class="rating-block">
                            <?php for ($i = 0, $j = $review->rating; $i < 5; $i++) {
                                if ($i < $j) {
                                    $class = 'icon-shop-star-fill star active';
                                } else {
                                    $class = 'icon-shop-star-line star';
                                }
                                ?>
                                <span class="<?= $class ?>"></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="products-reviews__info">
                    <p>
                        <?= reFilter($review->content) ?>
                    </p>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

    <?php if (User::get('id', 'candidate')) { ?>
        <div class="products-reviews__review">
            <h3>Submit your product review</h3>
            <form id="review_form">
                <textarea name="review"></textarea>
                <div class="star-sec" id="rating">
                    <div class="my-rating"></div>
                    <input type="hidden" name="rating" id="rating-input">
                    <link href="<?= _SITEDIR_ ?>public/css/backend/star-rating-svg.css" type="text/css" rel="stylesheet" />
                    <script src="<?= _SITEDIR_ ?>public/js/backend/jquery.star-rating-svg.js"></script>
                    <script type="text/javascript">
                        $(document).ready(function($) {
                            $(".my-rating").starRating({
                                starSize: 20,
                                useFullStars: true,
                                disableAfterRate: false,
                                ratedColor: 'gold',
                                callback: function(currentRating, $el){
                                    $("#rating-input").val(currentRating);
                                    $(".my-rating").addClass("yes-rating");
                                },
                                callback: function () {
                                    console.log(1)
                                    const rating = document.querySelector('.my-rating');
                                    rating.addEventListener('click', () => {
                                        console.log(2)
                                        if (document.querySelector('.yes-rating')) {
                                            console.log(3)
                                            $('.my-rating').starRating('unload');
                                            console.log(4)
                                        }
                                    })


                                }
                            });


                        });//конец ready
                    </script>
                </div>
                <a class="btn-shop btn-shop--fill" onclick="load('shop/add_review', 'form:#review_form', 'product_id=<?= $this->product->id ?>')">Submit</a>
            </form>
        </div>
    <?php } ?>

</section>

