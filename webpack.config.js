const OroConfig = require('./vendor/oro/platform/build/webpack-config-builder/oro-webpack-config');

OroConfig
    .setPublicPath('public/')
    .setCachePath('var/cache');

module.exports = OroConfig.getWebpackConfig();
