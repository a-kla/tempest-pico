import { defineConfig } from 'vite'
import tempest from 'vite-plugin-tempest'
import UnoCSS from 'unocss/vite'

/*
import { vite } from 'atomizer-plugins';
import atomizerConfig from './atomizer.config.js';

const atomizerPlugin = vite({
    config: atomizerConfig,
    outfile: 'public/build/atomizer.css',
});
*/
export default defineConfig({
	plugins: [
		tempest(),
		UnoCSS(),
	],
	  css: {
    transformer: 'lightningcss',
  },
  build: {
    cssMinify: 'lightningcss',
  },

})
