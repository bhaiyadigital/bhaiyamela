/**
jquery-inertiaScroll (FIXED & MODERNIZED)
*/

'use strict';

(function($){
  $.fn.inertiaScroll = function(options){

    var settings = $.extend({
      parent: '',
      childDelta1: 0.02,
      childDelta2: 0.1,
      parentDelta: 0.08
    }, options);

    var $window = $(window);
    var $body = $('body');
    var $parent = settings.parent;
    var $child = this;

    var ChildBox = function(elm, offset, speed, margin){
      this.elm = elm;
      this.offset = offset !== undefined ? offset : 0;
      this.speed = speed !== undefined ? speed : 1;
      this.margin = margin !== undefined ? margin : 0;
    };
    ChildBox.prototype.update = function(windowOffset,offsetBottom){
      this.offset += (windowOffset * settings.childDelta1 * Number(this.speed) - this.offset) * settings.childDelta2;
      this.elm.css({transform:'translate3d(' + 0 + ',' + ( Number(this.margin) - Number(this.offset) ) + 'px ,' + 0 +')'});
    };

    var ParentBox = function(elm, offset, speed, margin){
      ChildBox.apply(this,arguments);
    };
    ParentBox.prototype = Object.create(ChildBox.prototype,{
      constructor:{ value: ParentBox }
    });
    ParentBox.prototype.update = function(windowOffset){
      this.offset += (windowOffset - this.offset) * settings.parentDelta;
      this.elm.css({transform:'translate3d(' + 0 + ',' + (-this.offset) + 'px ,' + 0 + ')'});
    };
    ParentBox.prototype.setcss = function(){
      this.elm.css({
        'width':'100%',
        'position':'fixed',
        'top': '0',
        'left': '0',
        'will-change': 'transform'
      });
    };

    var Boxes = function(){
      this.ChildBox = [];
      this.ChildBoxLength = 0;
      this.ParentBox = '';
      this.windowHeight = 0;
    };
    Boxes.prototype = {
      init:function(){
        this.createElm($child,$parent);
        this.loop();
      },
      createElm:function(child,parent){
        this.ParentBox = new ParentBox(parent,0,1);
        this.ParentBox.setcss();
        this.boxArrayLength = child.length;
        for (var i = 0; i < this.boxArrayLength; i++) {
          var e = child.eq(i);
          var speed = e.data('speed');
          var margin = e.data('margin');
          this.ChildBox.push(new ChildBox(e,0,speed,margin));
        }
      },
      smoothScroll:function(){
        var windowOffset = $window.scrollTop();
        var offsetBottom = windowOffset + this.windowHeight;
        this.ParentBox.update(windowOffset);
        for (var i = 0; i < this.boxArrayLength; i++) {
          this.ChildBox[i].update(windowOffset,offsetBottom);
        }
      },
      loop:function(){
        this.smoothScroll();
        window.requestAnimationFrame(this.loop.bind(this));
      }
    };

    // ==========================================
    // BUG FIXED HERE (Wait for window load)
    // ==========================================
    $(window).on('load', function(){
      // Set initial height
      $body.height($parent.outerHeight());
      
      var boxes = new Boxes();
      boxes.init();

      // Auto update height if content changes (Dynamic Fix)
      if (typeof ResizeObserver !== 'undefined') {
          new ResizeObserver(function() {
              $body.height($parent.outerHeight());
          }).observe($parent[0]);
      } else {
          $(window).on('resize', function(){
              $body.height($parent.outerHeight());
          });
      }
    });

    return this;

  };
})(jQuery);