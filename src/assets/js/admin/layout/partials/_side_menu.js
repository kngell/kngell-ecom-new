// import AppConfig from "corejs/app_config.js";
class SideBarMenu {
  constructor() {
    this.body = $("body");
    this.sidebarBody = $(".sidebar-body");
    this.sidebarWrapper = $(".sidebar-wrapper");
  }
  _init = () => {
    this.menuItems = document.querySelectorAll(".dropdown-toggle");
    this.global_bar = document.querySelector(".sidebar-toggle-btn");
    this.grid = document.querySelector(".grid-container");
    this._addActiveMenuOnLeftSidebar();
    return this;
  };

  _addActiveMenuOnLeftSidebar = () => {
    $(".sidebar a").each(function () {
      let pageUrl = window.location.href.split(/[?#]/)[0];
      if (this.href == pageUrl) {
        $(this).addClass("active");
        $(this).parent().addClass("active"); // add active to li of the current link
        $(this).parent().parent().addClass("show");
        $(this)
          .parent()
          .parent()
          .prev()
          .addClass("active") // add active class to an anchor
          .removeClass("collapsed");
        $(this).parent().parent().parent().addClass("active");
        $(this).parent().parent().parent().parent().addClass("show"); // add active to li of the current link
      }
    });
  };
  _toogle_grid = () => {
    const plugin = this;
    plugin.global_bar.addEventListener("click", (e) => {
      plugin.grid.classList.toggle("grid-close");
    });
  };
  _toogle_menu = () => {
    const plugin = this;
    plugin.menuItems.forEach((a) => {
      $(a).on("click", function () {
        $(this)
          .toggleClass("collapsed")
          .next(".list-item__submenu")
          .toggleClass("show");
      });
    });
  };
}
export default new SideBarMenu()._init();
