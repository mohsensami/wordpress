'use strict';

(function($) {
  var woosg_timeout = null;

  $(function() {
    // ready
    // options page
    woosg_active_options();

    woosg_active_settings();

    // total price
    if ($('#product-type').val() == 'woosg') {
      woosg_change_price();
    }

    // arrange
    woosg_arrange();
  });

  $(document).on('click touch', '#woosg_search_settings_btn', function(e) {
    // open search settings popup
    e.preventDefault();

    var title = $('#woosg_search_settings').attr('data-title');

    $('#woosg_search_settings').
        dialog({
          minWidth: 540,
          title: title,
          modal: true,
          dialogClass: 'wpc-dialog',
          open: function() {
            $('.ui-widget-overlay').bind('click', function() {
              $('#woosg_search_settings').dialog('close');
            });
          },
        });
  });

  $(document).on('click touch', '#woosg_search_settings_update', function(e) {
    // save search settings
    e.preventDefault();

    $('#woosg_search_settings').addClass('woosg_search_settings_updating');

    var data = {
      action: 'woosg_update_search_settings',
      limit: $('input[name="_woosg_search_limit"]').val(),
      sku: $('select[name="_woosg_search_sku"]').val(),
      id: $('select[name="_woosg_search_id"]').val(),
      exact: $('select[name="_woosg_search_exact"]').val(),
      sentence: $('select[name="_woosg_search_sentence"]').val(),
      same: $('select[name="_woosg_search_same"]').val(),
      types: $('select[name="_woosg_search_types[]"]').val(),
    };

    $.post(ajaxurl, data, function(response) {
      $('#woosg_search_settings').removeClass('woosg_search_settings_updating');
    });
  });

  $(document).on('change', '#product-type', function() {
    woosg_active_settings();
  });

  $(document).on('change', 'select[name="_woosg_change_price"]', function() {
    woosg_active_options();
  });

  // search input
  $(document).on('keyup', '#woosg_keyword', function() {
    if ($('#woosg_keyword').val() != '') {
      $('#woosg_loading').show();
      if (woosg_timeout != null) {
        clearTimeout(woosg_timeout);
      }
      woosg_timeout = setTimeout(woosg_ajax_get_data, 300);
      return false;
    }
  });

  // actions on search result items
  $(document).on('click touch', '#woosg_results li', function() {
    $(this).children('span.remove').attr('aria-label', 'Remove').html('×');
    $('#woosg_selected ul').append($(this));
    $('#woosg_results').hide();
    $('#woosg_keyword').val('');
    woosg_get_ids();
    woosg_change_price();
    woosg_arrange();
    return false;
  });

  // change qty of each item
  $(document).on('keyup change', '#woosg_selected .qty input', function() {
    woosg_get_ids();
    woosg_change_price();
    return false;
  });

  // actions on selected items
  $(document).on('click touch', '#woosg_selected span.remove', function() {
    $(this).parent().remove();
    woosg_get_ids();
    woosg_change_price();
    return false;
  });

  // hide search result box if click outside
  $(document).on('click touch', function(e) {
    if ($(e.target).closest($('#woosg_results')).length == 0) {
      $('#woosg_results').hide();
    }
  });

  $(document).on('woosg_drag_event', function() {
    woosg_get_ids();
  });

  function woosg_arrange() {
    $('#woosg_selected ul').sortable({
      handle: '.move',
      update: function(event, ui) {
        woosg_get_ids();
      },
    });
  }

  function woosg_get_ids() {
    var listId = new Array();

    $('#woosg_selected li').each(function() {
      listId.push($(this).data('id') + '/' + $(this).find('input').val());
    });
    if (listId.length > 0) {
      $('#woosg_ids').val(listId.join(','));
    } else {
      $('#woosg_ids').val('');
    }
  }

  function woosg_active_options() {
    if ($('select[name="_woosg_change_price"]').val() == 'yes_custom') {
      $('input[name="_woosg_change_price_custom"]').show();
    } else {
      $('input[name="_woosg_change_price_custom"]').hide();
    }
  }

  function woosg_active_settings() {
    if ($('#product-type').val() == 'woosg') {
      $('li.general_tab').addClass('show_if_woosg');
      $('#general_product_data .pricing').addClass('show_if_woosg');

      $('.show_if_external').hide();
      $('.show_if_simple').show();
      $('.show_if_woosg').show();

      $('.product_data_tabs li').removeClass('active');
      $('.product_data_tabs li.woosg_tab').addClass('active');

      $('.panel-wrap .panel').hide();
      $('#woosg_settings').show();

      if ($('#woosg_optional_products').is(':checked')) {
        $('.woosg_tr_show_if_optional_products').show();
      } else {
        $('.woosg_tr_show_if_optional_products').hide();
      }

      if ($('#woosg_disable_auto_price').is(':checked')) {
        $('.woosg_tr_show_if_auto_price').hide();
      } else {
        $('.woosg_tr_show_if_auto_price').show();
      }
    } else {
      $('li.general_tab').removeClass('show_if_woosg');
      $('#general_product_data .pricing').removeClass('show_if_woosg');

      $('#_regular_price').prop('readonly', false);
      $('#_sale_price').prop('readonly', false);

      if ($('#product-type').val() != 'grouped') {
        $('.general_tab').show();
      }

      if ($('#product-type').val() == 'simple') {
        $('#_downloadable').closest('label').show();
        $('#_virtual').closest('label').show();
      }
    }
  }

  function woosg_round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
  }

  function woosg_format_money(number, places, symbol, thousand, decimal) {
    number = number || 0;
    places = !isNaN(places = Math.abs(places)) ? places : 2;
    symbol = symbol !== undefined ? symbol : '$';
    thousand = thousand || ',';
    decimal = decimal || '.';
    var negative = number < 0 ? '-' : '',
        i = parseInt(
            number = woosg_round(Math.abs(+number || 0), places).
                toFixed(places),
            10) + '',
        j = 0;
    if (i.length > 3) {
      j = i.length % 3;
    }
    return symbol + negative + (
        j ? i.substr(0, j) + thousand : ''
    ) + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) + (
        places ?
            decimal +
            woosg_round(Math.abs(number - i), places).toFixed(places).slice(2) :
            ''
    );
  }

  function woosg_change_price() {
    var total = 0;

    $('#woosg_selected li').each(function() {
      total += parseFloat($(this).data('price')) *
          parseFloat($(this).find('input').val());
    });

    total = woosg_format_money(total, woosg_vars.price_decimals, '',
        woosg_vars.price_thousand_separator,
        woosg_vars.price_decimal_separator);

    $('#woosg_regular_price').html(total);
    //$('#_regular_price').val(total).trigger('change');
  }

  function woosg_ajax_get_data() {
    // ajax search product
    woosg_timeout = null;
    var data = {
      action: 'woosg_get_search_results',
      keyword: $('#woosg_keyword').val(),
      ids: $('#woosg_ids').val(),
    };

    jQuery.post(ajaxurl, data, function(response) {
      $('#woosg_results').show();
      $('#woosg_results').html(response);
      $('#woosg_loading').hide();
    });
  }
})(jQuery);