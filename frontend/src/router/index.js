import { createRouter, createWebHistory } from "vue-router";
import DefaultLayout from "@/components/layouts/DefaultLayout.vue";
import AdminLayout from "@/components/layouts/AdminLayout.vue";
import Cookies from "js-cookie";

const defaultMeta = {
    layout: AdminLayout,
    requiresAuth: true,
};

const routes = [
    {
        path: "/",
        children: [
            {
                path: "",
                name: "Login",
                component: () => import("../views/Login.vue"),
                meta: {
                    title: "Login",
                    layout: DefaultLayout,
                },
            },
        ],
    },
    {
        path: "/dashboard",
        children: [
            {
                path: "",
                name: "Dashboard",
                component: () => import("../views/Dashboard.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Dashboard",
                },
            },
        ],
    },
    {
        path: "/masters",
        children: [
            {
                path: "party",
                name: "Party",
                component: () => import("../views/masters/party/List.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Party List",
                },
            },
            {
                path: "add-party",
                name: "AddParty",
                component: () => import("../views/masters/party/Add.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Add Party",
                },
            },
            {
                path: "edit-party/:id",
                name: "EditParty",
                component: () => import("../views/masters/party/Edit.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Edit Party",
                },
            },
            {
                path: "product",
                name: "Product",
                component: () => import("../views/masters/product/List.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Product List",
                },
            },
            {
                path: "add-product",
                name: "AddProduct",
                component: () => import("../views/masters/product/Add.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Add Product",
                },
            },
            {
                path: "edit-product/:id",
                name: "EditProduct",
                component: () => import("../views/masters/product/Edit.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Edit Product",
                },
            },
        ],
    },
    {
        path: "/expense",
        children: [
            {
                path: "",
                name: "Expense",
                component: () => import("../views/expense/List.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Expense List",
                },
            },
            {
                path: "add-expense",
                name: "AddExpense",
                component: () => import("../views/expense/Add.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Add Expense",
                },
            },
            {
                path: "edit-expense/:id",
                name: "EditExpense",
                component: () => import("../views/expense/Edit.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Edit Expense",
                },
            },
        ],
    },
    {
        path: "/sales",
        children: [
            {
                path: "",
                name: "Sale",
                component: () => import("../views/sales/List.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Sales List",
                },
            },
            {
                path: "add-sale",
                name: "AddSale",
                component: () => import("../views/sales/Add.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Add Sale",
                },
            },
            {
                path: "edit-sale/:id",
                name: "EditSale",
                component: () => import("../views/sales/Edit.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Edit Sale",
                },
            },
        ],
    },
    {
        path: "/payment-received",
        children: [
            {
                path: "",
                name: "PaymentReceived",
                component: () => import("../views/payment_received/Add.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Payment Received",
                },
            },
        ],
    },
    {
        path: "/reports",
        children: [
            {
                path: "expense",
                name: "ExpenseReport",
                component: () => import("../views/reports/Expense.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Expense Report",
                },
            },
            {
                path: "payment-received",
                name: "PaymentReceivedReport",
                component: () => import("../views/reports/PaymentReceived.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Payment Received Report",
                },
            },
            {
                path: "balance-sheet",
                name: "BalanceSheet",
                component: () => import("../views/reports/BalanceSheet.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Balance Sheet",
                },
            },
        ],
    },
    {
        path: "/setting",
        children: [
            {
                path: "",
                name: "Settings",
                component: () => import("../views/Settings.vue"),
                meta: {
                    ...defaultMeta,
                    title: "Settings",
                },
            },
        ],
    },
    // Other routes
    {
        path: "/:catchAll(.*)", // Catch-all route for undefined paths
        name: "NotFound",
        component: () => import("../views/NotFound.vue"),
        meta: {
            title: "404 Not Found",
            layout: DefaultLayout,
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const appTitle = import.meta.env.VITE_APP_TITLE;
    document.title = to.meta.title ? `${to.meta.title}` : appTitle;
    const loggedIn = Cookies.get("token");
    if (to.matched.some((record) => record.meta.requiresAuth) && !loggedIn) {
        next("/");
    } else {
        next();
    }
});

export default router;
