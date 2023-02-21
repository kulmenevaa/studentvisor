<template>
    <div>
        <div class="tabs-container">
            <ul class="tabs-menu">
                <li>
                    <router-link :to="{name:'settings.notification'}">
                        <h1>Уведомления</h1>
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name:'settings.verified'}">
                        <h1>Проверенные IP</h1>
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name:'settings.scenario'}">
                        <h1>Сценарии поведения</h1>
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="settings-blocks">
            <section class="section">
                <router-view></router-view>
            </section>
        </div>
    </div>
</template>

<style>
    .tabs-container ul.tabs-menu {
        display: flex;
        justify-content: flex-start;
        flex-wrap: nowrap;
        margin-bottom: -1px;
        font-size: 0.875rem;
        line-height: 1.25rem;
        font-weight: 500;
        text-align: center;
        padding: 0 2.5rem;
    }
    @media(max-width: 640px) {
        .tabs-container ul.tabs-menu {
            justify-content: center;
            padding: 0;
        }
    }
    .tabs-container ul.tabs-menu li:not(:last-child) {
        margin-right: 0.5rem;
    }
    .tabs-container ul.tabs-menu li a {
        display: inline-block;
        padding: 0.825rem;
        border-width: 1px;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .tabs-container ul.tabs-menu li a:hover {
        --tw-text-opacity: 1;
        color: rgb(75 85 99 / var(--tw-text-opacity));
        --tw-border-opacity: 1;
        border-color: rgb(209 213 219 / var(--tw-border-opacity));
    }
    .tabs-container ul.tabs-menu li a.active {
        color: #3c388d;
        font-weight: 600;
        border-bottom-width: 1px;
        border-bottom-color: #fff;
    }
    .settings-blocks {
        border-width: 1px;
        min-height: 70vh;
        border-radius: 0.5rem;
    }
    .settings-blocks .settings-content:not(:last-child) {
        margin-bottom: 1rem;
    }
    .settings-blocks .settings-content .settings-item {
        margin: 0.5rem 0;
    }
    .settings-blocks .settings-content .settings-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .settings-blocks .settings-content .settings-title h3 {
        font-weight: 600;
        font-size: 1.25rem;
        cursor: pointer;
    }
    .settings-blocks .settings-content .settings-title h3.accord:hover {
        text-decoration: underline
    }
</style>