/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import store from './vuex/store'
import Vue from 'vue'
import Form from './services/form'
import helper from './services/helper'
import Sortable from 'vue-sortable'
import VueRouter from 'vue-router'
import router from './routes'
import paginationRecord from './components/PaginationRecord'
import wareHouse from './views/ware-house/WareHouse'
import showError from './components/ShowError'
import Meta from 'vue-meta'
window._get = require('lodash/get');
window._eachRight = require('lodash/eachRight');
window._replace = require('lodash/replace');
window._has = require('lodash/has');
window._size = require('lodash/size');
window.Vue = Vue;
window.Form = Form;
window.helper = helper;

Vue.prototype.trans = (string, args) => {
    let value = _get(window.i18n, string);
    _eachRight(args, (paramVal, paramKey) => {
        value = _replace(value, `:${paramKey}`, paramVal);
    });
    return value;
};
Vue.prototype.$last = function (item, list) {
    return item === list[list.length - 1]
};

Vue.use(VueRouter);
Vue.use(Meta);
Vue.use(Sortable);
Vue.component('pagination-record', paginationRecord);
Vue.component('show-error', showError);
Vue.component('ware-house', wareHouse);
// Vue.component('pagination', pagination);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: '#root',
    store,
    router
});
