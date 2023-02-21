<template>
    <div id="nav">
        <header>
            <nav class="nav-top">
                <div class="container">
                    <router-link :to="{name:'home'}" class="logo">
                        <img :src="image">
                        <!--<h1>ФДО Мониторинг</h1>-->
                    </router-link>
                    <div class="navbar">
                        <button type="button" class="menu" @click="toggleMobile()">
                            <i class="bx bx-menu"></i>
                        </button>
                        <div v-if="$store.state.profile">
                            <button type="button" class="cache" @click="clearCache()">
                                <i class='bx bx-data' ></i>
                            </button>
                        </div>
                        <div class="relative" v-if="$store.state.profile">
                            <button type="button" class="avatar" @click="toggleProfile()">
                                <img :src="avatar">
                            </button>
                            <div class="mobile-menu" :class="{'active':profileMenu}">
                                <div class="mobile-menu-info">
                                    <div><b>{{ $store.state.profile.name }}</b></div>
                                    <div>{{ $store.state.profile.email }}</div>
                                </div>
                                <ul>
                                    <!--
                                    <li>
                                        <router-link :to="{name:'notification'}">Уведомления</router-link>
                                    </li>
                                    -->
                                    <li>
                                        <button type="button" @click="logout">Выйти</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <nav class="nav-bottom">
                <div class="container">
                    <div class="navbar" :class="{'active':showMenu}">
                        <ul>
                            <li class="active">
                                <router-link :to="{name:'home'}" :class="{'active': subIsActive('/detail')}">Личная страница</router-link>
                            </li>
                            <li>
                                <router-link :to="{name:'reports.authorized'}" :class="{'active': subIsActive('/reports')}">Отчеты</router-link>
                            </li>
                            <li>
                                <router-link :to="{name:'suspicious'}">Подозрительные</router-link>
                            </li>
                            <li>
                                <router-link :to="{name:'settings.notification'}" :class="{'active': subIsActive('/settings')}">Настройки</router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main class="container">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
    import * as auth from '../services/auth'
    import * as settings from '../services/settings'
    export default {
        name: 'App',
        data() {
            return {
                image: '/public/img/logo.svg',
                avatar: '/public/img/no-avatar.png',
                showMenu: true,
                profileMenu: false
            }
        },
        beforeCreate() {
            if (auth.isLoggedIn()) {
                auth.getProfile().then(response => {
                    this.$store.dispatch('authenticate', response.data)
                }).catch(errors => {
                    auth.logout();
                })
            }
        },
        mounted() {
            this.toggleMobile()
        },
        methods: {
            toggleMobile() {
                this.showMenu = !this.showMenu
            },
            toggleProfile() {
                this.profileMenu = !this.profileMenu
            },
            subIsActive(input) {
                const paths = Array.isArray(input) ? input : [input]
                return paths.some(path => {
                    return this.$route.path.indexOf(path) === 0
                })
            },
            logout() {
                auth.logout()
                this.$router.push({name:'login'})
            },
            clearCache() {
                settings.clearCache().then(response => {
                    this.$toast.success(response.message)
                })
            }
        }
    }
</script>

