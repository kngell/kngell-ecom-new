export default class ModalManager {
  constructor(modalNamesAry) {
    this.modalNamesAry = modalNamesAry;
    this.promise = async () => {
      const bsm = await import(
        /* webpackChunkName: "bsmodal" */ "corejs/bootstrap_modal"
      );
      return new bsm.default(this.modalNamesAry)._init();
    };
  }
  _init = () => {
    return this;
  };
  _open = (modalName) => {
    const plugin = this;
    plugin.promise.then((modal) => {
      modal[modalName].show();
    });
  };
}
