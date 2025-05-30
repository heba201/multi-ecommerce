@extends('layouts.site')
@section('content')
<section class="section-box shop-template mt-30">
        <div class="container box-account-template">
          <h3>Hello Steven</h3>
          <p class="font-md color-gray-500">From your account dashboard. you can easily check & view your recent orders,<br class="d-none d-lg-block">manage your shipping and billing addresses and edit your password and account details.</p>
          <div class="box-tabs mb-100">
            <ul class="nav nav-tabs nav-tabs-account" role="tablist">
              <li><a class="active" href="#tab-notification" data-bs-toggle="tab" role="tab" aria-controls="tab-notification" aria-selected="true">Notification</a></li>
              <li><a href="#tab-wishlist" data-bs-toggle="tab" role="tab" aria-controls="tab-wishlist" aria-selected="true">Wishlist</a></li>
              <li><a href="#tab-orders" data-bs-toggle="tab" role="tab" aria-controls="tab-orders" aria-selected="true">Orders</a></li>
              <li><a href="#tab-order-tracking" data-bs-toggle="tab" role="tab" aria-controls="tab-order-tracking" aria-selected="true">Order Tracking</a></li>
              <li><a href="#tab-setting" data-bs-toggle="tab" role="tab" aria-controls="tab-setting" aria-selected="true">Setting</a></li>
            </ul>
            <div class="border-bottom mt-20 mb-40"></div>
            <div class="tab-content mt-30">
              <div class="tab-pane fade active show" id="tab-notification" role="tabpanel" aria-labelledby="tab-notification">
                <div class="list-notifications">
                  <div class="item-notification">
                    <div class="image-notification"><img src="assets/imgs/page/account/img-1.png" alt="Ecom"></div>
                    <div class="info-notification">
                      <h5 class="mb-5">COD payment confirmed</h5>
                      <p class="font-md color-brand-3">Order<span class="font-md-bold"> 220914QR92BXNH</span> has been confirmed. Please check the estimated delivery time in the order details section!</p>
                    </div>
                    <div class="button-notification"><a class="btn btn-buy w-auto">View Details</a></div>
                  </div>
                  <div class="item-notification">
                    <div class="image-notification"><img src="assets/imgs/page/account/img-2.png" alt="Ecom"></div>
                    <div class="info-notification">
                      <h5 class="mb-5">COD payment confirmed</h5>
                      <p class="font-md color-brand-3">Order<span class="font-md-bold"> 220914QR92BXNH</span> has been confirmed. Please check the estimated delivery time in the order details section!</p>
                    </div>
                    <div class="button-notification"><a class="btn btn-buy w-auto">View Details</a></div>
                  </div>
                  <div class="item-notification">
                    <div class="image-notification"><img src="assets/imgs/page/account/img-3.png" alt="Ecom"></div>
                    <div class="info-notification">
                      <h5 class="mb-5">COD payment confirmed</h5>
                      <p class="font-md color-brand-3">Order<span class="font-md-bold"> 220914QR92BXNH</span> has been confirmed. Please check the estimated delivery time in the order details section!</p>
                    </div>
                    <div class="button-notification"><a class="btn btn-buy w-auto">View Details</a></div>
                  </div>
                  <div class="item-notification">
                    <div class="image-notification"><img src="assets/imgs/page/account/img-4.png" alt="Ecom"></div>
                    <div class="info-notification">
                      <h5>COD payment confirmed</h5>
                      <p class="font-md color-brand-3">Order<span class="font-md-bold"> 220914QR92BXNH</span> has been confirmed. Please check the estimated delivery time in the order details section!</p>
                    </div>
                    <div class="button-notification"><a class="btn btn-buy w-auto">View Details</a></div>
                  </div>
                </div>
                <nav>
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link page-prev" href="#"></a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link active" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item"><a class="page-link" href="#">6</a></li>
                    <li class="page-item"><a class="page-link page-next" href="#"></a></li>
                  </ul>
                </nav>
              </div>
              <div class="tab-pane fade" id="tab-wishlist" role="tabpanel" aria-labelledby="tab-wishlist">
                <div class="box-wishlist">
                  <div class="head-wishlist">
                    <div class="item-wishlist">
                      <div class="wishlist-cb">
                        <input class="cb-layout cb-all" type="checkbox">
                      </div>
                      <div class="wishlist-product"><span class="font-md-bold color-brand-3">Product</span></div>
                      <div class="wishlist-price"><span class="font-md-bold color-brand-3">Price</span></div>
                      <div class="wishlist-status"><span class="font-md-bold color-brand-3">Stock status</span></div>
                      <div class="wishlist-action"><span class="font-md-bold color-brand-3">Action</span></div>
                      <div class="wishlist-remove"><span class="font-md-bold color-brand-3">Remove</span></div>
                    </div>
                  </div>
                  <div class="content-wishlist">
                    <div class="item-wishlist">
                      <div class="wishlist-cb">
                        <input class="cb-layout cb-select" type="checkbox">
                      </div>
                      <div class="wishlist-product">
                        <div class="product-wishlist">
                          <div class="product-image"><a href="shop-single-product.html"><img src="assets/imgs/page/product/img-sub.png" alt="Ecom"></a></div>
                          <div class="product-info"><a href="shop-single-product.html">
                              <h6 class="color-brand-3">Samsung 36&quot; French door 28 cu. ft. Smart Energy Star Refrigerator </h6></a>
                            <div class="rating"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><span class="font-xs color-gray-500"> (65)</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="wishlist-price">
                        <h4 class="color-brand-3">$2.51</h4>
                      </div>
                      <div class="wishlist-status"><span class="btn btn-gray font-md-bold color-brand-3">In Stock</span></div>
                      <div class="wishlist-action"><a class="btn btn-cart font-sm-bold" href="shop-cart.html">Add to Cart</a></div>
                      <div class="wishlist-remove"><a class="btn btn-delete" href="#"></a></div>
                    </div>
                    <div class="item-wishlist">
                      <div class="wishlist-cb">
                        <input class="cb-layout cb-select" type="checkbox">
                      </div>
                      <div class="wishlist-product">
                        <div class="product-wishlist">
                          <div class="product-image"><a href="shop-single-product.html"><img src="assets/imgs/page/product/img-sub2.png" alt="Ecom"></a></div>
                          <div class="product-info"><a href="shop-single-product.html">
                              <h6 class="color-brand-3">Samsung 36&quot; French door 28 cu. ft. Smart Energy Star Refrigerator </h6></a>
                            <div class="rating"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><span class="font-xs color-gray-500"> (65)</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="wishlist-price">
                        <h4 class="color-brand-3">$1.51</h4>
                      </div>
                      <div class="wishlist-status"><span class="btn btn-gray font-md-bold color-brand-3">In Stock</span></div>
                      <div class="wishlist-action"><a class="btn btn-cart font-sm-bold" href="shop-cart.html">Add to Cart</a></div>
                      <div class="wishlist-remove"><a class="btn btn-delete" href="#"></a></div>
                    </div>
                    <div class="item-wishlist">
                      <div class="wishlist-cb">
                        <input class="cb-layout cb-select" type="checkbox">
                      </div>
                      <div class="wishlist-product">
                        <div class="product-wishlist">
                          <div class="product-image"><a href="shop-single-product.html"><img src="assets/imgs/page/product/img-sub3.png" alt="Ecom"></a></div>
                          <div class="product-info"><a href="shop-single-product.html">
                              <h6 class="color-brand-3">Samsung 36&quot; French door 28 cu. ft. Smart Energy Star Refrigerator </h6></a>
                            <div class="rating"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><span class="font-xs color-gray-500"> (65)</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="wishlist-price">
                        <h4 class="color-brand-3">$3.51</h4>
                      </div>
                      <div class="wishlist-status"><span class="btn btn-gray font-md-bold color-brand-3">In Stock</span></div>
                      <div class="wishlist-action"><a class="btn btn-cart font-sm-bold" href="shop-cart.html">Add to Cart</a></div>
                      <div class="wishlist-remove"><a class="btn btn-delete" href="#"></a></div>
                    </div>
                    <div class="item-wishlist">
                      <div class="wishlist-cb">
                        <input class="cb-layout cb-select" type="checkbox">
                      </div>
                      <div class="wishlist-product">
                        <div class="product-wishlist">
                          <div class="product-image"><a href="shop-single-product.html"><img src="assets/imgs/page/product/img-sub4.png" alt="Ecom"></a></div>
                          <div class="product-info"><a href="shop-single-product.html">
                              <h6 class="color-brand-3">Samsung 36&quot; French door 28 cu. ft. Smart Energy Star Refrigerator </h6></a>
                            <div class="rating"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><span class="font-xs color-gray-500"> (65)</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="wishlist-price">
                        <h4 class="color-brand-3">$4.51</h4>
                      </div>
                      <div class="wishlist-status"><span class="btn btn-gray font-md-bold color-brand-3">In Stock</span></div>
                      <div class="wishlist-action"><a class="btn btn-cart font-sm-bold" href="shop-cart.html">Add to Cart</a></div>
                      <div class="wishlist-remove"><a class="btn btn-delete" href="#"></a></div>
                    </div>
                    <div class="item-wishlist">
                      <div class="wishlist-cb">
                        <input class="cb-layout cb-select" type="checkbox">
                      </div>
                      <div class="wishlist-product">
                        <div class="product-wishlist">
                          <div class="product-image"><a href="shop-single-product.html"><img src="assets/imgs/page/product/img-sub5.png" alt="Ecom"></a></div>
                          <div class="product-info"><a href="shop-single-product.html">
                              <h6 class="color-brand-3">Samsung 36&quot; French door 28 cu. ft. Smart Energy Star Refrigerator </h6></a>
                            <div class="rating"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><span class="font-xs color-gray-500"> (65)</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="wishlist-price">
                        <h4 class="color-brand-3">$3.51</h4>
                      </div>
                      <div class="wishlist-status"><span class="btn btn-gray font-md-bold color-brand-3">In Stock</span></div>
                      <div class="wishlist-action"><a class="btn btn-cart font-sm-bold" href="shop-cart.html">Add to Cart</a></div>
                      <div class="wishlist-remove"><a class="btn btn-delete" href="#"></a></div>
                    </div>
                    <div class="item-wishlist">
                      <div class="wishlist-cb">
                        <input class="cb-layout cb-select" type="checkbox">
                      </div>
                      <div class="wishlist-product">
                        <div class="product-wishlist">
                          <div class="product-image"><a href="shop-single-product.html"><img src="assets/imgs/page/product/img-sub.png" alt="Ecom"></a></div>
                          <div class="product-info"><a href="shop-single-product.html">
                              <h6 class="color-brand-3">Samsung 36&quot; French door 28 cu. ft. Smart Energy Star Refrigerator </h6></a>
                            <div class="rating"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><span class="font-xs color-gray-500"> (65)</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="wishlist-price">
                        <h4 class="color-brand-3">$2.51</h4>
                      </div>
                      <div class="wishlist-status"><span class="btn btn-gray font-md-bold color-brand-3">In Stock</span></div>
                      <div class="wishlist-action"><a class="btn btn-cart font-sm-bold" href="shop-cart.html">Add to Cart</a></div>
                      <div class="wishlist-remove"><a class="btn btn-delete" href="#"></a></div>
                    </div>
                    <div class="item-wishlist">
                      <div class="wishlist-cb">
                        <input class="cb-layout cb-select" type="checkbox">
                      </div>
                      <div class="wishlist-product">
                        <div class="product-wishlist">
                          <div class="product-image"><a href="shop-single-product.html"><img src="assets/imgs/page/product/img-sub2.png" alt="Ecom"></a></div>
                          <div class="product-info"><a href="shop-single-product.html">
                              <h6 class="color-brand-3">Samsung 36&quot; French door 28 cu. ft. Smart Energy Star Refrigerator </h6></a>
                            <div class="rating"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><img src="assets/imgs/template/icons/star.svg" alt="Ecom"><span class="font-xs color-gray-500"> (65)</span></div>
                          </div>
                        </div>
                      </div>
                      <div class="wishlist-price">
                        <h4 class="color-brand-3">$1.51</h4>
                      </div>
                      <div class="wishlist-status"><span class="btn btn-gray font-md-bold color-brand-3">In Stock</span></div>
                      <div class="wishlist-action"><a class="btn btn-cart font-sm-bold" href="shop-cart.html">Add to Cart</a></div>
                      <div class="wishlist-remove"><a class="btn btn-delete" href="#"></a></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="tab-orders" role="tabpanel" aria-labelledby="tab-orders">
                <div class="box-orders">
                  <div class="head-orders">
                    <div class="head-left">
                      <h5 class="mr-20">Order ID: #EWFDSAF1321655</h5><span class="font-md color-brand-3 mr-20">Date: 18 September 2022</span><span class="label-delivery">Delivery in progress</span>
                    </div>
                    <div class="head-right"><a class="btn btn-buy font-sm-bold w-auto">View Order</a></div>
                  </div>
                  <div class="body-orders">
                    <div class="list-orders">
                      <div class="item-orders">
                        <div class="image-orders"><img src="assets/imgs/page/account/img-1.png" alt="Ecom"></div>
                        <div class="info-orders">
                          <h5>Samsung 36" French door 28 cu. ft. Smart Energy Star Refrigerator</h5>
                        </div>
                        <div class="quantity-orders">
                          <h5>Quantity: 01</h5>
                        </div>
                        <div class="price-orders">
                          <h3>$2.51</h3>
                        </div>
                      </div>
                      <div class="item-orders">
                        <div class="image-orders"><img src="assets/imgs/page/account/img-1.png" alt="Ecom"></div>
                        <div class="info-orders">
                          <h5>Samsung 36" French door 28 cu. ft. Smart Energy Star Refrigerator</h5>
                        </div>
                        <div class="quantity-orders">
                          <h5>Quantity: 01</h5>
                        </div>
                        <div class="price-orders">
                          <h3>$2.51</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-orders">
                  <div class="head-orders">
                    <div class="head-left">
                      <h5 class="mr-20">Order ID: #EWFDSAF1321655</h5><span class="font-md color-brand-3 mr-20">Date: 18 September 2022</span><span class="label-delivery label-delivered">Delivered</span>
                    </div>
                    <div class="head-right"><a class="btn btn-buy font-sm-bold w-auto">View Order</a></div>
                  </div>
                  <div class="body-orders">
                    <div class="list-orders">
                      <div class="item-orders">
                        <div class="image-orders"><img src="assets/imgs/page/account/img-1.png" alt="Ecom"></div>
                        <div class="info-orders">
                          <h5>Samsung 36" French door 28 cu. ft. Smart Energy Star Refrigerator</h5>
                        </div>
                        <div class="quantity-orders">
                          <h5>Quantity: 01</h5>
                        </div>
                        <div class="price-orders">
                          <h3>$2.51</h3>
                        </div>
                      </div>
                      <div class="item-orders">
                        <div class="image-orders"><img src="assets/imgs/page/account/img-1.png" alt="Ecom"></div>
                        <div class="info-orders">
                          <h5>Samsung 36" French door 28 cu. ft. Smart Energy Star Refrigerator</h5>
                        </div>
                        <div class="quantity-orders">
                          <h5>Quantity: 01</h5>
                        </div>
                        <div class="price-orders">
                          <h3>$2.51</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-orders">
                  <div class="head-orders">
                    <div class="head-left">
                      <h5 class="mr-20">Order ID: #EWFDSAF1321655</h5><span class="font-md color-brand-3 mr-20">Date: 18 September 2022</span><span class="label-delivery label-cancel">Cancel</span>
                    </div>
                    <div class="head-right"><a class="btn btn-buy font-sm-bold w-auto">View Order</a></div>
                  </div>
                  <div class="body-orders">
                    <div class="list-orders">
                      <div class="item-orders">
                        <div class="image-orders"><img src="assets/imgs/page/product/ss.jpg" alt="Ecom"></div>
                        <div class="info-orders">
                          <h5>Samsung 36" French door 28 cu. ft. Smart Energy Star Refrigerator</h5>
                        </div>
                        <div class="quantity-orders">
                          <h5>Quantity: 01</h5>
                        </div>
                        <div class="price-orders">
                          <h3>$2.51</h3>
                        </div>
                      </div>
                      <div class="item-orders">
                        <div class="image-orders"><img src="assets/imgs/page/product/ss2.jpg" alt="Ecom"></div>
                        <div class="info-orders">
                          <h5>Samsung 36" French door 28 cu. ft. Smart Energy Star Refrigerator</h5>
                        </div>
                        <div class="quantity-orders">
                          <h5>Quantity: 01</h5>
                        </div>
                        <div class="price-orders">
                          <h3>$2.51</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <nav>
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link page-prev" href="#"></a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link active" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item"><a class="page-link" href="#">6</a></li>
                    <li class="page-item"><a class="page-link page-next" href="#"></a></li>
                  </ul>
                </nav>
              </div>
              <div class="tab-pane fade" id="tab-order-tracking" role="tabpanel" aria-labelledby="tab-order-tracking">
                <p class="font-md color-gray-600">To track your order please enter your OrderID in the box below and press "Track" button. This was given to you on<br class="d-none d-lg-block">your receipt and in the confirmation email you should have received.</p>
                <div class="row mt-30">
                  <div class="col-lg-6">
                    <div class="form-tracking">
                      <form action="#" method="get">
                        <div class="d-flex">
                          <div class="form-group box-input">
                            <input class="form-control" type="text" placeholder="FDSFWRFAF13585">
                          </div>
                          <div class="form-group box-button">
                            <button class="btn btn-buy font-md-bold" type="submit">Tracking Now</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="border-bottom mb-20 mt-20"></div>
                <h3 class="mb-10">Order Status:<span class="color-success">International shipping</span></h3>
                <h6 class="color-gray-500">Estimated Delivery Date: 27 August - 29 August</h6>
                <div class="table-responsive">
                  <div class="list-steps">
                    <div class="item-step">
                      <div class="rounded-step">
                        <div class="icon-step step-1 active"></div>
                        <h6 class="mb-5">Order Placed</h6>
                        <p class="font-md color-gray-500">15 August 2022</p>
                      </div>
                    </div>
                    <div class="item-step">
                      <div class="rounded-step">
                        <div class="icon-step step-2 active"></div>
                        <h6 class="mb-5">In Producttion</h6>
                        <p class="font-md color-gray-500">16 August 2022</p>
                      </div>
                    </div>
                    <div class="item-step">
                      <div class="rounded-step">
                        <div class="icon-step step-3 active"></div>
                        <h6 class="mb-5">International shipping</h6>
                        <p class="font-md color-gray-500">17 August 2022</p>
                      </div>
                    </div>
                    <div class="item-step">
                      <div class="rounded-step">
                        <div class="icon-step step-4"></div>
                        <h6 class="mb-5">Shipping Final Mile</h6>
                        <p class="font-md color-gray-500">18 August 2022</p>
                      </div>
                    </div>
                    <div class="item-step">
                      <div class="rounded-step">
                        <div class="icon-step step-5"></div>
                        <h6 class="mb-5">Delivered</h6>
                        <p class="font-md color-gray-500">19 August 2022</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="list-features">
                  <ul>
                    <li>09:10 25 August 2022: Delivery in progress</li>
                    <li>08:25 25 August 2022: The order has arrived at warehouse 05-YBI Marvel LM Hub</li>
                    <li>05:44 25 August 2022: Order has been shipped</li>
                    <li>04:51 25 August 2022: The order has arrived at Marvel SOC warehouse</li>
                    <li>20:54 18 August 2022: Order has been shipped</li>
                    <li>18:21 17 August 2022: The order has arrived at Marvel SOC warehouse</li>
                    <li>17:09 17 August 2022: Order has been shipped</li>
                    <li>15:23 17 August 2022: The order has arrived at warehouse 20-HNI Marvel 2 SOC</li>
                    <li>12:42 16 August 2022: Successful pick up</li>
                    <li>10:44 15 August 2022: The sender is preparing the goods</li>
                  </ul>
                </div>
                <h3>Package Location</h3>
                <div class="map-account">
                  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193548.25784139088!2d-74.12251055507726!3d40.71380001554004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2zVGjDoG5oIHBo4buRIE5ldyBZb3JrLCBUaeG7g3UgYmFuZyBOZXcgWW9yaywgSG9hIEvhu7M!5e0!3m2!1svi!2s!4v1664974174994!5m2!1svi!2s" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <p class="color-gray-500 mb-20">Maecenas porttitor augue sit amet nibh venenatis bibendum. Morbi lorem elit, fringilla quis libero vitae, tincidunt commodo purus. Quisque diam nisi, tincidunt sed vehicula nec, fermentum vitae lectus. Curabitur sit amet sagittis libero. Pellentesque cursus turpis at ipsum luctus tempor.</p>
                  </div>
                  <div class="col-lg-6">
                    <p class="color-gray-500 mb-20">Ut auctor varius nisl, scelerisque dictum justo maximus ut. Fusce rhoncus, augue sed molestie consectetur, leo felis ultricies erat, nec lobortis enim dui eu justo. Pellentesque aliquam hendrerit venenatis. Integer efficitur bibendum lectus sed sollicitudin. Suspendisse faucibus posuere euismod.</p>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="tab-setting" role="tabpanel" aria-labelledby="tab-setting">
                <div class="row">
                  <div class="col-lg-6 mb-20">
                    <form action="#" method="get">
                      <div class="row">
                        <div class="col-lg-12 mb-20">
                          <h5 class="font-md-bold color-brand-3 text-sm-start text-center">Contact information</h5>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="Fullname *">
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="Username *">
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="Phone Number *">
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="Email *">
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label class="font-sm color-brand-3" for="checkboxOffers">
                              <input class="checkboxOffer" id="checkboxOffers" type="checkbox">
                              Keep me up to date on news and exclusive offers
                            </label>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <h5 class="font-md-bold color-brand-3 mt-15 mb-20">Shipping address</h5>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="First name*">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="Last name*">
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="Address 1*">
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="Address 2*">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <select class="form-control font-sm select-style1 color-gray-700">
                              <option value="">Select an option...</option>
                              <option value="1">Option 1</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="City*">
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="PostCode / ZIP*">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="Company name">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <input class="form-control font-sm" type="text" placeholder="Phone*">
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group mb-0">
                            <textarea class="form-control font-sm" placeholder="Additional Information" rows="5"></textarea>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group mt-20">
                            <input class="btn btn-buy w-auto h54 font-md-bold" value="Save change">
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="col-lg-1 mb-20"></div>
                  <div class="col-lg-5 mb-20">
                    <div class="mt-40">
                      <h4 class="mb-10">Steven Job</h4>
                      <div class="mb-10">
                        <p class="font-sm color-brand-3 font-medium">Home Address:</p><span class="font-sm color-gray-500 font-medium">205 North Michigan Avenue, Suite 810 Chicago, 60601, USA</span>
                      </div>
                      <div class="mb-10">
                        <p class="font-sm color-brand-3 font-medium">Delivery address:</p><span class="font-sm color-gray-500 font-medium">205 North Michigan Avenue, Suite 810 Chicago, 60601, USA</span>
                      </div>
                      <div class="mb-10">
                        <p class="font-sm color-brand-3 font-medium">Phone Number:</p><span class="font-sm color-gray-500 font-medium">(+01) 234 567 89 - (+01) 688 866 99</span>
                      </div>
                      <div class="mb-10 mt-15"><a class="btn btn-cart w-auto">Set as Default</a></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    @endsection