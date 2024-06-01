import {createRouter, createWebHistory} from 'vue-router';
import auth  from "../middleware/auth";
import middlewarePipeline from "../middleware/middleware-pipeline";

const routes = [
    {
        path: '/',
        redirect: {name: 'dashboard'},
        children: [
            {
                path: 'dashboard',
                name: 'dashboard',
                component: () => import('@/pages/Dashboard/index.vue'),
                meta: {middlewares: [auth], title: 'Dashboard'}
            },
            {
                path: 'authors',
                name: 'authors',
                component: () => import('@/pages/authors/index.vue'),
                meta: {middlewares: [auth], title: 'Authors'}
            },
            {
                path: '/authors/:id',
                name: 'authorView',
                component: () => import('@/pages/authors/details.vue'),
                meta: {middlewares: [auth], title: 'Author Details'}
            },
            {
                path: 'books',
                name: 'books',
                component: () => import('@/pages/books/create.vue'),
                meta: {middlewares: [auth], title: 'Books'}
            },
            {
                path: 'profile',
                name: 'profile',
                component: () => import('@/pages/profile/Index.vue'),
                meta: {middlewares: [auth], title: 'Profile'}
            },
        ],
    },
    {
        path: '/',
        redirect: {name: 'login'},
        children: [
            {
                path: 'login',
                name: 'login',
                component: () => import('@/pages/login.vue'),
            },
            {
                path: '/:pathMatch(.*)*',
                name: 'pathMatch',
                component: () => import('@/pages/404.vue'),
            },
        ]
    }
]

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
    if (!to.meta.middleware) {
        return next()
    }

    const middleware = to.meta.middleware;
    const context = { to, from, next, }

    return middleware[0]({
        ...context,
        next:middlewarePipeline(context, middleware,1)
    })
})


export default router;
