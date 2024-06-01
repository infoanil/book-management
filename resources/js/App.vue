<template>
    <router-view v-if="authRoute" />
    <Layout v-else>
        <router-view />
    </Layout>
</template>

<script>
    import Layout from '@/layouts/default.vue'
    export default {
        name: 'App',
        components: {
            Layout
        },
        data() {
            return {
                authRoute: true,
            }
        },
        watch: {
            $route: {
                handler: function (path) {
                    this.authRoute = path.name === 'login'
                },
            }
        },
    }

</script>
