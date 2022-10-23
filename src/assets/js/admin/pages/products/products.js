import AddUpdate from "corejs/cruds_partials/_add_update";
import Edit from "corejs/cruds_partials/_edit";
import media_obj from "js/admin/components/dragAndDrop";
import modals_manager from "corejs/cruds_partials/_modals_manager";
import formManager from "corejs/cruds_partials/_form_manager";
import appConf from "corejs/app_config";
import select2 from "corejs/select2_manager";
class Products {
  constructor(element) {
    this.wrapper = element;
    this.selectTag = ["company", "warehouse", "shipping_class", "unit_id"];
  }
  _init = () => {
    this.modal = this.wrapper.find("#modal-box");
    this.frm = this.modal.find("#new-product-frm");
    this.submitBtn = this.frm.find("save-all");
    this.media = this.frm.find("#media");
    this.media_upload = media_obj._init();
    this.modalsManager = new modals_manager(["modal-box"])._init();
    this.formManager = new formManager({
      frm: this.frm,
      appConfig: appConf,
    })._init();
    this.add_update_params = {
      frm: this.frm,
      frm_name: this.frm.attr("id"),
      submitBtn: this.submitBtn,
      modalManager: this.modalsManager,
      formManager: this.formManager,
      swal: true,
      datatable: false,
    };
    return this;
  };
  _handle = () => {
    const plugin = this;
    let upload = plugin.media_upload._handle();
    plugin._choose_action(upload);
    plugin.formManager.dropzone = plugin.media_upload;
    plugin.add_update_params.dropzone = plugin.media_upload;
    plugin._add_update_data();
    plugin._clean_form();
    plugin._handle_select_tag();
  };
  _add_update_data = () => {
    const plugin = this;
    plugin.frm.on("submit", (e) => {
      e.preventDefault();
      plugin.media_upload._clear();
      plugin.add_update_params.modalManager = plugin.formManager;
      let add_update = new AddUpdate(plugin.add_update_params)._init();
      add_update._execute({
        url: "admin/admin_products/add",
        categorie: plugin.frm.find("#check-box-wrapper input:checked"),
      });
    });
  };
  _clean_form = () => {
    const plugin = this;
    plugin.formManager._clean_invalid_input();
    plugin.formManager._clean_on_modal_open(plugin.modalsManager, "modal-box");
    plugin.formManager._clean_on_modal_hide(plugin.modalsManager, "modal-box");
  };
  _choose_action = (upload) => {
    const plugin = this;
    plugin.wrapper.on("click", "div.input-group button:first-child", () => {
      plugin.modal.find(".modal-title").html("Ajouter un produit");
      plugin.frm.find("#operation").val("add");
    });
    plugin.wrapper.on("click", ".button-box .editBtn", (e) => {
      plugin.modal.find(".modal-title").html("Modifier le produit");
      plugin.frm.find("#operation").val("edit");
      upload._clear();
      const edit = new Edit({
        wrapper: this.wrapper,
        frm: $(e.target).parents("form"),
        frm_name: $(e.target).parents("form").attr("id"),
        ck_content: ["ck-content"],
        loader: null,
        dropzone: upload, //new upload(plugin.media)._upload(),
        mainfrm: this.frm,
        appConfig: appConf,
        categorieSelector: this.frm.find("#check-box-wrapper"),
      })._init();
      edit._execute({
        url: "admin/admin_pages/edit_product",
      });
    });
  };
  _handle_select_tag = () => {
    const plugin = this;
    new select2()._init({
      url: "select_field/all",
      element: plugin.frm.find(".shipping_class"),
      tbl_options: "shipping_class",
      placeholder: "Please select a shipping class",
      csrftoken: plugin.frm.find("input[name='csrftoken']").val(),
      frm_name: plugin.frm.attr("id"),
    });
    new select2()._init({
      url: "select_field/all",
      element: plugin.frm.find(".company"),
      tbl_options: "company",
      placeholder: "Select a company",
      csrftoken: plugin.frm.find("input[name='csrftoken']").val(),
      frm_name: plugin.frm.attr("id"),
    });
    new select2()._init({
      url: "select_field/all",
      element: plugin.frm.find(".warehouse"),
      tbl_options: "warehouse",
      placeholder: "Select a warehouse",
      csrftoken: plugin.frm.find("input[name='csrftoken']").val(),
      frm_name: plugin.frm.attr("id"),
    });
    new select2()._init({
      url: "select_field/all",
      element: plugin.frm.find(".unit_id"),
      tbl_options: "units",
      placeholder: "Select a unit",
      csrftoken: plugin.frm.find("input[name='csrftoken']").val(),
      frm_name: plugin.frm.attr("id"),
    });
  };
}
new Products($("#main"))._init()._handle();
