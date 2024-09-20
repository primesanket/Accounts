<template>
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="" class="-intro-x flex items-center pt-5">
                    <img
                        alt="Midone - HTML Admin Template"
                        class="w-6"
                        src="/src/assets/images/logo.svg"
                    />
                    <span class="text-white text-lg ml-3">
                        Prime Accounts
                    </span>
                </a>
                <div class="my-auto">
                    <img
                        alt="Midone - HTML Admin Template"
                        class="-intro-x w-1/2 -mt-16"
                        src="/src/assets/images/illustration.svg"
                    />
                    <div
                        class="-intro-x text-white font-medium text-4xl leading-tight mt-10"
                    >
                        A few more clicks to
                        <br />
                        sign in to your account.
                    </div>
                    <div
                        class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400"
                    >
                        Manage all your accounts in one place
                    </div>
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div
                    class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto"
                >
                    <h2
                        class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left"
                    >
                        Sign In
                    </h2>
                    <div
                        class="intro-x mt-2 text-slate-400 xl:hidden text-center"
                    >
                        A few more clicks to sign in to your account. Manage all
                        your e-commerce accounts in one place
                    </div>
                    <form @submit.prevent="login">
                        <div class="intro-x mt-8">
                            <input
                                v-model="email"
                                type="text"
                                class="intro-x login__input form-control py-3 px-4 block"
                                placeholder="Email"
                                @blur="validateField('email')"
                            />
                            <div class="text-danger mt-2" v-if="errors.email">
                                {{ errors.email[0] }}
                            </div>
                            <input
                                v-model="password"
                                type="password"
                                class="intro-x login__input form-control py-3 px-4 block mt-4"
                                placeholder="Password"
                                @blur="validateField('password')"
                            />
                            <div
                                class="text-danger mt-2"
                                v-if="errors.password"
                            >
                                {{ errors.password[0] }}
                            </div>
                        </div>
                        <div
                            class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4"
                        >
                            <div class="flex items-center mr-auto">
                                <input
                                    id="remember-me"
                                    type="checkbox"
                                    class="form-check-input border mr-2"
                                    v-model="rememberMe"
                                />
                                <label
                                    class="cursor-pointer select-none"
                                    for="remember-me"
                                    >Remember me</label
                                >
                            </div>
                            <a href="">Forgot Password?</a>
                        </div>
                        <div
                            class="intro-x mt-5 xl:mt-5 text-center xl:text-left"
                        >
                            <button
                                type="submit"
                                class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top"
                                :disabled="isLoading"
                            >
                                <span>
                                    <svg
                                        width="25"
                                        viewBox="-2 -2 42 42"
                                        xmlns="http://www.w3.org/2000/svg"
                                        stroke="rgb(255, 255, 255)"
                                        class="w-4 h-4 mr-2"
                                        v-if="isLoading"
                                    >
                                        <g fill="none" fill-rule="evenodd">
                                            <g
                                                transform="translate(1 1)"
                                                stroke-width="4"
                                            >
                                                <circle
                                                    stroke-opacity=".5"
                                                    cx="18"
                                                    cy="18"
                                                    r="18"
                                                ></circle>
                                                <path
                                                    d="M36 18c0-9.94-8.06-18-18-18"
                                                >
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
                                <span>Login</span>
                            </button>
                            <!-- <button
                            class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top"
                        >
                            Register
                        </button> -->
                        </div>
                    </form>
                    <div
                        class="intro-x mt-10 xl:mt-24 text-slate-600 dark:text-slate-500 text-center xl:text-left"
                    >
                        By signin up, you agree to our
                        <a class="text-primary dark:text-slate-200" href=""
                            >Terms and Conditions</a
                        >
                        &
                        <a class="text-primary dark:text-slate-200" href=""
                            >Privacy Policy</a
                        >
                    </div>
                </div>
            </div>
            <!-- END: Login Form -->
        </div>
    </div>
</template>

<script>
import { useAuthStore } from "@/stores/auth";

export default {
    data() {
        return {
            email: "",
            password: "",
            rememberMe: false,
            isLoading: false,
            errors: [],
        };
    },
    beforeMount() {
        document.body.classList.add("login");
    },
    beforeUnmount() {
        document.body.classList.remove("login");
    },
    methods: {
        validateField(field) {
            switch (field) {
                case "email":
                    this.errors.email = [];
                    if (this.email.trim() === "") {
                        this.errors.email = ["The email field is required."];
                    } else if (!this.isValidEmail(this.email)) {
                        this.errors.email = [
                            "The email must be a valid email address.",
                        ];
                    }
                    break;
                case "password":
                    this.errors.password =
                        this.password.trim() === ""
                            ? ["The password field is required."]
                            : [];
                    break;
                default:
                    break;
            }
        },
        isValidEmail(email) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        },
        async login() {
            this.validateField("email");
            this.validateField("password");

            const hasErrors = Object.values(this.errors).some(
                (errorArray) => errorArray.length > 0
            );

            if (hasErrors) {
                this.$swal.showErrorToast("Please enter all required data.");
                return;
            }

            const authStore = useAuthStore();

            this.isLoading = true;

            const { status, data } = await authStore.login({
                email: this.email,
                password: this.password,
                remember: this.rememberMe,
            });

            if (status === 200 && data) {
                await authStore.fetchUserData();
                this.$router.push("/dashboard");
            }

            if (status === 422) {
                this.errors = data.errors;
            }

            this.isLoading = false;
        },
    },
};
</script>
