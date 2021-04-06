/*!
 * remark v1.0.6 (http://getbootstrapadmin.com/remark)
 * Copyright 2015 amazingsurge
 * Licensed under the Themeforest Standard Licenses
 */
(function(document, window, $) {
  'use strict';

  window.App = Site.extend({
    handleSlidePanel: function() {
      if (typeof $.slidePanel === 'undefined') return;

      var defaults = $.components.getDefaults("slidePanel");
      var options = $.extend({}, defaults, {
        template: function(options) {
          return '<div class="' + options.classes.base + ' ' + options.classes.base + '-' + options.direction + '">' +
            '<div class="' + options.classes.base + '-scrollable"><div>' +
            '<div class="' + options.classes.content + '"></div>' +
            '</div></div>' +
            '<div class="' + options.classes.base + '-handler"></div>' +
            '</div>';
        },
        afterLoad: function() {
          this.$panel.find('.' + this.options.classes.base + '-scrollable').asScrollable({
            namespace: 'scrollable',
            contentSelector: '>',
            containerSelector: '>'
          });
        }
      });

      $(document).on('click', '[data-toggle=slidePanel]', function(e) {
        $.slidePanel.show({
          url: $(this).data('url'),
          settings: {
            cache: false
          }
        }, options);

        e.stopPropagation();
      });
    },
    handleMultiSelect: function() {
      var $all = $('.select-all');

      $(document).on('change', '.multi-select', function(e, isSelectAll) {
        if (isSelectAll) return;

        var $select = $('.multi-select'),
          total = $select.length,
          checked = $select.find('input:checked').length;
        if (total === checked) {
          $all.find('input').prop('checked', true);
        } else {
          $all.find('input').prop('checked', false);
        }
      });

      $all.on('change', function() {
        var checked = $(this).find('input').prop('checked');

        $('.multi-select input').each(function() {
          $(this).prop('checked', checked).trigger('change', [true]);
        });

      });
    },

    handleListActions: function() {
      $(document).on('click', '[data-toggle="list-editable"]', function() {
        var $btn = $(this),
          $list = $btn.parents('.list-group-item'),
          $content = $list.find('.list-content'),
          $editable = $list.find('.list-editable');

        $content.hide();
        $editable.show();
        $editable.find('input:first-child').focus().select();
      });

      $(document).on('keydown', '.list-editable [data-bind]', function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);

        if (keycode == 13 || keycode == 27) {
          var $input = $(this),
            bind = $input.data('bind'),
            $list = $input.parents('.list-group-item'),
            $content = $list.find('.list-content'),
            $editable = $list.find('.list-editable'),
            $update = bind ? $list.find(bind) : $list.find('.list-text');

          if (keycode == 13) {
            $update.html($input.val());
          } else {
            $input.val($update.text());
          }

          $content.show();
          $editable.hide();
        }
      });

      $(document).on('click', '[data-toggle="list-editable-close"]', function() {
        var $btn = $(this),
          $list = $btn.parents('.list-group-item'),
          $content = $list.find('.list-content'),
          $editable = $list.find('.list-editable');

        $content.show();
        $editable.hide();
      });
    },

    run: function(next) {
      this.handleSlidePanel();
      this.handleListActions();
      next();
    }
  });
})(document, window, jQuery);

$(function() {
  $(window).bind("load resize", function() {
    topOffset = 50;
    width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
    if (width < 768) {
      $('div.navbar-collapse').addClass('collapse');
      topOffset = 100; // 2-row-menu
    } else {
      $('div.navbar-collapse').removeClass('collapse');
    }

    height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
    height = height - topOffset;
    if (height < 1) height = 1;
    if (height > topOffset) {
      $("#page-wrapper").css("min-height", (height) + "px");
    }
  });

  var url = window.location;
  var element = $('ul.nav a').filter(function() {
    return this.href == url || url.href.indexOf(this.href) == 0;
  }).addClass('active').parent().parent().addClass('in').parent();
  if (element.is('li')) {
    element.addClass('active');
  }


  $.ajaxSetup({data:{csrf_token:$('meta[name="csrf-token"]').attr("content")}});

  $(document).on('click','a',function(a){if($(this).attr('href')=='#'){return false}});
  $(document).on('click','.modalfy',function(a){a.preventDefault();modalfyRun(this,$(this).attr('href'))});

  $('.delete').click(function(e) {
    e.preventDefault();
    if(confirm(LNG_ARE_YOU_SURE)) {
      $('<form method="POST" style="display:none"><input type="hidden" name="_method" value="DELETE" /><input type="hidden" name="_token" value="'+ $('meta[name="csrf-token"]').attr("content") +'"></form>')
          .insertAfter($(this))
          .attr({
            action: $(this).attr('href')
          }).submit();
    }
  });

});

function modalfyRun(e,t){$.ajax({type:"GET",url:t}).done(function(e){if(e){$("#site-modal").html(e).modal({backdrop:'static'})}else{console.log(e);bootbox.alert(lang_unable_to_exec)}}).fail(function(e,t){console.log(e);bootbox.alert(lang_unable_to_exec+t)})}
function throttle(b,a){var c=null;return function(){var e=this,d=arguments;clearTimeout(c);c=window.setTimeout(function(){b.apply(e,d)},a||500)}};

