const defaultConfig = {};

class AppConfig {
  static isMobile = () => {
    return $(window).width() < 992;
  };

  static setConfig = (newConfig) => {
    let config = this.getConfig();
    localStorage.setItem(
      "ABCADMIN_CONFIG",
      JSON.stringify({ ...config, ...newConfig })
    );
  };

  static getConfig = () => {
    let config = localStorage.getItem("ABCADMIN_CONFIG");
    return config ? JSON.parse(config) : defaultConfig;
  };

  static resetConfig = () => {
    this.setConfig(defaultConfig);
  };
}

export default AppConfig;
