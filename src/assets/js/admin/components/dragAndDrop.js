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
    plugin.up = plugin.up._upload();
    return plugin;
  };
  _get_existing_files = () => {
    const plugin = this;
    const inputEl = plugin.element.parent().find("input[type='file']");
    console.log(inputEl.prop("files"), plugin.up.files);
    plugin.up.files = inputEl.prop("files");
    return plugin;
  };
  _clear = () => {
    const plugin = this;
    plugin.up._clear();
  };
}
export default new DragAndDrop($(".dragAndDrop.dropzone"))._init();
