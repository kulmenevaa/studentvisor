<template>
    <div>
        <div class="settings-blocks">
            <section class="section settings-content">
                <div class="settings-title">
                    <h3 class="accord">Управление {{ setSection(this.$route.path) }}</h3>
                    <div class="relative">
                        <button type="button" class="btn btn-primary" @click="toggleMenu">
                            <span>Выберите раздел</span>
                            <i class="bx bx-chevron-right ml-2" :class="{'rotate-90':showMenu}"></i>
                        </button>
                        <div class="admin-menu" :class="{'active':showMenu}">
                            <ul>
                                <li>
                                    <router-link :to="{name:'control'}">Приложение</router-link>
                                </li>
                                <li>
                                    <router-link :to="{name:'control.users'}">Пользователи</router-link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <router-view></router-view>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Control',
        metaInfo: {
            title: 'Управление приложением',
            meta: [
                { name:'keywords', content: '' },
                { name:'description', content: '' },
            ]
        },
        data() {
            return {
                showMenu: true,
            }
        },
        mounted() {
            this.toggleMenu()
        },
        methods: {
            toggleMenu() {
                this.showMenu = !this.showMenu
            },
            setSection(type) {
                const titles = {
                    '/control': 'приложением',
                    '/control/users': 'пользователями',
                };
                return titles[type]
            },
        },
    }
</script>

<style>
    .admin-menu {
        position: absolute;
        top: 3.4rem;
        right:0;
        width: 100%;
        z-index: 10;
        border-radius: 0.5rem;
        --tw-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --tw-shadow-colored: 0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        background-color: #fff;
        border-width: 1px;
        border-color: #3c388d;
        display: none;
    }
    .admin-menu.active {
        display: block;
    }
    .admin-menu ul {
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    .admin-menu ul li a {
        display: block;
        padding: 0.5rem 1rem;
        cursor: pointer;
    }
    .admin-menu ul li:not(:last-child) {
        border-bottom-width: 1px;
        border-color: #3c388d;
    }
    .admin-menu ul li a:hover {
        --tw-bg-opacity: 1;
        background-color: rgb(243 244 246 / var(--tw-bg-opacity));
    }
    .admin-menu ul li:first-child a:hover {
        border-top-right-radius: 0.5rem;
        border-top-left-radius: 0.5rem;
    }
    .admin-menu ul li:last-child a:hover {
        border-bottom-right-radius: 0.5rem;
        border-bottom-left-radius: 0.5rem;
    }
    .rotate-90 {
        transform: rotate(90deg);
    }
</style>