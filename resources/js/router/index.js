import { createRouter, createWebHistory } from "vue-router";
import NotFound from "../views/NotFound.vue";
import store from "../store";
import SignIn from "../views/sessions/SignIn.vue";
import PhoneAuth from "../views/sessions/PhoneAuth.vue";
import SignUp from "../views/sessions/SignUp.vue";
import Home from "../views/sessions/home/index.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: () => import("../layout/index.vue"),
        redirect: "/dashboards",
        meta: {
            title: "Home",
        },

        children: [
            {
                path: "/dashboards",
                name: "Dashboards",
                component: () =>
                    import("../views/dashboards/Dashboards.v1.vue"),
                meta: {
                    title: "اسم الشركة",
                    requiresAuth: true,
                },
            },
            {
                path: "/invoices",
                name: "Invoices",
                component: () => import("../views/invoices/index.vue"),
                meta: {
                    title: "الفاتورة",
                },
                children: [
                    {
                        path: "list",
                        name: "InvoiceList",
                        component: () => import("../views/invoices/List.vue"),
                        meta: {
                            requiresAuth: true,
                        },
                    },
                ],
            },
            {
                path: "/analytics",
                name: "Statistics",
                component: () => import("../views/analytics/Analytics.vue"),
                meta: {
                    title: "الإحصائيات",
                    requiresAuth: true,
                },
                children: [
                    {
                        path: "profileTwo",
                        name: "ProfileTwo",
                        component: () =>
                            import("../views/analytics/Analytics.vue"),
                        meta: {
                            requiresAuth: true,
                        },
                    },
                ],
            },
            {
                path: "/prices",
                name: "Manage Price",
                component: () => import("../views/prices/index.vue"),
                meta: {
                    title: "الأقسام",
                },
                children: [
                    {
                        path: "list",
                        name: "PriceList",
                        component: () => import("../views/prices/List.vue"),
                        meta: {
                            requiresAuth: true,
                        },
                    },
                ],
            },
            {
                path: "/pieces",
                name: "Manage Pieces",
                component: () => import("../views/pieces/index.vue"),
                meta: {
                    title: "الأقسام",
                },
                children: [
                    {
                        path: "list",
                        name: "PieceList",
                        component: () => import("../views/pieces/List.vue"),
                        meta: {
                            requiresAuth: true,
                        },
                    },
                ],
            },
            {
                path: "/services",
                name: "Manage Services",
                component: () => import("../views/services/index.vue"),
                meta: {
                    title: "الأقسام",
                },
                children: [
                    {
                        path: "list",
                        name: "ServiceList",
                        component: () => import("../views/services/List.vue"),
                        meta: {
                            requiresAuth: true,
                        },
                    },
                ],
            },
            {
                path: "/users",
                name: "Manage Users",
                component: () => import("../views/users/index.vue"),
                meta: {
                    title: "الأقسام",
                },
            },
            {
                path: "/settings",
                name: "Settings",
                component: () => import("../views/settings/index.vue"),
                meta: {
                    title: "الإحصائيات",
                    requiresAuth: true,
                },
                children: [
                    {
                        path: "profileTwo",
                        name: "ProfileTwo",
                        component: () =>
                            import("../views/profile/ProfileTwo.vue"),
                    },
                ],
            },
        ],
    },

    { path: "/login", name: "Login", component: SignIn },
    { path: "/phoneVerify", name: "PhoneAuth", component: PhoneAuth },
    { path: "/register", component: SignUp },
    { path: "/home", component: Home },

    { path: "/:path(.*)", component: NotFound },
];

const router = createRouter({
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { left: 0, top: 0 };
    },
    routes,
});

router.beforeEach((to, from, next) => {
    let mainDomain = false;
    if (
        window.location.hostname == "sass.test" ||
        window.location.hostname == "maghsalah.com"
    ) {
        mainDomain = true;
    }
    const isAuthenticated = localStorage.getItem("laundry-token") || false;

    // Implement your authentication or access control logic here
    if (mainDomain && to.path !== "/home" && to.path !== "/register") {
        console.log("Redirecting to /home because we are on the main domain");
        next("/home");
    }  else if (to.meta.requiresAuth && !isAuthenticated) {
        // Redirect to login page if the route requires authentication and user is not authenticated
        console.log(
            "Redirecting to /login because the route requires authentication and the user is not authenticated"
        );
        next("/login");
    } else {
        // Proceed with the navigation
        console.log("Proceeding with the navigation");
        next();
    }
});

router.afterEach(() => {
    if (window.innerWidth <= 1200) {
        const sidenav =
            store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen;

        store.commit("largeSidebar/toggleSidebarProperties");
    }
});

export default router;
