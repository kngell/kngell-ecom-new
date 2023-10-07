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
    this.Call_controller = parameters.Call_controller;
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
    plugin.Call_controller({ ...data, ...ajax_params }, manageR);
    function manageR(response, fields) {
      plugin._manageResponse(response, fields);
    }
  };
  _manageResponse = (response) => {
    const plugin = this;
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
      if (plugin.frm.find(".alertErr").length != 0) {
        plugin.frm.find(".alertErr").html(response.msg);
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
    if (response.msg.selectedOptions.hasOwnProperty("select_field")) {
      const value = response.msg.selectedOptions.select_field[field];
      if (Object.keys(value).length !== 0) {
        let select = plugin.mainfrm.find("#" + field);
        if (select.prop("multiple")) {
          let selected = Array();
          let options = Array();
          value.forEach(function (item, i) {
            selected.push(item.id);
            options.push(new Option(item.text, item.id, false, true));
            if (!select.find("option[value='" + item.id + "']").length) {
              select
                .append(new Option(item.text, item.id, false, true))
                .trigger("change");
            }
          });
          select.val(selected).trigger("change");
        } else {
          plugin._fill_select_options(select, value);
        }
      }
    }
  };
  _fill_select_options = (select, value) => {
    if (!select.find("option[value='" + value.id + "']").length) {
      select
        .append(new Option(value.text, value.id, false, true))
        .trigger("change");
    }
    select.val(value.id).trigger("change");
  };
  _fetch_media_field = async (response, field) => {
    const plugin = this;
    let dz = plugin.dropzone.up;
    if (response.msg.items[field] && dz != null) {
      dz.files = [];
      $.each(response.msg.items[field], (key, value) => {
        dz._urlToObject(value).then((file) => {
          dz._handleDrop([file], dz._createInputFile());
        });
      });
    }
    return dz;
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
