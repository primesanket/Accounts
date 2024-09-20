<template>
    <button class="btn btn-primary" :disabled="loading" @click="handleClick">
        <span>
            <svg
                width="25"
                viewBox="-2 -2 42 42"
                xmlns="http://www.w3.org/2000/svg"
                stroke="rgb(255, 255, 255)"
                class="w-4 h-4 mr-2"
                v-if="loading"
            >
                <g fill="none" fill-rule="evenodd">
                    <g transform="translate(1 1)" stroke-width="4">
                        <circle
                            stroke-opacity=".5"
                            cx="18"
                            cy="18"
                            r="18"
                        ></circle>
                        <path d="M36 18c0-9.94-8.06-18-18-18">
                            <animateTransform
                                attributeName="transform"
                                type="rotate"
                                from="0 18 18"
                                to="360 18 18"
                                dur="1s"
                                repeatCount="indefinite"
                            ></animateTransform>
                        </path>
                    </g>
                </g>
            </svg>
        </span>
        <span><slot></slot></span>
    </button>
</template>

<script>
export default {
    name: "LoadingButton",
    props: {
        loading: {
            type: Boolean,
            default: false,
        },
    },
    methods: {
        handleClick(event) {
            if (!this.loading) {
                this.$emit("click", event);
            }
        },
    },
};
</script>

<style scoped>
button:disabled {
    cursor: not-allowed;
}

span {
    display: inline-block;
}

span[style*="Loading"] {
    display: flex;
    align-items: center;
    justify-content: center;
}

span[style*="Loading"]::after {
    content: "";
    margin-left: 8px;
    width: 16px;
    height: 16px;
    border: 2px solid white;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 0.75s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
