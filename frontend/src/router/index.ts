import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";
import Home from "@/views/Home.vue";
import store from "@/store/index";

type NextFunction = () => void;

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    name: "Home",
    component: Home,
    beforeEnter: (to, from, next) => {
      authCheck(next);
    },
  },
  {
    path: "/lists",
    name: "Lists",
    // route level code-splitting
    // this generates a separate chunk (list.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "list" */ "@/views/Lists.vue"),
    beforeEnter: (to, from, next) => {
      authCheck(next);
    },
  },
  {
    path: "/settings",
    name: "Settings",
    component: () =>
      import(/* webpackChunkName: "profile" */ "@/views/Settings.vue"),
    beforeEnter: (to, from, next) => {
      authCheck(next);
    },
  },
  {
    path: "/register",
    name: "Register",
    component: () =>
      import(/* webpackChunkName: "register" */ "@/views/Register.vue"),
  },
  {
    path: "/schedule",
    name: "Schedule",
    component: () =>
      import(/* webpackChunkName: "schedule" */ "@/views/Schedule.vue"),
    beforeEnter: (to, from, next) => {
      authCheck(next);
    },
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
      authCheck(next);
    },
  },
  {
    path: "/p/:slug",
    name: "ListView",
    component: () =>
      import(/* webpackChunkName: "newlist" */ "@/views/public/ListView.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

function authCheck(next: NextFunction) {
  if (store.state.loggedIn) {
    next();
  } else {
    router.push("/login");
  }
}

export default router;
