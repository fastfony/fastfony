const Encore = require('@symfony/webpack-encore');
const path = require('path');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore.setOutputPath('public/build/')
  .setPublicPath('/build')
  .copyFiles({
    from: './assets/images',
    to: 'images/[path][name].[hash:8].[ext]',
  })
  .addEntry('install', './assets/install.js')
  .addEntry('front', './assets/front.js')
  .addEntry('admin', './assets/admin.js')
  .splitEntryChunks()
  .enableVueLoader()
  .enablePostCssLoader()
  .enableVueLoader(() => {}, { runtimeCompilerBuild: false })
  .enableStimulusBridge('./assets/controllers.json')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'usage';
    config.corejs = '3.38';
  })
  .configureDevServerOptions((options) => {
    options.liveReload = true;
    options.static = {
      watch: false,
    };
    options.watchFiles = {
      paths: ['assets/locales/*', 'src/**/*.php', 'templates/**/*'],
    };
    options.allowedHosts = 'all';
    options.server = {
      type: 'https',
      options: {
        pfx: path.join(process.env.HOME, '.symfony5/certs/default.p12'),
      },
    };
  });

module.exports = Encore.getWebpackConfig();
