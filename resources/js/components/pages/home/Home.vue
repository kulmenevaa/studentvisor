<template>
    <div>
        <div class="options-block select-params row">
            <div class="col-md-6 col-xs-12">
                <div class="options">
                    <button type="button" class="btn btn-outline-primary min-btn" 
                        @click="sortByDate('today')" 
                        :class="{'active':period=='today'}">Сегодня</button>
                    <button type="button" class="btn btn-outline-primary min-btn" 
                        @click="sortByDate('yesterday')" 
                        :class="{'active':period=='yesterday'}">Вчера</button>
                    <button type="button" class="btn btn-outline-primary min-btn" 
                        @click="sortByDate('week')" 
                        :class="{'active':period=='week'}">Неделя</button>
                    <button type="button" class="btn btn-outline-primary min-btn" 
                        @click="sortByDate('month')" 
                        :class="{'active':period=='month'}">Месяц</button>
                    <button type="button" class="btn btn-outline-primary min-btn" 
                        @click="sortByDate('quarter')" 
                        :class="{'active':period=='quarter'}">Квартал</button>
                    <button type="button" class="btn btn-outline-primary min-btn" 
                        @click="sortByDate('year')" 
                        :class="{'active':period=='year'}">Год</button>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 flex">
                
            </div>
        </div> 
        <div class="settings-blocks">
            <section class="section settings-content">
                <div class="settings-title">
                    <h3 class="accord" v-collapse-toggle="'auth'">Авторизация</h3>
                </div>
                <v-collapse-wrapper ref="auth" :active="true">
                    <hr>
                    <div class="row mb-4" v-collapse-content>
                        <div class="col-xl-4 col-md-6 settings-item" v-if="breakingList">
                            <fieldset>
                                <legend>Попытки взлома аккаунта студента</legend>
                                <div v-if="loading_breaking" class="loading">
                                    <img :src="'/public/img/load.gif'">
                                </div>
                                <div v-else>
                                    <div class="between">
                                        <span>Записей: {{ breakingList.count }}</span>
                                    </div>
                                    <hr>
                                    <div class="card">
                                        <div class="card-content between" v-for="(item, index) in breakingList.array">
                                            <!-- :class="{'suspicious':item.suspicious}" -->
                                            <p>{{ item.ip }} {{ setPlace(item.place) }} (Попыток: {{ item.count }})</p>
                                            <button @click="redirectReports(item, breakingList.type)" class="btn btn-primary"><i class='bx bx-right-arrow-alt'></i></button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-md-6 settings-item" v-if="plunkList">
                            <fieldset>
                                <legend>Перебор аккаунтов студентов</legend>
                                <div v-if="loading_plunk" class="loading">
                                    <img :src="'/public/img/load.gif'">
                                </div>
                                <div v-else>
                                    <div class="between">
                                        <span>Записей: {{ plunkList.count }}</span>
                                    </div>
                                    <hr>
                                    <div class="card">
                                        <div class="card-content between" v-for="(item, index) in plunkList.array">
                                            <!-- :class="{'suspicious':item.suspicious}" -->
                                            <p>{{ item.ip }} {{ setPlace(item.place) }} (Входов: {{ item.count }})</p>
                                            <button @click="redirectReports(item, plunkList.type)" class="btn btn-primary"><i class='bx bx-right-arrow-alt'></i></button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-md-6 settings-item" v-if="authList">
                            <fieldset>
                                <legend>Множественный вход</legend>
                                <div v-if="loading_auth" class="loading">
                                    <img :src="'/public/img/load.gif'">
                                </div>
                                <div v-else>
                                    <div class="between">
                                        <span>Записей: {{ authList.count }}</span>
                                    </div>
                                    <hr>
                                    <div class="card">
                                        <div class="card-content between" v-for="(item, index) in authList.array">
                                            <!-- :class="{'suspicious':item.suspicious}" -->
                                            <p>{{ item.user }} (Входов: {{ item.count }})</p>
                                            <button @click="redirectReports(item, authList.type)" class="btn btn-primary"><i class='bx bx-right-arrow-alt'></i></button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </v-collapse-wrapper>  
                <div class="settings-title">
                    <h3 class="accord" v-collapse-toggle="'exams'">Сдача экзаменов</h3>
                </div>
                <v-collapse-wrapper ref="exams" :active="true">
                    <hr>
                    <div class="row mb-4" v-collapse-content>
                        
                    </div>
                </v-collapse-wrapper>
            </section>
        </div>
    </div>
