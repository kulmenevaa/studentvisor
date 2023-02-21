<template>
    <div>
        <div class="options-block">
            <div class="options">
                <button type="button" class="btn btn-primary" @click="showModal">Добавить</button>
            </div>
        </div> 
        <div class="table-responsive">
            <div v-if="loading" class="loading">
                <img :src="'/public/img/load.gif'">
            </div>
            <table class="table" v-else>
                <thead>
                    <tr>
                        <th width="15%">IP</th>
                        <th width="15%">Местоположение</th>
                        <th width="20%">Примечание</th>
                        <th width="20%">Дата добавления</th>
                        <th width="10%">Признак</th>
                        <th width="5%">Проверяется</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in suspiciousList.data" :key="item.id">
                        <td>{{ item.ip }}</td>
                        <td>{{ item.place }}</td>
                        <td>{{ item.desc }}</td>
                        <td>{{ item.created_at}}</td>
                        <td>{{ setType(item.type) }}</td>
                        <td>{{ setCheck(item.check) }}</td>
                        <td class="buttons-table">
                            <button type="button" class="btn btn-square btn-primary" @click="showModal(item)">
                                <i class="bx bx-edit"></i>
                                <!--<span>Изменить</span>-->
                            </button>
                            <button type="button" class="btn btn-square btn-primary" @click="deleteSuspicious(item)">
                                <i class="bx bx-trash"></i>
                                <!--<span>Удалить</span>-->
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <pagination :data="suspiciousList" @pagination-change-page="getSuspicious"></pagination>
        <div class="modal" :class="{'view':modal}">
            <div class="shadow-window"></div>
            <div class="modal-container">
                <div class="modal-content">
                    <button type="button" class="modal-close" @click="showModal">
                        <i class="bx bx-x"></i>
                    </button>
                    <div class="modal-body">
                        <h3>{{ titleModal }} IP</h3>
                        <form @submit.prevent="saveSuspicious">
                            <div>
                                <label for="ip">Введите IP</label>
                                <input type="text" id="ip" v-model="form.ip" :class="{'is-invalid':errors.ip}">
                            </div>
                            <div>
                                <label for="desc">Введите Описание</label>
                                <textarea id="desc" rows="6" v-model="form.desc"></textarea>
                            </div>
                            <label class="input-checkbox">
                                <input type="checkbox" id="check" v-model="form.check" :false-value="0" :true-value="1">
                                <span>Включить проверку</span>
                            </label>
                            <div class="form-options">
                                <button type="button" class="btn btn-white" @click="showModal">Отмена</button>
                                <button type="submit" class="btn btn-primary" ref="btnSubmit">Сохранить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</template>

<script>
    const formData = {ip: '', desc: '', check: 0}
    import * as suspicious from '../../../services/suspicious'
    import pagination from 'laravel-vue-pagination'
    export default {
        name: 'Suspicious',
        metaInfo: {
            title: 'Подозрительные',
            meta: [
                { name:'keywords', content: '' },
                { name:'description', content: '' },
            ]
        },
        components: {
            pagination
        },
        data() {
            return {
                loading: false,
                suspiciousList: {},
                form: Object.assign({}, formData),
                errors: false,
                modal: false,
                titleModal: ''
            }
        },
        mounted() {
            this.getSuspicious()
        },
        methods: {
            showModal(data) {
                this.errors = false
                this.modal = !this.modal
                this.form = {...data}
                if(this.form.id) {
                    this.titleModal = 'Изменение' 
                } else {
                    this.form = Object.assign({}, formData)
                    this.titleModal = 'Добавление'
                }
            }, 
            disableSubmission(btn) {
                btn.setAttribute('disabled', 'disabled')
                this.btnHtml = btn.innerHTML
                btn.innerHTML = 'Загрузка...'
            },
            enableSubmission(btn) {
                btn.removeAttribute('disabled')
                btn.innerHTML = this.btnHtml
            },
            errorsSwitch(errors) {
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
                this.enableSubmission(this.$refs.btnSubmit)
            },
            resetForm() {
                this.enableSubmission(this.$refs.btnSubmit)
                this.errors = false
                this.modal = false
                this.form = Object.assign({}, formData)
            },
            getSuspicious(page = 1) {
                this.loading = true
                setTimeout(() => {
                    suspicious.getSuspiciousList(page).then(response => {
                        this.loading = false
                        this.suspiciousList = response.data.suspicious
                    }).catch(errors => {
                        this.errorsSwitch(errors)
                    })
                }, 0)
            },
            saveSuspicious() {
                this.disableSubmission(this.$refs.btnSubmit)
                let data = new FormData()
                data.append('ip', this.form.ip ?? '')
                data.append('desc', this.form.desc ?? '')
                data.append('check', this.form.check ?? '')
                if(this.form.id) {
                    data.append('_method', 'put')
                    suspicious.updateSuspicious(this.form.id, data).then(response => {
                        this.resetForm()
                        this.suspiciousList.data.map(suspicious => {
                            if(suspicious.id == response.suspicious.id) {
                                for (let key in response.suspicious) {
                                    suspicious[key] = response.suspicious[key]
                                }
                            }
                        })
                        this.$toast.success(response.message)
                    }).catch(errors => {
                        this.errorsSwitch(errors)
                    })
                } else {
                    suspicious.createSuspicious(data).then(response => {
                        this.resetForm()
                        this.suspiciousList.data.unshift(response.suspicious)
                        this.$toast.success(response.message)
                    }).catch(errors => {
                        this.errorsSwitch(errors)
                    })
                }
            },
            deleteSuspicious(value) {
                if (!window.confirm('Вы действительно хотитете удалить IP: ' + value.ip + '?')) {
                    return
                }
                suspicious.deleteSuspicious(value.id).then(response => {
                    this.errors = false
                    this.suspiciousList.data = this.suspiciousList.data.filter(obj => {
                        return obj.id != value.id
                    })
                    this.$toast.success(response.message)
                }).catch(errors => {
                    this.errorsSwitch(errors)
                })
            },
            setCheck(check) {
                return check == 1 ? 'Да' : 'Нет'
            },
            setType(type) {
                switch(type) {
                    case 'default':
                        return 'Добавленный вручную'
                    case 'plunk':
                        return 'Перебор аккаунтов'
                    case 'breaking':
                        return 'Взлом аккаунтов'
                }
            }
        }
    }
</script>

<style>
    .pagination {
        margin: 1rem 0;
    }
    .pagination, .pagination .page-item {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .pagination .page-item {
        border-width: 1px;
        border-color: rgb(128, 128, 128, .5);
    }
    .pagination .page-item .page-link {
        padding: 0.45rem 1rem;
        cursor: pointer;
        font-weight: 600;
    }
    .pagination .page-item:hover {
        background-color: rgb(128, 128, 128, .3);
    }
    .pagination .page-item.active {
        border-color: #3c388d;
        background-color: #3c388d;
        color: #fff;
    }
    .pagination .page-item .sr-only {
        display: none;
    }
</style>