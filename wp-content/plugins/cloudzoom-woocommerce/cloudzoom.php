<?php
/*
  Plugin Name: Cloud Zoom For WooCommerce (Official)
  Description: Integrates Cloud Zoom V2.0+ jQuery plugin (only from starplugins.com) into WordPress/WooCommerce. 
  Version: 1.4
  Author: Star Plugins
  Plugin URI: http://www.starplugins.com/cloudzoom
  Author URI: http://www.starplugins.com
*/

/*
 * (c)2013 Star Plugins
 */

function registerFiles() {
    wp_register_script('cloudzoom_js', plugins_url('/cloudzoom/cloudzoom.js', __FILE__), array('jquery'), '2.5.1');
    wp_register_style('cloudzoom_css', plugins_url('/cloudzoom/cloudzoom.css', __FILE__), false, '1.0.0', 'all');
}

function enqueueFiles() {
    wp_enqueue_script('cloudzoom_js');
    wp_enqueue_style('cloudzoom_css');
}
// Register Cloud Zoom JavaScript and Cloud Zoom CSS styles on initialization.
add_action('init', 'registerFiles');

// Use the registered JavaScript and CSS.
add_action('wp_enqueue_scripts', 'enqueueFiles');

// Include quickstart function into head, and
// adjust CSS to work better with default Word Press.
function quickStartAndCss() {
    $pathToBlank = plugins_url('/cloudzoom/blank.png', __FILE__);
    $pathToAjaxLoader = plugins_url('/cloudzoom/ajax-loader.gif', __FILE__);
    if (wp_script_is('cloudzoom_js', 'done')) {
        ?>
        <style>
            .cloudzoom img, img.cloudzoom {
                padding:0px !important;
                border:none !important;
            }
            .cloudzoom-blank {
                background-image:url("<?= $pathToBlank ?>");
            }
            .cloudzoom-ajax-loader {
                background-image:url("<?= $pathToAjaxLoader ?>");
            }
        </style>
     
        <script type="text/javascript">
(function ($) {
    $(function () {
        var $czImage = $('.zoom img:first'),
            mainWidth = $czImage.attr('width'),
            mainHeight = $czImage.attr('height'),
            numThumbs, lastImagePath;

        $czImage.bind('cloudzoom_ready', function () {
            lastImagePath != $czImage.attr('src')
        });

        // Modal popup image using built in Fancy Box (WooCommerce < 2.0)
        if ($.fancybox != undefined) {
            $czImage.bind('click', function () {
                var cloudZoom = $(this).data('CloudZoom'); // Get Cloud Zoom instance.
                cloudZoom.closeZoom(); // Close Cloud Zoom window.

                $.fancybox(cloudZoom.getGalleryList()); // Trigger Fancy Box to open (could pass FB options here too).
            });
        }

        // Modal popup image using built in Pretty Photo (WooCommerce >= 2.0)
        if ($.prettyPhoto != undefined) {
            $czImage.bind('click', function () {
                var cloudZoom = $(this).data('CloudZoom'); // Get Cloud Zoom instance.
                var list = cloudZoom.getGalleryList(),
                    images = [],
                    titles = []; // Arrays for prettyPhoto.
                cloudZoom.closeZoom(); // Close Cloud Zoom window.
                if (!$.isArray(list)) list = [list];       // If only one image, getGalleryList returns an object, not an array of objects.                
                for (var i = 0; i < list.length; i++) {
                    images.push(list[i].href);
                    titles.push(list[i].title);
                }               
                $.prettyPhoto.open(images, titles, titles);              
            });
        }
        // Copy title from anchor to image.
        $czImage.attr('title', $('.zoom:first').attr('title'));
        // For the main image, add an id, the cloudzoom class and override clicks to prevent default zoom.
        $czImage.attr({
            'data-cloudzoom': "",
            id: "cloudzoom-image"
        }).addClass('cloudzoom').bind('click', function () {
            return false
        });

        // This section polls (every 1/4 sec) to see if the image has been changed e.g. by
        // a variation product select, or other method.
        // Should work with 3rd party plugins that change the image, but oviously can't guanrantee this.
        lastImagePath = $czImage.attr('src');

        if ($czImage.length) {
            setInterval(function () {
                var $parentAnchor = $czImage.parent(),
                    cz = $czImage.data('CloudZoom'); // Get the Cloud Zoom instance;
                // If image change was from Cloud Zoom thumbnail, just return because
                // regular Cloud Zoom behaviour will take care of things.
                if ($czImage.attr('src').indexOf('viaCloudZoom') != -1) return;
                // If image has change via non-Cloud Zoom method, then we need to load a
                // new cloud zoom image.
                if (lastImagePath != $czImage.attr('src')) {
                    lastImagePath = $czImage.attr('src');
                    cz.loadImage($czImage.attr('src'), $parentAnchor.attr('href'));
                }
            }, 250);
        }


        numThumbs = $('.thumbnails a').length;

        // Create a thumbnail for the feature image if more than 1 image attached to product i.e. thumbnails showing.
        if (numThumbs) {
            var $last = $('.thumbnails a:last');
            var $newThumb = $last.clone(),
                $newThumbImg = $('img', $newThumb),
                path = $('.zoom').attr('href'),
                thumbPath;
            // Add thumbnail of primary image to end of thumbnail list.
            // If previous thumbnail is last one on line, then make new one first one on line.
            $newThumb.removeClass('first last');
            if( $last.hasClass('last')) $newThumb.addClass('first');
            else $newThumb.addClass('last')

            thumbPath = path.substring(0, path.lastIndexOf('.jpg')) + '-' + $newThumbImg.attr('width') + "x" + $newThumbImg.attr('height') + ".jpg";
            $newThumbImg.attr('src', thumbPath);
            $newThumb.attr({
                href: path,
                title: $czImage.attr('title')
            });

            $('.thumbnails').append($newThumb);
        }

        // For each thumbnail, set up the Cloud Zoom image and useZoom properties.
        $('.thumbnails .zoom img').each(function (index) {
            var path = $(this).attr('src');
            path = path.substring(0, path.lastIndexOf('-') + 1) + mainWidth + "x" + mainHeight + ".jpg";
            // We add the viathumb query to flag that the image change was via Cloud Zoom, and not some other JS.
            $(this).attr('data-cloudzoom', "useZoom:'#cloudzoom-image', image: '" + path + "?viaCloudZoom'").addClass('cloudzoom-gallery');
            // Copy title from anchor to image.
            $(this).attr('title', $(this).parent().attr('title'));
        });

        $.extend($.fn.CloudZoom.defaults, {
            // Add desired default Cloud Zoom properties here.
            zoomSizeMode: 'image',
            captionPosition: 'bottom'
        });

        CloudZoom.quickStart();
    })

})(jQuery);

        </script>
                    
        <?php
    }
}

add_action('wp_head', 'quickStartAndCss');
?>