/**
 * Created by xiabin on 16/6/1.
 */

var Vue = require('vue');
var child = require('./components/child.vue');
$(function () {
    var vm = new Vue({
        el: '#vue_container',
        data: function () {
            return {
                parentMsg: "Vue CI Webpack"
            };
        },
        components: {
            child: child
        }
    });
});