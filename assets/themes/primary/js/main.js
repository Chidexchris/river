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

        // ============== Header Hide Click On Body Js Start ========
        $(".header-button").on("click", function () {
            $(".body-overlay").toggleClass("show");
        });

        let bodyOverlay = $(".body-overlay");

        bodyOverlay.on("click", function () {
            $(".header-button").trigger("click");
            $(this).removeClass("show");
        });
        // =============== Header Hide Click On Body Js End =========

        // ========================== Header Hide Scroll Bar Js Start =====================
        $(".navbar-toggler.header-button").on("click", function () {
            $("body").toggleClass("scroll-hide-sm");
        });

        bodyOverlay.on("click", function () {
            $("body").removeClass("scroll-hide-sm");
        });
        // ========================== Header Hide Scroll Bar Js End =====================

        // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js Start =====================
        $(".dropdown-item").on("click", function () {
            $(this).closest(".dropdown-menu").addClass("d-block");
        });
        // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js End =====================

        // ========================== Add Attribute For Bg Image Js Start =====================
        $(".bg-img").css("background-image", function () {
            return "url(" + $(this).data("background-image") + ")";
        });
        // ========================== Add Attribute For Bg Image Js End =====================

        // ========================== Add Attribute For Mask Image Js Start =====================
        $(".styled-title").each(function () {
            var maskImageUrl = $(this).data("mask-image");
            var uniqueClass = "styled-title-" + $(this).index();
        
            $(this).addClass(uniqueClass);
            $("<style>")
                .prop("type", "text/css")
                .html("." + uniqueClass + "::after { mask-image: url(" + maskImageUrl + "); }")
                .appendTo("head");
        });
        $(".pricing__card-2__top").each(function () {
            var maskImageUrl = $(this).data("mask-image");
            var uniqueClass = "pricing__card-2__top-" + $(this).index();
        
            $(this).addClass(uniqueClass);
            $("<style>")
                .prop("type", "text/css")
                .html("." + uniqueClass + "::after { mask-image: url(" + maskImageUrl + "); }")
                .appendTo("head");
        });
        $(".dashboard-card__txt").each(function () {
            var maskImageUrl = $(this).data("mask-image");
            var uniqueClass = "dashboard-card__txt-" + $(this).index();
        
            $(this).addClass(uniqueClass);
            $("<style>")
                .prop("type", "text/css")
                .html("." + uniqueClass + "::after { mask-image: url(" + maskImageUrl + "); }")
                .appendTo("head");
        });
        // ========================== Add Attribute For Mask Image Js End =====================

        // ========================== Add Class On Accordion Open Start =====================
        $('#faqAccordion .accordion-collapse').on('shown.bs.collapse', function () {
            $(this).parent('.accordion-item').addClass('opened').siblings().removeClass('opened');
        });
        // ========================== Add Class On Accordion Open End =====================

        // ================== Password Show Hide Js Start ==========
        $(".toggle-password").on("click", function () {
            $(this).toggleClass("ti-eye ti-eye-off")

            let input = $($(this).attr("id"))

            if (input.attr("type") === "password") {
                input.attr("type", "text")
            } else {
                input.attr("type", "password")
            }
        })
        // =============== Password Show Hide Js End =================

        // ========================= Slick Slider Js Start ==============
        let testimonialImgSlider = $(".testimonial-img-slider");

        if (testimonialImgSlider.length) {
            testimonialImgSlider.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                speed: 1500,
                prevArrow: '<button type="button" class="slick-prev"><i class="ti ti-arrow-narrow-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="ti ti-arrow-narrow-right"></i></button>',
                asNavFor: ".testimonial-txt-slider",
            });
        }

        let testimonialTxtSlider = $(".testimonial-txt-slider");

        if (testimonialTxtSlider.length) {
            testimonialTxtSlider.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                speed: 1500,
                arrows: false,
                fade: true,
                asNavFor: ".testimonial-img-slider",
            });
        }
        // ========================= Slick Slider Js End ===================

        // ========================= Client Slider Js Start ===============
        let partnerSlider = $(".partner__slider");

        if (partnerSlider.length) {
            partnerSlider.slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 1000,
                pauseOnHover: true,
                speed: 2000,
                dots: false,
                arrows: false,
                responsive: [
                    {
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 5,
                        },
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 4,
                        },
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 4,
                        },
                    },
                    {
                        breakpoint: 400,
                        settings: {
                            slidesToShow: 3,
                        },
                    },
                ],
            });
        }
        // ========================= Client Slider Js End ===================

        // ========================= Refer Link Copy Start ==========
        $('.refer-link__copy').on('click', function () {
            let inputElement = $('#referLink');
            inputElement.select();
            document.execCommand('copy');

            showToasts('success', 'Link has copied');
        });
        // ========================= Refer Link Copy End ==========

        // ========================= Account Setup Key Copy Start ==========
        $('.account-setup-key__copy').on('click', function () {
            let inputElement = $('#accountSetupKey');
            inputElement.select();
            document.execCommand('copy');

            showToasts('success', 'Link has copied');
        });
        // ========================= Account Setup Key Copy End ==========

                // ========================= Aos Animation Start ==========
                AOS.init({
                    once: true,
                });
                function handleDynamicChanges() {
                    AOS.refresh();
                }
                window.addEventListener('scroll', handleDynamicChanges);
                window.addEventListener('resize', handleDynamicChanges);
                // ========================= Aos Animation End ==========

        // ========================= Image Upload With Preview Start ==========
        function updatePreviewLogo(file) {
            let imgPreview = $('.image-preview');

            if (file) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;

                    imgPreview.html(img);
                    imgPreview.addClass('active');
                }

                reader.readAsDataURL(file);
            } else {
                imgPreview.html('+');
                imgPreview.removeClass('active');
            }
        }

        let imgUpload = $('#imageUpload')

        imgUpload.on('change', function () {
            updatePreviewLogo(this.files[0]);
        });

        imgUpload.on('click', '.custom-file-input-clear', function () {
            updatePreviewLogo(null);
        });
        // ========================= Image Upload With Preview End ==========

        // ========================= Service Section Hover Start ==========
        $('.service__list li').on('mouseenter', function () {
            let serviceIndex = $(this).data('service');

            $(this).addClass('active').siblings().removeClass('active');
            $('#' + serviceIndex).addClass('active').siblings().removeClass('active');
        });
        // ========================= Service Section Hover End ==========

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

        // ========================= Remaining Character Show of Textarea Start ==========
        $('#feedbackDetails').on('keyup', function () {
            let charCount = $(this).val().length;

            $('#leftCharacter').text(500 - charCount);
        });
        // ========================= Remaining Character Show of Textarea End ==========

        // ========================= Table Filter Panel Collapse Start ==========
        $('.filter-btn').on('click', function () {
            let filterForm = $(this).data('filter-form');

            $('#' + filterForm).slideToggle();
        });
        // ========================= Table Filter Panel Collapse End ==========

        // ========================= Bootstrap Dropdown as Select Start ==========
        $('.select-dropdown').each(function () {
            let buttonText = $(this).find('.dropdown-item.active').text();

            $(this).find('.dropdown-toggle').text(buttonText);
        });

        $('.select-dropdown .dropdown-item').on('click', function () {
            $(this).closest('.dropdown-menu').siblings('.dropdown-toggle').text($(this).text());
        });
        // ========================= Bootstrap Dropdown as Select End ==========

        // ========================= Odometer Counter Js End =====================
        if($(".odometer").length) {
            $(".odometer").isInViewport(function (status) {
                if (status === "entered") {
                    setTimeout(function () {
                        $(".odometer").each(function () {
                            $(this).html($(this).attr("data-count"));
                        });
                    }, 0);
                }
            });
        }
        // ========================= Odometer Counter Js End =====================

        // =============== Sidebar Menu Js Start ===============
        $('.has-sub').on('click', function () {
            if ($('.sidebar-link').hasClass('has-sub')) {
                $(this).toggleClass('show')
                $(this).siblings('.sidebar-dropdown-menu').slideToggle(300).parent('.sidebar-item')
                $(this).parent('.sidebar-item').siblings().find('.sidebar-dropdown-menu').hide(300).siblings().removeClass("show")
                $(this).parent('.sidebar-dropdown-item').siblings().children('.sidebar-link').removeClass('show')
                $(this).parent('.sidebar-dropdown-item').siblings().find('.sidebar-dropdown-menu').hide(300).siblings().removeClass("show")
            }
        })

        $(".sidebar-link").each(function () {
            let pageUrl = window.location.href.split(/[?#]/)[0];

            if (this.href === pageUrl) {
                $(this).addClass("active");
                $(this).parents(".sidebar-item").addClass("open")
            }
        });

        $(".sidebar-menu .active").parent().parents(".sidebar-dropdown-menu").show().siblings().addClass("show");

        $('.sidebar-toggler').on('click', function () {
            $('.main-sidebar').toggleClass('active');
            $('.sidebar-overlay-2').toggleClass('show');
        });

        $('.sidebar-overlay-2').on('click', function () {
            $(this).removeClass('show');
            $('.main-sidebar').removeClass('active');
        });
        // =============== Sidebar Menu Js End ===============

        // ========================= Modal Video Js Start =====================
        let videoBtn = $('.video-btn');

        if (videoBtn.length) videoBtn.modalVideo();
        // ========================= Modal Video Js End =====================

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

        // ========================= Nice Select Js Start ===================
        if($('select.form--control').length) {
            $('select').not('.select-2').niceSelect();
        }
        // ========================= Nice Select Js End =====================

        // ========================= Select2 Js Start ===================
        let select2Tag = $(".select-2");

        if (select2Tag.length) {
            select2Tag.each(function() {
                const $this = $(this);
                const noSearch = $this.data('search') === false;
                
                let options = {
                    containerCssClass: ":all:",
                    dropdownAutoWidth: false,
                    minimumResultsForSearch: noSearch ? Infinity : 0
                };

                if ($this.prop('multiple') === true) {
                    options.dropdownAutoWidth = true;
                    options.tags = true;
                }

                if ($this.parents('.modal').length > 0) {
                    options.dropdownParent = $this.parents('.modal');
                }

                $this.select2(options);
            });
        }
        // ========================= Select2 Js End =====================
    });
    // ==========================================
    //      End Document Ready function
    // ==========================================

    // ========================= Preloader Js Start =====================
    $(window).on("load", function () {
        $(".preloader").fadeOut();
    });
    // ========================= Preloader Js End=====================

    // ========================= Header Sticky Js Start ==============
    $(window).on("scroll", function () {
        if ($(window).scrollTop() >= 300) {
            $(".header").addClass("fixed-header");
        } else {
            $(".header").removeClass("fixed-header");
        }
    });
    // ========================= Header Sticky Js End===================

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
