import { select2AjaxParams } from "corejs/form_crud";
import "select2";
export default class Upload {
  constructor(params = {}) {
    this.params = params;
  }

  //=======================================================================
  //Manage select tag
  //=======================================================================
  _init = (params = {}) => {
    let plugin = this;
    var data = {};
    for (let [key, value] of Object.entries(params)) {
      if (!(value instanceof Object)) {
        if (key == "tbl_options") {
          key = "table";
        }
        data[`${key}`] = `${value}`;
      }
    }
    data["data_type"] = "select2";
    this.params.hasOwnProperty("parentID")
      ? (data.parentID = this.params.parentID)
      : "";
    let select = params.element
      .select2({
        placeholder: params.placeholder + " ---",
        // minimumInputLength: 3,
        // maximumSelectionLength: 2,
        maximumInputLength: 20,
        // minimumResultsForSearch: Infinity,
        language: "fr",
        tags: true,
        // tokenSeparators: [";", "\n", "\t"],
        allowClear: true,
        width: "100%",
        ajax: select2AjaxParams(data),
        theme: "bootstrap-5",
        selectionCssClass: "select2--normal",
        dropdownCssClass: "select2--normal",
        dropdownParent: params.element.parent(),
        multiple: !params.hasOwnProperty("multiple") ? false : params.multiple,
      })
      .on("select2:close", function () {
        $(this)
          .removeClass("is-invalid")
          .parent()
          .find(".invalid-feedback")
          .html("");
      });

    plugin.select = select;
    return plugin;
  };
  _destroy = () => {
    const plugin = this;
    if (this.select.hasClass("select2-hidden-accessible")) {
      this.select.select2("destroy");
    }
    return plugin;
  };
}
