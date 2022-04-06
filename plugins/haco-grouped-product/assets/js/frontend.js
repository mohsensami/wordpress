'use strict';

(function($) {
  $(function() {
    if (!$('.woosg-wrap').length) {
      return;
    }

    $('.woosg-wrap').each(function() {
      woosg_init($(this));
    });
  });

  $(document).on('woosq_loaded', function() {
    // WPC Smart Quick View
    woosg_init($('#woosq-popup .woosg-wrap'));
  });

  $(document).on('woovr_selected', function(e, selected, variations) {
    // WPC Variations Radio Buttons
    var $wrap = variations.closest('.woosg-wrap');
    var $product = variations.closest('.woosg-product');

    if ($product.length) {
      var _id = selected.attr('data-id');
      var _price = selected.attr('data-price');
      var _regular_price = selected.attr('data-regular-price');
      var _price_html = selected.attr('data-pricehtml');
      var _image_src = selected.attr('data-imagesrc');
      var _purchasable = selected.attr('data-purchasable');

      if (_purchasable === 'yes') {
        // change data
        $product.attr('data-id', _id);
        $product.attr('data-price', _price);
        $product.attr('data-regular-price', _regular_price);

        // change image
        $product.find('.woosg-thumb-ori').hide();
        $product.find('.woosg-thumb-new').
            html('<img src="' + _image_src + '"/>').show();

        // change price
        $product.find('.woosg-price-ori').hide();
        $product.find('.woosg-price-new').html(_price_html).show();

        // change attributes
        var attrs = {};

        $product.find('select[name^="attribute_"]').each(function() {
          var name = $(this).attr('name');

          attrs[name] = $(this).val();
        });

        $product.attr('data-attrs', JSON.stringify(attrs));
      } else {
        // reset data
        $product.attr('data-id', 0);
        $product.attr('data-price', 0);
        $product.attr('data-regular-price', 0);
        $product.attr('data-attrs', '');

        // reset image
        $product.find('.woosg-thumb-new').html('').hide();
        $product.find('.woosg-thumb-ori').show();

        // reset price
        $product.find('.woosg-price-ori').show();
        $product.find('.woosg-price-new').html('').hide();

        // reset availability
        $product.find('.woosg-availability').html('').hide();
      }
    }

    if ($wrap.length) {
      woosg_init($wrap);
    }
  });

  $(document).on('found_variation', function(e, t) {
    var $wrap = $(e['target']).closest('.woosg-wrap');
    var $products = $(e['target']).closest('.woosg-products');
    var $product = $(e['target']).closest('.woosg-product');

    if ($product.length) {
      if (t['image']['url'] && t['image']['srcset']) {
        // change image
        $product.find('.woosg-thumb-ori').hide();
        $product.find('.woosg-thumb-new').
            html('<img src="' + t['image']['url'] + '" srcset="' +
                t['image']['srcset'] + '"/>').
            show();
      }

      if (t['price_html']) {
        // change price
        $product.find('.woosg-price-ori').hide();
        $product.find('.woosg-price-new').html(t['price_html']).show();
      }

      if (t['is_purchasable']) {
        // change stock notice
        if (t['is_in_stock']) {
          $products.next('p.stock').show();
          $product.attr('data-id', t['variation_id']);
          $product.attr('data-price', t['display_price']);
          $product.attr('data-regular-price', t['display_regular_price']);

          // change add to cart button
          $product.find('.add_to_cart_button').
              attr('data-product_id', t['variation_id']).
              attr('data-product_sku', t['sku']).
              attr('data-quantity', $product.attr('data-qty')).
              removeClass('product_type_variable').
              addClass('product_type_simple ajax_add_to_cart').
              html(woosg_vars.add_to_cart);
        } else {
          $products.next('p.stock').hide();
          $product.attr('data-id', 0);
          $product.attr('data-price', 0);
          $product.attr('data-regular-price', 0);

          // reset add to cart button
          $product.find('.add_to_cart_button').
              attr('data-product_id', 0).attr('data-product_sku', '').
              removeClass('product_type_simple ajax_add_to_cart').
              addClass('product_type_variable').
              html(woosg_vars.select_options);
        }

        // change availability text
        if (t['availability_html'] && t['availability_html'] !== '') {
          $product.find('.woosg-availability').
              html(t['availability_html']).
              show();
        } else {
          $product.find('.woosg-availability').html('').hide();
        }
      }

      if (t['variation_description'] !== '') {
        $product.find('.woosg-variation-description').
            html(t['variation_description']).
            show();
      } else {
        $product.find('.woosg-variation-description').html('').hide();
      }

      // change attributes
      var attrs = {};

      $product.find('select[name^="attribute_"]').each(function() {
        var name = $(this).attr('name');

        attrs[name] = $(this).val();
      });

      $product.attr('data-attrs', JSON.stringify(attrs));

      if (woosg_vars.change_image === 'no') {
        // prevent changing the main image
        $(e['target']).closest('.variations_form').trigger('reset_image');
      }

      if ($wrap.length) {
        woosg_init($wrap);
      }
    }
  });

  $(document).on('reset_data', function(e) {
    var $wrap = $(e['target']).closest('.woosg-wrap');
    var $product = $(e['target']).closest('.woosg-product');

    if ($product.length) {
      // reset image
      $product.find('.woosg-thumb-new').html('').hide();
      $product.find('.woosg-thumb-ori').show();

      // reset price
      $product.find('.woosg-price-new').html('').hide();
      $product.find('.woosg-price-ori').show();

      // reset availability
      $product.find('.woosg-availability').html('').hide();

      // reset desc
      $product.find('.woosg-variation-description').html('').hide();

      // reset data
      $product.attr('data-id', 0);
      $product.attr('data-price', 0);
      $product.attr('data-regular-price', 0);
      $product.attr('data-attrs', '');

      // reset add to cart button
      $product.find('.add_to_cart_button').
          attr('data-product_id', 0).attr('data-product_sku', '').
          removeClass('product_type_simple ajax_add_to_cart').
          addClass('product_type_variable').
          html(woosg_vars.select_options);

      if ($wrap.length) {
        woosg_init($wrap);
      }
    }
  });

  $(document).on('click touch', '.single_add_to_cart_button', function(e) {
    var $this = $(this);

    if ($this.hasClass('woosg-disabled')) {
      e.preventDefault();
    }
  });

  $(document).on('change', '.woosg-checkbox', function() {
    var $this = $(this);
    var $wrap = $this.closest('.woosg-wrap');

    if ($this.prop('checked')) {
      $this.closest('.woosg-product').attr('data-qty', 1);
    } else {
      $this.closest('.woosg-product').attr('data-qty', 0);
    }

    woosg_init($wrap);
  });

  $(document).on('keyup change', '.woosg-qty .qty', function() {
    var $this = $(this);
    var $wrap = $this.closest('.woosg-wrap');
    var qty = parseFloat($this.val());
    var min = parseFloat($this.closest('.woosg-qty').attr('data-min'));
    var max = parseFloat($this.closest('.woosg-qty').attr('data-max'));

    if ((
        qty > 0
    ) && (
        qty < min
    )) {
      qty = min;
      $this.val(qty);
    }

    if ((
        max > 0
    ) && (
        qty > max
    )) {
      qty = max;
      $this.val(qty);
    }

    $this.closest('.woosg-product').attr('data-qty', qty);
    $this.closest('.woosg-product').
        find('.add_to_cart_button').
        attr('data-quantity', qty);

    woosg_init($wrap);
  });

  $(document).on('change', '.woosg-qty input[type=radio]', function() {
    var $this = $(this);
    var $wrap = $this.closest('.woosg-wrap');
    var qty = parseFloat($this.val());
    var min = parseFloat($this.closest('.woosg-qty').attr('data-min'));

    if ((
        qty > 0
    ) && (
        qty < min
    )) {
      qty = min;
      $this.val(qty);
    }

    $this.closest('.woosg-product').attr('data-qty', qty);

    woosg_init($wrap);
  });

  $(document).
      on('click touch', '.woosg-qty-plus, .woosg-qty-minus',
          function() {
            // get values
            var $qty = $(this).
                    closest('.woosg-qty').
                    find('.qty'),
                val = parseFloat($qty.val()),
                max = parseFloat($qty.attr('max')),
                min = parseFloat($qty.attr('min')),
                step = $qty.attr('step');

            // format values
            if (!val || val === '' || val === 'NaN') {
              val = 0;
            }

            if (max === '' || max === 'NaN') {
              max = '';
            }

            if (min === '' || min === 'NaN') {
              min = 0;
            }

            if (step === 'any' || step === '' || step === undefined ||
                parseFloat(step) === 'NaN') {
              step = 1;
            } else {
              step = parseFloat(step);
            }

            // change the value
            if ($(this).is('.woosg-qty-plus')) {
              if (max && (
                  max == val || val > max
              )) {
                $qty.val(max);
              } else {
                $qty.val((val + step).toFixed(woosg_decimal_places(step)));
              }
            } else {
              if (min && (
                  min == val || val < min
              )) {
                $qty.val(min);
              } else if (val > 0) {
                $qty.val((val - step).toFixed(woosg_decimal_places(step)));
              }
            }

            // trigger change event
            $qty.trigger('change');
          });
})(jQuery);

