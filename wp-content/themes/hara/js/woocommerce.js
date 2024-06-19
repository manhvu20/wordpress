'use strict';

const ADDED_TO_CART_EVENT = "added_to_cart";
const PRODUCT_LIST_AJAX_SHOP_PAGE = "hara_products_list_ajax";
const PRODUCT_GRID_AJAX_SHOP_PAGE = "hara_products_grid_ajax";

class AjaxCart {
  constructor() {
    if (typeof hara_settings === "undefined") return;

    let _this = this;

    _this.ajaxCartPosition = hara_settings.cart_position;

    switch (_this.ajaxCartPosition) {
      case "popup":
        _this._initAjaxPopup();

        break;

      case "left":
        _this._initAjaxCartOffCanvas("left");

        break;

      case "right":
        _this._initAjaxCartOffCanvas("right");

        break;
    }

    _this._initEventRemoveProduct();

    _this._initEventMiniCartAjaxQuantity();
  }

  _initAjaxPopupContent(button) {
    var _this = this;

    if (button.closest('form.cart').find('input[name="hara_buy_now"]').length > 0 && button.closest('form.cart').find('input[name="hara_buy_now"]').val() === "1") return;
    let title = '';

    if (button.closest('form.cart').length > 0) {
      let form = button.closest('form.cart'),
          variation_id = jQuery(form).find('input[name="variation_id"]').length ? parseInt(jQuery(form).find('input[name="variation_id"]').val()) : 0;
      if (jQuery(form).find('input[name="data-type"]').length === 0) return;

      if (variation_id !== 0) {
        var urlAjax = hara_settings.wc_ajax_url.toString().replace('%%endpoint%%', 'hara_popup_variation_name');
        title = _this._initAjaxPopupVariationName(variation_id, urlAjax);
      } else {
        title = button.closest('.product').find('.product_title').html();
      }
    } else {
      title = button.closest('.product').find('.name  a').html();
    }

    if (typeof title === "undefined") return;

    _this._initAjaxPopupShow(title);
  }

  _initAjaxPopupShow(title) {
    let cart_popup = jQuery('#tbay-cart-popup'),
        cart_popup_content = jQuery('#tbay-cart-popup').find('.toast-body'),
        cart_notification = hara_settings.popup_cart_noti,
        string = '';
    string += hara_settings.popup_cart_icon;
    string += `<p>"${title}" ${cart_notification}</p>`;

    if (!wc_add_to_cart_params.is_cart) {
      string += `<a href="${wc_add_to_cart_params.cart_url}" title="${wc_add_to_cart_params.i18n_view_cart}">${wc_add_to_cart_params.i18n_view_cart}</a>`;
    }

    if (typeof string !== "undefined") {
      cart_popup_content.html(string);
    }

    cart_popup.toast('show');
  }

  _initAjaxPopupVariationName(variation_id, urlAjax) {
    var _this = this;

    jQuery.ajax({
      url: urlAjax,
      data: {
        variation_id: variation_id,
        security: hara_settings.wp_popupvariationnamenonce
      },
      dataType: 'json',
      method: 'POST',
      success: function (data) {
        _this._initAjaxPopupShow(data);
      }
    });
  }

  _initAjaxPopup() {
    var _this = this;

    if (typeof wc_add_to_cart_params === 'undefined') {
      return false;
    }

    if (hara_settings.ajax_popup_quick) {
      jQuery(`.ajax_cart_popup`).on('click', '.ajax_add_to_cart, .single_add_to_cart_button', function (e) {
        let button = jQuery(this);
        if (button.parent().hasClass('shop-now') && !button.parent().hasClass('ajax-single-cart')) return;

        _this._initAjaxPopupContent(button);
      });
    } else {
      jQuery(`.ajax_cart_popup, .single_add_to_cart_button`).on(ADDED_TO_CART_EVENT, function (ev, fragmentsJSON, cart_hash, button) {
        if (typeof fragmentsJSON == 'undefined') fragmentsJSON = JSON.parse(sessionStorage.getItem(wc_cart_fragments_params.fragment_name));

        _this._initAjaxPopupContent(button);
      });
    }
  }

  _initAjaxCartOffCanvas(position) {
    jQuery(`.ajax_cart_${position}`).on(ADDED_TO_CART_EVENT, function () {
      if (hara_settings.mobile) position = 'mobile';
      var Offcanvasopen = new bootstrap.Offcanvas(`#cart-offcanvas-${position}`);
      Offcanvasopen.show();
      jQuery(document.body).trigger('wc_fragments_refreshed');
      jQuery.magnificPopup.close();
    });
  }

  _initEventRemoveProduct() {
    if (typeof wc_add_to_cart_params === 'undefined') {
      return false;
    }

    jQuery(document).on('click', '.mini_cart_content a.remove', event => {
      this._onclickRemoveProduct(event);
    });
  }

  _onclickRemoveProduct(event) {
    event.preventDefault();
    var product_id = jQuery(event.currentTarget).attr("data-product_id"),
        cart_item_key = jQuery(event.currentTarget).attr("data-cart_item_key"),
        thisItem = jQuery(event.currentTarget).closest('.widget_shopping_cart_content');

    this._callRemoveProductAjax(product_id, cart_item_key, thisItem, event);
  }

  _callRemoveProductAjax(product_id, cart_item_key, thisItem, event) {
    var urlAjax = hara_settings.wc_ajax_url.toString().replace('%%endpoint%%', 'hara_product_remove');
    jQuery.ajax({
      type: 'POST',
      dataType: 'json',
      url: urlAjax,
      data: {
        product_id: product_id,
        cart_item_key: cart_item_key,
        security: hara_settings.wp_productremovenonce
      },
      beforeSend: function () {
        thisItem.find('.mini_cart_content').append('<div class="ajax-loader-wapper"><div class="ajax-loader"></div></div>').fadeTo("slow", 0.3);
      },
      success: response => {
        this._onRemoveSuccess(response, product_id);

        jQuery(document.body).trigger('removed_from_cart');
      }
    });
  }

