import axios from 'axios';
import { setLogged } from '@/utils/auth';

export default {
    namespaced: true,
    state: {
        authenticated: false,
        user: {}
    },
    getters: {
        authenticated(state) {
            return state.authenticated
        },
        user(state) {
            return state.user
        }
    },
    mutations: {
        SET_AUTHENTICATED(state, value) {
            state.authenticated = value
        },
        SET_USER(state, value) {
            state.user = value
        }
    },
    actions: {
        login({ commit }, data) {
            commit('SET_USER', data);
            commit('SET_AUTHENTICATED', true);
            setLogged('1', data.token);
        },
        getUser({ commit }, payload) {
            const config = {
                headers: { Authorization: `Bearer ${payload.token}` }
            };
            return axios.get('/api/auth/user', config).then(({ data }) => {
                if (data.success) {
                    commit('SET_USER', data.data)
                    commit('SET_AUTHENTICATED', true)
                }
            }).catch(({ res }) => {
                commit('SET_USER', {})
                commit('SET_AUTHENTICATED', false)
            })
        },
        logout({ commit }) {
            commit('SET_USER', {})
            commit('SET_AUTHENTICATED', false)
        }
    }
}