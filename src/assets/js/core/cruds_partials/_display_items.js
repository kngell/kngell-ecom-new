import dataTable from "./_data_table";
export default class displayItems {
  constructor(parameters) {
    this.wrapper = parameters.wrapper;
    this.csrftoken = parameters.csrftoken;
    this.frm_name = parameters.frm_name;
    this.datatableEl = parameters.datatableEl;
    this.alertEl = parameters.alertEl;
  }
  /**
   * Display All Items
   * ===================================================
   * @param {*} params
   */
  _show = (params = {}) => {
    const plugin = this;
    const wrapper = plugin.wrapper;
    var data = {
      url: "show_all",
      csrftoken: plugin.csrftoken,
      frm_name: plugin.frm_name,
    };
    Call_controller({ ...data, ...params }, (response) => {
      if (response.result == "success") {
        wrapper.find("#showAll").html(response.msg);
        if (params.datatable) {
          new dataTable({
            datatableEl: plugin.datatableEl.element,
            pluginType: plugin.datatableEl.pluginType ?? "",
            stateSave: plugin.datatableEl.stateSave ?? "",
            responsive: plugin.datatableEl.responsive ?? "",
            order: plugin.datatableEl.order ?? "",
          })._load();
        }
      } else {
        wrapper.find(plugin.alertEl).html(response.msg);
      }
    });
  };
}
