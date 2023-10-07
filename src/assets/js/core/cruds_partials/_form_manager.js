import input from "corejs/inputErrManager";
export default class FormManager {
  constructor(app = {}) {
    this.frm = app.frm;
    this.appConfig = app.appConfig;
    this.dropzone = app.dropzone;
    this.select = app.select;
  }
  _init = () => {
    return this;
  };
  _clean_invalid_input = () => {
    const plugin = this;
    input.removeInvalidInput(plugin.frm, (el) => {
      $("label[for='" + el.attr("id") + "']").css("top", "");
    });
  };
  _show_errors = (msgs) => {
    const plugin = this;
    input.error(plugin.frm, msgs);
  };
  _clean_on_modal_open = (modal, modalName) => {
    const plugin = this;
    modal.promise().then((m) => {
      m[modalName].selector.addEventListener("shown.bs.modal", (e) => {});
    });
  };
  _clean_on_modal_hide = (modal, modalName) => {
    const plugin = this;
    modal.promise().then((m) => {
      m[modalName].selector.addEventListener("hide.bs.modal", (e) => {
        plugin.frm.get(0).reset();
        if (plugin.frm.find(".is-invalid").length != 0) {
          input.reset_invalid_input(plugin.frm);
        }
        if (plugin.appConfig !== null) {
          const hidden = plugin.appConfig.getConfig();
          $.map(hidden, function (field, value) {
            plugin.frm.find("#" + field).remove();
          });
          plugin.appConfig.resetConfig();
        }
        if (plugin.hasOwnProperty("dropzone")) {
          $(plugin.dropzone.element)
            .find(".gallery-wrapper .gallery_item")
            .remove();
          $(plugin.dropzone.element).find(".message").show();
          plugin.dropzone._clear();
        }
        plugin.frm.find("textarea").empty();
        plugin.frm.find("input[type='checkbox']").empty();
        plugin.hasOwnProperty("profile_img")
          ? plugin.frm
              .parents(".form-wrapper")
              .find(plugin.profile_img.img)
              .attr("src", plugin.profile_img.avatar)
          : "";
        if (plugin.hasOwnProperty("select")) {
          if (Array.isArray(plugin.select)) {
            for (const [key, value] of Object.entries(plugin.select)) {
              plugin.frm
                .find("#" + key)
                .empty()
                .trigger("input");
            }
          } else {
            plugin.frm.find(plugin.select).empty().trigger("input");
          }
        }
      });
    });
  };
}
