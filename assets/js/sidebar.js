$(document).ready(function () {
    $('.loader').fadeOut();
});

(function ($) {
    var $shadow_film = $('.shadow-film'),
            $mobile_menu_handler = $('.mobile-menu-handler'),
            $mobile_menu = $('#mobile-menu'),
            $side_list_handler = $('.dropdown.interactive'),
            $mobile_account_options_handler = $('.mobile-account-options-handler'),
            $account_options_menu = $('#account-options-menu'),
            $db_sidemenu_handler = $('.db-side-bar-handler'),
            $dashboard_options_menu = $('#dashboard-options-menu');

    $mobile_menu_handler.on('click', {id: '#mobile-menu'}, showSideMenu);
    $mobile_menu.children('.svg-plus').on('click', {id: '#mobile-menu'}, showSideMenu);

    $mobile_account_options_handler.on('click', {id: '#account-options-menu'}, showSideMenu);
    $account_options_menu.children('.svg-plus').on('click', {id: '#account-options-menu'}, showSideMenu);

    $db_sidemenu_handler.on('click', {id: '#dashboard-options-menu'}, showSideMenu);
    $dashboard_options_menu.children('.svg-plus').on('click', {id: '#dashboard-options-menu'}, showSideMenu);

    function showSideMenu(e) {
        var $menu = $(e.data.id);

        toggleVisibility($menu);
        toggleVisibility($shadow_film);
    }

    function toggleVisibility(togglableItem) {
        if (togglableItem.hasClass('closed')) {
            togglableItem
                    .removeClass('closed')
                    .addClass('open');
        } else {
            togglableItem
                    .removeClass('open')
                    .addClass('closed');
        }
    }

    $side_list_handler
            .children('.dropdown-item.interactive')
            .on('click', toggleInnerMenu)
            .children('a').click(function (e) {
        e.preventDefault();
    });


    function toggleInnerMenu(e) {
        var $this = $(this);

        $this
                .toggleClass('active')
                .children('.inner-dropdown')
                .slideToggle(600);
    }
})(jQuery);

//New Sidebar
$.sidebarMenu = function (menu) {
    var animationSpeed = 200,
            subMenuSelector = '.sidebar-submenu';

    $(menu).on('click', 'li a', function (e) {
        var $this = $(this);
        var checkElement = $this.next();

        if (checkElement.is(subMenuSelector) && checkElement.is(':visible')) {
            checkElement.slideUp(animationSpeed, function () {
                checkElement.removeClass('menu-open');
            });
            checkElement.parent("li").removeClass("active");
        }

        //If the menu is not visible
        else if ((checkElement.is(subMenuSelector)) && (!checkElement.is(':visible'))) {
            //Get the parent menu
            var parent = $this.parents('ul').first();
            //Close all open menus within the parent
            var ul = parent.find('ul:visible').slideUp(animationSpeed);
            //Remove the menu-open class from the parent
            ul.removeClass('menu-open');
            //Get the parent li
            var parent_li = $this.parent("li");

            //Open the target menu and add the menu-open class
            checkElement.slideDown(animationSpeed, function () {
                //Add the class active to the parent li
                checkElement.addClass('menu-open');
                parent.find('li.active').removeClass('active');
                parent_li.addClass('active');
            });
        }
        //if this isn't a link, prevent the page from being redirected
        if (checkElement.is(subMenuSelector)) {
            e.preventDefault();
        }
    });
}
