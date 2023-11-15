const path = require("path");
const webpack = require("webpack");
const plugins = require("./webpack.plugins");
const { merge } = require("webpack-merge");
const RemovePlugin = require("remove-files-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const ImageMinimizerPlugin = require("image-minimizer-webpack-plugin");
const FileManagerPlugin = require("filemanager-webpack-plugin");
const config = require("./config");
const { alias, assetConfig, viewsConfig } = require("./webpack.partials");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const ASSET_PATH = config.ROOT + config.PATH;
const Files = require("./src/entries/assets/frontend/copyFilesEntries");

const commonConfig = merge(plugins, {
  devtool: false,
  resolve: {
    alias: alias,
  },
  stats: {
    errorDetails: true,
    children: true,
  },
});
const removePlug = () => {
  return new RemovePlugin({
    after: {
      test: [
        {
          folder: "public/assets/css",
          method: (absoluteItemPath) => {
            return new RegExp(/\.js$/, "m").test(absoluteItemPath);
          },
          recursive: true,
        },
        {
          folder: "public/assets/js",
          method: (absoluteItemPath) => {
            return new RegExp(/\.hot-update.js$/, "m").test(absoluteItemPath);
          },
          recursive: true,
        },
        {
          folder: "public/assets",
          method: (absoluteItemPath) => {
            return new RegExp(/\.hot-update.json$/, "m").test(absoluteItemPath);
          },
          recursive: true,
        },
        {
          folder: "App/Views",
          method: (absoluteItemPath) => {
            return new RegExp(/\.hot-update.json$/, "m").test(absoluteItemPath);
          },
          recursive: true,
        },
      ],
    },
  });
};

/**
 * Developpement Config
 * =============================================================
 */
const developmentConfig = {
  devtool: false,
  plugins: [new webpack.SourceMapDevToolPlugin()],
  optimization: {
    minimize: false,
  },
};

/**
 * Production config
 * ==============================================================
 */
const productionConfig = {
  plugins: [
    new webpack.SourceMapDevToolPlugin({
      filename: "sourcemaps/[file].map",
      publicPath: ASSET_PATH,
      fileContext: "public",
    }),
    new ImageMinimizerPlugin({
      minimizer: {
        implementation: ImageMinimizerPlugin.imageminMinify,
        options: {
          // Lossless optimization with custom option
          // Feel free to experiment with options for better result for you
          plugins: [
            ["gifsicle", { interlaced: true }],
            ["jpegtran", { progressive: true }],
            ["optipng", { optimizationLevel: 5 }],
            // Svgo configuration here https://github.com/svg/svgo#configuration
            [],
          ],
        },
      },
    }),
  ],
  optimization: {
    minimizer: [
      new CssMinimizerPlugin({
        parallel: true,
        minimizerOptions: {
          preset: [
            "default",
            {
              discardComments: { removeAll: true },
            },
          ],
        },
        minify: CssMinimizerPlugin.cssnanoMinify,
      }),
      new TerserPlugin({
        exclude: [path.resolve(__dirname, "node_modules")],
        terserOptions: {
          format: {
            comments: false,
          },
        },
        extractComments: false,
      }),
    ],
    // usedExports: true,
  },
};

module.exports = () => {
  switch (process.env.NODE_ENV) {
    case "development":
      assetConfig.plugins.push(
        new CleanWebpackPlugin(),
        new FileManagerPlugin({
          events: {
            onEnd: {
              copy: Files,
            },
          },
        }),
        removePlug()
      );
      viewsConfig.plugins.push(
        new CleanWebpackPlugin({
          dry: false,
          dangerouslyAllowCleanPatternsOutsideProject: true,
        })
      );
      return [
        merge(viewsConfig, commonConfig, developmentConfig),
        merge(assetConfig, commonConfig, developmentConfig),
      ];
    case "production":
      viewsConfig.plugins.push(
        new RemovePlugin({
          before: {
            include: [
              path.join(__dirname, "public", "assets"),
              path.join(__dirname, "app", "views"),
            ],
          },
        }),
        new CleanWebpackPlugin()
      );
      return [
        merge(viewsConfig, commonConfig, productionConfig),
        merge(assetConfig, commonConfig, developmentConfig),
      ];
    default:
      throw new Error("No matching configuration was found!");
  }
};
