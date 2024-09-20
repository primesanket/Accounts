<template>
    <!-- BEGIN: Side Menu -->
    <nav class="side-nav">
        <a href="" class="intro-x flex items-center pl-5 pt-4">
            <img
                alt="Midone - HTML Admin Template"
                class="w-6"
                src="/src/assets/images/logo.svg"
            />
            <span class="hidden xl:block text-white text-lg ml-3">
                {{ title }}
            </span>
        </a>
        <div class="side-nav__devider my-6"></div>
        <ul>
            <li v-for="item in menuItems" :key="item.label">
                <a
                    href="javascript:void(0);"
                    class="side-menu"
                    :class="{
                        'side-menu--active': item.active,
                        'side-menu--open': item.open,
                    }"
                    v-if="item.children && item.children.length"
                    @click="item.open = !item.open"
                >
                    <div class="side-menu__icon">
                        <component :is="item.icon" />
                    </div>
                    <div class="side-menu__title">
                        {{ item.label }}
                        <div
                            class="side-menu__sub-icon"
                            :class="{ 'transform rotate-180': item.open }"
                        >
                            <ChevronDown />
                        </div>
                    </div>
                </a>
                <router-link
                    :to="item.path"
                    class="side-menu"
                    :class="{ 'side-menu--active': item.active }"
                    v-else
                >
                    <div class="side-menu__icon">
                        <component :is="item.icon" />
                    </div>
                    <div class="side-menu__title">
                        {{ item.label }}
                    </div></router-link
                >
                <ul
                    :class="{ 'side-menu__sub-open': item.open }"
                    v-if="item.children"
                    v-show="item.open"
                >
                    <li v-for="subItem in item.children" :key="subItem.label">
                        <router-link
                            :to="subItem.path"
                            class="side-menu"
                            :class="{ 'side-menu--active': subItem.active }"
                        >
                            <div class="side-menu__icon">
                                <component :is="subItem.icon" />
                            </div>
                            <div class="side-menu__title">
                                {{ subItem.label }}
                            </div>
                        </router-link>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- END: Side Menu -->
</template>

<script>
import {
    House,
    ChevronDown,
    Activity,
    Box,
    Landmark,
    HandCoins,
    BadgeCheck,
    ChartColumnIncreasing,
    Settings,
} from "lucide-vue-next";

export default {
    name: "NavBar",
    components: {
        House,
        ChevronDown,
        Activity,
        Box,
        Landmark,
        HandCoins,
        BadgeCheck,
        ChartColumnIncreasing,
        Settings,
    },
    data() {
        return {
            title: import.meta.env.VITE_APP_TITLE,
            menuItems: [
                {
                    label: "Dashboard",
                    path: "/dashboard",
                    icon: House,
                    active: false,
                    open: false,
                    pathNamesForActive: ["Dashboard"],
                    children: [],
                },
                {
                    label: "Masters",
                    path: "/masters",
                    icon: Box,
                    active: false,
                    open: false,
                    pathNamesForActive: [],
                    children: [
                        {
                            label: "Party",
                            path: "/masters/party",
                            icon: Activity,
                            active: false,
                            children: [
                                {
                                    name: "AddParty",
                                    path: "/masters/add-party",
                                },
                                {
                                    name: "EditParty",
                                    path: "/masters/edit-party:id",
                                },
                            ],
                        },
                        {
                            label: "Product",
                            path: "/masters/product",
                            icon: Activity,
                            active: false,
                            children: [
                                {
                                    name: "AddProduct",
                                    path: "/masters/add-product",
                                },
                                {
                                    name: "EditProduct",
                                    path: "/masters/edit-product:id",
                                },
                            ],
                        },
                    ],
                },
                {
                    label: "Expense",
                    path: "/expense",
                    icon: Landmark,
                    active: false,
                    open: false,
                    pathNamesForActive: [
                        "Expense",
                        "AddExpense",
                        "EditExpense",
                    ],
                    children: [],
                },
                {
                    label: "Sale",
                    path: "/sales",
                    icon: HandCoins,
                    active: false,
                    open: false,
                    pathNamesForActive: ["Sale", "AddSale", "EditSale"],
                    children: [],
                },
                {
                    label: "Payment Received",
                    path: "/payment-received",
                    icon: BadgeCheck,
                    active: false,
                    open: false,
                    pathNamesForActive: ["PaymentReceive"],
                    children: [],
                },
                {
                    label: "Reports",
                    path: "/reports",
                    icon: ChartColumnIncreasing,
                    active: false,
                    open: false,
                    pathNamesForActive: [],
                    children: [
                        {
                            label: "Expense",
                            path: "/reports/expense",
                            icon: Activity,
                            active: false,
                            children: [],
                        },
                        {
                            label: "Payment Received",
                            path: "/reports/payment-received",
                            icon: Activity,
                            active: false,
                            children: [],
                        },
                        {
                            label: "Balance Sheet",
                            path: "/reports/balance-sheet",
                            icon: Activity,
                            active: false,
                            children: [],
                        },
                    ],
                },
                {
                    label: "Settings",
                    path: "/setting",
                    icon: Settings,
                    active: false,
                    open: false,
                    pathNamesForActive: ["Settings"],
                    children: [],
                },
            ],
        };
    },
    methods: {
        resetMenuItems() {
            this.menuItems.forEach((item) => {
                item.active = false;
                item.open = false;
                if (item.children) {
                    item.children.forEach((subItem) => {
                        subItem.active = false;
                        if (subItem.children) {
                            subItem.children.forEach((childItem) => {
                                childItem.active = false;
                            });
                        }
                    });
                }
            });
        },
        setActiveMenuItem() {
            const currentRoutePath = this.$route.path;
            const currentRouteName = this.$route.name;

            // Reset states of all menu items before setting new active states
            this.resetMenuItems();

            // Recursive function to set active and open states
            const setActiveRecursively = (item) => {
                // Check if current route name matches any of the names in pathNamesForActive
                if (
                    item.pathNamesForActive &&
                    item.pathNamesForActive.includes(currentRouteName)
                ) {
                    item.active = true;
                    item.open = true;
                }

                if (item.children) {
                    let childActive = false;

                    item.children.forEach((subItem) => {
                        setActiveRecursively(subItem);

                        // If the subItem is active, set childActive to true
                        if (subItem.active) {
                            childActive = true;
                        }

                        // Check for grandchild routes to match current route path or name
                        if (subItem.children) {
                            subItem.children.forEach((childItem) => {
                                if (
                                    childItem.path === currentRoutePath ||
                                    childItem.name === currentRouteName
                                ) {
                                    subItem.active = true;
                                    childActive = true;
                                }
                            });
                        }
                    });

                    // If any child or grandchild is active, set the parent as active
                    if (childActive) {
                        item.active = true;
                        item.open = true;
                    }
                }

                // Finally, check if the item's own path or name matches the current route
                if (
                    item.path === currentRoutePath ||
                    item.name === currentRouteName
                ) {
                    item.active = true;
                    item.open = true;
                }
            };

            // Apply the recursive function to each menu item
            this.menuItems.forEach((item) => {
                setActiveRecursively(item);
            });
        },
    },
    watch: {
        "$route.path": "setActiveMenuItem",
    },
    mounted() {
        this.setActiveMenuItem();
    },
};
</script>
