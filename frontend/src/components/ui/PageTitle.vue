<template>
    <div class="intro-y flex flex-col sm:flex-row items-center justify-between">
        <h2 class="mr-5 truncate text-lg font-medium">
            {{ routeMetaTitle }}
        </h2>
        <button
            class="btn btn-primary shadow-md"
            v-if="isBackButtonAllow"
            @click="goBack"
        >
            <Undo2 />
        </button>
    </div>
</template>

<script>
import { Undo2 } from "lucide-vue-next";
export default {
    name: "PageTitle",
    components: {
        Undo2,
    },
    props: {
        isBackButtonAllow: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        routeMetaTitle() {
            return this.$route.meta.title;
        },
    },
    methods: {
        goBack() {
            this.$router.go(-1);
        },
    },
};
</script>
