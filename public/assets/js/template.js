(function ($) {
  'use strict';
  $(function () {
    var body = $('body');
    var contentWrapper = $('.content-wrapper');
    var scroller = $('.container-scroller');
    var footer = $('.footer');
    var sidebar = $('.sidebar');

    //Add active class to nav-link based on url dynamically
    //Active class can be hard coded directly in html file also as required

    function addActiveClass(element) {
      if (current === "") {
        //for root url
        if (element.attr('href').indexOf("index.html") !== -1) {
          element.parents('.nav-item').last().addClass('active');
          if (element.parents('.sub-menu').length) {
            element.closest('.collapse').addClass('show');
            element.addClass('active');
          }
        }
      } else {
        //for other url
        console.log(element.attr('href'));
        console.log(current);
        if (element.attr('href') === location.pathname) {
          element.parents('.nav-item').last().addClass('active');
          if (element.parents('.sub-menu').length) {
            element.closest('.collapse').addClass('show');
            element.addClass('active');
          }
          if (element.parents('.submenu-item').length) {
            element.addClass('active');
          }
        }
      }
    }

    var current = location.pathname.split("/").slice(-1)[0].replace(/^\/|\/$/g, '');
    $('.nav li a', sidebar).each(function () {
      var $this = $(this);
      addActiveClass($this);
    })

    $('.horizontal-menu .nav li a').each(function () {
      var $this = $(this);
      addActiveClass($this);
    })

    //Close other submenu in sidebar on opening any

    sidebar.on('show.bs.collapse', '.collapse', function () {
      sidebar.find('.collapse.show').collapse('hide');
    });


    //Change sidebar and content-wrapper height
    applyStyles();

    function applyStyles() {
      //Applying perfect scrollbar
      if (!body.hasClass("rtl")) {
        if ($('.settings-panel .tab-content .tab-pane.scroll-wrapper').length) {
          const settingsPanelScroll = new PerfectScrollbar('.settings-panel .tab-content .tab-pane.scroll-wrapper');
        }
        if ($('.chats').length) {
          const chatsScroll = new PerfectScrollbar('.chats');
        }
        if (body.hasClass("sidebar-fixed")) {
          if ($('#sidebar').length) {
            var fixedSidebarScroll = new PerfectScrollbar('#sidebar .nav');
          }
        }
      }
    }

    $('[data-bs-toggle="minimize"]').on("click", function () {
      if ((body.hasClass('sidebar-toggle-display')) || (body.hasClass('sidebar-absolute'))) {
        body.toggleClass('sidebar-hidden');
      } else {
        body.toggleClass('sidebar-icon-only');
      }
    });

    //checkbox and radios
    $(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

    //Horizontal menu in mobile
    $('[data-toggle="horizontal-menu-toggle"]').on("click", function () {
      $(".horizontal-menu .bottom-navbar").toggleClass("header-toggled");
    });
    // Horizontal menu navigation in mobile menu on click
    var navItemClicked = $('.horizontal-menu .page-navigation >.nav-item');
    navItemClicked.on("click", function (event) {
      if (window.matchMedia('(max-width: 991px)').matches) {
        if (!($(this).hasClass('show-submenu'))) {
          navItemClicked.removeClass('show-submenu');
        }
        $(this).toggleClass('show-submenu');
      }
    })

    $(window).scroll(function () {
      if (window.matchMedia('(min-width: 992px)').matches) {
        var header = $('.horizontal-menu');
        if ($(window).scrollTop() >= 70) {
          $(header).addClass('fixed-on-scroll');
        } else {
          $(header).removeClass('fixed-on-scroll');
        }
      }
    });
    if ($("#datepicker-popup").length) {
      $('#datepicker-popup').datepicker({
        enableOnReadonly: true,
        todayHighlight: true,
      });
      $("#datepicker-popup").datepicker("setDate", "0");
    }

  });

  //check all boxes in order status 
  $("#check-all").click(function () {
    $(".form-check-input").prop('checked', $(this).prop('checked'));
  });

  // focus input when clicking on search icon
  $('#navbar-search-icon').click(function () {
    $("#navbar-search-input").focus();
  });

  $(window).scroll(function () {
    var scroll = $(window).scrollTop();

    //>=, not <=
    if (scroll >= 97) {
      //clearHeader, not clearheader - caps H
      $(".fixed-top").addClass("headerLight");
    }
    else {
      $(".fixed-top").removeClass("headerLight");
    }
  }); //missing );

  // Empêche l'installation de la PWA sur desktop
  function isDesktop() {
    return !/android|iphone|ipad|ipod|mobile/i.test(navigator.userAgent);
  }
  window.addEventListener('beforeinstallprompt', function (e) {
    if (isDesktop()) {
      e.preventDefault();
      return false;
    }
    // Sinon, laisse le prompt s'afficher sur mobile
  });

})(jQuery);

// quand le dom est chargé
document.addEventListener('DOMContentLoaded', function () {
  // Responsive header - only initialize if header elements exist
  const contentHeader = document.querySelector('.content_header_bottom');
  if (contentHeader) {
    const btnToggleHeader = contentHeader.querySelector('.btn_toggle_header');
    if (btnToggleHeader) {
      const meniLi = contentHeader.querySelectorAll('.nav-item-custome');
      btnToggleHeader.addEventListener('click', function () {
        contentHeader.classList.toggle('active');
        document.querySelector('html').classList.toggle('no-scroll');

        meniLi.forEach(item => {
          item.classList.remove('nav-item');
        });
      });
    } else {
      console.warn("Toggle header button not found, skipping header toggle initialization");
    }

    const sousMenuLinks = document.querySelectorAll('.sous-menu a');
    if (sousMenuLinks.length > 0) {
      sousMenuLinks.forEach(item => {
        item.addEventListener('click', function (e) {
          contentHeader.classList.remove('active');
          document.querySelector('html').classList.remove('no-scroll');
        });
      });
    }

    // accordeon mobile
    const btnCaret = contentHeader.querySelectorAll('.btn_caret');

    console.log("btnCaret", btnCaret.length);

    function closeOtherMenus(currentMenu) {
      const allActiveMenus = contentHeader.querySelectorAll('.sous-menu.active');
      allActiveMenus.forEach(menu => {
        if (menu !== currentMenu) {
          const parentLi = menu.closest('.nav-item-custome');
          const caretBtn = parentLi ? parentLi.querySelector('.btn_caret') : null;
          const icon = caretBtn ? caretBtn.querySelector('i') : null;

          if (icon) {
            icon.classList.remove('fa-caret-up');
            icon.classList.add('fa-caret-down');
          }

          menu.classList.remove('active');
        }
      });
    }

    btnCaret.forEach((el) => {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const parentLi = this.closest('.nav-item-custome');
        const sousMenu = parentLi ? parentLi.querySelector('.sous-menu') : null;

        console.log("Sous menu trouvé :", sousMenu);

        if (sousMenu) {
          if (sousMenu.classList.contains('active')) {
            sousMenu.classList.remove('active');

            const icon = this.querySelector('i');
            if (icon) {
              icon.classList.remove('fa-caret-up');
              icon.classList.add('fa-caret-down');
            }
          }
          else {
            closeOtherMenus(sousMenu);
            sousMenu.classList.add('active');

            const icon = this.querySelector('i');
            if (icon) {
              icon.classList.remove('fa-caret-down');
              icon.classList.add('fa-caret-up');
            }
          }
        }
      });
    });

    document.addEventListener('click', function (e) {
      if (!e.target.closest('.content_header_bottom') && !e.target.closest('.btn_toggle_header')) {
        contentHeader.classList.remove("active");
        document.querySelector('html').classList.remove('no-scroll');
      }
      if (!e.target.closest('.nav-item-custome')) {

        const allActiveMenus = contentHeader.querySelectorAll('.sous-menu.active');
        allActiveMenus.forEach(menu => {
          menu.classList.remove('active');
          const parentLi = menu.closest('.nav-item-custome');
          const caretBtn = parentLi ? parentLi.querySelector('.btn_caret') : null;
          const icon = caretBtn ? caretBtn.querySelector('i') : null;

          if (icon) {
            icon.classList.remove('fa-caret-up');
            icon.classList.add('fa-caret-down');
          }
        });

      }
    });
  } else {
    console.warn("Content header element not found, skipping header initialization");
  }
});
