require('babel-polyfill');
const path = require('path');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const dev = process.env.NODE_ENV === 'dev';

let cssLoaders = [
  {loader: 'css-loader', options: {importLoaders: 1, minimize: !dev}},
]

let cleanOptions = {
  root: path.resolve('./'),
  exclude: ['shared.js'],
  verbose: true,
  dry: false
}

if (!dev) {
  cssLoaders.push(
    {
      loader: 'postcss-loader', options: {
      plugins: (loader) => [
        require('autoprefixer')({
          browsers: ['last 2 versions', 'ie > 9']
        })
      ]
    }
    }
  )
}

let config = {
  entry: {
    app: ['babel-polyfill', './src/css/app.scss', './src/js/app.js']
  },
  watch: dev,
  output: {
    path: path.resolve('./dist'),
    filename: dev ? '[name].js' : '[name].[chunkhash:4].js',
    publicPath: "/dist/"
  },
  devServer: {

  },
  devtool: dev ? 'cheap-module-eval-source-map' : false,
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: ['eslint-loader']
      },
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: 'babel-loader'
      },
      {
        test: /\.css$/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: cssLoaders
        })
      },
      {
        test: /\.scss/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: [
            ...cssLoaders,
            'sass-loader?includePaths[]=' + path.resolve(__dirname, "./node_modules/compass-mixins/lib") + "&includePaths[]=" + path.resolve(__dirname, "./mixins/app_mixins")
          ]
        })
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: 'file-loader',
        options: {
          name: dev ? '[name].[ext]' : '[name].[hash:4].[ext]',
          outputPath: 'fonts/',
          publicPath: dev ? '/src/' : 'dist/',
          useRelativePath: !dev
        }
      },
      {
        test: /\.(png|jpg|gif|svg)$/,
        use: [
          {
            loader: 'url-loader',
            options: {
              limit: 8192,
              name: dev ? '[name].[ext]' : '[name].[hash:4].[ext]',
              outputPath: 'images/',
              publicPath: dev ? '/src/' : 'dist/',
              useRelativePath: !dev
            }
          },
          {
            loader: 'img-loader',
            options: {
              enabled: !dev
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new ExtractTextPlugin({
      filename: dev ? '[name].css' : '[name].[contenthash:4].css',
      disable: dev
    }),
    new CleanWebpackPlugin(['dist/*'], cleanOptions)
  ]
};

if (!dev) {
  config.plugins.push(new UglifyJSPlugin({
    sourceMap: false
  }));
  config.plugins.push(new ManifestPlugin())
}

module.exports = config;
