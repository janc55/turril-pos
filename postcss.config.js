// postcss.config.js
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';
import postcssImport from 'postcss-import';

export default {
  plugins: [
    // postcss-import debe ir primero para manejar los @import de CSS
    postcssImport,
    // Luego, Tailwind CSS, apuntando a tu tailwind3.config.js
    tailwindcss('./tailwind3.config.js'),
    // Finalmente, Autoprefixer para añadir prefijos de navegador
    autoprefixer,
  ],
};