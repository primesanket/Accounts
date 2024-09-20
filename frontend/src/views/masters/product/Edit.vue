<template>
    <PageTitle isBackButtonAllow="true" class="mb-5" />
    <div class="intro-y col-span-12 lg:col-span-6">
        <!-- BEGIN: Input -->
        <form @submit.prevent="handleSubmit">
            <div class="intro-y box">
                <div id="input" class="p-5">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-4">
                            <label for="party_name" class="form-label"
                                >Product No <span class="text-danger">*</span></label
                            >
                            <input
                                id="product_no"
                                type="text"
                                v-model="form.product_no"
                                class="form-control"
                                placeholder=""
                            />
                            <div
                                class="text-danger mt-2"
                                v-if="errors.product_no"
                            >
                                {{ errors.product_no[0] }}
                            </div>
                        </div>
                        <div class="col-span-4">
                            <label for="party_name" class="form-label"
                                >Product Name <span class="text-danger">*</span></label
                            >
                            <input
                                id="product_name"
                                type="text"
                                v-model="form.product_name"
                                class="form-control"
                                placeholder=""
                            />
                            <div
                                class="text-danger mt-2"
                                v-if="errors.product_name"
                            >
                                {{ errors.product_name[0] }}
                            </div>
                        </div>
                        <div class="col-span-4">
                            <label for="owner_name" class="form-label"
                                >Company Name</label
                            >
                            <input
                                id="company_name"
                                type="text"
                                v-model="form.company_name"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="col-span-4">
                            <label for="reference_name" class="form-label"
                                >GST (%)</label
                            >
                            <input
                                id="gst"
                                type="text"
                                v-model="form.gst"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="col-span-4">
                            <label for="email" class="form-label"
                                >HSN/SAC</label
                            >
                            <input
                                id="hsn_sac"
                                type="text"
                                v-model="form.hsn_sac"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="col-span-4">
                            <label for="mobile_no" class="form-label"
                                >Opening Stock</label
                            >
                            <input
                                id="opening_stock"
                                type="text"
                                v-model="form.opening_stock"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="col-span-12">
                            <label for="address" class="form-label"
                                >Remark</label
                            >
                            <textarea
                                id="remark"
                                v-model="form.remark"
                                class="form-control"
                                placeholder=""
                                rows="5"
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <LoadingButton :loading="isLoading" class="mt-5"
                >Update</LoadingButton
            >
        </form>
        <!-- END: Input -->
    </div>
</template>

<script>
import apiClient from "@/services/axios";
import LoadingButton from "@/components/ui/LoadingButton.vue";
import swal from "@/services/sweetalert2Service";
export default {
    components: {
        LoadingButton,
    },
    data() {
        return {
            form: {
                id: "",
                product_no: "",
                product_name: "",
                company_name: "",
                gst: "",
                hsn_sac: "",
                opening_stock: "",
            },
            errors: [],
            isDataLoading: false,
            isLoading: false,
        };
    },
    beforeMount() {
        this.fetchData(parseInt(this.$route.params.id) || null);
    },
    methods: {
        async fetchData(id) {
            try {
                this.isDataLoading = true;
                const response = await apiClient.get(`/product/${id}`);
                this.isDataLoading = false;
                this.form = response.data.data;
            } catch (error) {
                this.isDataLoading = false;
                console.error("Fetch user data error:", error);
            }
        },
        async handleSubmit() {
            try {
                this.isLoading = true;
                const response = await apiClient.put(
                    `/product/${this.form.id}`,
                    this.form
                );
                if (response.status === 201) {
                    this.$swal.showSuccessToast(response.data.message);
                }
                setTimeout(() => {
                    this.isLoading = false;
                    this.$router.push("/masters/product");
                }, 500);
            } catch (error) {
                if (error.response) {
                    this.isLoading = false;
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                }
            }
        },
    },
    mounted() {},
};
</script>
