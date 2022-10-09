function requireAll(r) {
    r.keys().forEach(r);
}
  
requireAll(require.context('../img/icons/', true, /\.svg$/));
require('slick-carousel');
require('jquery-datetimepicker');
import Inputmask from"inputmask";
require('@fancyapps/fancybox');
require('./main.js');