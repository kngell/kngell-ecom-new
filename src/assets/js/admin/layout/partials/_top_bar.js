class TopBar {
  constructor() {}
  _init = () => {
    this.account_menu = document.querySelector(".profile__group__menu");
    this.user_profile = document.querySelector(".profile__group__img");
    return this;
  };
  _account_menu_toogle = () => {
    const plugin = this;
    // plugin.user_profile.addEventListener("click", function () {
    //   plugin.account_menu.classList.toggle("active");
    // });
  };
}
export default new TopBar()._init();
