import layout from "js/admin/layout/layout";

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
  };
}
document.addEventListener("DOMContentLoaded", function () {
  new Main()._init()._setupEvents();
});
