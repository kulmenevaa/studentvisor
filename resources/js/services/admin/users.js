import {http} from './../http'

export function getUsersList(page) {
    return http().get('/users?page='+ page).then(response => {
        return response
    });
}

export function createUsers(data) {
    return http().post('/users', data).then(response => {
        return response.data
    })
}

export function updateUsers(id, data) {
    return http().post('/users/' + id, data).then(response => {
        return response.data
    })
}

export function deleteUsers(id) {
    return http().delete('/users/' + id).then(response => {
        return response.data
    });
}