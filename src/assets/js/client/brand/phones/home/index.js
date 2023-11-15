import { get_visitors_data } from "corejs/visitors";
class Home {
  constructor(element) {
    this.element = element;
  }
  _init = () => {
    this._setupEvents();
  };
  _setupEvents = () => {
    get_visitors_data();
  };
}
new Home($("#main-site"))._init();
