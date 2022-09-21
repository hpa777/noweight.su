

function requireAll(r) {
    r.keys().forEach(r);
}
  
requireAll(require.context('../img/icons/', true, /\.svg$/));



require('./main.js');