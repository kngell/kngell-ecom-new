const path = require("path");
const AssetsPlugin = require("assets-webpack-plugin");
const DelWebpackPlugin = require("del-webpack-plugin");
const webpack = require("webpack");

module.exports = {
  plugins: [
    new DelWebpackPlugin({
      include: ["*.js", "*.css", "*vendors-node_modules_ckeditor_*"], //"*.js",
      info: true,
      keepGeneratedAssets: false,
      allowExternal: false,
    }),

    new AssetsPlugin({
      filename: "assets.json",
      includeManifest: "manifest",
      path: path.join(__dirname, "App"),
      processOutput: function (assets) {
        return JSON.stringify(assets);
      },
      includeAllFileTypes: false,
      fileTypes: ["js", "css"],
      integrity: true,
    }),
    new webpack.ProvidePlugin({
      $: "jquery",
      jQuery: "jquery",
      "window.jQuery": "jquery",
      Popper: "@popperjs/core",
    }),
    new webpack.ProgressPlugin(),
  ],
};
