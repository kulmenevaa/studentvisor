<template>
    <div>
        <div class="row auth">
            <div class="col-xl-3 col-lg-5 col-md-6">
                <form @submit.prevent="login">
                    <div>
                        <label for="ip">Введите Email</label>
                        <input type="email" id="email" v-model="form.email" :class="{'is-invalid':errors.email}">
                    </div>
                    <div>
                        <label for="password">Введите Пароль</label>
                        <input type="password" id="password" v-model="form.password" :class="{'is-invalid':errors.password}">
                    </div>
                    <label class="input-checkbox">
                        <input type="checkbox" id="remember_me" v-model="form.remember_me" :value="1">
                        <span>Запомнить меня</span>
                    </label>
                    <button type="submit" class="btn btn-primary w-full" ref="btnSubmit">Войти</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import * as auth from '../../../services/auth'
    const formData = {email: '', password: '', remember_me: false}
    export default {
        name: 'Login',
        metaInfo: {
            title: 'Вход',
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
        methods: {
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
            login() {
                this.disableSubmission(this.$refs.btnSubmit)
                auth.login(this.form).then(response => {
                    this.resetForm()
                    this.errors = false
                    this.form = Object.assign({}, formData)
                    this.$router.push({name:'home'})
                    console.log(response)
                }).catch(errors => {
                    this.errorsSwitch(errors)
                })
            },
            logout() {
                auth.logout()
            }
        }
    } 
</script>

<style>
    .row.auth {
        justify-content: center; 
        align-items: center; 
        height: calc(100vh - 18rem);
    }
</style>