  _onRemoveSuccess(response, product_id) {
    if (!response || response.error) return;
    var fragments = response.fragments;

    if (fragments) {
      jQuery.each(fragments, function (key, value) {
        jQuery(key).replaceWith(value);
      });
    }

    jQuery('.add_to_cart_button.added[data-product_id="' + product_id + '"]').removeClass("added").next('.wc-forward').remove();
  }

  _initEventMiniCartAjaxQuantity() {
    var timeout;
    jQuery('body').on('change', '.mini-cart-item .qty', function () {
      var urlAjax = hara_settings.wc_ajax_url.toString().replace('%%endpoint%%', 'hara_quantity_mini_cart'),
          input = jQuery(this),
          wrap = jQuery(input).parents('.mini_cart_content'),
          hash = jQuery(input).attr('name').replace(/cart\[([\w]+)\]\[qty\]/g, "$1"),
          max = parseFloat(jQuery(input).attr('max'));

      if (!max) {
        max = false;
      }

      clearTimeout(timeout);
      var quantity = parseFloat(jQuery(input).val());

      if (max > 0 && quantity > max) {
        jQuery(input).val(max);
        quantity = max;
      }

      timeout = setTimeout(function () {
        jQuery.ajax({
          url: urlAjax,
          type: 'POST',
          dataType: 'json',
          cache: false,
          data: {
            hash: hash,
            quantity: quantity,
            security: hara_settings.wp_minicartquantitynonce
          },
          beforeSend: function () {
            wrap.append('<div class="ajax-loader-wapper"><div class="ajax-loader"></div></div>').fadeTo("slow", 0.3);
          },
          success: function (data) {
            if (data && data.fragments) {
              jQuery.each(data.fragments, function (key, value) {
                if (jQuery(key).length) {
                  jQuery(key).replaceWith(value);
                }
              });

              if (typeof $supports_html5_storage !== 'undefined' && $supports_html5_storage) {
                sessionStorage.setItem(wc_cart_fragments_params.fragment_name, JSON.stringify(data.fragments));
                set_cart_hash(data.cart_hash);

                if (data.cart_hash) {
                  set_cart_creation_timestamp();
                }
              }

              jQuery(document.body).trigger('wc_fragments_refreshed');
            }
          }
        });
      }, 300);
    });
  }

}

class WishList {
  constructor() {
    this._onChangeWishListItem();
  }

  _onChangeWishListItem() {
    jQuery(document).on('added_to_wishlist removed_from_wishlist', () => {
      var counter = jQuery('.count_wishlist').find('> span');
      if (counter.length === 0) return;
      var urlAjax = hara_settings.wc_ajax_url.toString().replace('%%endpoint%%', 'hara_update_wishlist_count');
      jQuery.ajax({
        url: urlAjax,
        type: 'POST',
        data: {
          security: hara_settings.wp_wishlistcountnonce
        },
        dataType: 'json',
        success: function (data) {
          counter.html(data.count);
        },
        beforeSend: function () {
          counter.block({
            message: null,
            overlayCSS: {
              background: '#fff url(' + hara_settings.loader + ') no-repeat center',
              opacity: 0.5,
              cursor: 'none'
            }
          });
        },
        complete: function () {
          counter.unblock();
        }
      });
    });
  }

}

class ProductItem {
  _initAddButtonQuantity() {
    let input = jQuery('.quantity input');
    input.each(function () {
      if (jQuery(this).parent('.box').length) return;
      jQuery(this).wrap('<span class="box"></span>');

      if (jQuery(this).attr('type') == 'hidden') {
        jQuery(this).parents('.quantity').addClass('hidden');
      } else {
        jQuery(`<button class="minus" type="button" value="&nbsp;">${hara_settings.quantity_minus}</button>`).insertBefore(jQuery(this));
        jQuery(`<button class="plus" type="button" value="&nbsp;">${hara_settings.quantity_plus}</button>`).insertAfter(jQuery(this));
      }
    });
  }

  _initOnChangeQuantity(callback) {
    if (typeof hara_settings === "undefined") return;

    this._initAddButtonQuantity();

    jQuery(document).off('click', '.plus, .minus').on('click', '.plus, .minus', function (event) {
      event.preventDefault();
      var qty = jQuery(this).closest('.quantity').find('.qty'),
          currentVal = parseFloat(qty.val()),
          max = jQuery(qty).attr("max"),
          min = jQuery(qty).attr("min"),
          step = jQuery(qty).attr("step");
      currentVal = !currentVal || currentVal === '' || currentVal === 'NaN' ? 0 : currentVal;
      max = max === '' || max === 'NaN' ? '' : max;
      min = min === '' || min === 'NaN' ? 0 : min;
      step = step === 'any' || step === '' || step === undefined || parseFloat(step) === NaN ? 1 : step;

      if (jQuery(this).is('.plus')) {
        if (max && (max == currentVal || currentVal > max)) {
          qty.val(max);
        } else {
          qty.val(currentVal + parseFloat(step));
        }
      } else {
        if (min && (min == currentVal || currentVal < min)) {
          qty.val(min);
        } else if (currentVal > min) {
          qty.val(currentVal - parseFloat(step));
        }
      }

      if (callback && typeof callback == "function") {
        jQuery(this).parent().find('input').trigger("change");
        callback();
      }
    });
  }

