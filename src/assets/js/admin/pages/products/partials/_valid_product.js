import input from "corejs/inputErrManager";
class ProductFormValidation {
  constructor(response, form) {
    this.response = response;
    this.form = form;
  }
  _init = () => {
    return this;
  };
  _validate = () => {
    const plugin = this;
    if (plugin.response.result == "error-field") {
      input.error(plugin.form, response.msg, 30);
    }
  };
}
