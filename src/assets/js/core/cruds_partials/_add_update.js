import Swal from "sweetalert2";
import All from "./_display_items";
import { Call_controller } from "corejs/form_crud";
export default class AddUpdate {
  constructor(parameters) {
    this.frm = parameters.frm;
    this.frm_name = parameters.frm_name;
    this.submitBtn = parameters.submitBtn ?? "";
    this.modalManager = parameters.modalManager ?? false;
    this.formManager = parameters.formManager ?? false;
    this.swal = parameters.swal ?? false;
    this.datatable = parameters.datatable ?? false;
    this.table = parameters.table ?? "";
    this.dropzone = parameters.dropzone ?? null;
  }
  _init = () => {
    return this;
  };
  _execute = (params) => {
    const plugin = this;
    var data = {
      url: params.url,
      frm: plugin.frm,
      frm_name: plugin.frm_name,
      params: params,
    };
    params.hasOwnProperty("imageUrlsAry")
      ? (data.imageUrlsAry = params.imageUrlsAry)
      : "";
    plugin.hasOwnProperty("select")
      ? (data.select2 = plugin._get_select2_data(plugin.select))
      : "";
    params.hasOwnProperty("categorie")
      ? (data.categories = plugin._get_selected_categories(params.categorie))
      : "";
    params.hasOwnProperty("folder") ? (data.folder = params.folder) : "";
    params.hasOwnProperty("validator_rules")
      ? (data.validator_rules = params.validator_rules)
      : "";
    if (plugin.hasOwnProperty("dropzone")) {
      Call_controller(
        { ...data, ...{ files: plugin.dropzone.up.files } },
        manageR
      );
    } else {
      Call_controller(data, manageR);
    }
    function manageR(response, params) {
      plugin._manageResponse(response, params);
    }
  };

  _manageResponse = (response, params) => {
    const plugin = this;
    plugin.submitBtn.val("Submit");
    switch (response.result) {
      case "error-field":
        plugin.formManager._show_errors(response.msg);
        break;
      case "success":
        plugin.frm.trigger("reset");
        if (plugin.modal) {
          plugin.modal.then((m) => {
            m._open();
          });
          if (params.swal) {
            plugin._swal(response, params);
          }
        }
        if (params.prepend) {
          params.nested.prepend(response.msg);
        } else {
          if (params.prepend === false) {
            params.nested.before(response.msg);
            params.nested.hide();
          }
        }
        break;
      case "error-file":
        if (typeof params.dropzone != "undefined") {
          params.dropzone._manageErrors(response.msg);
          params.dropzone._removeErrMsg();
        } else {
          plugin.form.find(".alertErr").html(response.msg);
          plugin.form.trigger("reset");
        }
        break;
      default:
        plugin.form.find(".alertErr").html(response.msg);
        plugin.form.trigger("reset");
        break;
    }
  };
  _swal = (response, params) => {
    const plugin = this;
    Swal.fire("Success!", response.msg, "success").then(() => {
      if (plugin.datatable == true) {
        const { frm_name, frm, categorie, dropzone, ...dysplayparams } = params;
        new All(dysplayparams)._show();
      }
      if (plugin.table == "users") {
        if (plugin.hasOwnProperty("tag")) {
          plugin.tag
            .parents(".card-footer")
            .siblings(".card-body")
            .find(".img-wrapper img")
            .attr("src", response.url);
        }
      }
    });
  };
  _get_select2_data = (params) => {
    let select_data = [];
    $(params).each(function () {
      if ($("." + this).length != 0) {
        select_data[this] = Object.values($("." + this).select2("data"));
      }
    });
    return select_data;
  };
  _get_selected_categories = (selector) => {
    if (selector) {
      return selector
        .map(function (i, cat) {
          if ($(this).is(":checked")) {
            return $(this).val();
          }
        })
        .get();
    } else {
      return "";
    }
  };
}
