/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./vendors');

import Errors from './utilities/Errors';
import Form from './utilities/Form';

window.Errors = Errors;
window.Form = Form;

/** Chief components */
Vue.component('tab', require('./components/Tab.vue').default);
Vue.component('tabs', require('./components/Tabs.vue').default);
Vue.component('translation-tabs', require('./components/TranslationTabs.vue').default);
Vue.component('multiselect', require('./components/MultiSelect.vue').default);

Vue.component('modal', require('./components/Modal.vue').default);
Vue.component('sidebar', require('./components/Sidebar.vue').default);
Vue.component('alert', require('./components/Alert.vue').default);
Vue.component('delete', require('./components/RemoveButton.vue').default);
Vue.component('error', require('./components/Error.vue').default);

// sticky polyfill init
Stickyfill.add(document.querySelectorAll('.sticky'));

require('es6-promise').polyfill();