  _initSwatches() {
    if (jQuery('.tbay-swatches-wrapper li a').length === 0) return;
    jQuery('body').on('click', '.tbay-swatches-wrapper li a', function (event) {
      event.preventDefault();
      let $active = false;
      let $parent = jQuery(this).closest('.product-block');
      var $image = $parent.find('.image img:eq(0)');

      if (!jQuery(this).closest('ul').hasClass('active')) {
        jQuery(this).closest('ul').addClass('active');
        $image.attr('data-old', $image.attr('src'));
      }

      if (!jQuery(this).hasClass('selected')) {
        jQuery(this).closest('ul').find('li a').each(function () {
          if (jQuery(this).hasClass('selected')) {
            jQuery(this).removeClass('selected');
          }
        });
        jQuery(this).addClass('selected');
        $parent.addClass('product-swatched');
        $active = true;
      } else {
        $image.attr('src', $image.data('old'));
        jQuery(this).removeClass('selected');
        $parent.removeClass('product-swatched');
      }

      if (!$active) return;

      if (typeof jQuery(this).data('imageSrc') !== 'undefined') {
        $image.attr('src', jQuery(this).data('imageSrc'));
      }

      if (typeof jQuery(this).data('imageSrcset') !== 'undefined') {
        $image.attr('srcset', jQuery(this).data('imageSrcset'));
      }

      if (typeof jQuery(this).data('imageSizes') !== 'undefined') {
        $image.attr('sizes', jQuery(this).data('imageSizes'));
      }
    });
  }

  _initQuantityMode() {
    if (typeof hara_settings === "undefined" || !hara_settings.quantity_mode) return;
    jQuery(".woocommerce .products").on("click", ".quantity .qty", function () {
      return false;
    });
    jQuery(".woocommerce .products").on("change input", ".quantity .qty", function () {
      var add_to_cart_button = jQuery(this).parents(".product").find(".add_to_cart_button");
      add_to_cart_button.attr("data-quantity", jQuery(this).val());
    });
    jQuery(".woocommerce .products").on("keypress", ".quantity .qty", function (e) {
      if ((e.which || e.keyCode) === 13) {
        jQuery(this).parents(".product").find(".add_to_cart_button").trigger("click");
      }
    });
  }

}

class Cart {
  constructor() {
    this._initEventChangeQuantity();

    this._init_shipping_free_notification();

    jQuery(document.body).on('updated_wc_div', () => {
      this._initEventChangeQuantity();

      if (typeof wc_add_to_cart_variation_params !== 'undefined') {
        jQuery('.variations_form').each(function () {
          jQuery(this).wc_variation_form();
        });
      }
    });
    jQuery(document.body).on('cart_page_refreshed', () => {
      this._initEventChangeQuantity();
    });
    jQuery(document.body).on('tbay_display_mode', () => {
      this._initEventChangeQuantity();
    });
  }

  _initEventChangeQuantity() {
    if (jQuery("body.woocommerce-cart [name='update_cart']").length === 0) {
      new ProductItem()._initOnChangeQuantity(() => {});
    } else {
      new ProductItem()._initOnChangeQuantity(() => {
        jQuery('.woocommerce-cart-form :input[name="update_cart"]').prop('disabled', false);

        if (typeof hara_settings !== "undefined" && hara_settings.ajax_update_quantity) {
          jQuery("[name='update_cart']").trigger('click');
        }
      });
    }
  }

  _init_shipping_free_notification() {
    if (jQuery('.tbay-total-condition').length > 0) {
      jQuery('.tbay-total-condition').each(function () {
        if (!jQuery(this).hasClass('tbay-active')) {
          jQuery(this).addClass('tbay-active');
          var per = jQuery(this).attr('data-per');
          jQuery(this).find('.tbay-total-condition-hint, .tbay-subtotal-condition').css({
            'width': per + '%'
          });
        }
      });
    }
  }

}

class Checkout {
  constructor() {
    this._toogleWoocommerceIcon();

    this._initEventCheckoutAjaxQuantity();
  }

  _toogleWoocommerceIcon() {
    if (jQuery('.woocommerce-info a').length < 1) {
      return;
    }

    jQuery('.woocommerce-info a').click(function () {
      jQuery(this).find('.icons').toggleClass('icon-arrow-down').toggleClass('icon-arrow-up');
    });
  }

  _initEventCheckoutAjaxQuantity() {
    var timeout;
    jQuery("body").on("change input", ".woocommerce-checkout-review-order-table .quantity .qty", function () {
      var urlAjax = hara_settings.wc_ajax_url.toString().replace("%%endpoint%%", "hara_quantity_mini_cart"),
          input = jQuery(this),
          hash = jQuery(input).attr("name").replace(/cart\[([\w]+)\]\[qty\]/g, "$1"),
          max = parseFloat(jQuery(input).attr("max"));

      if (!max) {
        max = false;
      }

      clearTimeout(timeout);
      var quantity = parseFloat(jQuery(input).val());

      if (max > 0 && quantity > max) {
        jQuery(input).val(max);
        quantity = max;
      }

      timeout = setTimeout(function () {
        jQuery.ajax({
          url: urlAjax,
          type: "POST",
          dataType: "json",
          cache: false,
          data: {
            hash: hash,
            quantity: quantity,
            security: hara_settings.wp_minicartquantitynonce
          },
          beforeSend: function () {
            jQuery('form.checkout').trigger('update');
          },
          success: function (data) {
            if (data && data.fragments) {
              jQuery.each(data.fragments, function (key, value) {
                if (jQuery(key).length) {
                  jQuery(key).replaceWith(value);
                }
              });

              if (typeof $supports_html5_storage !== "undefined" && $supports_html5_storage) {
                sessionStorage.setItem(wc_cart_fragments_params.fragment_name, JSON.stringify(data.fragments));
                set_cart_hash(data.cart_hash);

                if (data.cart_hash) {
                  set_cart_creation_timestamp();
                }
              }

              jQuery('form.checkout').trigger('update');
            }
          }
        });
      }, 300);
    });
  }

}

class WooCommon {
  constructor() {
    this._tbayFixRemove();

    jQuery(document.body).on('tbayFixRemove', () => {
      this._tbayFixRemove();
    });
  }

  _tbayFixRemove() {
    jQuery('.tbay-gallery-varible .woocommerce-product-gallery__trigger').remove();
  }

}

class QuickView {
  constructor() {
    if (typeof hara_settings === "undefined") return;

    this._init_tbay_quick_view();
  }

