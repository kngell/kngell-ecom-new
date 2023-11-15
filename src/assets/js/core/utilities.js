class Utilities {
  constructor() {}
  _init = () => {
    return this;
  };

  _child_by_class = (el, className) => {
    var eleChild = el.childNodes;

    for (let i = 0; i < eleChild.length; i++) {
      console.log(eleChild[i]);
      if (eleChild[i] && eleChild[i].classList.contains(className)) {
        return eleChild[i];
      }
    }
  };
}
export default new Utilities()._init();
