const path = require("path");
const { merge } = require("webpack-merge");
const devMode = process.env.NODE_ENV !== "production";
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const RemoveEmptyScriptsPlugin = require("webpack-remove-empty-scripts");
const frontendEntries = require("./src/entries/assets/frontend/frontendEntries");
const adminEntries = require("./src/entries/assets/backend/admin/adminEntries");
const { viewRules, assetsRules } = require("./webpack.modules");
const config = require("./config");
const ASSET_PATH = config.PATH;

//process.env.ASSET_PATH || `${path.sep}public${path.sep}assets${path.sep}`;

/**
 * Alias
 * ========================================================================================
 */
exports.alias = {
  corejs: path.resolve(__dirname, "src", "assets", "js", "core"),
  corecss: path.resolve(__dirname, "src", "assets", "css", "core"),
  img: path.resolve(__dirname, "src", "assets", "img"),
  fonts: path.resolve(__dirname, "src", "assets", "fonts"),
  plugins: path.resolve(__dirname, "src", "assets", "plugins"),
  views: path.resolve(__dirname, "src", "views"),
  index: path.resolve(__dirname, "src"),
  entries: path.resolve(__dirname, "src", "entries"),
  js: path.resolve(__dirname, "src", "assets", "js"),
  css: path.resolve(__dirname, "src", "assets", "css"),
  module: path.resolve(__dirname, "node_modules"),
};

/**
 * Server Options
 * ========================================================================================
 */
const serverOpt = {
  compress: true,
  allowedHosts: "auto",
  // host: "localhost",
  bonjour: true,
  client: {
    logging: "none",
    overlay: {
      errors: true,
      warnings: false,
    },
    // progress: true,
    reconnect: true,
    webSocketTransport: "ws",
    // webSocketURL: "ws://localhost/ws",
  },
  webSocketServer: "ws",
  devMiddleware: {
    index: true,
    publicPath: ASSET_PATH,
    serverSideRender: true,
    writeToDisk: true,
    // writeToDisk: (filePath) => {
    //   return /^(?!.*(hot)).*/.test(filePath);
    // },
  },
  hot: true,
  liveReload: false,
  // magicHtml: true,
  open: {
    app: {
      name: "Chrome",
      // arguments: ["--incognito", "--new-window"],
    },
  },
  port: 8001,
  proxy: {
    context: () => true,
    "/**": {
      target: "https://localhost",
      secure: false,
      changeOrigin: true,
      pathRewrite: { "^/kngell-ecom": "" },
    },
  },
  server: {
    type: "https",
    options: {
      key: "/mnt/d/ssl/local/ssl/localhost.key",
      cert: "/mnt/d/ssl/local/ssl/localhost.crt",
    },
  },
  static: {
    directory: path.resolve(__dirname, "App", "Views"),
  },
  // headers: {
  //   "Access-Control-Allow-Origin": "https://corona.lmao.ninja/v2/countries",
  //   "Access-Control-Allow-Methods": "GET, POST, PUT, DELETE, PATCH, OPTIONS",
  //   "Access-Control-Allow-Headers":
  //     "X-Requested-With, content-type, Authorization",
  // },
  // watchFiles: ["App/**/*.php", "public/**/*"],
};
const entries = {
  "css/librairies/frontlib": "./src/assets/css/lib/frontlib.sass",
  "js/librairies/frontlib": "./src/assets/js/lib/frontlib",
  "css/librairies/adminlib": "./src/assets/css/lib/adminlib.sass",
  "js/librairies/adminlib": "./src/assets/js/lib/adminlib",
};
const optimConfig = {
  splitChunks: {
    cacheGroups: {
      homeCommonVendor: {
        test: (module) => {
          const path = require("path");
          return (
            module.resource &&
            ((module.resource.includes(`${path.sep}node_modules${path.sep}`) &&
              !module.resource.includes(`${path.sep}@ckeditor${path.sep}`)) ||
              module.resource.includes(`${path.sep}core${path.sep}`))
          );
        },
        name: "commons/client/commonVendor",
        chunks: (chunk) => {
          return (
            chunk.name &&
            chunk.name.includes(`${path.sep}client${path.sep}`) &&
            chunk.name !== "commons/client/commonVendor"
          );
        },
        minSize: 5000,
      },
      adminCommonVendor: {
        test: (module) => {
          const path = require("path");
          return (
            module.resource &&
            module.resource.includes(`${path.sep}node_modules${path.sep}`) &&
            !module.resource.includes(`${path.sep}@ckeditor${path.sep}`)
          );
        },
        name: "commons/admin/commonVendor",
        chunks: (chunk) => {
          return (
            chunk.name &&
            chunk.name.includes(`${path.sep}admin${path.sep}`) &&
            chunk.name !== "commons/admin/commonVendor"
          );
        },
        minSize: 5000,
      },
      adminCustomModules: {
        test: (module) => {
          const path = require("path");
          return (
            module.resource &&
            module.resource.includes(`${path.sep}core${path.sep}`)
          );
        },
        name: "commons/admin/commonCustomModules",
        chunks: (chunk) => {
          return (
            chunk.name &&
            chunk.name.includes(`${path.sep}admin${path.sep}`) &&
            chunk.name !== "commons/admin/commonCustomModules"
          );
        },
        minSize: 5000,
      },
      styles: {
        test: (module) => {
          const path = require("path");
          return (
            module.resource &&
            ((module.resource.includes(`${path.sep}node_modules${path.sep}`) &&
              !module.resource.includes(`${path.sep}@ckeditor${path.sep}`)) ||
              module.resource.includes(`${path.sep}core${path.sep}`))
          );
        },
        name: "commons/admin/commoncss",
        chunks: (chunk) => {
          return (
            chunk.name &&
            chunk.name.includes(`${path.sep}admin${path.sep}`) &&
            chunk.name !== "commons/admin/commoncss"
          );
        },
        type: "css/mini-extract",
        minSize: 10000,
      },
    },
  },
};

