<template>
    <!-- BEGIN: Top Bar -->
    <div class="top-bar">
        <!-- BEGIN: Breadcrumb -->
        <nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">
            <ol class="breadcrumb">
                <!-- <li class="breadcrumb-item">
                    <a href="#">Application</a>
                </li> -->
                <li class="breadcrumb-item active" aria-current="page">
                    <!-- <h2 class="mr-5 truncate text-lg font-medium">
                        {{ $route.meta.title }}
                    </h2> -->
                </li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->
        <!-- BEGIN: Account Menu -->
        <div class="intro-x dropdown w-8 h-8">
            <div
                class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in"
                role="button"
                aria-expanded="false"
                data-tw-toggle="dropdown"
                @click="open = !open"
            >
                <img
                    alt="Midone - HTML Admin Template"
                    src="/src/assets/images/profile-5.jpg"
                />
            </div>
            <div
                class="dropdown-menu w-56"
                :class="{ show: open }"
                data-popper-placement="bottom-end"
            >
                <ul class="dropdown-content bg-primary text-white">
                    <li class="p-2">
                        <div class="font-medium">{{ name }}</div>
                    </li>
                    <!-- <li>
                        <hr class="dropdown-divider border-white/[0.08]" />
                    </li> -->
                    <!-- <li>
                        <a href="" class="dropdown-item hover:bg-white/5">
                            <User size="18" class="mr-2" />
                            Profile
                        </a>
                    </li> -->
                    <li>
                        <hr class="dropdown-divider border-white/[0.08]" />
                    </li>
                    <li>
                        <button
                            class="dropdown-item hover:bg-white/5 w-full"
                            @click="logout"
                        >
                            <LogOut size="20" class="mr-2" />
                            Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END: Account Menu -->
    </div>
    <!-- END: Top Bar -->
</template>

<script>
import { LogOut, User } from "lucide-vue-next";
import { useAuthStore } from "@/stores/auth";

export default {
    name: "TopBar",
    components: {
        LogOut,
        User,
    },
    data() {
        return {
            name: useAuthStore().userInfo?.name,
            open: false,
        };
    },
    methods: {
        async logout() {
            const authStore = useAuthStore();
            await authStore.logout();
        },
    },
};
</script>

<style scoped>
.dropdown .dropdown-menu {
    transform: translate3d(-190px, 45px, 0px) !important;
}
</style>
