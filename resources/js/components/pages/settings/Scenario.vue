<template>
    <div>
        <form @submit.prevent="saveScenario">
            <div class="settings-content">
                <div class="settings-title">
                    <h3 class="accord" v-collapse-toggle="'scenario_1'">Сценарии авторизации</h3>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
                <v-collapse-wrapper ref="scenario_1" :active="true">
                    <hr>
                    <div class="row" v-collapse-content>
                        <div class="col-xl-6 settings-item">
                            <fieldset>
                                <legend>Попытка взлома аккаунта студента</legend>
                                <div class="radio-inline">
                                    <span>Обрабатывается</span>
                                    <div class="radio-item">
                                        <input type="radio" id="breaking-yes" v-model="form.array[0].status" name="breaking" :value="1" required>
                                        <label for="breaking-yes">Да</label>
                                    </div>
                                    <div class="radio-item">
                                        <input type="radio" id="breaking-no" v-model="form.array[0].status" name="breaking" :value="0">
                                        <label for="breaking-no">Нет</label>
                                    </div>
                                </div>
                                <table class="adaptive-table">
                                    <tr>
                                        <th>Описание</th>
                                        <td>Более {{ form.array[0].amount }} раз некорректно введены данные авторизации с одного компьютера (и браузера) за {{ form.array[0].action }} {{  setMetering(form.array[0].metering) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Настройки</th>
                                        <td>
                                            <div class="fields-group">
                                                <div class="field">
                                                    <label for="breaking-amount">Количество подтверждение</label>
                                                    <input type="number" id="breaking-amount" v-model="form.array[0].amount">
                                                </div>
                                                <div class="field">
                                                    <label for="breaking-action">Время действия</label>
                                                    <div class="field-between">
                                                        <input type="number" id="breaking-action" v-model="form.array[0].action">
                                                        <select id="breaking-metering" v-model="form.array[0].metering">
                                                            <option value="SECOND">сек</option>
                                                            <option value="MINUTE">мин</option>
                                                            <option value="HOUR">час</option>
                                                            <option value="DAY">дн</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 settings-item">
                            <fieldset>
                                <legend>Перебор аккаунтов студентов</legend>
                                <div class="radio-inline">
                                    <span>Обрабатывается</span>
                                    <div class="radio-item">
                                        <input type="radio" id="plunk-yes" v-model="form.array[1].status" name="plunk" :value="1" required> 
                                        <label for="inline-radio">Да</label>
                                    </div>
                                    <div class="radio-item">
                                        <input type="radio" id="plunk-no" v-model="form.array[1].status" name="plunk" :value="0">
                                        <label for="plunk-no">Нет</label>
                                    </div>
                                </div>
                                <table class="adaptive-table">
                                    <tr>
                                        <th>Описание</th>
                                        <td>Более {{ form.array[1].amount }} раз студент авторизовался с одного компьютера (и браузера) за {{ form.array[1].action }} {{  setMetering(form.array[1].metering) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Настройки</th>
                                        <td>
                                            <div class="fields-group">
                                                <div class="field">
                                                    <label for="plunk-amount">Количество студентов</label>
                                                    <input type="number" id="plunk-amount" v-model="form.array[1].amount">
                                                </div>
                                                <div class="field">
                                                    <label for="breaking-action">Время действия</label>
                                                    <div class="field-between">
                                                        <input type="number" id="plunk-action" v-model="form.array[1].action">
                                                        <select id="plunk-metering" v-model="form.array[1].metering">
                                                            <option value="SECOND">сек</option>
                                                            <option value="MINUTE">мин</option>
                                                            <option value="HOUR">час</option>
                                                            <option value="DAY">дн</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 settings-item">
                            <fieldset>
                                <legend>Авторизация студента с разных устройств</legend>
                                <div class="radio-inline">
                                    <span>Обрабатывается</span>
                                    <div class="radio-item">
                                        <input type="radio" id="auth-yes" v-model="form.array[2].status" name="auth" :value="1" required>
                                        <label for="auth-yes">Да</label>
                                    </div>
                                    <div class="radio-item">
                                        <input type="radio" id="auth-no" v-model="form.array[2].status" name="auth" :value="0">
                                        <label for="auth-no">Нет</label>
                                    </div>
                                </div>
                                <table class="adaptive-table">
                                    <tr>
                                        <th>Описание</th>
                                        <td>Студент авторизовался одновременно с {{ form.array[2].amount }} устройств в течении {{ form.array[2].action }} {{  setMetering(form.array[2].metering) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Настройки</th>
                                        <td>
                                            <div class="fields-group">
                                                <div class="field">
                                                    <label for="auth-amount">Количество устройств</label>
                                                    <input type="number" id="auth-amount" v-model="form.array[2].amount">
                                                </div>
                                                <div class="field">
                                                    <label for="breaking-action">Время действия</label>
                                                    <div class="field-between">
                                                        <input type="number" id="auth-action" v-model="form.array[2].action">
                                                        <select id="auth-metering" v-model="form.array[2].metering">
                                                            <option value="SECOND">сек</option>
                                                            <option value="MINUTE">мин</option>
                                                            <option value="HOUR">час</option>
                                                            <option value="DAY">дн</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                    </div>
                </v-collapse-wrapper>
            </div>
            <div class="settings-content">
                <div class="settings-title">
                    <h3 class="accord" v-collapse-toggle="'scenario_2'">Сценарии сдачи экзаменов</h3>
                </div>
                <v-collapse-wrapper ref="scenario_2">
                    <hr>
                    <div class="row" v-collapse-content>
                        <div class="col-xl-6 settings-item">
                            <fieldset>
                                <legend>Сдача экзаменов с одного устройства</legend>
                                <div class="radio-inline">
                                    <span>Обрабатывается</span>
                                    <div class="radio-item">
                                        <input type="radio" id="lease-yes" v-model="form.array[3].status" name="lease" :value="1" required>
                                        <label for="lease-yes">Да</label>
                                    </div>
                                    <div class="radio-item">
                                        <input type="radio" id="lease-no" v-model="form.array[3].status" name="lease" :value="0">
                                        <label for="lease-no">Нет</label>
                                    </div>
                                </div>
                                <table class="adaptive-table">
                                    <tr>
                                        <th>Описание</th>
                                        <td>Множество попыток разных студентов сдать экзамено с одного компьютера за {{ form.array[3].action }} {{  setMetering(form.array[3].metering) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Настройки</th>
                                        <td>
                                            <div class="fields-group">
                                                <div class="field">
                                                    <label for="lease-amount">Количество попыток</label>
                                                    <input type="number" id="lease-amount" v-model="form.array[3].amount">
                                                </div>
                                                <div class="field">
                                                    <label for="breaking-action">Время действия</label>
                                                    <div class="field-between">
                                                        <input type="number" id="lease-action" v-model="form.array[3].action">
                                                        <select id="lease-metering" v-model="form.array[3].metering">
                                                            <option value="SECOND">сек</option>
                                                            <option value="MINUTE">мин</option>
                                                            <option value="HOUR">час</option>
                                                            <option value="DAY">дн</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 settings-item">
                            <fieldset>
                                <legend>Зафиксировано нетипичное местоположение устройства</legend>
                                <div class="radio-inline">
                                    <span>Обрабатывается</span>
                                    <div class="radio-item">
                                        <input type="radio" id="place-yes" v-model="form.array[4].status" name="place" :value="1" required>
                                        <label for="place-yes">Да</label>
                                    </div>
                                    <div class="radio-item">
                                        <input type="radio" id="place-no" v-model="form.array[4].status" name="place" :value="0">
                                        <label for="place-no">Нет</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </v-collapse-wrapper>
            </div>
        </form>
    </div>
</template>

<script>
    import * as settings from '../../../services/settings'
    const formData = {
        array: [
            {
                type: 'breaking',
                status: '',
                amount: '',
                action: '',
                metering: ''
            },
            {
                type: 'plunk',
                status: '',
                amount: '',
                action: '',
                metering: ''
            },
            {
                type: 'auth',
                status: '',
                amount: '',
                action: '',
                metering: ''
            },
            {
                type: 'lease',
                status: '',
                amount: '',
                action: '',
                metering: ''
            },
            {
                type: 'place',
                status: '',
                amount: '',
                action: '',
                metering: ''
            }
        ]
    }
    export default {
        name: 'Scenario',
        metaInfo: {
            title: 'Сценарий',
            meta: [
                { name:'keywords', content: '' },
                { name:'description', content: '' },
            ]
        },
        data() {
            return {
                form: Object.assign({}, formData),
                errors: false,
            }
        },
        mounted() {
            settings.getSettingsList().then(response => {
                Object.entries(this.form.array).forEach(function(form) {
                    Object.entries(response.data.settings).forEach(function(value) {
                        if(form[1].type == value[1].type) {
                            form[1].status = value[1].status
                            form[1].amount = value[1].amount
                            form[1].action = value[1].action
                            form[1].metering = value[1].metering
                        }
                    })
                })   
            })
        },
        methods: {
            saveScenario() {
                let data = new FormData()
                data.append('array', JSON.stringify(this.form.array))
                settings.saveSettings(data).then(response => {
                    this.$toast.success(response.message)
                    this.errors = false
                }).catch(errors => {
                    this.$toast.error(errors.response.data.message)
                    this.errors = true
                })
            },
            setMetering(metering) {
                switch(metering) {
                    case 'SECOND':
                        return 'сек.'
                    case 'MINUTE':
                        return 'мин.'
                    case 'HOUR':
                        return 'час.'
                    case 'DAY':
                        return 'дн.'
                }
            }
        }
    }
</script>

<style>
    table.adaptive-table {
        border-top-width: 1px;
        border-color: #3c388d;
        font-size: 0.925rem;
        margin: 1rem 0;
    }
    table.adaptive-table tr:not(:last-child) {
        border-bottom-width: 1px;
    }
    table.adaptive-table tr th {
        text-align: left;
        vertical-align: top;
    }
    table.adaptive-table th,
    table.adaptive-table td {
        padding: 1rem 0.5rem;
    }
    table.adaptive-table td {
        padding-left: 2.5rem
    }
    .v-collapse-content {
        transform: translateY(5%);
        animation: ani 1s forwards;
        display: none;
    }
    .v-collapse-content.v-collapse-content-end {
        display: flex;
    }
    @keyframes ani {
        0% {transform: translateY(5%);}
        100% {transform: translateY(0);}
    }
</style>