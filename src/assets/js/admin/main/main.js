import home from "js/admin/home/home_page";
import layout from "js/admin/layout/layout";
// import "overlayscrollbars/overlayscrollbars.css";
// import { OverlayScrollbars } from "overlayscrollbars";
class Main {
  constructor(el = {}) {
    this.element = el;
  }
  _init = () => {
    this.scrollbar = document.querySelector(".sidebar-body");
    return this;
  };
  _setupEvents = () => {
    const plugin = this;
    layout._handle();
    home._display();
    // OverlayScrollbars(plugin.scrollbar, {
    //   overflow: {
    //     x: "hidden",
    //   },
    // });
  };
}
document.addEventListener("DOMContentLoaded", function () {
  new Main()._init()._setupEvents();
});
