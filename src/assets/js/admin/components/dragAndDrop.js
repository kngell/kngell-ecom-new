import uploadMedia from "corejs/upload_interface";
class DragAndDrop {
  constructor(element) {
    this.element = element;
  }
  _init = () => {
    this.up = new uploadMedia(this.element);
    return this;
  };
  _handle = () => {
    const plugin = this;
    return plugin.up._upload();
  };
  _clear = () => {
    const plugin = this;
    plugin.up._clear();
  };
}
export default new DragAndDrop($(".dragAndDrop.dropzone"))._init();
