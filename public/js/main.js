(function ($) {
  "use strict";
  $(function () {
    const sidebar = $(".sidebar");

    function addActiveClass(element) {
      if (element.attr("href") === location.pathname) {
        element
          .parents(".nav-item")
          .last()
          .addClass("active");
        if (element.parents(".sub-menu").length) {
          element.closest(".collapse").addClass("show");
          element.addClass("active");
        }
        if (element.parents(".submenu-item").length) {
          element.addClass("active");
        }
      }
    }

    $(".nav li a", sidebar).each(function () {
      const $this = $(this); //eslint-disable-line
      addActiveClass($this);
    });

    //Close other submenu in sidebar on opening any
    sidebar.on("show.bs.collapse", ".collapse", function () {
      sidebar.find(".collapse.show").collapse("hide");
    });

    //checkbox and radios
    $(".form-check label,.form-radio label").append(
      '<i class="input-helper"></i>' //eslint-disable-line
    );

    //off canvas- delete if not necesite
    //eslint-disable-next-line
    $('[data-toggle="offcanvas"]').on("click", function () {
      $(".sidebar-offcanvas").toggleClass("active");
      const isFloatingSidebar = $(window).width() < 982;
      if (isFloatingSidebar) {
        $("#overlay").show(400);
      }
    });

    $('#overlay').on("click", function () {
      $(".sidebar-offcanvas").removeClass("active");
      $("#overlay").hide(400);
    });
  });
})(jQuery);