  _init_tbay_quick_view() {
    var _this = this;

    jQuery(document).off('click', 'a.qview-button').on('click', 'a.qview-button', function (e) {
      e.preventDefault();
      let self = jQuery(this);
      self.parent().addClass('loading');
      let mainClass = self.attr('data-effect');
      let is_blocked = false,
          product_id = jQuery(this).data('product_id'),
          url = hara_settings.wc_ajax_url.toString().replace('%%endpoint%%', 'hara_quickview_product') + '&product_id=' + product_id;

      if (typeof hara_settings.loader !== 'undefined') {
        is_blocked = true;
        self.block({
          message: null,
          overlayCSS: {
            background: '#fff url(' + hara_settings.loader + ') no-repeat center',
            opacity: 0.5,
            cursor: 'none'
          }
        });
      }

      _this._ajax_call(self, url, is_blocked, mainClass);

      e.stopPropagation();
    });
  }

  _ajax_call(self, url, is_blocked, mainClass) {
    jQuery.get(url, function (data, status) {
      jQuery.magnificPopup.open({
        removalDelay: 0,
        closeMarkup: '<button title="%title%" type="button" class="mfp-close"> ' + hara_settings.close + '</button>',
        callbacks: {
          beforeOpen: function () {
            this.st.mainClass = mainClass + ' hara-quickview';
          }
        },
        items: {
          src: data,
          type: 'inline'
        }
      });
      let qv_content = jQuery("#tbay-quick-view-content");
      let form_variation = qv_content.find('.variations_form');

      if (typeof wc_add_to_cart_variation_params !== 'undefined') {
        form_variation.each(function () {
          jQuery(this).wc_variation_form();
        });
      }

      if (typeof wc_single_product_params !== 'undefined') {
        qv_content.find('.woocommerce-product-gallery').each(function () {
          jQuery(this).wc_product_gallery(wc_single_product_params);
        });
      }

      jQuery(document.body).trigger('updated_wc_div');
      self.parent().removeClass('loading');

      if (is_blocked) {
        self.unblock();
      }

      jQuery(document.body).trigger('tbay_quick_view');
    });
  }

}

class StickyBar {
  constructor() {
    if (typeof jQuery.fn.onePageNav === "undefined") return;

    this._productSingleOnepagenav();
  }

  _productSingleOnepagenav() {
    if (jQuery('#sticky-menu-bar').length > 0) {
      let offset_adminbar = 0;

      if (jQuery('#wpadminbar').length > 0) {
        offset_adminbar = jQuery('#wpadminbar').outerHeight();
      }

      let offset = jQuery('#sticky-menu-bar').outerHeight() + offset_adminbar;
      jQuery('#sticky-menu-bar').onePageNav({
        currentClass: 'current',
        changeHash: false,
        scrollSpeed: 750,
        scrollThreshold: 0.5,
        scrollOffset: offset,
        filter: '',
        easing: 'swing',
        begin: function () {},
        end: function () {},
        scrollChange: function () {}
      });
    }

    var onepage = jQuery('#sticky-menu-bar');

    if (onepage.length > 0) {
      var tbay_width = jQuery(window).width();
      jQuery('.tbay_header-template').removeClass('main-sticky-header');
      var btn_cart_offset = jQuery('.single_add_to_cart_button').length > 0 ? jQuery('.single_add_to_cart_button').offset().top : 0;
      var out_of_stock_offset = jQuery('div.product .out-of-stock').length > 0 ? jQuery('div.product .out-of-stock').offset().top : 0;

      if (jQuery('.by-vendor-name-link').length > 0) {
        out_of_stock_offset = jQuery('.by-vendor-name-link').offset().top;
      }

      var sum_height = jQuery('.single_add_to_cart_button').length > 0 ? btn_cart_offset : out_of_stock_offset;

      this._checkScroll(tbay_width, sum_height, onepage);

      jQuery(window).scroll(() => {
        this._checkScroll(tbay_width, sum_height, onepage);
      });
    }

    if (onepage.hasClass('active') && jQuery('#wpadminbar').length > 0) {
      onepage.css('top', jQuery('#wpadminbar').height());
    }
  }

  _checkScroll(tbay_width, sum_height, onepage) {
    if (tbay_width >= 768) {
      var NextScroll = jQuery(window).scrollTop();

      if (NextScroll > sum_height) {
        onepage.addClass('active');

        if (jQuery('#wpadminbar').length > 0) {
          onepage.css('top', jQuery('#wpadminbar').height());
        }
      } else {
        onepage.removeClass('active');
      }
    } else {
      onepage.removeClass('active');
    }
  }

}

class DisplayMode {
  constructor() {
    if (typeof hara_settings === "undefined") return;

    this._initModeListShopPage();

    this._initModeGridShopPage();

    jQuery(document.body).on('displayMode', () => {
      this._initModeListShopPage();

      this._initModeGridShopPage();
    });
  }

