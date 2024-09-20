<template>
    <div class="relative mx-auto">
        <div
            class="absolute index-top-1 rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400"
        >
            <slot></slot>
        </div>
        <div>
            <vue3-datepicker
                v-model="internalValue"
                :format="format"
                :placeholder="placeholder"
                class="datepicker form-control pl-12"
            />
        </div>
    </div>
</template>

<script>
import Vue3Datepicker from "vue3-datepicker";

export default {
    name: "DatePicker",
    components: {
        Vue3Datepicker,
    },
    props: {
        modelValue: {
            type: Date,
            default: null,
        },
        format: {
            type: String,
            default: "YYYY-MM-DD",
        },
        placeholder: {
            type: String,
            default: "Select date",
        },
    },
    data() {
        return {
            internalValue: this.modelValue,
        };
    },
    watch: {
        modelValue(newValue) {
            this.internalValue = newValue;
        },
        internalValue(newValue) {
            this.$emit("update:modelValue", newValue);
        },
    },
};
</script>