const assetParams = {
  output: {
    path: path.resolve(__dirname, "public", "assets"),
    chunkFilename: devMode
      ? "lazyload/js/home/[name].js"
      : "lazyload/js/home/[name]_[chunkhash].js",
    filename: devMode ? "[name].js" : "[name].[contenthash].js",
    // assetModuleFilename: devMode
    //   ? "ressources/[name][ext][query]"
    //   : "[name].[contenthash].js",
    publicPath: ASSET_PATH,
    library: "kngell",
    libraryTarget: "umd",
  },
  plugins: [
    new RemoveEmptyScriptsPlugin(),
    new MiniCssExtractPlugin({
      filename: devMode ? "[name].css" : "[name].[contenthash].css",
      chunkFilename: devMode
        ? "lazyload/css/home/[name].css"
        : "lazyload/css/home/[name]_[chunkhash].css",
    }),
  ],
};
exports.assetConfig = merge(
  merge({ entry: entries }, frontendEntries, adminEntries),
  assetParams,
  assetsRules,
  {
    optimization: merge(
      {
        removeAvailableModules: true,
        removeEmptyChunks: true,
      },
      {
        splitChunks: {
          cacheGroups: {
            default: {
              minChunks: 2,
              priority: -20,
              reuseExistingChunk: true,
            },
          },
        },
      },
      optimConfig
    ),
  }
);

/**
 * Views cinfig
 * ========================================================================================
 */
exports.viewsConfig = merge(
  {
    entry: "entries/views/views",
    output: {
      path: path.resolve(__dirname, "App", "Views"),
      assetModuleFilename: (pathData) => {
        const filepath = path
          .dirname(pathData.filename)
          .split("/")
          .slice(2)
          .join("/");
        return `${filepath}/[name][ext]`;
      },
      clean: true,
    },
    devServer: serverOpt,
    plugins: [],
  },
  viewRules
);
