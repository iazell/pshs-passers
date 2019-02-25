require('./bootstrap');
import { Form, HasError, AlertError } from 'vform';
window.Vue = require('vue');
import VueSweetalert2 from 'vue-sweetalert2'; 
Vue.use(VueSweetalert2);

import VueTabs from 'vue-nav-tabs'
import 'vue-nav-tabs/themes/vue-tabs.css'
Vue.use(VueTabs)


const options = {
    confirmButtonColor: '#41b882',
    cancelButtonColor: '#ff7674'
  }
   
Vue.use(VueSweetalert2, options)

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.component('pagination', require('laravel-vue-pagination'));

Vue.component('navbar', require('./components/NavBarComponent.vue').default);
Vue.component('passers', require('./components/PassersComponent.vue').default);

const app = new Vue({
    el: '#app'
});
