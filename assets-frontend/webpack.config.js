const webpack = require('webpack');

const fs = require('fs');
const ini = require('ini');

const config = ini.parse(fs.readFileSync('../config.ini', 'utf-8'));
const staticPath = config.app.staticPath;
const version = config.frontend.version;
const env = config.frontend.env;

const publicPath = '//' +
  config.app.staticDomain +
  config.frontend.staticUri.replace('%version%', version);

const assetsPath = `${staticPath + version}/`;

module.exports = {
  entry: ['./app'],
  output: {
    filename: 'bundle.js',
    path: assetsPath,
    publicPath: publicPath,
  },
  plugins: [
    new webpack.ProvidePlugin({
      $: 'jquery/dist/jquery.js',
      jQuery: 'jquery/dist/jquery.js',
      'window.jQuery': 'jquery/dist/jquery.js',
    }),
  ],
  module: {
    rules: [
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
