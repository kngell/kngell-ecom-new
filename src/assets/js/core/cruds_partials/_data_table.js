export default class loadDatatables {
  constructor(elObj = {}) {
    this.el = elObj.datatableEl;
    this.pluginType = elObj.pluginType;
    this.stateSave = elObj.stateSave;
    this.responsive = elObj.responsive;
    this.order = elObj.order;
  }
  _load = async () => {
    const plugin = this;
    const DataTable = await import(
      /* webpackChunkName: "datatables" */ "datatables.net-responsive-dt"
    );
    plugin.wrapper.find(plugin.el).DataTable({
      order: plugin.order ?? [0, "desc"],
      pagingType: plugin.pagingType ?? "full_numbers",
      stateSave: plugin.stateSave ?? true,
      responsive: plugin.responsive ?? true,
    });
  };
}
