import {http} from './http'

export function getSuspiciousList(page) {
    return http().get('/suspicious?page='+ page).then(response => {
        return response
    });
}

export function createSuspicious(data) {
    return http().post('/suspicious', data).then(response => {
        return response.data
    })
}

export function updateSuspicious(id, data) {
    return http().post('/suspicious/' + id, data).then(response => {
        return response.data
    })
}

export function deleteSuspicious(id) {
    return http().delete('/suspicious/' + id).then(response => {
        return response.data
    });
}