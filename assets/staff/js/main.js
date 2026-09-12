(function ($) {
    "use strict";

    // hasAttr function
    $.fn.hasAttr = function (name) {
        return this.attr(name) !== undefined;
    };

    // ==========================================
    //      Start Document Ready function
    // ==========================================
    $(function () {
        // ============== Bootstrap Tooltip Enable Start ========
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))

        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        // =============== Bootstrap Tooltip Enable End =========

        // ========================== Add Attribute For Bg Image Js Start =====================
        $(".bg-img").css("background-image", function () {
            return "url(" + $(this).data("background-image") + ")";
        });
        // ========================== Add Attribute For Bg Image Js End =====================

        // ================== Password Show Hide Js Start ==========
        $(".toggle-password").on("click", function () {
            $(this).toggleClass("ti-eye ti-eye-off");

            let input = $($(this).attr("id"));

            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        $('.show-password-btn').on('click', function () {
            let input = $(this).attr('data-input');
            let inputSelector = $('#' + input);

            if (inputSelector.attr('type') === 'password') {
                $(this).html('<i class="ti ti-eye-off"></i>');
                inputSelector.attr('type', 'text');
            } else {
                $(this).html('<i class="ti ti-eye"></i>');
                inputSelector.attr('type', 'password');
            }
        });
        // =============== Password Show Hide Js End =================

        // ========================= Change Password Modal Start ==========
        $('.modal-close').on('click', function () {
            $('.change-password-modal').removeClass('active');
        });

        $('.change-password-modal').on('click', function (e) {
            if ($(e.target).is('.change-password-modal *') === false) {
                $('.change-password-modal').removeClass('active');
            }
        });
        // ========================= Change Password Modal End ==========

        // ========================= For Th In Small Devices Start ==========
        if ($('th').length) {
            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelector('thead') ? table.querySelectorAll('thead tr th') : null;

                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    Array.from(row.querySelectorAll('td')).forEach((column, i) => {
                        if (heading && heading[i]) {
                            column.setAttribute('data-label', heading[i].innerText);
                        }
                    });
                });
            });
        }
        // ========================= For Th In Small Devices End ==========

        $('.header__search__input').on('input', function () {
            let search = $(this).val().toLowerCase();
            let search_result_pane = $('.search-list');
            $(search_result_pane).html('');

            if (search.length === 0) {
                search_result_pane.addClass('d-none');
                return;
            }

            search_result_pane.removeClass('d-none');

            // search
            let match = $('.sidebar-menu .sidebar-link:not(.has-sub)').filter(function (idx, elem) {
                return $(elem).text().trim().toLowerCase().indexOf(search) >= 0 ? elem : null;
            }).sort();

            // search not found
            if (match.length === 0) {
                $(search_result_pane).append('<li class="text--muted pl-5">No search result found.</li>');
                return;
            }

            // search found
            match.each(function (idx, elem) {
                let parent = $(elem).parents('.sidebar-item').find('.has-sub').find('.sidebar-txt').first().text();

                if (!parent) parent = `Main Menu`

                parent = `<small class="d-block">${parent}</small>`
                let item_url = $(elem).attr('href') || $(elem).data('default-url');
                let item_text = $(elem).text().replace(/(\d+)/g, '').trim();

                $(search_result_pane).append(`<li>
                                                ${parent}
                                                <a href="${item_url}" class="fw-bold text-color--3 d-block">${item_text}</a>
                                            </li>`);
            });
        });

        let len = 0;
        let clickLink = 0;
        let search = null;
        let process = false;

        $('#searchInput').on('keydown', function (e) {
            let length = $('.search-list li').length;

            if (search !== $(this).val() && process) {
                len = 0;
                clickLink = 0;
                $(`.search-list li:eq(${len}) a`).trigger('focus');
                $(`#searchInput`).trigger('focus');
            }

            //Down
            if (e.key === 'ArrowDown' && length) {
                process = true;

                if (len < clickLink && clickLink < length) len += 2;

                $(`.search-list li[class="active"]`).removeClass('active');
                $(`.search-list li a[class="text--white"]`).removeClass('text--white');
                $(`.search-list li:eq(${len}) a`).trigger('focus').addClass('text--white');
                $(`.search-list li:eq(${len})`).addClass('active');
                $(`#searchInput`).trigger('focus');
                clickLink = len;

                if (!$(`.search-list li:eq(${clickLink}) a`).length) {
                    $(`.search-list li:eq(${len})`).addClass('text--white');
                }

                len += 1;

                if (length === Math.abs(clickLink)) len = 0;
            }

            //Up
            else if (e.key === 'ArrowUp' && length) {
                process = true;

                if (len > clickLink && len !== 0) len -= 2;

                $(`.search-list li[class="active"]`).removeClass('active');
                $(`.search-list li a[class="text--white"]`).removeClass('text--white');
                $(`.search-list li:eq(${len}) a`).trigger('focus').addClass('text--white');
                $(`.search-list li:eq(${len})`).addClass('active');
                $(`#searchInput`).trigger('focus');
                clickLink = len;

                if (!$(`.search-list li:eq(${clickLink}) a`).length) {
                    $(`.search-list li:eq(${len})`).addClass('text--white');
                }

                len -= 1;

                if (length === Math.abs(clickLink)) len = 0;
            }

            //Enter
            else if (e.key === 'Enter') {
                e.preventDefault();

                if ($(`.search-list li:eq(${clickLink}) a`).length && process) {
                    $(`.search-list li:eq(${clickLink}) a`)[0].click();
                }
            }

            //Retry
            else if (e.key === 'Backspace') {
                len = 0;
                clickLink = 0;

                $(`.search-list li:eq(${len}) a`).trigger('focus');
                $(`#searchInput`).trigger('focus');
            }

            search = $(this).val();
        });

        // ========================= Overlay Scrollbar Js Start =====================
        let scrollBar = $('.scroll');

        if (scrollBar.length) {
            scrollBar.overlayScrollbars({});
        }
        // ========================= Overlay Scrollbar Js End =====================

        // ========================= Sidebar Menu Js Start =========================
        $('.has-sub').on('click', function () {
            if ($('.sidebar-link').hasClass('has-sub')) {
                $(this).toggleClass('show')
                $(this).siblings('.sidebar-dropdown-menu').slideToggle(300).parent('.sidebar-item')
                $(this).parent('.sidebar-item').siblings().find('.sidebar-dropdown-menu').hide(300).siblings().removeClass("show")
                $(this).parent('.sidebar-dropdown-item').siblings().children('.sidebar-link').removeClass('show')
                $(this).parent('.sidebar-dropdown-item').siblings().find('.sidebar-dropdown-menu').hide(300).siblings().removeClass("show")
            }
        });

        $(".sidebar-link").each(function () {
            let pageUrl = window.location.href.split(/[?#]/)[0];

            if (this.href === pageUrl) {
                $(this).addClass("active");
                $(this).parents(".sidebar-dropdown-menu").siblings('.sidebar-link').addClass("active");
            }
        });

        $(".sidebar-menu .active").parent().parents(".sidebar-dropdown-menu").show().siblings().addClass("show");

        let mainSidebar = $('.main-sidebar');

        $('.sidebar-toggler').on('click', function () {
            if ($(window).width() > 1499) {
                $('body').toggleClass('nav-collapsed');
                $('header').toggleClass('nav-collapsed')
                mainSidebar.toggleClass('collapsed');
                $('.sidebar-overlay-2').toggleClass('show');
            } else {
                mainSidebar.toggleClass('expanded');
            }
        });

        $(document).on('click', function (e) {
            if ($(e.target).is('.main-sidebar, .main-sidebar *, .sidebar-toggler, .sidebar-toggler *') === false) {
                mainSidebar.removeClass('expanded');
            }
        });

        mainSidebar.on('mouseenter', function () {
            if ($('body').hasClass('nav-collapsed')) {
                mainSidebar.removeClass('collapsed');
            }
        });

        mainSidebar.on('mouseleave', function () {
            if ($('body').hasClass('nav-collapsed')) {
                mainSidebar.addClass('collapsed');
            }
        });

        $('.sidebar-link.show').siblings('.sidebar-dropdown-menu').show();

        if (mainSidebar.length) {
            let activeLink = $('.sidebar-link.active:not(.has-sub)');
            let osViewport = $('.sidebar-menu .os-viewport');
            let menuHeight = osViewport.height();

            if (activeLink.length) {
                osViewport.animate({
                    scrollTop: activeLink.offset().top - menuHeight / 2
                }, 0);
            }
        }
        // ========================= Sidebar Menu Js End =========================

        // ========================= Nice Select Js Start ===================
        $('select').not('.select-2').niceSelect();
        // ========================= Nice Select Js End =====================

        // ========================= Select2 Js Start =====================
        let selectTwo = $(".select-2")

        selectTwo.select2({
            containerCssClass: ":all:",
            dropdownAutoWidth: true,
        });

        if ($("select").prop('multiple') === true) {
            selectTwo.select2({
                containerCssClass: ":all:",
                dropdownAutoWidth: true,
                tags: true,
            });
        }

        if (selectTwo.parents('.modal').length > 0) {
            selectTwo.each(function () {
                let modalParent = $(this).parents('.modal');

                $(this).select2({
                    containerCssClass: ":all:",
                    dropdownParent: modalParent
                });
            });
        }
        // ========================= Select2 Js End =====================

        // ========================= Image Upload With Preview Start ==========
        function updatePreviewLogo(input, file) {
            let $preview = $(input).siblings('.image-preview');

            if (file) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    $preview.html(img);
                    $preview.addClass('active');
                }

                reader.readAsDataURL(file);
            } else {
                $preview.html('<i class="ti ti-photo-up"></i>');
                $preview.removeClass('active');
            }
        }

        $('.image-upload').on('change', function () {
            updatePreviewLogo(this, this.files[0]);
            $(this).siblings('.custom-file-input-clear').removeClass('d-none');
        });

        $('.custom-file-input-clear').on('click', function () {
            let $input = $(this).siblings('.image-upload');
            $input.val('');
            $(this).addClass('d-none');
            updatePreviewLogo($input, null);
        });
        // ========================= Image Upload With Preview End ==========

        let tr_elements = $('.custom-data-table tbody tr');

        $(document).on('input', '[name=search_table]', function () {
            let search = $(this).val().toUpperCase();
            let match = tr_elements.filter(function (idx, elem) {
                return $(elem).text().trim().toUpperCase().indexOf(search) >= 0 ? elem : null;
            }).sort();

            let table_content = $('.custom-data-table tbody');

            if (match.length === 0) {
                table_content.html(`<tr><td colspan="100%" class="text-center">No data found</td></tr>`);
            } else {
                table_content.html(match);
            }
        });

        // ========================= Custom Pagination Start ==========
        $('.pagination').each(function () {
            let prevArrow = $(this).find('.page-item:first-child');
            let nextArrow = $(this).find('.page-item:last-child');

            if (prevArrow.hasAttr('aria-label', '« Previous') || prevArrow.children().hasAttr('aria-label', '« Previous')) {
                prevArrow.children().html('<i class="ti ti-chevrons-left"></i>');
            }

            if (nextArrow.hasAttr('aria-label', 'Next »') || nextArrow.children().hasAttr('aria-label', 'Next »')) {
                nextArrow.children().html('<i class="ti ti-chevrons-right"></i>');
            }
        });
        // ========================= Custom Pagination End ==========
    });
    // ==========================================
    //      End Document Ready function
    // ==========================================

    //============================ Scroll To Top Icon Js Start =========
    let btn = $(".scroll-top");

    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 300) {
            btn.addClass("show");
        } else {
            btn.removeClass("show");
        }
    });

    btn.on("click", function (e) {
        e.preventDefault();
        $("html, body").animate({scrollTop: 0}, "300");
    });
    //========================= Scroll To Top Icon Js End ======================
})(jQuery);
