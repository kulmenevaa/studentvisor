<template>
    <div>
        <div class="settings-blocks">
            <section class="section settings-content">
                <div class="row">
                    <div class="col-xl-8 col-md-12">
                        <div class="settings-title">
                            <h3>IP: {{ item.ip }} ({{ item.place }})</h3>
                            <h3><span>Записей: </span>{{ item.count }}</h3>
                        </div>
                        <div class="table-responsive mt-5">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="20%">Дата и время</th>
                                        <th width="20%">Логин</th>
                                        <th width="60%">Информация</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="value in paginatedData">
                                        <td>{{ value.date }}</td>
                                        <td>{{ value.user }}</td>
                                        <td>{{ isJsonString(value.info) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-12">
                        <div class="settings-title" style="justify-content: center">
                            <h3>Уникальные логины: {{ groupArray.count }}</h3>
                        </div>
                        <div class="table-responsive mt-5">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="50%">Логин</th>
                                        <th width="50%">Количество</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in groupArray.array">
                                        <td>{{ index }}</td>
                                        <td>{{ item }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="my_pagination">
                    <button @click="prevPage" :disabled="page === 0" class="btn btn-primary">Пред</button>
                    <button @click="nextPage" :disabled="page >= pageCount -1" class="btn btn-primary">Next</button>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import * as statistics from '../../../services/statistics'
    export default {
        name: 'Detail',
        metaInfo: {
            title: 'Авторизация',
            meta: [
                { name:'keywords', content: '' },
                { name:'description', content: '' },
            ]
        },
        data() {
            return {
                array: [],
                groupArray: [],
                item: [],
                loading: false,
                parameters: false,
                page: 0,
                size: 10
            }
        },
        mounted() {
            this.getFindStatistics()
            this.getGroupItemStatistics()
        },
        methods: {
            getFindStatistics() {
                this.loading = true
                let type = this.$route.query.type
                let ip = this.$route.query.ip
                let period = this.$route.query.period
                statistics.getFindStatisticsList(type, ip, period).then(response => {
                    this.item = response
                    this.array = response.array
                }).catch(errors => {
                    this.loading = false
                    this.$toast.error(errors.response.data.message)
                })
            },
            getGroupItemStatistics() {
                this.loading = true
                let type = this.$route.query.type
                let ip = this.$route.query.ip
                let period = this.$route.query.period
                statistics.getGroupItemStatisticsList(type, ip, period).then(response => {
                    this.groupArray = response
                    this.size = response.count
                }).catch(errors => {
                    this.loading = false
                    this.$toast.error(errors.response.data.message)
                })
            },
            nextPage() {
                this.page++;
            },
            prevPage() {
                this.page--;
            },
            isJsonString(str) {
                try {
                    return JSON.parse(str).user_agent;
                } catch (e) {
                    return str
                }
            }
        },
        computed: {
            pageCount(){
                let l = this.array.length,
                    s = this.size;
                // редакция переводчика спасибо комментаторам
                return Math.ceil(l/s);
                // оригинал
                // return Math.floor(l/s);
            },
            paginatedData() {
                let start = this.page * this.size
                let end = start + this.size
                return this.array.slice(start, end)
            },
        }
    }
</script>

<style>
    .my_pagination {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
    }
</style>