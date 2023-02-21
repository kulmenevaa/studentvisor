<template>
    <div>
        <form @submit.prevent="saveNotification">
            <div class="settings-content">
                <div class="settings-title">
                    <h3>Умедомление пользователей</h3>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 settings-item">
                        <fieldset>
                            <legend>Уведомлять о подозрительных попытках входа</legend>
                            <div class="radio-inline">
                                <div class="radio-item">
                                    <input type="radio" id="entry-yes" v-model="form.array[0].status" name="entry" :value="1" required>
                                    <label for="entry-yes">Да</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="entry-no" v-model="form.array[0].status" name="entry" :value="0">
                                    <label for="entry-no">Нет</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 settings-item">
                        <fieldset>
                            <legend>Уведомлять о подозрительных попытках сдачи экзаменов</legend>
                            <div class="radio-inline">
                                <div class="radio-item">
                                    <input type="radio" id="exams-yes" v-model="form.array[1].status" name="exams" :value="1" required>
                                    <label for="exams-yes">Да</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="exams-no" v-model="form.array[1].status" name="exams" :value="0">
                                    <label for="exams-no">Нет</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import * as settings from '../../../services/settings'
    const formData = {
        array: [
            {
                type: 'entry',
                status: ''
            },
            {
                type: 'exams',
                status: ''
            }
        ]
    }
    export default {
        name: 'Notification',
        metaInfo: {
            title: 'Уведомления',
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
                        }
                    })
                })   
            })
        },
        methods: {
            saveNotification() {
                let data = new FormData()
                data.append('array', JSON.stringify(this.form.array))
                settings.saveSettings(data).then(response => {
                    this.$toast.success(response.message)
                    this.errors = false
                }).catch(errors => {
                    this.$toast.error(errors.response.data.message)
                    this.errors = true
                })
            }
        }
    }
</script>