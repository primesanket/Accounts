<template>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <!-- Loader -->
                <div
                    v-if="isLoading"
                    class="col-span-12 flex justify-center mt-12"
                >
                    <img class="loader" src="@/assets/images/loader.svg" />
                </div>
                <div v-else class="col-span-12">
                    <div class="col-span-12">
                        <div class="intro-y flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                General Report
                            </h2>
                            <a
                                href="#"
                                @click="fetchGeneralReportData"
                                class="ml-auto flex items-center text-primary"
                            >
                                <RefreshCcw class="mr-3" size="18" />
                                Reload Data
                            </a>
                        </div>
                        <div class="grid grid-cols-12 gap-6 mt-5">
                            <!-- Report Data -->
                            <div
                                v-for="(item, index) in generalReportData"
                                :key="index"
                                class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y"
                            >
                                <div class="report-box zoom-in">
                                    <div class="box p-5">
                                        <div class="flex">
                                            <Activity
                                                :class="`report-box__icon ${item.iconColor}`"
                                            />
                                        </div>
                                        <div
                                            class="flex align-center text-3xl font-medium leading-8 mt-6"
                                        >
                                            {{ item.value }}
                                        </div>
                                        <div
                                            class="text-base text-slate-500 mt-1"
                                        >
                                            {{ item.label }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y"
                            >
                                <div class="report-box zoom-in">
                                    <div class="box p-5">
                                        <div class="flex justify-between">
                                            <Activity
                                                :class="`report-box__icon text-success`"
                                            />
                                            <div
                                                class="text-2xl text-slate-400"
                                            >
                                                Outstanding
                                            </div>
                                        </div>
                                        <div
                                            class="flex align-center text-3xl font-medium leading-8 mt-6"
                                        ></div>
                                        <div
                                            class="flex justify-between text-base text-slate-500 mt-1"
                                        >
                                            <span class="text-slate-500"
                                                >Mehul</span
                                            >
                                            <span
                                                class="text-slate-800 font-bold"
                                                >{{
                                                    formatNumber(
                                                        outstandingData.mehul_profit ||
                                                            0
                                                    )
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="flex justify-between text-base text-slate-500 mt-1"
                                        >
                                            <span class="text-slate-500"
                                                >Bhavin</span
                                            >
                                            <span
                                                class="text-slate-800 font-bold"
                                                >{{
                                                    formatNumber(
                                                        outstandingData.bhavin_profit ||
                                                            0
                                                    )
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="col-span-5 mt-12">
                            <div class="intro-y flex items-center h-10">
                                <h2 class="text-lg font-medium truncate mr-5">
                                    Annual Report ({{ currentYear }})
                                </h2>
                            </div>
                            <div class="intro-y box p-5 mt-5">
                                <div class="grid grid-cols-12 gap-6">
                                    <div
                                        class="col-span-12 sm:col-span-12 xl:col-span-12 intro-y"
                                    >
                                        <div class="h-[400px]">
                                            <BarChart
                                                chartId="annualReportBarChart"
                                                :labels="
                                                    charts.annualReportBarChart
                                                        .chartLabels
                                                "
                                                :datasets="
                                                    charts.annualReportBarChart
                                                        .chartData
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-5 mt-12">
                            <div class="intro-y flex items-center h-10">
                                <h2
                                    class="text-lg font-medium truncate mr-5"
                                ></h2>
                            </div>
                            <div class="intro-y box p-5 mt-5">
                                <div class="grid grid-cols-12 gap-6">
                                    <div
                                        class="col-span-12 sm:col-span-12 xl:col-span-12 intro-y"
                                    >
                                        <div class="h-[400px]">
                                            <LineChart
                                                chartId="annualReportLineChart"
                                                :labels="
                                                    charts.annualReportLineChart
                                                        .chartLabels
                                                "
                                                :datasets="
                                                    charts.annualReportLineChart
                                                        .chartData
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 mt-12">
                            <div
                                class="intro-y flex justify-center items-center h-10"
                            >
                                <h2 class="text-lg font-medium truncate">
                                    Payment Recieved
                                </h2>
                            </div>
                            <div class="intro-y box p-5 mt-5">
                                <div class="grid grid-cols-12">
                                    <div
                                        class="col-span-12 sm:col-span-12 xl:col-span-12 intro-y"
                                    >
                                        <div class="h-[400px]">
                                            <Datepicker
                                                v-model="date_payment_recieved"
                                                format="MM-yyyy"
                                                value-format="MM-yyyy"
                                                auto-apply
                                                placeholder="Pick a month"
                                                class="p-0 border-0"
                                                month-picker
                                            />
                                            <div
                                                v-if="is_payment_recieved_load"
                                                class="col-span-12 flex justify-center mt-12"
                                            >
                                                <img
                                                    class="loader"
                                                    src="@/assets/images/loader.svg"
                                                />
                                            </div>
                                            <div v-else>
                                                <PieChart
                                                    ref="paymentRecievedPieChart"
                                                    chartId="paymentRecievedPieChart"
                                                    :labels="
                                                        charts
                                                            .paymentRecievedPieChart
                                                            .chartLabels
                                                    "
                                                    :datasets="
                                                        charts
                                                            .paymentRecievedPieChart
                                                            .chartData
                                                    "
                                                    class="mt-5 mx-auto"
                                                    v-if="
                                                        charts
                                                            .paymentRecievedPieChart
                                                            .chartLabels.length
                                                    "
                                                    style="
                                                        width: 190px;
                                                        height: 190px;
                                                    "
                                                />
                                                <div
                                                    v-else
                                                    class="alert border-0 show mt-5 pb-10"
                                                    role="alert"
                                                >
                                                    <TriangleAlert
                                                        size="80"
                                                        class="mx-auto mt-5 text-slate-400"
                                                    />
                                                    <p
                                                        class="text-slate-500 text-center"
                                                    >
                                                        No data found.
                                                    </p>
                                                </div>
                                            </div>
                                            <div
                                                class="w-52 sm:w-auto mx-auto mt-5 legends"
                                            >
                                                <div
                                                    class="flex items-center"
                                                    v-for="(
                                                        item, index
                                                    ) in charts
                                                        .paymentRecievedPieChart
                                                        .legends"
                                                    :key="index"
                                                >
                                                    <div
                                                        class="w-2 h-2 rounded-full mr-3"
                                                        :style="{
                                                            'background-color':
                                                                item.color,
                                                        }"
                                                    ></div>
                                                    <span class="truncate">{{
                                                        item.label
                                                    }}</span>
                                                    <span
                                                        class="font-medium font-bold ml-auto"
                                                        >{{ item.value }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Activity, RefreshCcw, TriangleAlert } from "lucide-vue-next";
import apiClient from "@/services/axios";
import BarChart from "@/components/chart/BarChart.vue";
import LineChart from "@/components/chart/LineChart.vue";
import PieChart from "@/components/chart/PieChart.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default {
    name: "Dashboard",
    components: {
        Activity,
        RefreshCcw,
        BarChart,
        LineChart,
        PieChart,
        Datepicker,
        TriangleAlert,
    },
    data() {
        return {
            generalReportData: [],
            outstandingData: [],
            isLoading: false,
            currentYear: new Date().getFullYear(),
            charts: {
                annualReportBarChart: {
                    chartLabels: [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                    ],
                    chartData: [],
                },
                annualReportLineChart: {
                    chartLabels: [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                    ],
                    chartData: [],
                },
                paymentRecievedPieChart: {
                    chartLabels: [],
                    chartData: [],
                    legends: {},
                },
            },
            date_payment_recieved: "",
            is_payment_recieved_load: false,
        };
    },
    methods: {
        async fetchGeneralReportData() {
            this.isLoading = true;

            try {
                const response = await apiClient.get("/report/general");
                const {
                    total,
                    monthly_data,
                    payment_recieved_data,
                    outstanding_data,
                } = response.data;

                this.processTotalData(total);
                this.processMonthlyData(monthly_data);
                this.processPaymentReceivedData(payment_recieved_data);
                this.outstandingData = outstanding_data;
            } catch (error) {
                console.error("Fetch data error:", error);
            } finally {
                this.isLoading = false;
            }
        },
        async getPaymentRecievedPieChart() {
            this.is_payment_recieved_load = true;
            const { month, year } = this.date_payment_recieved || {};
            const date = month != null ? { month: (month ?? 0) + 1, year } : {};
            try {
                const response = await apiClient.get(
                    "/report/payment-recieved-by-reciever",
                    {
                        params: {
                            date: date,
                        },
                    }
                );
                this.processPaymentReceivedData(response.data);
            } catch (error) {
                console.error("Fetch data error:", error);
            } finally {
                this.is_payment_recieved_load = false;
            }
        },
        processTotalData(total) {
            this.generalReportData = [
                {
                    label: "Expenses",
                    value: this.formatNumber(total?.expenses || 0),
                    percentage: 0,
                    iconColor: "text-success",
                    indicatorBgColor: "text-success",
                },
                {
                    label: "Incomes",
                    value: this.formatNumber(total?.incomes || 0),
                    percentage: 0,
                    iconColor: "text-success",
                    indicatorBgColor: "text-success",
                },
                {
                    label: "Profit",
                    value: this.formatNumber(total?.profit || 0),
                    percentage: 0,
                    iconColor: "text-success",
                    indicatorBgColor: "text-success",
                },
            ];
        },
        processMonthlyData(monthly_data) {
            this.charts.annualReportBarChart.chartData = [
                {
                    label: "Expenses",
                    barThickness: 12,
                    maxBarThickness: 10,
                    data: monthly_data.expenses,
                    backgroundColor: "rgba(5, 155, 255, 1)",
                },
                {
                    label: "Incomes",
                    barThickness: 12,
                    maxBarThickness: 10,
                    data: monthly_data.incomes,
                    backgroundColor: "rgba(80, 141, 78, 1)",
                },
            ];
            this.charts.annualReportLineChart.chartData = [
                {
                    label: "Expenses",
                    data: monthly_data.expenses,
                    borderColor: "rgba(255, 64, 105, 1)",
                    fill: false,
                },
                {
                    label: "Incomes",
                    data: monthly_data.incomes,
                    borderColor: "rgba(80, 141, 78, 1)",
                    fill: false,
                },
            ];
        },
        processPaymentReceivedData(payment_recieved_data) {
            if (
                Array.isArray(payment_recieved_data.labels) &&
                Array.isArray(payment_recieved_data.data)
            ) {
                const total = payment_recieved_data.data.reduce(
                    (sum, value) => sum + value,
                    0
                );
                this.charts.paymentRecievedPieChart = {
                    chartLabels: payment_recieved_data.labels,
                    chartData: [
                        {
                            data: payment_recieved_data.data,
                            backgroundColor: [
                                "#508D4E",
                                "#059BFF",
                                "#FF4069",
                                "#FF9020",
                                "#FFC234",
                                "#9966FF",
                                "#C9CBCF",
                            ], // Use a default color array if colors are not provided
                        },
                    ],
                };

                // Calculate percentages
                this.charts.paymentRecievedPieChart.legends =
                    payment_recieved_data.labels.map((label, index) => {
                        return {
                            label: label,
                            value: this.formatNumber(
                                payment_recieved_data.data[index] || 0
                            ),
                            color: this.charts.paymentRecievedPieChart
                                .chartData[0].backgroundColor[index],
                            percentage:
                                (payment_recieved_data.data[index] / total) *
                                100,
                        };
                    });
            } else {
                console.error("Invalid payment received data format");
            }
        },
        formatNumber(value) {
            return new Intl.NumberFormat("en-IN", {
                style: "decimal",
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(value);
        },
    },
    mounted() {
        this.fetchGeneralReportData();
    },
    watch: {
        date_payment_recieved() {
            this.getPaymentRecievedPieChart();
        },
    },
};
</script>

<style scoped>
.legends {
    position: absolute;
    bottom: 0;
    width: 100%;
}
</style>