</template>

<script>
    import * as statistics from '../../../services/statistics'
    export default {
        name: 'Home',
        metaInfo: {
            title: 'Личная страница',
            meta: [
                { name:'keywords', content: '' },
                { name:'description', content: '' },
            ]
        },
        data() {
            return {
                loading_breaking: false,
                loading_plunk: false,
                loading_auth: false,
                period: 'today',
                breakingList: [],
                plunkList: [],
                authList: [],
            }
        },
        created() {
            this.getBreaking()
            this.getPlunk()
            this.getAuth()
        },
        mounted() {
            if(localStorage.period) 
                this.period = localStorage.period
        },
        methods: {
            getBreaking() {
                this.loading_breaking = true
                setTimeout(() => {
                    statistics.getBreakingList(this.period).then(response => {
                        this.loading_breaking = false
                        this.breakingList = response
                    }).catch(errors => {
                        this.loading_breaking = false
                        this.$toast.error(errors.response.data.message)
                    })
                }, 100)
            },
            getPlunk() {
                this.loading_plunk = true
                setTimeout(() => {
                    statistics.getPlunkList(this.period).then(response => {
                        this.loading_plunk = false
                        this.plunkList = response
                    }).catch(errors => {
                        this.loading_plunk = false
                        this.$toast.error(errors.response.data.message)
                    })
                }, 100)
            },
            getAuth() {
                this.loading_auth = true
                setTimeout(() => {
                    statistics.getAuthList(this.period).then(response => {
                        this.loading_auth = false
                        this.authList = response
                    }).catch(errors => {
                        this.loading_auth = false
                        this.$toast.error(errors.response.data.message)
                    })
                }, 100)
            },
            sortByDate(period) {
                this.period = period
                localStorage.period = this.period
                this.getBreaking()
                this.getPlunk()
                this.getAuth()
            },
            setPlace(place) {
                if(place !== false) {
                    return '-' + place
                }
                return null
            },
            redirectReports(item, type) {
                this.$router.push({
                    name:'detail', 
                    query: {
                        type: type,
                        period: this.period,
                        ip: item.ip,
                        user: item.user
                    }
                })
            }
        }
    }
</script>

<style>
    .select-params {
        border-width: 1px;
        padding: 1rem 2rem;
        border-radius: 0.5rem;
    }
    .select-params.row {
        margin-left: 0;
        margin-right: 0;
    }
    .card {
        max-height: 40vh;
        overflow-y: auto;
    }
    .card-title {
        font-weight: 600;
        font-size: 1.25rem;
        line-height: 1.75rem;
        text-align: center;
        margin-bottom: 0.5rem;
    }
    .card .card-content {
        padding: 0.5rem 0.5rem;
    }
    .between {
        display: flex;
        justify-content: space-between;
    }
    .between button {
        border-width: 1px;
        border-radius: 50%;
        padding: 0.75rem;
        width: 1rem;
        height: 1rem;
    }
    .between:hover {
        background-color: rgb(229, 229, 229, .8);
    }
    .between button i {
        font-size: 1rem;
    }
    .card .card-content .suspicious,
    .table tbody tr.suspicious {
        color: red;
    }
    @media(max-width: 1400px) {
        .select-params .options {
            justify-content: center;
            flex-wrap: wrap;
        }
        .select-params .options .min-btn {
            margin: 0.15rem 0;
        }
    }
</style>