function woosg_init($wrap) {
  var wid = $wrap.attr('data-id');
  var is_selection = false;
  var selection_name = '';
  var is_empty = true;
  var $products = $wrap.find('.woosg-products');
  var $alert = $wrap.find('.woosg-alert');
  var $ids = jQuery('.woosg-ids-' + wid);
  var $btn = $ids.closest('form.cart').find('.single_add_to_cart_button');

  $products.find('.woosg-product').each(function() {
    var $this = jQuery(this);

    if ((
        $this.attr('data-qty') > 0
    ) && (
        $this.attr('data-id') == 0
    )) {
      is_selection = true;

      if (selection_name === '') {
        selection_name = $this.attr('data-name');
      }
    }

    if ($this.attr('data-qty') > 0) {
      is_empty = false;
    }
  });

  if (is_selection || is_empty) {
    $btn.addClass('woosg-disabled');

    if (is_selection) {
      $alert.
          html(woosg_vars.alert_selection.replace('[name]',
              '<strong>' + selection_name + '</strong>')).
          slideDown();
    } else if (is_empty) {
      $alert.html(woosg_vars.alert_empty).slideDown();
    }

    jQuery(document).
        trigger('woosg_check_ready', [false, is_selection, is_empty, $wrap]);
  } else {
    $alert.html('').slideUp();
    $btn.removeClass('woosg-disabled');

    // ready
    jQuery(document).
        trigger('woosg_check_ready', [true, is_selection, is_empty, $wrap]);
  }

  woosg_calc_price($wrap);
  woosg_save_ids($wrap);

  jQuery(document).trigger('woosg_init', [$wrap]);
}

