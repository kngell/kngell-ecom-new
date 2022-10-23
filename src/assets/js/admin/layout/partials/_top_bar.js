class TopBar {
  constructor() {}
  _init = () => {
    this.top_bar_right = document.querySelector(".topbar__right");
    return this;
  };
  _account_menu_toogle = () => {
    const plugin = this;
    plugin.top_bar_right.addEventListener("click", (e) => {
      e.preventDefault();
    });
  };
}
export default new TopBar()._init();
