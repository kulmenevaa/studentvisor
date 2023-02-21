require('./bootstrap');

import Vue from 'vue'
import App from './components/App'
import store from './store'
import router from './router'

import VueCollapse from 'vue2-collapse'
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

Vue.use(VueCollapse)
Vue.use(Toast, {
    maxToasts: 20,
    newestOnTop: false,
    position: "bottom-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: false,
    pauseOnHover: false,
    draggable: false,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true
})

Vue.config.productionTip = false
Vue.config.devtools = false

const app = new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
})