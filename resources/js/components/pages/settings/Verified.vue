<template>
    <div>
        <div class="options-block">
            <div class="options">
                <button type="button" class="btn btn-primary" @click="showModal">Добавить</button>
            </div>
        </div> 
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="20%">IP</th>
                        <th width="40%">Описание</th>
                        <th width="25%">Дата добавления</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading">
                        <td colspan="6" class="text-center">Загрузка...</td>
                    </tr>
                    <tr v-else v-for="(item, index) in verifiedList.data">
                        <td>{{ item.ip }}</td>
                        <td>{{ item.desc }}</td>
                        <td>{{ item.created_at}}</td>
                        <td class="buttons-table">
                            <button type="button" class="btn btn-square btn-primary" @click="showModal(item)">
                                <i class="bx bx-edit"></i>
                                <!--<span>Изменить</span>-->
                            </button>
                            <button type="button" class="btn btn-square btn-primary" @click="deleteVerified(item)">
                                <i class="bx bx-trash"></i>
                                <!--<span>Удалить</span>-->
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <pagination :data="verifiedList" @pagination-change-page="getVerified"></pagination>
        <div class="modal" :class="{'view':modal}">
            <div class="shadow-window"></div>
            <div class="modal-container">
                <div class="modal-content">
                    <button type="button" class="modal-close" @click="showModal">
                        <i class="bx bx-x"></i>
                    </button>
                    <div class="modal-body">
                        <h3>{{  titleModal  }} IP</h3>
                        <form @submit.prevent="saveVerified">
                            <div>
                                <label for="ip">Введите IP</label>
                                <input type="text" id="ip" v-model="form.ip" :class="{'is-invalid':errors.ip}">
                            </div>
                            <div>
                                <label for="desc">Введите Описание</label>
                                <textarea id="desc" rows="6" v-model="form.desc"></textarea>
                            </div>
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
    const formData = {ip: '', desc: ''}
    import * as verified from '../../../services/verified'
    import pagination from 'laravel-vue-pagination'
    export default {
        name: 'Verified',
        metaInfo: {
            title: 'Проверенные IP',
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
                verifiedList: {},
                form: Object.assign({}, formData),
                errors: false,
                modal: false,
                titleModal: ''
            }
        },
        mounted() {
            this.getVerified()
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
            getVerified(page = 1) {
                this.loading = true
                setTimeout(() => {
                    verified.getVerifiedList(page).then(response => {
                        this.loading = false
                        this.verifiedList = response.data.verified
                    }).catch(errors => {
                        this.errorsSwitch(errors)
                    }) 
                }, 0)
            },
            saveVerified() {
                this.disableSubmission(this.$refs.btnSubmit)
                let data = new FormData()
                data.append('ip', this.form.ip ?? '')
                data.append('desc', this.form.desc ?? '')
                if(this.form.id) {
                    data.append('_method', 'put')
                    verified.updateVerified(this.form.id, data).then(response => {
                        this.resetForm()
                        this.verifiedList.data.map(verified => {
                            if(verified.id == response.verified.id) {
                                for (let key in response.verified) {
                                    verified[key] = response.verified[key]
                                }
                            }
                        })
                        this.$toast.success(response.message)
                    }).catch(errors => {
                        this.errorsSwitch(errors)
                    })
                } else {
                    verified.createVerified(data).then(response => {
                        this.resetForm()
                        this.verifiedList.data.unshift(response.verified)
                        this.$toast.success(response.message)
                    }).catch(errors => {
                        this.errorsSwitch(errors)
                    })
                }
            },
            deleteVerified(value) {
                if (!window.confirm('Вы действительно хотитете удалить IP: ' + value.ip + '?')) {
                    return
                }
                verified.deleteVerified(value.id).then(response => {
                    this.errors = false
                    this.verifiedList.data = this.verifiedList.data.filter(obj => {
                        return obj.id != value.id
                    })
                    this.$toast.success(response.message)
                }).catch(errors => {
                    this.errorsSwitch(errors)
                })
            },
        }
    }
</script>
