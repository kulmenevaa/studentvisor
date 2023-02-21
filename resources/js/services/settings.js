import {http} from './http'

export function getSettingsList() {
    return http().get('/settings');
}

export function saveSettings(data) {
    return http().post('/settings', data).then(response => {
        return response.data
    })
}

export function clearCache() {
    return http().get('/clear').then(response => {
        return response.data
    })
}