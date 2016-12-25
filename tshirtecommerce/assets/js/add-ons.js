/* scroll */
(function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?e(require("jquery")):e(jQuery)})(function(e){"use strict";var t={wheelSpeed:10,wheelPropagation:!1,minScrollbarLength:null,useBothWheelAxes:!1,useKeyboard:!0,suppressScrollX:!1,suppressScrollY:!1,scrollXMarginOffset:0,scrollYMarginOffset:0,includePadding:!1},o=function(){var e=0;return function(){var t=e;return e+=1,".perfect-scrollbar-"+t}}();e.fn.perfectScrollbar=function(n,r){return this.each(function(){var l=e.extend(!0,{},t),s=e(this);if("object"==typeof n?e.extend(!0,l,n):r=n,"update"===r)return s.data("perfect-scrollbar-update")&&s.data("perfect-scrollbar-update")(),s;if("destroy"===r)return s.data("perfect-scrollbar-destroy")&&s.data("perfect-scrollbar-destroy")(),s;if(s.data("perfect-scrollbar"))return s.data("perfect-scrollbar");s.addClass("ps-container");var a,c,i,u,p,d,f,h,v,b,g=e("<div class='ps-scrollbar-x-rail'></div>").appendTo(s),m=e("<div class='ps-scrollbar-y-rail'></div>").appendTo(s),w=e("<div class='ps-scrollbar-x'></div>").appendTo(g),T=e("<div class='ps-scrollbar-y'></div>").appendTo(m),L=parseInt(g.css("bottom"),10),y=L===L,I=y?null:parseInt(g.css("top"),10),S=parseInt(m.css("right"),10),C=S===S,P=C?null:parseInt(m.css("left"),10),X="rtl"===s.css("direction"),Y=o(),k=function(e,t){var o=e+t,n=u-v;b=0>o?0:o>n?n:o;var r=parseInt(b*(d-u)/(u-v),10);s.scrollTop(r),y?g.css({bottom:L-r}):g.css({top:I+r})},x=function(e,t){var o=e+t,n=i-f;h=0>o?0:o>n?n:o;var r=parseInt(h*(p-i)/(i-f),10);s.scrollLeft(r),C?m.css({right:S-r}):m.css({left:P+r})},D=function(e){return l.minScrollbarLength&&(e=Math.max(e,l.minScrollbarLength)),e},M=function(){var e={width:i,display:a?"inherit":"none"};e.left=X?s.scrollLeft()+i-p:s.scrollLeft(),y?e.bottom=L-s.scrollTop():e.top=I+s.scrollTop(),g.css(e);var t={top:s.scrollTop(),height:u,display:c?"inherit":"none"};C?t.right=X?p-s.scrollLeft()-S-T.outerWidth():S-s.scrollLeft():t.left=X?s.scrollLeft()+2*i-p-P-T.outerWidth():P+s.scrollLeft(),m.css(t),w.css({left:h,width:f}),T.css({top:b,height:v})},W=function(){i=l.includePadding?s.innerWidth():s.width(),u=l.includePadding?s.innerHeight():s.height(),p=s.prop("scrollWidth"),d=s.prop("scrollHeight"),!l.suppressScrollX&&p>i+l.scrollXMarginOffset?(a=!0,f=D(parseInt(i*i/p,10)),h=parseInt(s.scrollLeft()*(i-f)/(p-i),10)):(a=!1,f=0,h=0,s.scrollLeft(0)),!l.suppressScrollY&&d>u+l.scrollYMarginOffset?(c=!0,v=D(parseInt(u*u/d,10)),b=parseInt(s.scrollTop()*(u-v)/(d-u),10)):(c=!1,v=0,b=0,s.scrollTop(0)),b>=u-v&&(b=u-v),h>=i-f&&(h=i-f),M()},j=function(){var t,o;w.bind("mousedown"+Y,function(e){o=e.pageX,t=w.position().left,g.addClass("in-scrolling"),e.stopPropagation(),e.preventDefault()}),e(document).bind("mousemove"+Y,function(e){g.hasClass("in-scrolling")&&(x(t,e.pageX-o),e.stopPropagation(),e.preventDefault())}),e(document).bind("mouseup"+Y,function(){g.hasClass("in-scrolling")&&g.removeClass("in-scrolling")}),t=o=null},E=function(){var t,o;T.bind("mousedown"+Y,function(e){o=e.pageY,t=T.position().top,m.addClass("in-scrolling"),e.stopPropagation(),e.preventDefault()}),e(document).bind("mousemove"+Y,function(e){m.hasClass("in-scrolling")&&(k(t,e.pageY-o),e.stopPropagation(),e.preventDefault())}),e(document).bind("mouseup"+Y,function(){m.hasClass("in-scrolling")&&m.removeClass("in-scrolling")}),t=o=null},O=function(e,t){var o=s.scrollTop();if(0===e){if(!c)return!1;if(0===o&&t>0||o>=d-u&&0>t)return!l.wheelPropagation}var n=s.scrollLeft();if(0===t){if(!a)return!1;if(0===n&&0>e||n>=p-i&&e>0)return!l.wheelPropagation}return!0},q=function(){l.wheelSpeed/=10;var e=!1;s.bind("mousewheel"+Y,function(t,o,n,r){var i=t.deltaX*t.deltaFactor||n,u=t.deltaY*t.deltaFactor||r;e=!1,l.useBothWheelAxes?c&&!a?(u?s.scrollTop(s.scrollTop()-u*l.wheelSpeed):s.scrollTop(s.scrollTop()+i*l.wheelSpeed),e=!0):a&&!c&&(i?s.scrollLeft(s.scrollLeft()+i*l.wheelSpeed):s.scrollLeft(s.scrollLeft()-u*l.wheelSpeed),e=!0):(s.scrollTop(s.scrollTop()-u*l.wheelSpeed),s.scrollLeft(s.scrollLeft()+i*l.wheelSpeed)),W(),e=e||O(i,u),e&&(t.stopPropagation(),t.preventDefault())}),s.bind("MozMousePixelScroll"+Y,function(t){e&&t.preventDefault()})},A=function(){var t=!1;s.bind("mouseenter"+Y,function(){t=!0}),s.bind("mouseleave"+Y,function(){t=!1});var o=!1;e(document).bind("keydown"+Y,function(n){if(t&&!e(document.activeElement).is(":input,[contenteditable]")){var r=0,l=0;switch(n.which){case 37:r=-30;break;case 38:l=30;break;case 39:r=30;break;case 40:l=-30;break;case 33:l=90;break;case 32:case 34:l=-90;break;case 35:l=-u;break;case 36:l=u;break;default:return}s.scrollTop(s.scrollTop()-l),s.scrollLeft(s.scrollLeft()+r),o=O(r,l),o&&n.preventDefault()}})},B=function(){var e=function(e){e.stopPropagation()};T.bind("click"+Y,e),m.bind("click"+Y,function(e){var t=parseInt(v/2,10),o=e.pageY-m.offset().top-t,n=u-v,r=o/n;0>r?r=0:r>1&&(r=1),s.scrollTop((d-u)*r)}),w.bind("click"+Y,e),g.bind("click"+Y,function(e){var t=parseInt(f/2,10),o=e.pageX-g.offset().left-t,n=i-f,r=o/n;0>r?r=0:r>1&&(r=1),s.scrollLeft((p-i)*r)})},F=function(){var t=function(e,t){s.scrollTop(s.scrollTop()-t),s.scrollLeft(s.scrollLeft()-e),W()},o={},n=0,r={},l=null,a=!1;e(window).bind("touchstart"+Y,function(){a=!0}),e(window).bind("touchend"+Y,function(){a=!1}),s.bind("touchstart"+Y,function(e){var t=e.originalEvent.targetTouches[0];o.pageX=t.pageX,o.pageY=t.pageY,n=(new Date).getTime(),null!==l&&clearInterval(l),e.stopPropagation()}),s.bind("touchmove"+Y,function(e){if(!a&&1===e.originalEvent.targetTouches.length){var l=e.originalEvent.targetTouches[0],s={};s.pageX=l.pageX,s.pageY=l.pageY;var c=s.pageX-o.pageX,i=s.pageY-o.pageY;t(c,i),o=s;var u=(new Date).getTime(),p=u-n;p>0&&(r.x=c/p,r.y=i/p,n=u),e.preventDefault()}}),s.bind("touchend"+Y,function(){clearInterval(l),l=setInterval(function(){return.01>Math.abs(r.x)&&.01>Math.abs(r.y)?(clearInterval(l),void 0):(t(30*r.x,30*r.y),r.x*=.8,r.y*=.8,void 0)},10)})},H=function(){s.bind("scroll"+Y,function(){W()})},K=function(){s.unbind(Y),e(window).unbind(Y),e(document).unbind(Y),s.data("perfect-scrollbar",null),s.data("perfect-scrollbar-update",null),s.data("perfect-scrollbar-destroy",null),w.remove(),T.remove(),g.remove(),m.remove(),g=m=w=T=a=c=i=u=p=d=f=h=L=y=I=v=b=S=C=P=X=Y=null},z=function(t){s.addClass("ie").addClass("ie"+t);var o=function(){var t=function(){e(this).addClass("hover")},o=function(){e(this).removeClass("hover")};s.bind("mouseenter"+Y,t).bind("mouseleave"+Y,o),g.bind("mouseenter"+Y,t).bind("mouseleave"+Y,o),m.bind("mouseenter"+Y,t).bind("mouseleave"+Y,o),w.bind("mouseenter"+Y,t).bind("mouseleave"+Y,o),T.bind("mouseenter"+Y,t).bind("mouseleave"+Y,o)},n=function(){M=function(){var e={left:h+s.scrollLeft(),width:f};y?e.bottom=L:e.top=I,w.css(e);var t={top:b+s.scrollTop(),height:v};C?t.right=S:t.left=P,T.css(t),w.hide().show(),T.hide().show()}};6===t&&(o(),n())},Q="ontouchstart"in window||window.DocumentTouch&&document instanceof window.DocumentTouch,G=function(){var e=navigator.userAgent.toLowerCase().match(/(msie) ([\w.]+)/);e&&"msie"===e[1]&&z(parseInt(e[2],10)),W(),H(),j(),E(),B(),Q&&F(),s.mousewheel&&q(),l.useKeyboard&&A(),s.data("perfect-scrollbar",s),s.data("perfect-scrollbar-update",W),s.data("perfect-scrollbar-destroy",K)};return G(),s})}});
(function ($) {

  // Detect touch support
  $.support.touch = 'ontouchend' in document;

  // Ignore browsers without touch support
  if (!$.support.touch) {
    return;
  }

  var mouseProto = $.ui.mouse.prototype,
      _mouseInit = mouseProto._mouseInit,
      touchHandled;

  /**
   * Simulate a mouse event based on a corresponding touch event
   * @param {Object} event A touch event
   * @param {String} simulatedType The corresponding mouse event
   */
  function simulateMouseEvent (event, simulatedType) {

    // Ignore multi-touch events
    if (event.originalEvent.touches.length > 1) {
      return;
    }

    event.preventDefault();

    var touch = event.originalEvent.changedTouches[0],
        simulatedEvent = document.createEvent('MouseEvents');
    
    // Initialize the simulated mouse event using the touch event's coordinates
    simulatedEvent.initMouseEvent(
      simulatedType,    // type
      true,             // bubbles                    
      true,             // cancelable                 
      window,           // view                       
      1,                // detail                     
      touch.screenX,    // screenX                    
      touch.screenY,    // screenY                    
      touch.clientX,    // clientX                    
      touch.clientY,    // clientY                    
      false,            // ctrlKey                    
      false,            // altKey                     
      false,            // shiftKey                   
      false,            // metaKey                    
      0,                // button                     
      null              // relatedTarget              
    );

    // Dispatch the simulated event to the target element
    event.target.dispatchEvent(simulatedEvent);
  }

  /**
   * Handle the jQuery UI widget's touchstart events
   * @param {Object} event The widget element's touchstart event
   */
  mouseProto._touchStart = function (event) {

    var self = this;

    // Ignore the event if another widget is already being handled
    if (touchHandled || !self._mouseCapture(event.originalEvent.changedTouches[0])) {
      return;
    }

    // Set the flag to prevent other widgets from inheriting the touch event
    touchHandled = true;

    // Track movement to determine if interaction was a click
    self._touchMoved = false;

    // Simulate the mouseover event
    simulateMouseEvent(event, 'mouseover');

    // Simulate the mousemove event
    simulateMouseEvent(event, 'mousemove');

    // Simulate the mousedown event
    simulateMouseEvent(event, 'mousedown');
  };

  /**
   * Handle the jQuery UI widget's touchmove events
   * @param {Object} event The document's touchmove event
   */
  mouseProto._touchMove = function (event) {

    // Ignore event if not handled
    if (!touchHandled) {
      return;
    }

    // Interaction was not a click
    this._touchMoved = true;

    // Simulate the mousemove event
    simulateMouseEvent(event, 'mousemove');
  };

  /**
   * Handle the jQuery UI widget's touchend events
   * @param {Object} event The document's touchend event
   */
  mouseProto._touchEnd = function (event) {

    // Ignore event if not handled
    if (!touchHandled) {
      return;
    }

    // Simulate the mouseup event
    simulateMouseEvent(event, 'mouseup');

    // Simulate the mouseout event
    simulateMouseEvent(event, 'mouseout');

    // If the touch interaction did not move, it should trigger a click
    if (!this._touchMoved) {

      // Simulate the click event
      simulateMouseEvent(event, 'click');
    }

    // Unset the flag to allow other widgets to inherit the touch event
    touchHandled = false;
  };

  /**
   * A duck punch of the $.ui.mouse _mouseInit method to support touch events.
   * This method extends the widget with bound touch event handlers that
   * translate touch events to mouse events and pass them to the widget's
   * original mouse event handling methods.
   */
  mouseProto._mouseInit = function () {
    
    var self = this;

    // Delegate the touch handlers to the widget's element
    self.element
      .bind('touchstart', $.proxy(self, '_touchStart'))
      .bind('touchmove', $.proxy(self, '_touchMove'))
      .bind('touchend', $.proxy(self, '_touchEnd'));

    // Call the original $.ui.mouse init method
    _mouseInit.call(self);
  };

})(jQuery);
var $jd = jQuery.noConflict();