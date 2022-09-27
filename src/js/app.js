function requireAll(r) {
    r.keys().forEach(r);
}
  
requireAll(require.context('../img/icons/', true, /\.svg$/));
require('slick-carousel');
require('@fancyapps/fancybox');
require('./main.js');