  _initModeListShopPage() {

    jQuery('#display-mode-list').each(function (index) {
      jQuery(this).click(function () {
        if (jQuery(this).hasClass('active')) return;
        var event = jQuery(this),
            urlAjax = hara_settings.wc_ajax_url.toString().replace('%%endpoint%%', PRODUCT_LIST_AJAX_SHOP_PAGE);
        jQuery.ajax({
          url: urlAjax,
          data: {
            query: hara_settings.posts,
            security: hara_settings.wp_productslistnonce
          },
          dataType: 'json',
          method: 'POST',
          beforeSend: function (xhr) {
            event.closest('#tbay-main-content').find('.display-products').addClass('load-ajax');
          },
          success: function (data) {
            if (data) {
              event.parent().children().removeClass('active');
              event.addClass('active');
              event.closest('#tbay-main-content').find('.display-products > div').html(data);
              event.closest('#tbay-main-content').find('.display-products').fadeOut(0, function () {
                jQuery(this).addClass('products-list').removeClass('products-grid grid').fadeIn(300);
              });

              if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                jQuery('.variations_form').each(function () {
                  jQuery(this).wc_variation_form().find('.variations select:eq(0)').trigger('change');
                });
              }

              jQuery(document.body).trigger('tbay_display_mode');
              event.closest('#tbay-main-content').find('.display-products').removeClass('load-ajax');
              Cookies.set('hara_display_mode', 'list', {
                expires: 0.1,
                path: '/'
              });
            }
          }
        });
        return false;
      });
    });
  }

  _initModeGridShopPage() {

    jQuery('#display-mode-grid').each(function (index) {
      jQuery(this).click(function () {
        if (jQuery(this).hasClass('active')) return;
        var event = jQuery(this),
            urlAjax = hara_settings.wc_ajax_url.toString().replace('%%endpoint%%', PRODUCT_GRID_AJAX_SHOP_PAGE);
        event.closest('#tbay-main-content').find('div.display-products');
        jQuery.ajax({
          url: urlAjax,
          data: {
            query: hara_settings.posts,
            security: hara_settings.wp_productsgridnonce
          },
          dataType: 'json',
          method: 'POST',
          beforeSend: function (xhr) {
            event.closest('#tbay-main-content').find('.display-products').addClass('load-ajax');
          },
          success: function (data) {
            if (data) {
              event.parent().children().removeClass('active');
              event.addClass('active');
              event.closest('#tbay-main-content').find('.display-products > div').html(data);
              let products = event.closest('#tbay-main-content').find('div.display-products');
              products.fadeOut(0, function () {
                jQuery(this).addClass('products-grid').removeClass('products-list').fadeIn(300);
              });

              if (typeof wc_add_to_cart_variation_params !== 'undefined') {
                jQuery('.variations_form').each(function () {
                  jQuery(this).wc_variation_form().find('.variations select:eq(0)').trigger('change');
                });
              }

              jQuery(document.body).trigger('tbay_display_mode');
              event.closest('#tbay-main-content').find('.display-products').removeClass('load-ajax');
              Cookies.set('hara_display_mode', 'grid', {
                expires: 0.1,
                path: '/'
              });
            }
          }
        });
        return false;
      });
    });
  }

}

class AjaxFilter {
  constructor() {
    this._intAjaxFilter();
  }

  _intAjaxFilter() {
    jQuery(document).on("woof_ajax_done", woof_ajax_done_handler);

    function woof_ajax_done_handler(e) {
      jQuery('.woocommerce-product-gallery').each(function () {
        jQuery(this).wc_product_gallery();
      });
      jQuery(document.body).trigger('tbayFixRemove');
      jQuery(document.body).trigger('displayMode');
      jQuery(document.body).trigger('ajax_sidebar_shop_mobile');

      if (jQuery('body').hasClass('filter-mobile-active')) {
        jQuery("body").removeClass('filter-mobile-active');
      }

      if (typeof tawcvs_variation_swatches_form !== 'undefined') {
        jQuery('.variations_form').tawcvs_variation_swatches_form();
        jQuery(document.body).trigger('tawcvs_initialized');
      }

      jQuery('.variations_form').each(function () {
        jQuery(this).wc_variation_form();
      });
    }
  }

}

class ShopProduct {
  constructor() {
    var _this = this;

    _this._SidebarShopMobile();

    _this._removeProductCategory();

    jQuery(document.body).on('ajax_sidebar_shop_mobile', () => {
      _this._SidebarShopMobile();
    });
  }

  _SidebarShopMobile() {
    let btn_filter = jQuery("#button-filter-btn"),
        btn_close = jQuery("#filter-close,.close-side-widget");
    btn_filter.on("click", function (e) {
      jQuery('.filter-btn-wrapper').addClass('active');
      jQuery("body").addClass('filter-mobile-active');
    });
    btn_close.on("click", function (e) {
      jQuery('.filter-btn-wrapper').removeClass('active');
      jQuery("body").removeClass('filter-mobile-active');
    });
  }

  _removeProductCategory() {
    let category = jQuery('.archive-shop .display-products .product-category');
    if (category.length === 0) return;
    category.remove();
  }

}

class SingleProduct {
  constructor() {
    var _this = this;

    _this._intStickyMenuBar();

    _this._intNavImage();

    _this._intReviewPopup();

    _this._intShareMobile();

    _this._intTabsMobile();

    _this._initBuyNow();

    _this._initChangeImageVarible();

    _this._initOpenAttributeMobile();

    _this._initCloseAttributeMobile();

    _this._initCloseAttributeMobileWrapper();

    _this._initAddToCartClickMobile();

    _this._initBuyNowwClickMobile();

    _this._initAjaxSingleCart();

    jQuery(document.body).on('tbay_quick_view', () => {
      _this._initBuyNow();

      _this._initAjaxSingleCart();
    });
  }

  _intStickyMenuBar() {
    if (jQuery('#sticky-custom-add-to-cart').length === 0) return;
    jQuery('body').on('click', '#sticky-custom-add-to-cart', function (event) {
      jQuery('#shop-now .single_add_to_cart_button').click();
      event.stopPropagation();
    });
  }

  _intNavImage() {
    jQuery(window).scroll(function () {
      let isActive = jQuery(this).scrollTop() > 400;
      jQuery('.product-nav').toggleClass('active', isActive);
    });
  }

  _intReviewPopup() {
    if (jQuery('#list-review-images').length === 0) return;
    var container = [];
    jQuery('#list-review-images').find('.review-item').each(function () {
      var $link = jQuery(this).find('.review-link'),
          item = {
        src: $link.attr('href'),
        w: $link.data('width'),
        h: $link.data('height'),
        title: $link.children('.caption').html()
      };
      container.push(item);
    });
    jQuery('#list-review-images > ul> li a').off('click').on('click', function (event) {
      event.preventDefault();
      var $pswp = jQuery('.pswp')[0],
          options = {
        index: jQuery(this).parents('.review-item').index(),
        showHideOpacity: true,
        closeOnVerticalDrag: false,
        mainClass: 'pswp-review-images'
      };
      var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
      gallery.init();
      event.stopPropagation();
    });
  }