function woosg_calc_price($wrap) {
  var total = 0;
  var total_regular = 0;
  var wid = $wrap.attr('data-id');
  var $woobt = jQuery('.woobt-wrap-' + wid);
  var $price = jQuery('.woosg-price-' + wid);
  var $products = $wrap.find('.woosg-products');
  var $total = $wrap.find('.woosg-total');

  $products.find('.woosg-product').each(function() {
    var $this = jQuery(this);

    if ((
        parseFloat($this.attr('data-price')) > 0
    ) && (
        parseFloat($this.attr('data-regular-price')) > 0
    ) && (
        parseFloat($this.attr('data-qty')) > 0
    )) {
      total += parseFloat($this.attr('data-price')) *
          parseFloat($this.attr('data-qty'));
      total_regular += parseFloat($this.attr('data-regular-price')) *
          parseFloat($this.attr('data-qty'));
    }
  });

  var total_html = woosg_price_html(total_regular, total);

  $total.html(woosg_vars.total_text + ' ' + total_html).slideDown();

  if (woosg_vars.change_price !== 'no') {
    // change the main price
    if ((woosg_vars.change_price === 'yes_custom') &&
        (woosg_vars.price_selector !== null) &&
        (woosg_vars.price_selector !== '')) {
      $price = jQuery(woosg_vars.price_selector);
    }

    $price.html(total_html);
  }

  if ($woobt.length) {
    $woobt.find('.woobt-products').attr('data-product-price-html', total_html);
    $woobt.find('.woobt-product-this').
        attr('data-price', total).
        attr('data-regular-price', total_regular);

    woobt_init($woobt);
  }

  jQuery(document).
      trigger('woosg_calc_price', [total, total_regular, total_html, $wrap]);
}

