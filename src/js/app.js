function requireAll(r) {
    r.keys().forEach(r);
}
global.$ = global.jQuery = require('jquery');
global.moment = require('moment');  
requireAll(require.context('../img/icons/', true, /\.svg$/));
require('slick-carousel');
require('jquery-datetimepicker');
import Inputmask from "inputmask";
require('@fancyapps/fancybox');
require('jquery-scrolla/dist/js/jquery.scrolla');
require('./main.js');


import Vue from 'vue';

Vue.component("calc", require("./calc.vue").default)
.directive("datepicker", {
    bind: function (el, binding, vnode) {        
        $(el).datetimepicker({
            format: 'd.m.Y',        
            formatDate: 'd.m.Y',
            timepicker: false,
            onChangeDateTime:function(date, $input){
                vnode.elm.dispatchEvent(new CustomEvent('input'));
                vnode.elm.dispatchEvent(new CustomEvent('change')); 
            }
        });
    }
});
new Vue({
    el: '#calc-form'
});


 

  