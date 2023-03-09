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
                        <th width="15%">Имя</th>
                        <th width="15%">Email</th>
                        <th width="15%">Роль</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in usersList.data">
                        <td>{{ item.name }}</td>
                        <td>{{ item.email }}</td>
                        <td>{{ item.role.name }}</td>
                        <td class="buttons-table">
                            <button type="button" class="btn btn-square btn-primary" @click="showModal(item)">
                                <i class="bx bx-edit"></i>
                                <!--<span>Изменить</span>-->
                            </button>
                            <button type="button" class="btn btn-square btn-primary" @click="deleteUsers(item)">
                                <i class="bx bx-trash"></i>
                                <!--<span>Удалить</span>-->
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <pagination :data="usersList" @pagination-change-page="getUsers"></pagination>
        <div class="modal" :class="{'view':modal}">
            <div class="shadow-window"></div>
            <div class="modal-container">
                <div class="modal-content">
                    <button type="button" class="modal-close" @click="showModal">
                        <i class="bx bx-x"></i>
                    </button>
                    <div class="modal-body">
                        <h3>{{ titleModal }}</h3>
                        <form @submit.prevent="saveUsers">
                            <div>
                                <label for="name">Введите Имя</label>
                                <input type="text" id="name" v-model="form.name" :class="{'is-invalid':errors.name}">
                            </div>
                            <div>
                                <label for="email">Введите Email</label>
                                <input type="text" id="email" v-model="form.email" :class="{'is-invalid':errors.email}">
                            </div>
                            <div>
                                <label for="email">Введите Пароль</label>
                                <input type="password" id="password" v-model="form.password" :class="{'is-invalid':errors.password}">
                            </div>
                            <div class="flex-checkbox">
                                <label>Выберите роль</label>
                                <label class="input-checkbox" v-for="(role, index) in rolesList" :key="role.id">
                                    <input type="radio" :id="role.id" v-model="form.role_id" :value="role.id">
                                    <span>{{ role.name }}</span>
                                </label>
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
    const formData = {name: '', email: '', password: '', role_id: ''}
    import pagination from 'laravel-vue-pagination'
    import * as roles from './../../../services/admin/roles'
    import * as users from './../../../services/admin/users'
    export default {
        name: 'Control',
        metaInfo: {
            title: 'Управление пользователями',
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
                usersList: {},
                rolesList: {},
                form: Object.assign({}, formData),
                errors: false,
                modal: false,
                titleModal: ''
            }
        },
        mounted() {
            this.getUsers()
            this.getRoles()
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
            disableSubmission(btn) {
                btn.setAttribute('disabled', 'disabled')
                this.btnHtml = btn.innerHTML
                btn.innerHTML = 'Загрузка...'
            },
            enableSubmission(btn) {
                btn.removeAttribute('disabled')
                btn.innerHTML = this.btnHtml
            },
            getRoles() {
                roles.getRolesList().then(response => {
                    this.rolesList = response.data.roles
                }).catch(errors => {
                    this.errorsSwitch(errors)
                })
            },
            getUsers(page = 1) {
                this.loading = true
                setTimeout(() => {
                    users.getUsersList(page).then(response => {
                        this.loading = false
                        this.usersList = response.data.users
                    }).catch(errors => {
                        this.errorsSwitch(errors)
                    })
                }, 0)
            },
            resetForm() {
                this.enableSubmission(this.$refs.btnSubmit)
                this.errors = false
                this.modal = false
                this.form = Object.assign({}, formData)
            },
            saveUsers() {
                this.disableSubmission(this.$refs.btnSubmit)
                let data = new FormData()
                data.append('name', this.form.name ?? '')
                data.append('email', this.form.email ?? '')
                data.append('password', this.form.password ?? '')
                data.append('role_id', this.form.role_id ?? '')
                if(this.form.id) {
                    data.append('_method', 'put')
                    users.updateUsers(this.form.id, data).then(response => {
                        this.resetForm()
                        this.usersList.data.map(users => {
                            if(users.id == response.users.id) {
                                for (let key in response.users) {
                                    users[key] = response.users[key]
                                }
                            }
                        })
                        this.$toast.success(response.message)
                    }).catch(errors => {
                        this.errorsSwitch(errors)
                    })
                } else {
                    users.createUsers(data).then(response => {
                        this.resetForm()
                        this.usersList.data.unshift(response.users)
                        this.$toast.success(response.message)
                    }).catch(errors => {
                        this.errorsSwitch(errors)
                    })
                }
            },
            deleteUsers(value) {
                if (!window.confirm('Вы действительно хотитете удалить Пользователя: ' + value.email + '?')) {
                    return
                }
                users.deleteUsers(value.id).then(response => {
                    this.errors = false
                    this.usersList.data = this.usersList.data.filter(obj => {
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

<style>
    .flex-checkbox > :not([hidden]) ~ :not([hidden]) {
        margin-top: 0.725rem;
        margin-bottom: 0.725rem;
    }
</style>