  _intShareMobile() {
    let share = jQuery('.woo-share-mobile'),
        close = jQuery('.image-mains .show-mobile .woo-share-mobile .share-content .share-header .share-close i');
    share.find('button').click(function () {
      jQuery(event.target).parents('.woo-share-mobile').toggleClass("open");
      jQuery('body').toggleClass("overflow-y");
    });
    let win_share = jQuery(window);
    let forcusshare = jQuery('.woo-share-mobile button, .woo-share-mobile button i, .woo-share-mobile .content, .woo-share-mobile .share-title, .woo-share-mobile .share-close');
    win_share.on("click.Bst", function (event) {
      if (!share.hasClass('open')) return;

      if (forcusshare.has(event.target).length == 0 && !forcusshare.is(event.target)) {
        share.removeClass("open");
        jQuery('body').removeClass("overflow-y");
      }
    });
    close.on("click", function () {
      share.removeClass("open");
      jQuery('body').removeClass("overflow-y");
    });
  }

  _intTabsMobile() {
    let tabs = jQuery('.woocommerce-tabs-sidebar'),
        click = tabs.find('.tabs-sidebar a'),
        close = tabs.find('.close-tab, #tab-sidebar-close'),
        body = jQuery('body'),
        sidebar = jQuery('.tabs-sidebar'),
        screen = window.matchMedia("(max-width: 1199px)");
    if (tabs.length === 0) return;
    click.on("click", function (e) {
      e.preventDefault();
      let tabid = jQuery(this).data('tabid');
      sidebar.addClass('open');
      tabs.find('.wc-tab-sidebar').removeClass('open');
      jQuery('#' + tabid).addClass('open');

      if (screen.matches) {
        body.addClass('overflow-y');
      }
    });
    close.on("click", function (e) {
      e.preventDefault();
      sidebar.removeClass('open');
      jQuery(this).closest('.woocommerce-tabs-sidebar').find('.wc-tab-sidebar').removeClass('open');

      if (screen.matches) {
        body.removeClass('overflow-y');
      }
    });
  }

  _initBuyNow() {
    if (jQuery('.tbay-buy-now').length === 0) return;
    jQuery('body').on('click', '.tbay-buy-now', function (e) {
      e.preventDefault();
      let productform = jQuery(this).closest('form.cart'),
          submit_btn = productform.find('[type="submit"]'),
          buy_now = productform.find('input[name="hara_buy_now"]'),
          is_disabled = submit_btn.is('.disabled');

      if (is_disabled) {
        submit_btn.trigger('click');
      } else {
        buy_now.val('1');
        productform.find('.single_add_to_cart_button').trigger('click');
      }
    });
    jQuery(document.body).on('check_variations', function () {
      let btn_submit = jQuery('form.variations_form').find('.single_add_to_cart_button');
      btn_submit.each(function (index) {
        let is_submit_disabled = jQuery(this).is('.disabled');

        if (is_submit_disabled) {
          jQuery(this).parent().find('.tbay-buy-now').addClass('disabled');
        } else {
          jQuery(this).parent().find('.tbay-buy-now').removeClass('disabled');
        }
      });
    });
  }

  _initFeatureVideo() {
    if (typeof hara_settings === "undefined") return;
    let featured = jQuery(document).find(hara_settings.img_class_container + '.tbay_featured_content');
    if (featured.length === 0) return;
    let featured_index = featured.index(),
        featured_gallery_thumbnail = jQuery(hara_settings.thumbnail_gallery_class_element).get(featured_index);
    jQuery(featured_gallery_thumbnail).addClass('tbay_featured_thumbnail');
  }

  _initChangeImageVarible() {
    let form = jQuery(".information form.variations_form");
    if (form.length === 0) return;
    form.on('change', function () {
      var _this = jQuery(this);

      var attribute_label = [];

      _this.find('.variations tr').each(function () {
        if (typeof jQuery(this).find('select').val() !== "undefined") {
          attribute_label.push(jQuery(this).find('select option:selected').text());
        }
      });

      _this.parent().find('.mobile-attribute-list .value').empty().append(attribute_label.join('/ '));

      if (form.find('.single_variation_wrap .single_variation').is(':empty')) {
        form.find('.mobile-infor-wrapper .infor-body').empty().append(form.parent().children('.price').html()).wrapInner('<p class="price"></p>');
      } else if (!form.find('.single_variation_wrap .single_variation .woocommerce-variation-price').is(':empty')) {
        form.find('.mobile-infor-wrapper .infor-body').empty().append(form.find('.single_variation_wrap .single_variation').html());
      } else {
        form.find('.mobile-infor-wrapper .infor-body').empty().append(form.find('.single_variation_wrap .single_variation').html());
        form.find('.mobile-infor-wrapper .infor-body .woocommerce-variation-price').empty().append(form.parent().children('.price').html()).wrapInner('<p class="price"></p>');
      }
    });
    setTimeout(function () {
      jQuery(document.body).on('reset_data', () => {
        form.find('.mobile-infor-wrapper .infor-body .woocommerce-variation-availability').empty();
        form.find('.mobile-infor-wrapper .infor-body').empty().append(form.parent().children('.price').html()).wrapInner('<p class="price"></p>');
        return;
      });
      jQuery(document.body).on('woocommerce_gallery_init_zoom', () => {
        let src_image = jQuery(".flex-control-thumbs").find('.flex-active').attr('src');
        jQuery('.mobile-infor-wrapper img').attr('src', src_image);
      });
      jQuery(document.body).on('mobile_attribute_open', () => {
        if (form.find('.single_variation_wrap .single_variation').is(':empty')) {
          form.find('.mobile-infor-wrapper .infor-body').empty().append(form.parent().children('.price').html());
        } else if (!form.find('.single_variation_wrap .single_variation .woocommerce-variation-price').is(':empty')) {
          form.find('.mobile-infor-wrapper .infor-body').empty().append(form.find('.single_variation_wrap .single_variation').html());
        } else {
          form.find('.mobile-infor-wrapper .infor-body').empty().append(form.find('.single_variation_wrap .single_variation').html());
          form.find('.mobile-infor-wrapper .infor-body .woocommerce-variation-price').empty().append(form.parent().children('.price').html()).wrapInner('<p class="price"></p>');
        }
      });
    }, 1000);
  }

