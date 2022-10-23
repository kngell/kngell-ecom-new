import { Call_controller } from "corejs/form_crud";

export default class Edit {
  constructor(parameters) {
    this.wrapper = parameters.wrapper;
    this.frm = parameters.frm;
    this.frm_name = parameters.frm_name;
    this.ck_content = parameters.ck_content ?? "";
    this.loader = parameters.loader ?? null;
    this.dropzone = parameters.dropzone ?? null;
    this.id = parameters.id;
    this.mainfrm = parameters.mainfrm;
    this.appConfig = parameters.appConfig;
    this.categorieSelector = parameters.categorieSelector;
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
    };
    const ajax_params = plugin._clean_params(params);
    Call_controller({ ...data, ...ajax_params }, manageR);
    function manageR(response, fields) {
      plugin._manageResponse(response, fields);
    }
  };
  _manageResponse = (response) => {
    const plugin = this;
    console.log(response);
    if (response.result === "success") {
      $.map(response.msg.items, function (value, field) {
        switch (true) {
          case response.msg.input_hidden.includes(field):
            $("<input>")
              .attr({
                type: "hidden",
                id: field,
                value: value,
                name: field,
                class: field,
              })
              .prependTo("#" + plugin.mainfrm.attr("id"));
            break;
          case plugin.ck_content != "" && plugin.ck_content.includes(field):
            plugin._open_editor(response, field);
            break;
          case $("#" + field).hasClass("select2-hidden-accessible"):
            plugin._fetch_select2_field(response, field);
            break;
          case field == "media":
            plugin._fetch_media_field(response, field);
            break;
          case this == "profileImage":
            plugin.wrapper
              .find(".upload-box .img")
              .attr("src", IMG + response.msg.items[field]);
            break;
          default:
            plugin._fetch_all_field(response, field);
            break;
        }
      });
      plugin._fetch_categories(response, plugin.categorieSelector);
    } else {
      if (plugin.form.find(".alertErr").length != 0) {
        plugin.form.find(".alertErr").html(response.msg);
      }
    }
    if (response.msg.input_hidden !== "undefined") {
      plugin.appConfig.setConfig(response.msg.input_hidden);
    }
  };
  _fetch_categories = (response, selector) => {
    if (response.msg.selectedOptions.hasOwnProperty("categorie")) {
      if (Object.keys(response.msg.selectedOptions["categorie"]).length > 0) {
        for (const [key, categorie] of Object.entries(
          response.msg.selectedOptions["categorie"]
        )) {
          selector.find("#categorieItem" + key).val(key);
          selector.find("#categorieItem" + key).prop("checked", true);
        }
      }
    }
  };
  _fetch_select2_field = (response, field) => {
    const plugin = this;
    if (response.msg.selectedOptions.hasOwnProperty(select_field)) {
      if (response.msg.selectedOptions[field].length != 0) {
        $(response.msg.selectedOptions[field][0]).each(function () {
          let select = plugin.frm.find("." + field);
          if (!select.find("option[value='" + field.id + "']").length) {
            select.append(new Option(this.text, field.id, false, true));
            select.val(response.msg.selectedOptions[field][1]);
            select.trigger("change");
          }
        });
      }
    }
  };
  _fetch_media_field = (response, field) => {
    const plugin = this;
    let dz = plugin.dropzone; //.up._upload();
    if (response.msg.items[field] && dz != null) {
      $(dz.element).find(".message").hide();
      dz.files = [];
      $.each(response.msg.items[field], (key, value) => {
        let gallery_item = dz._createGallery(value);
        dz._showFile(value)
          .then((file) => {
            dz.files.push(file);
            dz._createExtraDiv(file, gallery_item);
          })
          .catch(function (error) {
            console.log(
              "Il y a eu un problème avec l'opération fetch: " + error.message
            );
          });
        dz.element.find(".gallery").append(gallery_item);
        dz.element.on("click", ".gallery_item", (e) => {
          e.stopPropagation();
        });
      });
      dz._removeFiles();
    }
  };
  _fetch_all_field = (response, field) => {
    if ($("#" + field).is("input")) {
      if ($("#" + field).is(":checkbox")) {
        if (response.msg.items[field] == "on") {
          $("#" + field).prop("checked", true);
        } else {
          $("#" + field).prop("checked", false);
        }
      } else {
        $("#" + field).val(response.msg.items[field]);
      }
    } else {
      $("#" + field).html(response.msg.items[field]);
    }
  };
  _open_editor = (response, field) => {
    if (plugin.hasOwnProperty("loader") && plugin.loader !== null) {
      plugin.loader.editor[this].data.set(response.msg.items[field]);
    }
  };
  _clean_params = (params) => {
    let ajax_param = {};
    const exclude = ["fields", "inputElement", "dropzone", "categorieElement"];
    for (const [k, v] of Object.entries(params)) {
      if (!exclude.includes(k)) {
        ajax_param[k] = v;
      }
    }
    return ajax_param;
  };
}
