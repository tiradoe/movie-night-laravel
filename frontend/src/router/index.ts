import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import Home from "@/views/Home.vue";

const routes: Array<RouteRecordRaw> = [
    {
        path: "/",
        name: "Home",
        component: Home,
        beforeEnter: (to, from, next) => {
            const loggedIn = authCheck();

            if (loggedIn) {
                next();
            } else {
                router.push("/login");
            }
        },
    },
    {
        path: "/lists",
        name: "Lists",
        // route level code-splitting
        // this generates a separate chunk (list.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () =>
            import(/* webpackChunkName: "list" */ "@/views/Lists.vue"),
        beforeEnter: (to, from, next) => {
            const loggedIn = localStorage.getItem("loggedIn");

            if (loggedIn) {
                next();
            } else {
                next(false);
            }
        },
    },
    {
        path: "/settings",
        name: "Settings",
        component: () =>
            import(/* webpackChunkName: "profile" */ "@/views/Settings.vue"),
        beforeEnter: (to, from, next) => {
            const loggedIn = localStorage.getItem("loggedIn");

            if (loggedIn) {
                next();
            } else {
                next(false);
            }
        },
    },
    {
        path: "/signup",
        name: "Signup",
        component: () =>
            import(/* webpackChunkName: "signup" */ "@/views/Signup.vue"),
    },
    {
        path: "/login",
        name: "Login",
        component: () =>
            import(/* webpackChunkName: "login" */ "@/views/Login.vue"),
    },
    {
        path: "/lists/edit/:id",
        name: "EditList",
        component: () =>
            import(/* webpackChunkName: "newlist" */ "@/views/EditList.vue"),
        beforeEnter: (to, from, next) => {
            const loggedIn = localStorage.getItem("loggedIn");

            if (loggedIn) {
                next();
            } else {
                next(false);
            }
        },
    },
];

function authCheck(): boolean {
    return localStorage.getItem("loggedIn") !== null ? true : false;
}

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

export default router;