  _initOpenAttributeMobile() {
    let attribute = jQuery("#attribute-open");
    if (attribute.length === 0) return;
    attribute.off().on('click', function () {
      jQuery(this).parent().parent().find('form.cart').addClass('open open-btn-all');
    });
  }

  _initAddToCartClickMobile() {
    let addtocart = jQuery("#tbay-click-addtocart");
    if (addtocart.length === 0) return;
    addtocart.off().on('click', function () {
      jQuery(this).parent().parent().find('form.cart').addClass('open open-btn-addtocart');
    });
  }

  _initBuyNowwClickMobile() {
    let buy_now = jQuery("#tbay-click-buy-now");
    if (buy_now.length === 0) return;
    buy_now.off().on('click', function () {
      jQuery(this).parent().parent().find('form.cart').addClass('open open-btn-buynow');
    });
  }

  _initCloseAttributeMobile() {
    let close = jQuery("#mobile-close-infor");
    if (close.length === 0) return;
    close.off().on('click', function () {
      jQuery(this).parents('form.cart').removeClass('open');

      if (jQuery(this).parents('form.cart').hasClass('open-btn-all')) {
        jQuery(this).parents('form.cart').removeClass('open-btn-all');
      }

      if (jQuery(this).parents('form.cart').hasClass('open-btn-buynow')) {
        jQuery(this).parents('form.cart').removeClass('open-btn-buynow');
      }

      if (jQuery(this).parents('form.cart').hasClass('open-btn-addtocart')) {
        jQuery(this).parents('form.cart').removeClass('open-btn-addtocart');
      }
    });
  }

  _initCloseAttributeMobileWrapper() {
    let close = jQuery("#mobile-close-infor-wrapper");
    if (close.length === 0) return;
    close.off().on('click', function () {
      jQuery(this).parent().find('form.cart').removeClass('open');

      if (jQuery(this).parent().find('form.cart').hasClass('open-btn-all')) {
        jQuery(this).parent().find('form.cart').removeClass('open-btn-all');
      }

      if (jQuery(this).parent().find('form.cart').hasClass('open-btn-buynow')) {
        jQuery(this).parent().find('form.cart').removeClass('open-btn-buynow');
      }

      if (jQuery(this).parent().find('form.cart').hasClass('open-btn-addtocart')) {
        jQuery(this).parent().find('form.cart').removeClass('open-btn-addtocart');
      }
    });
  }

  _initAjaxSingleCart() {
    var _this = this;

    if (jQuery('#shop-now').length > 0 && !jQuery('#shop-now').hasClass('ajax-single-cart')) return;
    jQuery('body').on('click', 'form.cart .single_add_to_cart_button', function () {
      if (jQuery(this).closest('form.cart').find('input[name="hara_buy_now"]').length > 0 && jQuery(this).closest('form.cart').find('input[name="hara_buy_now"]').val() === "1") return;

      var flag_adding = true,
          _this2 = jQuery(this),
          form = jQuery(_this2).parents('form.cart');

      jQuery('body').trigger('hara_before_click_single_add_to_cart', [form]);
      let enable_ajax = jQuery(form).find('input[name="hara-enable-addtocart-ajax"]');

      if (jQuery(enable_ajax).length <= 0 || jQuery(enable_ajax).val() !== '1') {
        flag_adding = false;
        return;
      } else {
        let disabled = jQuery(_this2).hasClass('disabled') || jQuery(_this2).hasClass('hara-ct-disabled') ? true : false,
            product_id = !disabled ? jQuery(form).find('input[name="data-product_id"]').val() : false;

        if (product_id && !jQuery(_this2).hasClass('loading')) {
          let type = jQuery(form).find('input[name="data-type"]').val(),
              quantity = jQuery(form).find('.quantity input[name="quantity"]').val(),
              variation_id = jQuery(form).find('input[name="variation_id"]').length ? parseInt(jQuery(form).find('input[name="variation_id"]').val()) : 0,
              variation = {};

          if (type === 'variable' && !variation_id) {
            flag_adding = false;
            return false;
          } else {
            if (variation_id > 0 && jQuery(form).find('.variations').length) {
              jQuery(form).find('.variations').find('select').each(function () {
                variation[jQuery(this).attr('name')] = jQuery(this).val();
              });
            }
          }

          if (flag_adding) {
            _this._callAjaxSingleCart(_this2, product_id, quantity, type, variation_id, variation);
          }
        }

        return false;
      }
    });
  }

  _callAjaxSingleCart(_this, product_id, quantity, type, variation_id, variation) {
    var form = jQuery(_this).parents('form.cart');
    if (type === 'grouped') return;

    if (typeof hara_settings !== 'undefined' && typeof hara_settings.wc_ajax_url !== 'undefined') {
      var urlAjax = hara_settings.wc_ajax_url.toString().replace('%%endpoint%%', 'hara_single_add_to_cart');
      var data = {
        product_id: product_id,
        quantity: quantity,
        product_type: type,
        variation_id: variation_id,
        variation: variation
      };

      if (jQuery(form).length > 0) {
        if (type === 'simple') {
          jQuery(form).find('.hara-custom-fields').append('<input type="hidden" name="add-to-cart" value="' + product_id + '" />');
        }

        data = jQuery(form).serializeArray();
        jQuery(form).find('.hara-custom-fields [name="add-to-cart"]').remove();
      }

      jQuery.ajax({
        url: urlAjax,
        type: 'post',
        dataType: 'json',
        cache: false,
        data: data,
        beforeSend: function () {
          jQuery(_this).removeClass('added');
          jQuery(_this).removeClass('hara-added');
          jQuery(_this).addClass('loading');
        },
        success: function (res) {
          if (!res.error) {
            if (typeof res.redirect !== 'undefined' && res.redirect) {
              window.location.href = res.redirect;
            } else {
              var fragments = res.fragments;

              if (fragments) {
                jQuery.each(fragments, function (key, value) {
                  jQuery(key).addClass('updating');
                  jQuery(key).replaceWith(value);
                });

                if (!jQuery(_this).hasClass('added')) {
                  jQuery(_this).addClass('added');
                }

                if (!jQuery(_this).hasClass('hara-added')) {
                  jQuery(_this).addClass('hara-added');
                }
              }

              jQuery(document.body).trigger('added_to_cart', [res.fragments, res.cart_hash, _this]);
              jQuery("#mobile-close-infor-wrapper").trigger("click");
            }
          } else {
            jQuery(_this).removeClass('loading');
          }
        }
      });
    }

    return false;
  }

}

