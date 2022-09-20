<form id="search_form" class="sidebar__inner products-sidebar__container">
    <div class="top-block">
        <div id="filters-btn-shop-close" class="icon-shop-filter-line btn-shop btn-shop--line filters-btn-shop"></div>
        <p>Filters</p>
    </div>
    <div class="products-sidebar__elem products-sidebar__elem--key-words">
        <h5 style="margin-bottom: 15px">
            Key words
        </h5>
        <input type="text" name="keywords[]" value="" placeholder="Search...">
        <div class="keywords-block" id="list_tags">
            <?php include _SYSDIR_  . 'modules/shop/views/parts/_tags.php'; ?>
        </div>
    </div>

    <div class="products-sidebar__elem products-sidebar__accordion">
        <h5 class="icon-shop-arrow-down accordion-trigger active">
            Price
        </h5>
        <div class="accordion-content active">
            <input id="inputPieces" class="double-range" name="price" type="range" multiple value="<?= $this->price ?>" min="0" max="5000" unit="$" />
        </div>
    </div>

    <?php if ($this->types) { ?>
        <div class="products-sidebar__elem products-sidebar__accordion">
            <h5 class="icon-shop-arrow-down accordion-trigger active">
                Type
            </h5>
            <ul class="accordion-content active">
                <?php foreach ($this->types as $type) { ?>
                    <li>
                        <label class="checkbox">
                            <input type="checkbox" name="type[]" value="<?= $type->id ?>" <?= checkCheckboxValue(post('type') ?: [get('type')], $type->id, $this->type) ?>>
                            <p><?= $type->name ?> (<span><?= $type->counter ?></span>)</p>
                        </label>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

    <?php if ($this->brands) { ?>
        <div class="products-sidebar__elem products-sidebar__accordion">
            <h5 class="icon-shop-arrow-down accordion-trigger active">
                Brand
            </h5>
            <ul class="accordion-content active">
                <?php foreach ($this->brands as $brand) { ?>
                    <li>
                        <label class="checkbox">
                            <input type="checkbox" name="brand[]" value="<?= $brand->id ?>" <?= checkCheckboxValue(post('brand'), $brand->id, $this->brand) ?>>
                            <p><?= $brand->name ?> (<span><?= $brand->counter ?></span>)</p>
                        </label>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
    <div class="show-search-block">
        <button class="btn-shop btn-shop--fill" id="btn-shop-show" onclick="load('shop/search_process', 'form:#search_form'); scrollToEl('#main-block|900'); return false;">Show</button>
        <a href="{URL:shop/search}" class="icon-shop-delete-line btn-shop btn-shop--line"></a>
    </div>
</form>
<script>
    $(document).ready(function($) {

        const accordionFiltersProducts = () => {
            const btn = document.querySelectorAll('.accordion-trigger'),
                item = document.querySelectorAll('.accordion-content');

            if (document.querySelector('.products-sidebar__accordion')) {
                btn.forEach((elem, index) => {
                    elem.addEventListener('click', (event) => {
                        let target = event.target;
                        target = target.closest('.accordion-trigger');
                        if (target.closest('.accordion-trigger.active')) {
                            elem.classList.remove('active')
                            item[index].classList.remove('active')
                        } else {
                            elem.classList.toggle('active')
                            item[index].classList.toggle('active')
                        }
                    })
                })
            }
        };
        accordionFiltersProducts();

        OmRangeSlider.init();

        const doubleRangeDisplay = () => {
            const doubleRange = document.getElementsByClassName('double-range')[1];
            if (doubleRange) {
                const displayBlock = document.querySelector('.om-sliderrange-display'),
                    displaySpanArr = document.querySelectorAll('.om-sliderrange-display span'),
                    input = document.getElementById('inputPieces');

                const maxValue = +input.getAttribute('max');

                const buttonStart = document.querySelector('.om-sliderrange-button-start'),
                    buttonEnd = document.querySelector('.om-sliderrange-button-end');

                displaySpanArr[0].style.left = buttonStart.offsetLeft + 'px';
                displaySpanArr[2].style.left = buttonEnd.offsetLeft + 'px';

                doubleRange.addEventListener('mousemove', (e) => {
                    displaySpanArr[0].style.left = buttonStart.offsetLeft + 'px';
                    displaySpanArr[2].style.left = buttonEnd.offsetLeft + 'px';
                })
            }
        }
        doubleRangeDisplay();

        // Products sidebar sticky
        const productsSidebar = document.getElementsByClassName('products-sidebar')[0];
        if (productsSidebar && window.innerWidth >= 769) {

            const sidebarContainer = productsSidebar.querySelector('.products-sidebar__container'),
                searchContainer = document.querySelector(`.page-with-sidebar__container`);
            searchContainer.style.minHeight = `${searchContainer.offsetHeight}px`;
        }

        if (productsSidebar && window.innerWidth <= 769) {
            var filtersBtnOpen = document.getElementById('filters-btn-shop');
            var filtersBtnClose = document.getElementById('filters-btn-shop-close');
            var btnShow = document.getElementById('btn-shop-show');

            function openSidebar() {
                productsSidebar.classList.add(`active`);
            }
            function closeSidebar() {
                productsSidebar.classList.remove(`active`);
            }

            filtersBtnOpen.addEventListener('click', openSidebar);

            filtersBtnClose.addEventListener('click', closeSidebar);

            productsSidebar.addEventListener('click', (e) => {
                if(!e.target.closest('.products-sidebar__container')) {
                    productsSidebar.classList.remove(`active`);
                }
            })

            btnShow.addEventListener('click', (e) => {
                filtersBtnOpen.removeEventListener('click', openSidebar);
                filtersBtnClose.removeEventListener('click', closeSidebar);
                productsSidebar.classList.remove(`active`);
            })
        }
    });
</script>
