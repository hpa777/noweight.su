function requireAll(r) {
    r.keys().forEach(r);
}
global.$ = global.jQuery = require('jquery');  
requireAll(require.context('../img/icons/', true, /\.svg$/));
require('slick-carousel');
require('jquery-datetimepicker');
import Inputmask from"inputmask";
require('@fancyapps/fancybox');
require('jquery-scrolla/dist/js/jquery.scrolla');
require('./main.js');