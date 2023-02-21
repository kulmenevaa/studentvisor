import Vue from 'vue'
import Vuex from 'vuex'
import * as auth from './services/auth'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        isLoggedIn: null,
        apiURL: 'https://studentvisor.tusur.ru/api/v1',
        serverPath: 'https://studentvisor.tusur.ru',
        profile: false
    },
    mutations: {
        authenticate(state, payload) {
            state.isLoggedIn = auth.isLoggedIn();
            if (state.isLoggedIn) {
                state.profile = payload;
            } else {
                state.profile = false;
            }
        }
    },
    actions: {
        authenticate(context, payload) {
            context.commit('authenticate', payload);
        }
    }
})