function woosg_save_ids($wrap) {
  var ids = Array();
  var wid = $wrap.attr('data-id');
  var $products = $wrap.find('.woosg-products');
  var $ids = jQuery('.woosg-ids-' + wid);

  $products.find('.woosg-product').each(function() {
    var $this = jQuery(this);
    var id = parseInt($this.attr('data-id'));
    var qty = parseFloat($this.attr('data-qty'));
    var attrs = $this.attr('data-attrs');

    if ((id > 0) && (qty > 0)) {
      if (attrs != undefined) {
        attrs = encodeURIComponent(attrs);
      } else {
        attrs = '';
      }

      ids.push(id + '/' + qty + '/' + attrs);
    }
  });

  $ids.val(ids.join(','));
  jQuery(document).trigger('woosg_save_ids', [ids, $wrap]);
}

function woosg_decimal_places(num) {
  var match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);

  if (!match) {
    return 0;
  }

  return Math.max(
      0,
      // Number of digits right of decimal point.
      (match[1] ? match[1].length : 0)
      // Adjust for scientific notation.
      - (match[2] ? +match[2] : 0));
}

function woosg_format_money(number, places, symbol, thousand, decimal) {
  number = number || 0;
  places = !isNaN(places = Math.abs(places)) ? places : 2;
  symbol = symbol !== undefined ? symbol : '$';
  thousand = thousand || ',';
  decimal = decimal || '.';

  var negative = number < 0 ? '-' : '',
      i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + '',
      j = 0;

  if (i.length > 3) {
    j = i.length % 3;
  }

  return symbol + negative + (
      j ? i.substr(0, j) + thousand : ''
  ) + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) + (
      places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : ''
  );
}

function woosg_format_price(price) {
  var price_html = '<span class="woocommerce-Price-amount amount">';
  var price_formatted = woosg_format_money(price, woosg_vars.price_decimals, '',
      woosg_vars.price_thousand_separator, woosg_vars.price_decimal_separator);

  switch (woosg_vars.price_format) {
    case '%1$s%2$s':
      //left
      price_html += '<span class="woocommerce-Price-currencySymbol">' +
          woosg_vars.currency_symbol + '</span>' + price_formatted;
      break;
    case '%1$s %2$s':
      //left with space
      price_html += '<span class="woocommerce-Price-currencySymbol">' +
          woosg_vars.currency_symbol + '</span> ' + price_formatted;
      break;
    case '%2$s%1$s':
      //right
      price_html += price_formatted +
          '<span class="woocommerce-Price-currencySymbol">' +
          woosg_vars.currency_symbol + '</span>';
      break;
    case '%2$s %1$s':
      //right with space
      price_html += price_formatted +
          ' <span class="woocommerce-Price-currencySymbol">' +
          woosg_vars.currency_symbol + '</span>';
      break;
    default:
      //default
      price_html += '<span class="woocommerce-Price-currencySymbol">' +
          woosg_vars.currency_symbol + '</span>' + price_formatted;
  }

  price_html += '</span>';

  return price_html;
}

function woosg_price_html(regular_price, sale_price) {
  var price_html = '';

  if (sale_price < regular_price) {
    price_html = '<del>' + woosg_format_price(regular_price) + '</del> <ins>' +
        woosg_format_price(sale_price) + '</ins>';
  } else {
    price_html = woosg_format_price(regular_price);
  }

  return price_html;
}