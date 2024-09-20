<template>
    <main>
        <div class="flex mt-[4.7rem] md:mt-0">
            <NavBar />
            <!-- BEGIN: Content -->
            <div class="content">
                <TopBar />
                <div class="pt-5">
                    <RouterView />
                </div>
            </div>
            <!-- END: Content -->
        </div>
    </main>
</template>

<script>
import TopBar from "@/components/ui/TopBar.vue";
import NavBar from "@/components/ui/NavBar.vue";

export default {
    name: "AdminLayout",
    components: {
        TopBar,
        NavBar,
    },
    beforeMount() {
        document.body.classList.add("py-5");
    },
    beforeUnmount() {
        document.body.classList.remove("py-5");
    },
};
</script>
