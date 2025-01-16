(function (api) {
    'use strict';

    api.bind('ready', function () {

        api('mobile_card_and_images_use_value', function (setting) {
            var linkSettingValueToControlActiveState;

            /**
            * Update a control's active state according to the mobile_card_and_images_use_value setting's value.
            *
            * @param {api.Control} control Boxed body control.
            */
            linkSettingValueToControlActiveState = function (control) {
                var visibility = function () {
                    if ('custom' == setting.get()) {
                        control.container.slideDown(180);
                    } else {
                        control.container.slideUp(180);
                    }
                };

                // Set initial active state.
                visibility();
                // Update activate state whenever the setting is changed.
                setting.bind(visibility);
            };

            // Call linkSettingValueToControlActiveState on the border controls when they exist.
            api.control('mobile_coupon_card', linkSettingValueToControlActiveState);
            api.control('mobile_coupon_image', linkSettingValueToControlActiveState);
            api.control('mobile_product_card', linkSettingValueToControlActiveState);
            api.control('mobile_product_image', linkSettingValueToControlActiveState);
            api.control('mobile_carousel_card', linkSettingValueToControlActiveState);
            api.control('mobile_carousel_image', linkSettingValueToControlActiveState);
            api.control('mobile_store_image', linkSettingValueToControlActiveState);
        });

        api('desktop_card_and_images_use_value', function (setting) {
            var linkSettingValueToControlActiveState;

            /**
            * Update a control's active state according to the desktop_card_and_images_use_value setting's value.
            *
            * @param {api.Control} control Boxed body control.
            */
            linkSettingValueToControlActiveState = function (control) {
                var visibility = function () {
                    if ('custom' == setting.get()) {
                        control.container.slideDown(180);
                    } else {
                        control.container.slideUp(180);
                    }
                };

                // Set initial active state.
                visibility();
                // Update activate state whenever the setting is changed.
                setting.bind(visibility);
            };

            // Call linkSettingValueToControlActiveState on the border controls when they exist.
            api.control('desktop_coupon_card', linkSettingValueToControlActiveState);
            api.control('desktop_coupon_image', linkSettingValueToControlActiveState);
            api.control('desktop_product_card', linkSettingValueToControlActiveState);
            api.control('desktop_product_image', linkSettingValueToControlActiveState);
            api.control('desktop_carousel_card', linkSettingValueToControlActiveState);
            api.control('desktop_carousel_image', linkSettingValueToControlActiveState);
            api.control('desktop_store_image', linkSettingValueToControlActiveState);
        });

        api('welcome_type', function (setting) {
            var linkSettingValueToControlActiveState;

            /**
            * Update a control's active state according to the welcome_type setting's value.
            *
            * @param {api.Control} control Boxed body control.
            */
            linkSettingValueToControlActiveState = function (control) {
                var visibility = function () {
                    if ('video' == setting.get() && control.id.charAt(1) == 'p') {
                        control.container.slideDown(180);
                    } else if ('slides' == setting.get() && control.id.charAt(1) == 'l') {
                        control.container.slideDown(180);
                    } else if ('multi_slides' == setting.get() && control.id.charAt(1) == 'u') {
                        control.container.slideDown(180);
                    } else if ('banner' == setting.get() && control.id.charAt(1) == 'e') {
                        control.container.slideDown(180);
                    } else {
                        control.container.slideUp(180);
                    }
                };

                // Set initial active state.
                visibility();
                // Update activate state whenever the setting is changed.
                setting.bind(visibility);
            };

            // Call linkSettingValueToControlActiveState on the border controls when they exist.
            api.control('hp_video_url', linkSettingValueToControlActiveState);
            api.control('hp_video_search', linkSettingValueToControlActiveState);
            api.control('hp_video_title', linkSettingValueToControlActiveState);
            api.control('hp_video_title_color', linkSettingValueToControlActiveState);
            api.control('hp_video_tagline', linkSettingValueToControlActiveState);
            api.control('hp_video_tagline_color', linkSettingValueToControlActiveState);
            api.control('slide[1][0]', linkSettingValueToControlActiveState);
            api.control('slide[1][1]', linkSettingValueToControlActiveState);
            api.control('slide[2][0]', linkSettingValueToControlActiveState);
            api.control('slide[2][1]', linkSettingValueToControlActiveState);
            api.control('slide[3][0]', linkSettingValueToControlActiveState);
            api.control('slide[3][1]', linkSettingValueToControlActiveState);
            api.control('slide[4][0]', linkSettingValueToControlActiveState);
            api.control('slide[4][1]', linkSettingValueToControlActiveState);
            api.control('slide[5][0]', linkSettingValueToControlActiveState);
            api.control('slide[5][1]', linkSettingValueToControlActiveState);
            api.control('slide[6][0]', linkSettingValueToControlActiveState);
            api.control('slide[6][1]', linkSettingValueToControlActiveState);
            api.control('slide[7][0]', linkSettingValueToControlActiveState);
            api.control('slide[7][1]', linkSettingValueToControlActiveState);
            api.control('slide[8][0]', linkSettingValueToControlActiveState);
            api.control('slide[8][1]', linkSettingValueToControlActiveState);
            api.control('slide[9][0]', linkSettingValueToControlActiveState);
            api.control('slide[9][1]', linkSettingValueToControlActiveState);
            api.control('slide[10][0]', linkSettingValueToControlActiveState);
            api.control('slide[10][1]', linkSettingValueToControlActiveState);
            api.control('multi_images_per_slide', linkSettingValueToControlActiveState);
            api.control('multi_slide[1][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[1][1]', linkSettingValueToControlActiveState);
            api.control('multi_slide[2][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[2][1]', linkSettingValueToControlActiveState);
            api.control('multi_slide[3][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[3][1]', linkSettingValueToControlActiveState);
            api.control('multi_slide[4][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[4][1]', linkSettingValueToControlActiveState);
            api.control('multi_slide[5][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[5][1]', linkSettingValueToControlActiveState);
            api.control('multi_slide[6][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[6][1]', linkSettingValueToControlActiveState);
            api.control('multi_slide[7][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[7][1]', linkSettingValueToControlActiveState);
            api.control('multi_slide[8][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[8][1]', linkSettingValueToControlActiveState);
            api.control('multi_slide[9][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[9][1]', linkSettingValueToControlActiveState);
            api.control('multi_slide[10][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[10][1]', linkSettingValueToControlActiveState);
            api.control('multi_slide[11][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[11][1]', linkSettingValueToControlActiveState);
            api.control('multi_slide[12][0]', linkSettingValueToControlActiveState);
            api.control('multi_slide[12][1]', linkSettingValueToControlActiveState);
            api.control('header_image', linkSettingValueToControlActiveState);
            api.control('header_banner_search', linkSettingValueToControlActiveState);
            api.control('header_banner_title_setting', linkSettingValueToControlActiveState);
            api.control('header_banner_title_color', linkSettingValueToControlActiveState);
            api.control('header_banner_tagline_setting', linkSettingValueToControlActiveState);
            api.control('header_banner_tagline_color', linkSettingValueToControlActiveState);

        });

    });

}(wp.customize));


(function ($) {
    wp.customize('theme_option_setting', function (value) {
        value.bind(function (newPreset) {
            wp.customize.instance('header_color').set('');
        });
    });

})(jQuery);