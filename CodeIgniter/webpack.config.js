const path = require('path');

module.exports = {
    entry: './assets/js/custom/apps/swapy/swapy.js', // Your entry file
    output: {
        filename: 'bundle.js', // Output file name
        path: path.resolve(__dirname, 'dist'), // Output directory
        clean: true, // Clean the output directory before each build
    },
    mode: 'development', // Set to 'production' for production builds
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader', // Optional: if you want to use Babel
                    options: {
                        presets: ['@babel/preset-env'],
                    },
                },
            },
        ],
    },
};