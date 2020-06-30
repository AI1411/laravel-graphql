import Vue from 'vue'
import Vuex from 'vuex'
import router from '../router/index';

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        logined: !!localStorage.getItem('vue_token'),
        error: false
    },
    mutations: {
        logined(state, payload) {
            state.logined = true
        },
        error(state, payload) {
            router.push("/login")
        }
    },
    actions: {},
    modules: {}
})
