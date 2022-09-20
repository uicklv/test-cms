<div class="cart-popup">
    <div class="cart-popup__bg"></div>
    <div class="cart-popup__item">

        <div class="cont" id="cart-products">
            <?php include _SYSDIR_  . 'modules/shop/views/parts/_cart_products.php'; ?>
        </div>
    </div>
</div>
<script>
    function cartCalculator()
    {
        const cartPopup = () => {

            if (document.getElementsByClassName('cart-popup')[0]) {
                const cartPopup = document.getElementsByClassName('cart-popup')[0],
                    cartPopupBtn = document.querySelectorAll('.open-cart-popup'),
                    cartPopupBack = cartPopup.querySelector('.cart-popup__close'),
                    cartPopupClose = cartPopup.querySelector('.cart-popup__close-btn'),
                    cartPopupBg = cartPopup.querySelector('.cart-popup__bg');

                cartPopupBack.addEventListener('click', () => {
                    cartPopup.classList.remove('active');
                })

                cartPopupBtn.forEach((elem) => {
                    elem.addEventListener('click', (e) => {
                        e.preventDefault();
                        cartPopup.classList.add('active');
                    })
                })

                cartPopupClose.addEventListener('click', () => {
                    cartPopup.classList.remove('active');
                })

                cartPopupBg.addEventListener('click', () => {
                    cartPopup.classList.remove('active');
                })

                // calculator

                const topItemsLength = cartPopup.querySelector(`.items-card-length`),
                    cartList = cartPopup.querySelector(`.cart-list`),
                    cartListItems = cartList.querySelectorAll(`.cart-list__item`),
                    cartListPrice = cartPopup.querySelectorAll(`.cart-list__price`),
                    visibleTotalPrice = cartPopup.querySelector(`.price-item span`);

                topItemsLength.textContent = cartListItems.length + ` items`;

                cartListItems.forEach((elem, i,) => {
                    const counterBlock = elem.querySelector(`.counter-block`),
                        counterBtn = counterBlock.querySelectorAll(`.btn-shop`),
                        counterInput = counterBlock.querySelector(`.count-input`),
                        counterPrice = elem.querySelector(`.cart-list__price`);

                    let inputValue = +counterInput.value,
                        priceCounter = +counterPrice.textContent.replace(/[^0-9]/g,""),
                        firstPrice = +counterPrice.textContent.replace(/[^0-9]/g,"");

                    counterBtn[1].addEventListener(`click`, () => {
                        if (counterInput.value >= 1 && counterInput.value < 20) {
                            inputValue += 1;
                            counterInput.value = inputValue;

                            priceCounter = firstPrice * inputValue;
                            counterPrice.textContent = priceCounter + ` $`;
                            priceTotalCount();

                        } else if (counterInput.value >= 20) {

                            counterInput.value = 20;
                            inputValue = +counterInput.value;
                            priceCounter = firstPrice * inputValue;
                            counterPrice.textContent = priceCounter + ` $`;
                            priceTotalCount();
                        }

                    })

                    counterBtn[0].addEventListener(`click`, () => {
                        if (counterInput.value <= 1) return;

                        inputValue -= 1;
                        counterInput.value = inputValue;

                        priceCounter = firstPrice * inputValue;
                        counterPrice.textContent = priceCounter + ` $`;
                        priceTotalCount();

                    })

                    counterInput.addEventListener('input', (e) => {
                        counterInput.value = counterInput.value.replace(/[^\d.]/g, '');

                        if (counterInput.value >= 1 && counterInput.value <= 20) {
                            console.log(123)
                            inputValue = +counterInput.value;

                            priceCounter = firstPrice * inputValue;
                            counterPrice.textContent = priceCounter + ` $`;
                            priceTotalCount();

                        } else if (counterInput.value <= 0) {
                            counterInput.value = 1;
                            inputValue = +counterInput.value;

                            priceCounter = firstPrice * inputValue;
                            counterPrice.textContent = priceCounter + ` $`;
                            priceTotalCount();
                        } else if (counterInput.value >= 20) {

                            counterInput.value = 20;
                            inputValue = +counterInput.value;
                            priceCounter = firstPrice * inputValue;
                            counterPrice.textContent = priceCounter + ` $`;
                            priceTotalCount();
                        }
                    })

                })

                const priceTotalCount = () => {
                    let arrPrice = [];

                    if (cartListPrice.length === 0) {
                        visibleTotalPrice.textContent =`0 $`
                    } else {
                        cartListPrice.forEach((elem, i) => {
                            arrPrice.push(+elem.textContent.replace(/[^0-9]/g, ""));
                            visibleTotalPrice.textContent = summArrayElements(arrPrice) + ` $`;
                        })
                    }
                }

                function summArrayElements(arr){
                    let x = 0;
                    return arr.map(i=>x+=i, x).reverse()[0]
                }

                priceTotalCount();

            }
        }

        cartPopup();
    }
</script>
