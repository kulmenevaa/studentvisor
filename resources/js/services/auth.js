import { http, httpFile } from './http'
import store from '../store'

export function login(user) {
    return http().post('/login', user).then(response => {
        if(response.status === 200) {
            localStorage.setItem('auth-token', JSON.stringify(response.data))
            store.dispatch('authenticate', response.data.user)
        }
        return response.data
    })
}

export function logout() {
    http().get('/logout')
    localStorage.removeItem('auth-token')
    store.dispatch('authenticate', false)
}

export function getProfile() {
    return http().get('/profile')
}

export function isLoggedIn() {
    const token = localStorage.getItem('auth-token')
    return token != null;
}

export function getAccessToken() {
    const token = localStorage.getItem('auth-token')
    if(!token) {
        return null
    }
    const tokenData = JSON.parse(token)
    return tokenData.access_token
}