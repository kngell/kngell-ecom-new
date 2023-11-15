// SIDE BAR TOOGLE
class SideBar {
  _init = () => {
    // this.menu_icon = document.querySelector(".menu-icon");
    this.menu_icon_close_el = document.querySelector(".menu-icon-close");
    this.sidebar = document.getElementById("sidebar");
    return this;
  };
  _sideBarToogle = () => {
    const plugin = this;
    // plugin.menu_icon.addEventListener("click", function (e) {
    //   plugin.sidebar.classList.add("sidebar-responsive");
    // });
    // plugin.menu_icon_close_el.addEventListener("click", function (e) {
    //   plugin.sidebar.classList.remove("sidebar-responsive");
    // });
  };
}
export default new SideBar()._init();
