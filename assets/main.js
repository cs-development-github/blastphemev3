const $ = require('jquery');
global.$ = global.jQuery = $;

require('jquery-slimscroll');

'use strict';
(function() {

    // Menu toggle for admin dashboard

    if ($("#nav-toggle").length) {
        $("#nav-toggle").on("click", function(e) {
            e.preventDefault();
            $("#db-wrapper").toggleClass("toggled");
        });

    }




    //  slimscroll for sidebar nav

    if ($(".nav-scroller").length) {
        $(".nav-scroller").slimScroll({
            height: "90%",
        });
    }



    // Notification dropdown scroll List

   if ($('.notification-list-scroll').length) {
        $(".notification-list-scroll").slimScroll({
            height: 300,
        });
    }


    // Multi level menu dropdown

    if ($(".dropdown-menu a.dropdown-toggle").length) {
        $(".dropdown-menu a.dropdown-toggle").on("click", function (e) {
            if (!$(this)
                .next()
                .hasClass("show")
            ) {
                $(this)
                    .parents(".dropdown-menu")
                    .first()
                    .find(".show")
                    .removeClass("show");
            }
            var $subMenu = $(this).next(".dropdown-menu");
            $subMenu.toggleClass("show");

            $(this)
                .parents("li.nav-item.dropdown.show")
                .on("hidden.bs.dropdown", function (e) {
                    $(".dropdown-submenu .show").removeClass("show");
                });

            return false;
        });
    }
})();