class ProductTabs {
  constructor() {
    if (typeof hara_settings === "undefined") return;

    this._initProductTabs();
  }

  _initProductTabs() {
    var process = false;
    jQuery('.tbay-element-product-tabs.ajax-active').each(function () {
      var $this = jQuery(this);
      $this.find('.product-tabs-title li a').off('click').on('click', function (e) {
        e.preventDefault();
        var $this = jQuery(this),
            atts = $this.parent().parent().data('atts'),
            value = $this.data('value'),
            id = $this.attr('data-bs-target');
            $this.index();

        if (process || jQuery(id).hasClass('active-content')) {
          return;
        }

        process = true;
        var urlAjax = hara_settings.wc_ajax_url.toString().replace('%%endpoint%%', 'hara_products_tab_shortcode');
        jQuery.ajax({
          url: urlAjax,
          data: {
            atts: atts,
            value: value,
            security: hara_settings.wp_productstabnonce
          },
          dataType: 'json',
          method: 'POST',
          beforeSend: function (xhr) {
            jQuery(id).parent().addClass('load-ajax');
          },
          success: function (response) {
            if (response.success === true) {
              jQuery(id).html(response.data.html);
              jQuery(id).parent().find('.current').removeClass('current');
              jQuery(id).parent().removeClass('load-ajax');
              jQuery(id).addClass('active-content');
              jQuery(id).addClass('current');
              jQuery(document.body).trigger('tbay_carousel_slick');
              jQuery(document.body).trigger('tbay_ajax_tabs_products');
            } else {
              console.log('loading html products tab ajax returns wrong data');
            }
          },
          error: function () {
            console.log('ajax error');
          },
          complete: function () {
            process = false;
          }
        });
      });
    });
  }

}

class ProductCategoriesTabs {
  constructor() {
    if (typeof hara_settings === "undefined") return;

    this._initProductCategoriesTabs();
  }

  _initProductCategoriesTabs() {
    var process = false;
    jQuery('.tbay-element-product-categories-tabs.ajax-active').each(function () {
      var $this = jQuery(this);
      $this.find('.product-categories-tabs-title li a').off('click').on('click', function (e) {
        e.preventDefault();
        var $this = jQuery(this),
            atts = $this.parent().parent().data('atts'),
            value = $this.data('value'),
            id = $this.attr('data-bs-target');
            $this.index();

        if (process || jQuery(id).hasClass('active-content')) {
          return;
        }

        process = true;
        var urlAjax = hara_settings.wc_ajax_url.toString().replace('%%endpoint%%', 'hara_products_categories_tab_shortcode');
        jQuery.ajax({
          url: urlAjax,
          data: {
            atts: atts,
            value: value,
            security: hara_settings.wp_productscategoriestabnonce
          },
          dataType: 'json',
          method: 'POST',
          beforeSend: function (xhr) {
            jQuery(id).parent().addClass('load-ajax');
          },
          success: function (response) {
            if (response.success === true) {
              jQuery(id).html(response.data.html);
              jQuery(id).parent().find('.current').removeClass('current');
              jQuery(id).parent().removeClass('load-ajax');
              jQuery(id).addClass('active-content');
              jQuery(id).addClass('current');
              jQuery(document.body).trigger('tbay_carousel_slick');
              jQuery(document.body).trigger('tbay_ajax_tabs_products');
            } else {
              console.log('loading html products categories tab ajax returns wrong data');
            }
          },
          error: function () {
            console.log('ajax error');
          },
          complete: function () {
            process = false;
          }
        });
      });
    });
  }

}

jQuery(document).ready(() => {
  var product_item = new ProductItem();

  product_item._initSwatches();

  product_item._initQuantityMode();

  jQuery(document.body).trigger('tawcvs_initialized');
  new AjaxCart(), new WishList(), new Cart(), new Checkout(), new WooCommon(), new QuickView(), new StickyBar(), new DisplayMode(), new ShopProduct(), new AjaxFilter(), new SingleProduct(), new ProductTabs(), new ProductCategoriesTabs();
});
setTimeout(function () {
  jQuery(document.body).on('wc_fragments_refreshed wc_fragments_loaded removed_from_cart updated_checkout', function () {
    var product_item = new ProductItem();

    product_item._initAddButtonQuantity();

    var cart = new Cart();

    cart._init_shipping_free_notification();
  });
}, 30);
jQuery(document).ready(function ($) {
  var singleproduct = new SingleProduct();

  singleproduct._initFeatureVideo();
});

var AddButtonQuantity = function ($scope, $) {
  var product_item = new ProductItem();

  product_item._initAddButtonQuantity();
};

jQuery(window).on('elementor/frontend/init', function () {
  if (elementorFrontend.isEditMode() && typeof hara_settings !== "undefined" && Array.isArray(hara_settings.elements_ready.products)) {
    jQuery.each(hara_settings.elements_ready.products, function (index, value) {
      elementorFrontend.hooks.addAction('frontend/element_ready/tbay-' + value + '.default', AddButtonQuantity);
    });
  }
});

var AjaxProductTabs = function ($scope, $) {
  new ProductTabs(), new ProductCategoriesTabs();
};

jQuery(window).on('elementor/frontend/init', function () {
  if (elementorFrontend.isEditMode() && typeof hara_settings !== "undefined" && elementorFrontend.isEditMode() && Array.isArray(hara_settings.elements_ready.ajax_tabs)) {
    jQuery.each(hara_settings.elements_ready.ajax_tabs, function (index, value) {
      elementorFrontend.hooks.addAction('frontend/element_ready/tbay-' + value + '.default', AjaxProductTabs);
    });
  }
});
