import uploadMedia from "corejs/upload_interface";
class DragAndDrop {
  constructor(element) {
    this.element = element;
  }
  _init = () => {
    return this;
  };
  _handle = () => {
    new uploadMedia(this.element)._upload();
  };
}
export default new DragAndDrop($(".dragAndDrop.dropzone"))._init();
