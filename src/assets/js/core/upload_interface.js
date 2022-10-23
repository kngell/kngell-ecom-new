import { isEmpty } from "lodash";

export default class Upload {
  constructor(dz) {
    this.files = [];
    this.element = dz;
  }
  /**
   * Upload Files
   * ================================================================
   * @returns
   */
  _upload = () => {
    let plugin = this;
    const active = () => plugin.element.addClass("drag-over");
    const inactive = () => plugin.element.removeClass("drag-over");
    plugin.element.on("dragenter dragover dragleave drop", function (e) {
      e.preventDefault();
    });
    plugin.element.on("dragenter dragover", () => {
      active();
      return false;
    });
    plugin.element.on("dragleave drop", () => {
      inactive();
      return false;
    });
    plugin.element.on("click", () => {
      plugin._manageInputFile();
    });
    plugin.element.on("drop", (e) => {
      plugin.element.find(".button").prop("disabled", true).addClass("disable");
      let files = e.originalEvent.dataTransfer.files;
      let inputEl = plugin._createInputFile();
      plugin._handleDrop(files, inputEl);
    });
    return plugin;
  };
  /**
   * Creeate Input
   * =======================================================================
   */
  _createInputFile = () => {
    const plugin = this;
    let inputEL = plugin.element.find("input[type='file']");
    if (!inputEL.length) {
      inputEL = $("<input/>", {
        type: "file",
        name: "files[]",
        multiple: "multiple",
      }).css("display", "none");
    }
    return inputEL;
  };
  /**
   * Manage Input
   * =======================================================================
   */
  _manageInputFile = () => {
    const plugin = this;
    let inputEl = plugin._createInputFile();
    inputEl.click();
    inputEl.on("change", function (e) {
      let files = e.originalEvent.path[0].files;
      plugin._handleDrop(files, inputEl);
    });
  };
  /**
   * Make File list
   * @param {*} files
   * @returns
   */
  _makeFileList = (files) => {
    const reducer = (dataTransfer, file) => {
      dataTransfer.items.add(file);
      return dataTransfer;
    };
    return files.reduce(reducer, new DataTransfer()).files;
  };
  /**
   * Handle Drop Event
   * ======================================================================
   * @param {*} files
   */
  _handleDrop = (files, inputEl = null) => {
    let plugin = this;
    if (files.length != 0) {
      files = plugin._filter_files(files);
      plugin.element.addClass("drag-enter");
      if (files instanceof Array) {
        let gallery_item;
        for (let i = 0; i < files.length; i++) {
          gallery_item = plugin._createGallery(URL.createObjectURL(files[i]));
          plugin._createExtraDiv(files[i], gallery_item);
          plugin.element.find(".gallery").append(gallery_item);
          plugin.files.push(files[i]);
        }
        plugin._update_inputFiles(inputEl, plugin.files);
        plugin.element.on("click", ".gallery_item", function (e) {
          e.stopPropagation();
        });
        plugin._removeFiles();
        plugin.element.parent().append(inputEl);
      }
    }
    if (plugin.files.length == 0) {
      plugin.element.find(".message").show();
    } else {
      plugin.element.find(".message").hide();
    }
  };
  _update_inputFiles = (inputEl, files) => {
    const plugin = this;
    const input = plugin.element.parent().find("input[type='file']");
    if (input.length > 0) {
      input.remove();
    }
    inputEl.prop("files", plugin._makeFileList(files));
  };
  /**
   * Filter Files (no duplicate)
   * =================================================================
   * @param {*} files
   * @returns
   */
  _filter_files = (files) => {
    const plugin = this;
    let f;
    let x;
    let result = [];
    let z = 0;
    for (let i = 0; i < files.length; i++) {
      x = 0;
      f = [];
      plugin.files.forEach((p_file) => {
        if (p_file.name == files[i].name && p_file.size == files[i].size) {
          f[x] = files[i];
          x++;
        }
      });
      if (isEmpty(f)) {
        result[z] = files[i];
        z++;
      }
    }
    return result;
  };

  /**
   * Create Gallery Items
   * ========================================================================
   * @param {*} value
   * @returns
   */
  _createGallery = (value) => {
    var gallery_item = $("<div /> </div>").addClass("gallery_item");
    var img_remove = $("<div></div>").addClass("remove_item").text("Remove");
    var img_item = $("<div></div>").addClass("img_item");
    var img = $("<img />", {
      src: value,
    });
    img_item.append(img);
    return gallery_item.append(img_item, img_remove);
  };
  /**
   * Create Files actions
   * =========================================================================
   * @param {*} file
   * @param {*} gallery_item
   */
  _createExtraDiv = (file, gallery_item) => {
    gallery_item.append(
      $("<div></div>")
        .text(file.name)
        .css("display", "none")
        .addClass("file_name")
    );
    gallery_item.append(
      $("<div></div>")
        .text(file.size)
        .css("display", "none")
        .addClass("file_size")
    );
  };
  //=======================================================================
  //Show file array
  //=======================================================================
  _showFile = async (url) => {
    let myHeaders = new Headers();
    myHeaders.append("Content-Type", "image/jpeg");
    // myHeaders.append("Content-Length", content.length.toString());
    myHeaders.append("Access-Control-Allow-Origin", "*");
    var myInit = {
      method: "POST",
      headers: myHeaders,
      mode: "cors",
      cache: "default",
    };
    let response = await fetch(url, myInit);
    let data = await response.blob();
    return new File([data], url.split(/[\\/]/).pop());
  };

  //=======================================================================
  //Remove File
  //=======================================================================
  _removeFiles = () => {
    const plugin = this;
    plugin.element.find(".remove_item").on("click", function (e) {
      e.stopPropagation();
      let gallery_item = $(this).parents(".gallery_item");
      gallery_item.remove();
      if (plugin.element.find(".gallery").children().length == 0) {
        plugin.element.find(".message").show();
        plugin.element.parent().find("input[type='file']").remove();
        plugin.element.removeClass("drag-enter");
        plugin.element
          .find(".button.btn-highlight")
          .prop("disabled", false)
          .removeClass("disable");
      }
      let file_name = gallery_item.find(".file_name").text();
      let file_size = gallery_item.find(".file_size").text();
      plugin.files = plugin.files.filter((file) => {
        return file.name != file_name && file.size != file_size;
      });
    });
  };
  _manageErrors = (err_msg = "") => {
    let plugin = this;
    var error_div = $("<div></div>", { class: "error_message" });
    if (err_msg != "") {
      error_div.html(err_msg);
      plugin.element.append(error_div);
    }
  };
  _removeErrMsg = () => {
    let plugin = this;
    plugin.element.find(".error_message").on("click", function (e) {
      e.stopPropagation();
      $(this).remove();
    });
  };
  _clear = () => {
    const plugin = this;
    plugin.files = [];
  };
}
