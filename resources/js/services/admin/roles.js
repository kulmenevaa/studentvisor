import {http} from './../http'

export function getRolesList() {
    return http().get('/roles').then(response => {
        return response
    })
}