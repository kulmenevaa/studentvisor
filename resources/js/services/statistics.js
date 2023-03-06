import {http} from './http'

const alias = '/statistics/'

export function getBreakingList(period) {
    return http().get(alias + 'breaking?period=' + period).then(response => {
        return response.data
    })
}

export function getPlunkList(period) {
    return http().get(alias + 'plunk?period=' + period).then(response => {
        return response.data
    })
}

export function getAuthList(period) {
    return http().get(alias + 'auth?period=' + period).then(response => {
        return response.data
    })
}

export function getAuthReports(data) {
    return http().get(alias + 'reports', {params: data}).then(response => {
        return response.data
    })
}

export function getFindStatisticsList(type, ip, user, period) {
    return http().get(alias + 'item', {
        params:{
            type:type,
            period:period,
            ip:ip, 
            user:user,
        }
    }).then(response => {
        return response.data
    })
}

export function getGroupItemStatisticsList(type, ip, period) {
    return http().get(alias + 'group-item', {
        params:{
            type:type,
            period:period,
            ip:ip,
        }
    }).then(response => {
        return response.data
    })
}
