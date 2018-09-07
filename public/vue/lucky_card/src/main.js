// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import VueRouter from 'vue-router'
import './assets/base.css'
import Main from './components/main'
import Reg from './components/reg'
import myconf from './lib/config.js'

Vue.config.productionTip = false

Vue.use(VueRouter)


const router = new VueRouter({
    // mode:'history',
    routes:[
        {
            path: '/',
            component: Reg
        },
        {
            path: '/main',
            component: Main
        }
    ]
})


// vue全局变量
Vue.prototype.myconf = myconf

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
