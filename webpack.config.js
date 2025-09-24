const Encore = require("@symfony/webpack-encore");
const CopyWebpackPlugin = require("copy-webpack-plugin");

// Configure le runtime si nécessaire
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore
    // dossier où les assets compilés seront stockés
    .setOutputPath("public/build/")
    .setPublicPath("/build")

    // entrée principale JS
    .addEntry("app", "./assets/app.js")

    // optimisation
    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    // nettoyage avant chaque build
    .cleanupOutputBeforeBuild()

    // sourcemaps pour dev
    .enableSourceMaps(!Encore.isProduction())

    // versioning pour cache-busting en prod
    .enableVersioning(Encore.isProduction())

    // notifications
    .enableBuildNotifications()

    // Babel
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = "usage";
        config.corejs = "3.38";
    })

    // PostCSS (assurez-vous d’installer postcss-loader)
    .enablePostCssLoader()

    // Sass (décommenter si besoin)
    //.enableSassLoader()

    // Copier toutes les images du dossier assets/images vers public/build/images
    .addPlugin(
        new CopyWebpackPlugin({
            patterns: [
                {
                    from: "./assets/images",
                    to: "images",
                    globOptions: { dot: true, gitignore: false }, // copie tous les fichiers
                },
            ],
        })
    );

module.exports = Encore.getWebpackConfig();
