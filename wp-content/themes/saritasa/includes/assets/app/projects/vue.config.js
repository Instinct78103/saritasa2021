module.exports = {
  outputDir: '../../app-pub/projects/',
  filenameHashing: false,
  devServer: {
    proxy: {
      '/wp-json': {
        target: process.env.SITE_URL,
        changeOrigin: true,
      },
    },
  },
  css: {
    loaderOptions: {
      sass: {
        data: ``,
      },
    },
  },
};