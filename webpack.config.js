const defaultConfig = require("./node_modules/@wordpress/scripts/config/webpack.config");
const path = require( 'path' );

module.exports = {
    ...defaultConfig,
    entry: {
        'themes-shortcode': [path.resolve(process.cwd(), 'src/js', 'themes-shortcode.js'), path.resolve(process.cwd(), 'src/sass', 'themes-shortcode.scss')],
    },
    output: {
        filename: 'js/[name].js',
        path: path.resolve( process.cwd(), '' ),
    },
    module: {
		rules: [
			/**
			 * Running Babel on JS files.
			 */
			...defaultConfig.module.rules,
			{
				test: /\.scss$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: 'css/[name].css',
						}
					},
					{
						loader: 'extract-loader'
					},
					{
						loader: 'css-loader?-url'
					},
					{
						loader: 'postcss-loader'
					},
					{
						loader: 'sass-loader'
					}
				]
			}
		]
	}
};
