<template>
    <div>
        <form @submit.prevent="search">
            <div class="search-form">
                <div class="search-block">
                    <input type="search" id="main_search" v-model="form.main_search" :class="{'is-invalid':errors.main_search}" placeholder="Введите ФИО или Логин студента">
                    <button type="submit" class="btn btn-primary search-btn">
                        <i class="bx bx-search"></i>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-2 col-md-3 col-xs-12 sort-item">
                    <div>
                        <label for="date_search">Дата</label>
                        <input type="date" id="date_search" v-model="form.date_search">
                    </div>
                </div>
                <div class="col-xl-5 col-md-2 col-xs-12"></div>
                <div class="col-xl-3 col-md-4 col-xs-12 sort-item"></div>
                <div class="col-xl-2 col-md-3 col-xs-12 sort-item">
                    <div>
                        <label for="ip_search">IP</label>
                        <input type="text" id="ip_search" v-model="form.ip_search">
                    </div>
                </div>
                <!--
                <div class="col-xl-3 col-md-4 col-xs-12 sort-item">
                    <div>
                        <label for="place_search">Местоположение</label>
                        <select id="place_search" v-model="form.place_search">
                            <option>1</option>
                        </select>
                    </div>
                </div>
                -->
            </div>
        </form>
        <div v-if="loading" class="loading">
            <img :src="'/public/img/load.gif'">
        </div>
        <div class="table-responsive mt-5" v-else-if="reportsList.length > 0">
            <table class="table">
                <thead>
                    <tr>
                        <th width="30%">ФИО</th>
                        <th width="20%">Дата</th>
                        <th width="20%">IP</th>
                        <th width="30%">Местоположение</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in reportsList" :class="{'suspicious':item.suspicious}">
                        <td>{{ item.fio }}</td>
                        <td>{{ item.date }}</td>
                        <td>{{ item.ip}}</td>
                        <td>{{ item.place  }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    const formData = {main_search: '', date_search: '', ip_search: '', place_search: ''}
    import * as statistics from '../../../services/statistics'
    export default {
        name: 'Authorized',
        metaInfo: {
            title: 'Авторизация',
            meta: [
                { name:'keywords', content: '' },
                { name:'description', content: '' },
            ]
        },
        data() {
            return {
                form: Object.assign({}, formData),
                loading: false,
                reportsList: [],
                errors: false,
                sort: false,
                parameters: false,
            }
        },
        mounted() {
            this.parameters = this.$route.query
            if(Object.keys(this.parameters).length !== 0) {
                this.form.main_search = this.parameters.main_search
                this.form.ip_search = this.parameters.ip_search
                this.search()
            }
        },
        methods: {
            search() {
                this.loading = true
                this.$router.push({
                    name:'reports.authorized', 
                    query: {
                        main_search: this.form.main_search,
                        date_search: this.form.date_search,
                        ip_search: this.form.ip_search,
                        place_search: this.form.place_search
                    }
                })
                let data = new FormData()
                data.append('main_search', this.form.main_search ?? '')
                data.append('date_search', this.form.date_search ?? '')
                data.append('ip_search', this.form.ip_search ?? '')
                data.append('place_search', this.form.place_search ?? '')
                statistics.getAuthReports(this.form).then(response => {
                    this.loading = false
                    this.errors = false
                    this.reportsList = response.statistics   
                }).catch(errors => {
                    this.errorsSwitch(errors)
                })
            },
            errorsSwitch(errors) {
                this.loading = false
                switch(errors.response.status) {
                    case 422:
                        this.errors = errors.response.data.errors
                        var list = ''
                        Object.values(this.errors).forEach(function(value) {
                            list += value[0] + "\r\n"
                        })
                        this.$toast.error(list)
                        break
                    default:
                        this.$toast.error(errors.response.data.message)
                }
            },
        }
    }
</script>

<style>
    .search-form {
        display: flex;
    }
    .search-form .search-block {
        position: relative;
        width: 100%;
    }
    .search-form .search-block input {
        display: flex;
        padding: 0.625rem 1rem;
        padding-right: 3.825rem;
        width: 100%;
        font-size: 0.875rem;
        line-height: 1.25rem;
        border-radius: 0.5rem;
    }
    .search-form .search-block .search-btn {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        font-weight: 500;
        color: #fff;
        border: 0;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    .search-form .search-block .search-btn i {
        font-size: 1.25rem
    }
    .sort-item:not(:first-child) {
        margin: 0.5rem 0;
    }
</style>