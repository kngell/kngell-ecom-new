const path = require("path");
const { merge } = require("webpack-merge");
const devMode = process.env.NODE_ENV !== "production";
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const frontendEntries = require("./src/entries/assets/frontend/frontendEntries");
const adminEntries = require("./src/entries/assets/backend/admin/adminEntries");
const { viewRules, assetsRuless } = require("./webpack.modules");
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
  plug: path.resolve(__dirname, "src", "assets", "plugins_admin"),
  views: path.resolve(__dirname, "src", "views"),
  index: path.resolve(__dirname, "src"),
  entries: path.resolve(__dirname, "src", "entries"),
  js: path.resolve(__dirname, "src", "assets", "js"),
  css: path.resolve(__dirname, "src", "assets", "css"),
};

/**
 * Server Options
 * ========================================================================================
 */
const serverOpt = {
  static: ["./"],
  open: {
    app: {
      name: "Chrome",
    },
  },
  compress: true,
  host: "localhost",
  port: 8001,
  server: {
    type: "https",
    options: {
      key: "/mnt/d/ssl/local/ssl/localhost.key",
      cert: "/mnt/d/ssl/local/ssl/localhost.crt",
      // passphrase: "webpack-dev-server",
      // requestCert: true,
    },
  },
  proxy: {
    context: () => true,
    "/**": {
      target: "https://localhost",
      secure: false,
      changeOrigin: true,
      pathRewrite: { "^/ecom": "" },
    },
  },
  devMiddleware: {
    // index: false,
    // publicPath: "/public",
    writeToDisk: (filePath) => {
      return /^(?!.*(hot)).*/.test(filePath);
    },
  },
  client: {
    logging: "none",
    // webSocketURL: "auto://0.0.0.0:0/ws",
  },
  // headers: {
  //   "Access-Control-Allow-Origin": "*",
  //   "Access-Control-Allow-Headers": "*",
  //   "Access-Control-Allow-Methods": "*",
  // },
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
    new MiniCssExtractPlugin({
      filename: devMode ? "[name].css" : "[name].[contenthash].css",
      chunkFilename: devMode
        ? "lazyload/css/home/[name].css"
        : "lazyload/css/home/[name]_[chunkhash].css",
    }),
    // new PurgecssPlugin({
    //   paths: glob.sync([`${PATHS.src}/**/*`], { nodir: true }),
    //   extractors: [
    //     {
    //       extractor: (content) => content.match(/[A-z0-9-:\/]+/g) || [],
    //       extensions: ["js", "css", "php"],
    //     },
    //   ],
    //   whitelist: [],
    //   whitelistPatterns: [],
    //   whitelistPatternsChildren: [],
    // }),
  ],
};
/**
 * FrontEnd Assets
 * ========================================================================================
 */
exports.fontendAssetsConfig = merge(
  frontendEntries,
  assetParams,
  {
    entry: {
      "css/librairies/frontlib": "./src/assets/css/lib/frontlib.sass",
      "js/librairies/frontlib": "./src/assets/js/lib/frontlib",
    },
    optimization: {
      removeEmptyChunks: true,
      splitChunks: {
        cacheGroups: {
          homeCommonVendor: {
            test: /[\\/]node_modules[\\/]((?!@ckeditor).*)[\\/]/, //except ckeditor5
            name: "commons/client/commonVendor",
            chunks: "initial",
            minSize: 10000,
            priority: -10,
            minChunks: 2,
            reuseExistingChunk: true,
          },
          homeCustomModules: {
            test: /[\\/]((client).*)|((core).*)[\\/]/,
            name: "commons/client/commonCustomModules",
            chunks: "initial",
            minSize: 5000,
            minChunks: 2,
            priority: -20,
            reuseExistingChunk: true,
          },
        },
      },
    },
    devServer: serverOpt,
  },
  assetsRuless
);

/**
 * Backend Assets -- Admin
 * ========================================================================================
 */
exports.adminAssetsConfig = merge(
  adminEntries,
  assetParams,
  {
    entry: {
      "css/librairies/adminlib": "./src/assets/css/lib/adminlib.sass",
      "js/librairies/adminlib": "./src/assets/js/lib/adminlib",
    },
    // externals: {
    //   moment: "moment",
    // },
    optimization: {
      splitChunks: {
        cacheGroups: {
          adminCommonVendor: {
            test: /[\\/]node_modules[\\/]((?!@ckeditor).*)[\\/]/, //except ckeditor5
            name: "commons/admin/commonVendor",
            chunks: "initial",
            minSize: 10000,
            priority: -10,
            minChunks: 2,
            reuseExistingChunk: true,
          },
          adminCustomModules: {
            test: /[\\/]((admin).*)|((core).*)|((plugins).*)[\\/]/,
            name: "commons/admin/commonCustomModules",
            chunks: "initial",
            minSize: 10000,
            minChunks: 2,
            priority: -20,
            reuseExistingChunk: true,
          },
          styles: {
            test: /[\\/]((node_modules).*)|((plugins).*)[\\/]((?!@ckeditor).*)[\\/]/,
            name: "commons/admin/commoncss",
            type: "css/mini-extract",
            chunks: "initial",
            minSize: 10000,
            minChunks: 2,
            reuseExistingChunk: true,
          },
        },
      },
    },
  },
  assetsRuless
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
    plugins: [],
  },
  viewRules
);
