<template>
    <PageTitle :isBackButtonAllow="true" class="mb-5" />
    <div class="intro-y col-span-12 lg:col-span-6">
        <!-- BEGIN: Input -->
        <form @submit.prevent="handleSubmit">
            <div class="intro-y box">
                <div id="input" class="p-5">
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-4">
                            <label for="party_name" class="form-label"
                                >Party Name
                                <span class="text-danger">*</span></label
                            >
                            <input
                                id="party_name"
                                type="text"
                                v-model="form.party_name"
                                class="form-control"
                                placeholder=""
                            />
                            <div
                                class="text-danger mt-2"
                                v-if="errors.party_name"
                            >
                                {{ errors.party_name[0] }}
                            </div>
                        </div>
                        <div class="col-span-4">
                            <label for="owner_name" class="form-label"
                                >Owner Name</label
                            >
                            <input
                                id="owner_name"
                                type="text"
                                v-model="form.owner_name"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="col-span-4">
                            <label for="reference_name" class="form-label"
                                >Reference Name</label
                            >
                            <input
                                id="reference_name"
                                type="text"
                                v-model="form.reference_name"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="col-span-4">
                            <label for="email" class="form-label">Email</label>
                            <input
                                id="email"
                                type="text"
                                v-model="form.email"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="col-span-4">
                            <label for="mobile_no" class="form-label"
                                >Mobile No</label
                            >
                            <input
                                id="mobile_no"
                                type="text"
                                v-model="form.mobile_no"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="col-span-4">
                            <label for="office_no" class="form-label"
                                >Office No</label
                            >
                            <input
                                id="office_no"
                                type="text"
                                v-model="form.office_no"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="col-span-12">
                            <label for="address" class="form-label"
                                >Address
                                <span class="text-danger">*</span></label
                            >
                            <textarea
                                id="address"
                                v-model="form.address"
                                class="form-control"
                                placeholder=""
                                rows="5"
                            />
                            <div class="text-danger mt-2" v-if="errors.address">
                                {{ errors.address[0] }}
                            </div>
                        </div>
                        <div class="lg:col-span-3">
                            <label for="state" class="form-label">State</label>
                            <input
                                id="state"
                                type="text"
                                v-model="form.state"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="lg:col-span-3">
                            <label for="state_code" class="form-label"
                                >State Code</label
                            >
                            <input
                                id="state_code"
                                type="text"
                                v-model="form.state_code"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="col-span-3">
                            <label for="pan_no" class="form-label"
                                >Pan No</label
                            >
                            <input
                                id="pan_no"
                                type="text"
                                v-model="form.pan_no"
                                class="form-control"
                                placeholder=""
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                        <div class="lg:col-span-3">
                            <label for="gst_tin" class="form-label"
                                >GSTTIN</label
                            >
                            <input
                                id="gst_tin"
                                type="text"
                                v-model="form.gst_tin"
                                class="form-control"
                                placeholder=""
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

export default {
    name: "Edit party",
    components: {
        LoadingButton,
    },
    data() {
        return {
            form: {
                id: "",
                party_name: "",
                owner_name: "",
                reference_name: "",
                email: "",
                mobile_no: "",
                office_no: "",
                pan_no: "",
                address: "",
                state: "",
                state_code: "",
                gst_tin: "",
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
                const response = await apiClient.get(`/party/${id}`);
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
                    `/party/${this.form.id}`,
                    this.form
                );
                if (response.status === 201) {
                    this.$swal.showSuccessToast(response.data.message);
                }
                setTimeout(() => {
                    this.isLoading = false;
                    this.$router.push("/masters/party");
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