<style>
    :active, :hover, :focus {
        outline: 0;
        outline-offset: 0;
    }
    hr {
        margin: 0.5rem 0;
    }
    header {
        position: relative;
        background-color: rgb(255 255 255);
        border-bottom-width: 1px;
    }
    header .nav-top .container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 0.5rem 0 0.5rem;
        margin: 0 auto;
    }
    header .nav-top .container .logo {
        display: flex;
        align-items: center;
    }
    header .nav-top .container .logo img {
        /* width: 2.5rem; */
        width: 18rem;
    }
    header .nav-top .container .logo h1 {
        align-self: center;
        margin-left: 0.5rem;
        font-size: 1.25rem;
        line-height: 1.75rem;
        font-weight: 600;
        white-space: nowrap;
        display: none;
    }
    header .nav-top .container .navbar {
        display: flex;
        align-items: center;
    }
    header .nav-top .container .navbar > :not([hidden]) ~ :not([hidden]) {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    header .nav-top .container .navbar .avatar {
        display: flex;
        font-size: 0.875rem;
        line-height: 1.25rem;
        margin-right: 0.75rem;
        border-width: 3px;
        border-radius: 9999px;
    }
    header .nav-top .container .navbar .avatar img {
        width: 2rem;
        height: 2rem;
        border-radius: 9999px;
    }
    header .nav-top .container .navbar .menu,
    header .nav-top .container .navbar .cache {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.5rem;
        border-radius: 0.5rem;
    }
    header .nav-top .container .navbar .menu i,
    header .nav-top .container .navbar .cache i {
        font-size: 1.25rem;
        line-height: 1.75rem;
        color: rgb(107 114 128);
    }
    header .nav-top .container .navbar .menu:hover,
    header .nav-top .container .navbar .cache:hover  {
        background-color: rgb(243 244 246);
    }

    header .nav-bottom .container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0;
        margin: 0 auto;
    }
    header .nav-bottom .navbar {
        display: none;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    header .nav-bottom .navbar.active {
        display: block;
    }
    header .nav-bottom .navbar ul {
        display: flex;
        flex-direction: column;
        padding: 0;
        margin: 1rem 0rem 0.5rem 0rem;
    }
    header .nav-bottom .navbar ul li a {
        display: block;
        padding: 0.5rem 1rem;
        color: rgb(55 65 81);
        border-radius: 0.25rem;
        font-size: 1rem;
        line-height: 1.5rem;
    }
    header .nav-bottom .navbar ul li a.active {
        color: #3c388d;
        font-weight: 700;
    }
    @media (min-width: 768px) {
        header .nav-top .container .logo h1 {
            display: block;
        }
        header .nav-top .container .navbar .menu {
            display: none;
        }
        header .nav-bottom .navbar {
            display: flex;
            width: auto;
        }
        header .nav-bottom .navbar ul {
            flex-direction: row;
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
            padding: 0.5rem;
            margin: 0;
        }
        header .nav-bottom .navbar ul > :not([hidden]) ~ :not([hidden]) {
            --tw-space-x-reverse: 0;
            margin-right: calc(2rem * var(--tw-space-x-reverse));
            margin-left: calc(2rem * calc(1 - var(--tw-space-x-reverse)));
        }
        header .nav-bottom .navbar ul li a {
            padding: 0rem 1rem;
        }
    }
    
    main.container {
        margin: 0 auto;
        padding: 1.5rem .5rem;
    }

    /* */
    .search-block {
        position: relative;
    }
    .search-block .search-icon {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        display: flex;
        align-items: center;
        padding-left: 0.75rem;
        pointer-events: none;
    }
    .search-block .search-icon i {
        --tw-text-opacity: 1;
        color: rgb(107 114 128 / var(--tw-text-opacity));
        font-size: 1.25rem;
    }
    .search-block input {
        display: block;
        width: 100%;
        padding: 1rem;
        padding-left: 2.5rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        --tw-text-opacity: 1;
        color: rgb(17 24 39 / var(--tw-text-opacity));
        border-width: 1px;
        --tw-border-opacity: 1;
        border-color: rgb(209 213 219 / var(--tw-border-opacity));
        border-radius: 0.5rem;
        --tw-bg-opacity: 1;
        background-color: rgb(249 250 251 / var(--tw-bg-opacity));
    }
    .search-block input:focus {
        outline: none;
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb(59 130 246 / var(--tw-ring-opacity));
        --tw-border-opacity: 1;
        border-color: rgb(59 130 246 / var(--tw-border-opacity));
    }
    .search-block button {
        position: absolute;
        right: 0.625rem;
        bottom: 0.625rem;
        --tw-text-opacity: 1;
        color: rgb(255 255 255 / var(--tw-text-opacity));
        --tw-bg-opacity: 1;
        background-color: rgb(29 78 216 / var(--tw-bg-opacity));
        font-weight: 500;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        padding: 0.5rem 1rem;
    }
    .search-block button:hover {
        --tw-bg-opacity: 1;
        background-color: rgb(30 64 175 / var(--tw-bg-opacity));
    }
    .search-block button:focus {
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(4px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        outline: 2px solid transparent;
        outline-offset: 2px;
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb(147 197 253 / var(--tw-ring-opacity));
    }

    .section {
        padding: 2rem 2.5rem;
    }

    .btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        border-radius: 0.375rem;
        border-width: 1px;
        --tw-border-opacity: 1;
        border-color: rgb(209 213 219 / var(--tw-border-opacity));
        padding: 0.625rem 1.25rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        font-weight: 500;
        --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --tw-shadow-colored: 0 1px 2px 0 var(--tw-shadow-color);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
    }
    .btn.min-btn {
        display: block;
        border-radius: 0;
        padding: 0.5rem 1.25rem;
        min-width: 6rem;
        text-align: center;
    }
    .btn i {
        font-size: 1rem;
        line-height: 1.5rem;
    }
    .btn span {
        margin-left: 0.5rem
    }
    .btn.btn-square {
        padding: 0.35rem 0.65rem;
    }
    .btn.btn-primary,
    .btn.btn-outline-primary.active {
        color: #fff;
        background-color: #3c388d;
    }
    .btn.btn-primary:disabled {
        background-color: rgb(87, 79, 79);
        opacity: 0.5;
    }
    .btn.btn-outline-primary {
        border-width: 1px;
        border-color: #3c388d;
        background-color: #fff;
        color: #3c388d;
    }
    .btn.btn-white {
        background-color: #fff;
        color: rgb(87, 79, 79);
    }
    .btn:hover {
        opacity: .9;
    }
    .btn:focus {
        outline: 2px solid transparent;
        outline-offset: 2px;
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb(99 102 241 / var(--tw-ring-opacity));
        --tw-ring-offset-width: 2px;
    }

    .modal {
        position: fixed;
        top: 0px;
        right: 0px;
        bottom: 0px;
        left: 0px;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        overflow-x: hidden;
        overflow-y: auto;
        z-index: 100;
    }
    .modal.view {
        display: flex;
    }
    .modal .modal-container {
        position: relative;
        width: 100%;
        height: auto;
        max-width: 28rem;
    }
    .modal .modal-container .modal-content {
        position: relative;
        border-radius: 0.5rem;
        background-color: #fff;
        --tw-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --tw-shadow-colored: 0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
    }
    .modal .modal-container .modal-content .modal-close {
        position: absolute;
        top: 0.75rem;
        right: 0.625rem;
        --tw-text-opacity: 1;
        color: rgb(156 163 175 / var(--tw-text-opacity));
        background-color: transparent;
        border-radius: 0.5rem;
        padding: 0.375rem;
        margin-left: auto;
        display: inline-flex;
        align-items: center;
    }
    .modal .modal-container .modal-content .modal-close:hover {
        --tw-bg-opacity: 1;
        background-color: rgb(229 231 235 / var(--tw-bg-opacity));
        --tw-text-opacity: 1;
        color: rgb(17 24 39 / var(--tw-text-opacity));
    }
    .modal .modal-container .modal-content .modal-close i {
        font-size:1.5rem;
        line-height: 1.5rem;
    }
    .modal .modal-container .modal-content .modal-body {
        padding: 1.5rem 2rem;
    }
    .modal .modal-container .modal-content .modal-body h3 {
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        line-height: 1.75rem;
        font-weight: 500;
    }
    .shadow-window {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        --tw-bg-opacity: .8;
        background-color: rgb(0 0 0 / var(--tw-bg-opacity));
    }

    form > :not([hidden]) ~ :not([hidden]) {
        --tw-space-y-reverse: 0;
        margin-top: calc(1.5rem * calc(1 - var(--tw-space-y-reverse)));
        margin-bottom: calc(1.5rem * var(--tw-space-y-reverse));
    }
    form label {
        display: block;
        font-size: 0.875rem;
        line-height: 1.25rem;
        font-weight: 500;    
    }
    form .fields-group {
        position: relative;
        display: flex;
    }
    form .fields-group > :not([hidden]) ~ :not([hidden]) {
        --tw-space-y-reverse: 0;
        margin-left: calc(1.5rem * calc(1 - var(--tw-space-y-reverse)));
        margin-right: calc(1.5rem * var(--tw-space-y-reverse));
    }
    form .field {
        width: 100%;
    }
    form .field:not(:last-child) {
        margin-bottom: 1rem;
    }
    form .field .field-between {
        display: flex; 
        justify-content: space-between;
    }
    form .field .field-between select {
        margin-left: 0.5rem;
    }
    form input[type="text"],
    form input[type="number"],
    form input[type="date"],
    form input[type="email"],
    form input[type="password"],
    form select,
    form textarea {
        display: block;
        --tw-bg-opacity: 1;
        background-color: rgb(249 250 251 / var(--tw-bg-opacity));
        border-width: 1px;
        --tw-text-opacity: 1;
        color: rgb(17 24 39 / var(--tw-text-opacity));
        font-size: 0.875rem;
        line-height: 1.25rem;
        border-radius: 0.5rem;
        --tw-border-opacity: 1;
        border-color: rgb(209 213 219 / var(--tw-border-opacity));
        padding: 0.625rem;
        width: 100%;
    }
    form input[type="text"]:focus,
    form input[type="number"]:focus,
    form input[type="date"]:focus,
    form input[type="email"]:focus,
    form input[type="password"]:focus,
    form select:focus,
    form textarea:focus {
        --tw-ring-opacity: 1;
        --tw-ring-color: rgb(59 130 246 / var(--tw-ring-opacity));
        --tw-border-opacity: 1;
        border-color: rgb(59 130 246 / var(--tw-border-opacity));
    }
    form .form-options {
        display: flex;
        justify-content: space-between;
    }
    form input.is-invalid,
    form textarea.is-invalid {
        border-color: #ff5252
    }
    form .input-checkbox {
        display: flex;
    }
    form .input-checkbox input[type="checkbox"],
    input[type="radio"]  {
        width: 1rem;
        height: 1rem;
        border-radius: 0.25rem;
    }
    form .input-checkbox span {
        line-height: 1rem;
        margin-left: 0.5rem;
    }

    legend {
        text-align: center;
        border-width: 1px;
        border-radius: .5rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    fieldset {
        padding: 2rem 1.5rem;
        border-width: 1px;
    }
    .radio-inline {
        display: flex;
        justify-content: center;
    }
    .radio-inline span {
        font-size: 0.925rem;
        font-weight: 600;
        margin-right: 1.5rem;
        color: #3c388d;
    }
    .radio-inline .radio-item {
        display: flex;
        align-items: center;
    }
    .radio-inline .radio-item:not(:last-child) {
        margin-right: 2rem;
    }
    .radio-inline .radio-item label {
        margin-left: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        font-weight: 500;
    }

    .options-block {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .options-block .options {
        display: flex;
    }
    .table-responsive {
        position: relative;
        overflow-x: auto;
    }
    .table-responsive table.table {
        width: 100%;
        font-size: 0.875rem;
        line-height: 1.25rem;
        text-align: left;
    }
    .table-responsive table.table thead {
        font-size: 0.75rem;
        line-height: 1rem;
        text-transform: uppercase;
        --tw-bg-opacity: 1;
        background-color: rgb(249 250 251 / var(--tw-bg-opacity));
    }
    .table-responsive table.table tr {
        border-width: 1px;
    }
    .table-responsive table.table thead tr th,
    .table-responsive table.table tbody tr td {
        border-width: 1px;
        padding: 0.75rem 1.5rem;
    }
    .buttons-table {
        display: flex;
        justify-content: center;
    }
    .buttons-table > :not([hidden]) ~ :not([hidden]) {
        --tw-space-y-reverse: 0;
        margin-left: calc(0.5rem * calc(1 - var(--tw-space-y-reverse)));
        margin-right: calc(0.5rem * var(--tw-space-y-reverse));
    }
    @media (max-width: 768px) {
        form .fields-group {
            display: block;
        }
        form .fields-group > :not([hidden]) ~ :not([hidden]) {
            margin-left: 0;
            margin-right: 0;
        }
    }
    .mobile-menu {
        position: absolute;
        top: 3rem;
        right: 0;
        border-radius: 0.25rem;
        --tw-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --tw-shadow-colored: 0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        min-width: 11rem;
        background-color: #fff;
        z-index: 101;
        display: none;
    }
    .mobile-menu.active {
        display: block;
    }
    .mobile-menu > :not([hidden]) ~ :not([hidden]) {
        --tw-divide-y-reverse: 0;
        border-top-width: calc(1px * calc(1 - var(--tw-divide-y-reverse)));
        border-bottom-width: calc(1px * var(--tw-divide-y-reverse));
    }
    .mobile-menu .mobile-menu-info {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    .mobile-menu ul {
        font-size: 0.875rem;
        line-height: 1.25rem;
        --tw-text-opacity: 1;
        color: rgb(55 65 81 / var(--tw-text-opacity));
    }
    .mobile-menu ul li a,
    .mobile-menu ul li button {
        display: block;
        width:100%;
        padding: 0.5rem 1rem;
        text-align: center;
    }
    .mobile-menu ul li a:hover,
    .mobile-menu ul li button:hover {
        --tw-bg-opacity: 1;
        background-color: rgb(243 244 246 / var(--tw-bg-opacity));
    }
    .loading {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>