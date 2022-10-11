import bar_chart from "./partials/_bar_chart";
import area_chart from "./partials/_area_chart";
import world_map from "./partials/_world_map";
class HomePage {
  constructor(element = {}) {
    this.element = element;
  }
  _init = () => {
    return this;
  };
  _display = () => {
    bar_chart._show();
    area_chart._show();
    world_map._show();
  };
}
new HomePage()._init()._display();
