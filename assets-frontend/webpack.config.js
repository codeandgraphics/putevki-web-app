const webpack = require('webpack');
const path = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');

const fs = require('fs');
const ini = require('ini');

const config = ini.parse(fs.readFileSync('../app/config.ini', 'utf-8'));
const env = config.frontend.env;
const version = config.frontend.version;
const staticPath = config.app.staticPath;

const isProduction = env === 'production';

const publicPath = isProduction ? `//${
  config.app.staticDomain
  }${config.frontend.staticUri.replace('%version%', version)}`: `/build/`;

const assetsPath = isProduction
  ? `${staticPath + version}/`
  : path.resolve(__dirname, "../public/build");

module.exports = {
  entry: ['./app.es6'],
  output: {
    filename: 'bundle.js',
    path: assetsPath,
    publicPath,
  },
  devtool: 'source-map',
  plugins: [
    new webpack.ProvidePlugin({
      $: 'jquery/dist/jquery.js',
      jQuery: 'jquery/dist/jquery.js',
      'window.jQuery': 'jquery/dist/jquery.js',
    }),
    new CopyWebpackPlugin([
      {
        from: 'static',
        to: 'static/',
      },
    ]),
    new webpack.ContextReplacementPlugin(/moment[\/\\]locale$/, /ru/),
  ],
  resolve: {
    alias: {
      './dependencyLibs/inputmask.dependencyLib': './dependencyLibs/inputmask.dependencyLib.jquery',
      lodash: path.resolve(__dirname, 'node_modules/lodash'),
      jquery: path.resolve(__dirname, 'node_modules/jquery'),
    },
  },
  module: {
    rules: [
      { test: /\.es6$/,
        exclude: /(node_modules|bower_components)/,
        use: { loader: 'babel-loader',
          options: {
            presets: ['env'],
          },
        },
      },
      // { test: /\.(eot|svg|ttf|woff|woff2)$/, loader: 'file-loader?name=/assets/[hash].[ext]' },
      { test: /\.(woff|woff2)(\?v=\d+\.\d+\.\d+)?$/, loader: 'url-loader?limit=10000&mimetype=application/font-woff' },
      { test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/, loader: 'url-loader?limit=10000&mimetype=application/octet-stream' },
      { test: /\.eot(\?v=\d+\.\d+\.\d+)?$/, loader: 'file-loader' },
      { test: /\.svg(\?v=\d+\.\d+\.\d+)?$/, loader: 'url-loader?limit=10000&mimetype=image/svg+xml' },
      { test: /\.less$/, loader: 'style-loader!css-loader!less-loader' },
      { test: /\.(jpg|png)$/, loader: 'url-loader?limit=100000' },
    ],
  },
};
