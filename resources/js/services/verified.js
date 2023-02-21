import {http} from './http'

export function getVerifiedList(page) {
    return http().get('/verified?page='+ page).then(response => {
        return response
    })
}

export function createVerified(data) {
    return http().post('/verified', data).then(response => {
        return response.data
    })
}

export function updateVerified(id, data) {
    return http().post('/verified/' + id, data).then(response => {
        return response.data
    })
}

export function deleteVerified(id) {
    return http().delete('/verified/' + id).then(response => {
        return response.data
    })
}