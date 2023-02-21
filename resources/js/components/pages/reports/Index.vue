<template>
    <div>
        <div class="tabs-container">
            <ul class="tabs-menu">
                <li>
                    <router-link :to="{name:'reports.authorized'}">
                        <h1>Авторизация</h1>
                    </router-link>
                </li>
                <li>
                    <router-link :to="{name:'reports.records'}">
                        <h1>Отчеты</h1>
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