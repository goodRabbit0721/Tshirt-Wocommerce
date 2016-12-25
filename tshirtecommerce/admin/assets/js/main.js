// global variables
var isIE8 = false;
var isIE9 = false;
var $windowWidth;
var $windowHeight;
var $pageArea;
//Main Function
var Main = function () {
   //function to detect explorer browser and its version
    var runInit = function () {
        if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
            var ieversion = new Number(RegExp.$1);
            if (ieversion == 8) {
                isIE8 = true;
            } else if (ieversion == 9) {
                isIE9 = true;
            }
        }
    };
    //function to adjust the template elements based on the window size
    var runElementsPosition = function () {
        $windowWidth = jQuery(window).width();
        $windowHeight = jQuery(window).height();
        $pageArea = jQuery(window).height() - jQuery('header').outerHeight() - jQuery('.footer').outerHeight();
        jQuery('.sidebar-search input').removeAttr('style').removeClass('open');
        jQuery('.sidebar-fixed .wrap-menu').css('height', $pageArea);
        runContainerHeight();
    };
    //function to adapt the Main Content height to the Main Navigation height 
    var runContainerHeight = function () {
        mainContainer = jQuery('.main-content > .container');
        mainNavigation = jQuery('.main-navigation');
        if (mainContainer.outerHeight() < mainNavigation.outerHeight()) {
            mainContainer.css('min-height', mainNavigation.outerHeight());
        } else {
            mainContainer.css('min-height', '760px');
        };
    };
    //function to activate the ToDo list, if present 
    var runToDoAction = function () {
        if (jQuery(".todo-actions").length) {
            jQuery(".todo-actions").click(function () {
                if (jQuery(this).find("i").attr("class") == "icon-check-empty") {
                    jQuery(this).find("i").removeClass("icon-check-empty").addClass("icon-check");
                    jQuery(this).parent().find("span").css({
                        opacity: .25
                    });
                    jQuery(this).parent().find(".desc").css("text-decoration", "line-through");
                } else {
                    jQuery(this).find("i").removeClass("icon-check").addClass("icon-check-empty");
                    jQuery(this).parent().find("span").css({
                        opacity: 1
                    });
                    jQuery(this).parent().find(".desc").css("text-decoration", "none");
                }
                return !1;
            });
        }
    };
    //function to activate the Tooltips, if present 
    var runTooltips = function () {
        if (jQuery(".tooltips").length) {
            jQuery('.tooltips').tooltip();
        }
    };
    //function to activate the Popovers, if present 
    var runPopovers = function () {
        if (jQuery(".popovers").length) {
            jQuery('.popovers').popover();
        }
    };
    //function to allow a button or a link to open a tab
    var runShowTab = function () {
        if (jQuery(".show-tab").length) {
            jQuery('.show-tab').bind('click', function (e) {
                e.preventDefault();
                var tabToShow = jQuery(this).attr("href");
                if (jQuery(tabToShow).length) {
                    jQuery('a[href="' + tabToShow + '"]').tab('show');
                }
            });
        };
        if (getParameterByName('tabId').length) {
            jQuery('a[href="#' + getParameterByName('tabId') + '"]').tab('show');
        }
    };
    //function to extend the default settings of the Accordion
    var runAccordionFeatures = function () {
        if (jQuery('.accordion').length) {
            jQuery('.accordion .panel-collapse').each(function () {
                if (!jQuery(this).hasClass('in')) {
                    jQuery(this).prev('.panel-heading').find('.accordion-toggle').addClass('collapsed');
                }
            });
        }
        jQuery(".accordion").collapse().height('auto');
        var lastClicked;
        
        jQuery('.accordion .accordion-toggle').bind('click', function () {
            currentTab = jQuery(this);
            jQuery('html,body').animate({
                scrollTop: currentTab.offset().top - 100
            }, 1000);
        });
    };
    //function to reduce the size of the Main Menu
    var runNavigationToggler = function () {
        jQuery('.navigation-toggler').bind('click', function () {
            if (!jQuery('body').hasClass('navigation-small')) {
                jQuery('body').addClass('navigation-small');
            } else {
                jQuery('body').removeClass('navigation-small');
            };
        });
    };
    //function to activate the panel tools
    var runModuleTools = function () {
        jQuery('.panel-tools .panel-expand').bind('click', function (e) {
            jQuery('.panel-tools a').not(this).hide();
            jQuery('body').append('<div class="full-white-backdrop"></div>');
            jQuery('.main-container').removeAttr('style');
            backdrop = jQuery('.full-white-backdrop');
            wbox = jQuery(this).parents('.panel');
            wbox.removeAttr('style');
            if (wbox.hasClass('panel-full-screen')) {
                backdrop.fadeIn(200, function () {
                    jQuery('.panel-tools a').show();
                    wbox.removeClass('panel-full-screen');
                    backdrop.fadeOut(200, function () {
                        backdrop.remove();
                    });
                });
            } else {
                jQuery('body').append('<div class="full-white-backdrop"></div>');
                backdrop.fadeIn(200, function () {
                    jQuery('.main-container').css({
                        'max-height': jQuery(window).outerHeight() - jQuery('header').outerHeight() - jQuery('.footer').outerHeight() - 100,
                        'overflow': 'hidden'
                    });
                    backdrop.fadeOut(200);
                    backdrop.remove();
                    wbox.addClass('panel-full-screen').css({
                        'max-height': jQuery(window).height(),
                        'overflow': 'auto'
                    });;
                });
            }
        });
        jQuery('.panel-tools .panel-close').bind('click', function (e) {
            jQuery(this).parents(".panel").remove();
            e.preventDefault();
        });
        jQuery('.panel-tools .panel-refresh').bind('click', function (e) {
            var el = jQuery(this).parents(".panel");
            el.block({
                overlayCSS: {
                    backgroundColor: '#fff'
                },
                message: '<img src="assets/images/loading.gif" /> Just a moment...',
                css: {
                    border: 'none',
                    color: '#333',
                    background: 'none'
                }
            });
            window.setTimeout(function () {
                el.unblock();
            }, 1000);
            e.preventDefault();
        });
        jQuery('.panel-tools .panel-collapse').bind('click', function (e) {
            e.preventDefault();
            var el = jQuery(this).parent().closest(".panel").children(".panel-body");
            if (jQuery(this).hasClass("collapses")) {
                jQuery(this).addClass("expand").removeClass("collapses");
                el.slideUp(200);
            } else {
                jQuery(this).addClass("collapses").removeClass("expand");
                el.slideDown(200);
            }
        });
    };
   //function to activate the 3rd and 4th level menus
    var runNavigationMenu = function () {
        jQuery('.main-navigation-menu li.active').addClass('open');
        jQuery('.main-navigation-menu > li a').bind('click', function () {
            if (jQuery(this).parent().children('ul').hasClass('sub-menu') && (!jQuery('body').hasClass('navigation-small') || !jQuery(this).parent().parent().hasClass('main-navigation-menu'))) {
                if (!jQuery(this).parent().hasClass('open')) {
                    jQuery(this).parent().addClass('open');
                    jQuery(this).parent().parent().children('li.open').not(jQuery(this).parent()).not(jQuery('.main-navigation-menu > li.active')).removeClass('open').children('ul').slideUp(200);
                    jQuery(this).parent().children('ul').slideDown(200, function () {
                        runContainerHeight();
                    });
                } else {
                    if (!jQuery(this).parent().hasClass('active')) {
                        jQuery(this).parent().parent().children('li.open').not(jQuery('.main-navigation-menu > li.active')).removeClass('open').children('ul').slideUp(200, function () {
                            runContainerHeight();
                        });
                    } else {
                        jQuery(this).parent().parent().children('li.open').removeClass('open').children('ul').slideUp(200, function () {
                            runContainerHeight();
                        });
                    }
                }
            }
        });
    };
    //function to activate the Go-Top button
    var runGoTop = function () {
        jQuery('.go-top').bind('click', function (e) {
            jQuery("html, body").animate({
                scrollTop: 0
            }, "slow");
            e.preventDefault();
        });
    };
    //function to avoid closing the dropdown on click 
    var runDropdownEnduring = function () {
        if (jQuery('.dropdown-menu.dropdown-enduring').length) {
            jQuery('.dropdown-menu.dropdown-enduring').click(function (event) {
                event.stopPropagation();
            });
        }
    };
    //function to return the querystring parameter with a given name.
    var getParameterByName = function (name) {
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    };
    
    //Set of functions for Style Selector
    var runStyleSelector = function () {
        jQuery('#style_selector select').each(function () {
            jQuery(this).find('option:first').attr('selected', 'selected');
        });
        jQuery('.style-toggle').bind('click', function () {
            if (jQuery(this).hasClass('open')) {
                jQuery(this).removeClass('open').addClass('close');
                jQuery('#style_selector_container').hide();
            } else {
                jQuery(this).removeClass('close').addClass('open');
                jQuery('#style_selector_container').show();
            }
        });
        setColorScheme();
        setLayoutStyle();
        setHeaderStyle();
        setFooterStyle();
        setBoxedBackgrounds();
    };
    jQuery('.drop-down-wrapper').perfectScrollbar({
        wheelSpeed: 50,
        minScrollbarLength: 20,
        wheelPropagation: true
    });
    jQuery('.navbar-tools .dropdown').on('shown.bs.dropdown', function () {
        jQuery(this).find('.drop-down-wrapper').scrollTop(0).perfectScrollbar('update');
    });
    var setColorScheme = function () {
        jQuery('.icons-color a').bind('click', function () {
            jQuery('.icons-color img').each(function () {
                jQuery(this).removeClass('active');
            });
            jQuery(this).find('img').addClass('active');
            jQuery('#skin_color').attr("href", "assets/css/theme_" + jQuery(this).attr('id') + ".css");
        });
    };
    var setBoxedBackgrounds = function () {
        jQuery('.boxed-patterns a').bind('click', function () {
            if (jQuery('body').hasClass('layout-boxed')) {
                var classes = jQuery('body').attr("class").split(" ").filter(function (item) {
                    return item.indexOf("bg_style_") === -1 ? item : "";
                });
                jQuery('body').attr("class", classes.join(" "));
                jQuery('.boxed-patterns img').each(function () {
                    jQuery(this).removeClass('active');
                });
                jQuery(this).find('img').addClass('active');
                jQuery('body').addClass(jQuery(this).attr('id'));
            } else {
                alert('Select boxed layout');
            }
        });
    };
    var setLayoutStyle = function () {
        jQuery('select[name="layout"]').change(function () {
            if (jQuery('select[name="layout"] option:selected').val() == 'boxed')
                jQuery('body').addClass('layout-boxed');
            else
                jQuery('body').removeClass('layout-boxed');
        });
    };
    var setHeaderStyle = function () {
        jQuery('select[name="header"]').change(function () {
            if (jQuery('select[name="header"] option:selected').val() == 'default')
                jQuery('body').addClass('header-default');
            else
                jQuery('body').removeClass('header-default');
        });
    };
    var setFooterStyle = function () {
        jQuery('select[name="footer"]').change(function () {
            if (jQuery('select[name="footer"] option:selected').val() == 'fixed')
                jQuery('body').addClass('footer-fixed');
            else
                jQuery('body').removeClass('footer-fixed');
        });
    };
    var debounce = function (func, threshold, execAsap) {
        var timeout;
        return function debounced() {
            var obj = this,
                args = arguments;

            function delayed() {
                if (!execAsap)
                    func.apply(obj, args);
                timeout = null;
            };
            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);
            timeout = setTimeout(delayed, threshold || 50);
        };
    };
    //Window Resize Function
    var runWIndowResize = function (func, threshold, execAsap) {
        //wait until the user is done resizing the window, then execute
        jQuery(window).resize = debounce(function (e) {
            runElementsPosition();
        }, 50, false);
        jQuery('.panel-scroll').perfectScrollbar({
            wheelSpeed: 50,
            minScrollbarLength: 20,
            wheelPropagation: true
        });
    };
    return {
        //main function to initiate template pages
        init: function () {
            runWIndowResize();
            runInit();
            runStyleSelector();
            runElementsPosition();
            runToDoAction();
            runNavigationToggler();
            runNavigationMenu();
            runGoTop();
            runModuleTools();
            runDropdownEnduring();
            runTooltips();
            runPopovers();
            runShowTab();
            runAccordionFeatures();
        }
    };
}();