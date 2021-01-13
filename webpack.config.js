const OroConfig = require('@oroinc/oro-webpack-config-builder');

OroConfig
    .setPublicPath('public/')
    .setCachePath('var/cache');

module.exports = OroConfig.getWebpackConfig();
