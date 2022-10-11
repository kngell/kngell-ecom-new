import side_bar from "./partials/_side_bar";
import top_bar from "./partials/_top_bar";
import sidebar_menu from "./partials/_side_menu";
class Layout {
  _init = () => {
    return this;
  };
  _handle = () => {
    side_bar._sideBarToogle();
    top_bar._account_menu_toogle();
    sidebar_menu._toogle_grid();
    sidebar_menu._toogle_menu();
  };
}
export default new Layout()._init();
