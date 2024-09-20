import useTitle from '@/hooks/useTitle'
import React, { useEffect, useState } from 'react'
import { getGeneralData, getPaymentRecieved } from '@/services/admin/dashboard';
import { Activity, Calendar, TriangleAlert } from 'lucide-react';
import FormDatePicker from '@/components/Atoms/Form/FormDatePicker/FormDatePicker';
import BarChart from '@/components/Molecules/charts/BarChart';
import LineChart from '@/components/Molecules/charts/LineChart';
import PieChart from '@/components/Molecules/charts/PieChart';
import Loader from '@/components/Atoms/Loader/Loader';

interface generalDataTypes {
    label: string;
    value: number;
    percentage: number;
    iconColor: string;
    indicatorBgColor: string;
}

interface annualReportDataTypes {
    label: string;
    barThickness: number;
    maxBarThickness: number;
    data: number[];
    backgroundColor: string;
}

interface annualReportLineChartDataTypes {
    label: string;
    data: number[];
    borderColor: string;
    fill: boolean;
}

interface paymentReceivedPieChartDataTypes {
    chartLabels: string[];
    chartData: {
        data: number[];
        backgroundColor: string[];
    }[];
    legends: {
        label: string;
        value: number;
        color: string;
        percentage: number;
    }[];
}

interface outStandingDataTypes {
    mehulProfit: number;
    bhavinProfit: number;
}


const Dashboard: React.FC = () => {

    useTitle('Dashboard')

    const [isDataLoading, setIsDataLoading] = useState(false);
    const [generalData, setGeneralData] = useState<generalDataTypes[]>([]);
    const [annualReportData, setAnnualReportData] = useState<annualReportDataTypes[]>([]);
    const [annualReportDataForLineChart, setAnnualReportDataForLineChart] = useState<annualReportLineChartDataTypes[]>([]);
    const [paymentReceivedDate, setPaymentReceivedDate] = useState<string | null>(null);
    const [paymentReceivedLoad, setPaymentReceivedLoad] = useState<boolean>(true);
    const [paymentReceivedPieChartData, setPaymentReceivedPieChartData] = useState<paymentReceivedPieChartDataTypes>({
        chartLabels: [],
        chartData: [],
        legends: []
    });
    const [outStandingData, setOutStandingData] = useState<outStandingDataTypes>({
        mehulProfit: 0,
        bhavinProfit: 0
    });

    const currentYear = new Date().getFullYear();

    useEffect(() => {
        fetchGeneralReportData();
    }, []);

    const fetchGeneralReportData = async () => {
        setIsDataLoading(true);
        try {
            const response = await getGeneralData();
            const { total, monthly_data, payment_recieved_data, outstanding_data } = response.data;
            setGeneralData(processTotalData(total));
            setAnnualReportData(processAnnualReportData(monthly_data));
            setAnnualReportDataForLineChart(processAnnualReportDataForLineChart(monthly_data));
            setTimeout(() => {
                setPaymentReceivedLoad(false);
            }, 500);
            setPaymentReceivedPieChartData(processPaymentReceivedData(payment_recieved_data));
            setOutStandingData(proccessOutStandingData(outstanding_data))

        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            setIsDataLoading(false);
        }
    };

    const handlePaymentReceivedDate = (date: Date | null) => {
        if (date != null) {
            const month = date.getMonth() + 1; // Months are 0-indexed
            const year = date.getFullYear();
            getPaymentRecievedPieChart({
                month: month,
                year: year
            });
            setPaymentReceivedDate(date.toISOString().split('T')[0]);
        } else {
            setPaymentReceivedDate(null);
        }
    };

    const processTotalData = (total: any) => {
        return [
            {
                label: "Expenses",
                value: total?.expenses || 0,
                percentage: 0,
                iconColor: "text-success",
                indicatorBgColor: "text-success",
            },
            {
                label: "Incomes",
                value: total?.incomes || 0,
                percentage: 0,
                iconColor: "text-success",
                indicatorBgColor: "text-success",
            },
            {
                label: "Profit",
                value: total?.profit || 0,
                percentage: 0,
                iconColor: "text-success",
                indicatorBgColor: "text-success",
            },
        ];
    };

    const annualReportChartLabels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    const processAnnualReportData = (monthly_data: any) => {
        return [{
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
        }];
    };
    const processAnnualReportDataForLineChart = (monthly_data: any) => {
        return [{
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
        }];
    };
    const processPaymentReceivedData = (data: any): paymentReceivedPieChartDataTypes => {
        if (Array.isArray(data.labels) && Array.isArray(data.data)) {
            const total = data.data.reduce((sum: number, value: number) => sum + value, 0);
            const backgroundColors = [
                "#508D4E",
                "#059BFF",
                "#FF4069",
                "#FF9020",
                "#FFC234",
                "#9966FF",
                "#C9CBCF",
            ];
            return {
                chartLabels: data.labels || [],
                chartData: [
                    {
                        data: data.data,
                        backgroundColor: backgroundColors,
                    },
                ],
                legends: data.labels.map((label: string, index: number) => {
                    const value = data.data[index] || 0;
                    return {
                        label: label,
                        value: value,
                        color: backgroundColors[index],
                        percentage: total ? (value / total) * 100 : 0,
                    };
                })
            };
        }
        return {
            chartLabels: [],
            chartData: [],
            legends: []
        };
    };
    const proccessOutStandingData = (data: any) => {
        return {
            mehulProfit: data?.mehul_profit || 0,
            bhavinProfit: data?.bhavin_profit || 0
        }
    };

    const getPaymentRecievedPieChart = async (data: any) => {
        setPaymentReceivedLoad(true);
        const date = data ? data : {};
        try {
            const response = await getPaymentRecieved(date);
            setTimeout(() => {
                setPaymentReceivedLoad(false);
            }, 500);
            setPaymentReceivedPieChartData(processPaymentReceivedData(response.data));
        } catch (error) {
            console.error("Fetch data error:", error);
        } finally {
            setPaymentReceivedLoad(false);
        }
    };

    return (
        <div className="grid grid-cols-12 gap-6">
            <div className="col-span-12 2xl:col-span-12">
                <div className="grid grid-cols-12 gap-6">
                    {isDataLoading ? (
                        <div className="col-span-12 flex justify-center mt-12" >
                            <Loader stroke='rgb(0,0,0)' />
                        </div>
                    ) : (
                        <div className="col-span-12">
                            <div className="col-span-12">
                                <div className="intro-y flex items-center h-10">
                                    <h2 className="text-lg font-medium truncate mr-5"> General Report </h2>
                                    <a href="#" onClick={fetchGeneralReportData} className="ml-auto flex items-center text-primary" > Reload Data </a>
                                </div>
                                <div className="grid grid-cols-12 gap-6 mt-5">
                                    {/* <!-- Report Data --> */}
                                    {
                                        generalData.map((row, index) => (
                                            <div className="col-span-12 sm:col-span-6 xl:col-span-3 intro-y" key={index}>
                                                <div className="report-box zoom-in">
                                                    <div className="box p-5">
                                                        <div className="flex"><Activity className={`report-box__icon ${row.iconColor}`} /></div>
                                                        <div className="flex align-center text-3xl font-medium leading-8 mt-6" >{row.value}</div>
                                                        <div className="text-base text-slate-500 mt-1" >{row.label}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        ))
                                    }
                                    <div className="col-span-12 sm:col-span-6 xl:col-span-3 intro-y" >
                                        <div className="report-box zoom-in">
                                            <div className="box p-5">
                                                <div className="flex justify-between">
                                                    <Activity className="report-box__icon text-success" />
                                                    <div className="text-2xl text-slate-400" > Outstanding </div>
                                                </div>
                                                <div className="flex align-center text-3xl font-medium leading-8 mt-6" ></div>
                                                <div className="flex justify-between text-base text-slate-500 mt-1" >
                                                    <span className="text-slate-500" >Mehul</span >
                                                    <span className="text-slate-800 font-bold" >{outStandingData.mehulProfit}</span >
                                                </div>
                                                <div className="flex justify-between text-base text-slate-500 mt-1" >
                                                    <span className="text-slate-500" >Bhavin</span >
                                                    <span className="text-slate-800 font-bold" >{outStandingData.bhavinProfit}</span >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="grid grid-cols-12 gap-6 mt-5">
                                    <div className="col-span-5 mt-12">
                                        <div className="intro-y flex items-center h-10">
                                            <h2 className="text-lg font-medium truncate mr-5">
                                                Annual Report ({currentYear})
                                            </h2>
                                        </div>
                                        <div className="intro-y box p-5 mt-5">
                                            <div className="grid grid-cols-12 gap-6">
                                                <div className="col-span-12 sm:col-span-12 xl:col-span-12 intro-y">
                                                    <div className="h-[400px]">
                                                        <BarChart chartId={'annualReportBarChart'} labels={annualReportChartLabels} datasets={annualReportData} />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="col-span-5 mt-12">
                                        <div className="intro-y flex items-center h-10">
                                            <h2 className="text-lg font-medium truncate mr-5">
                                                Annual Report ({currentYear})
                                            </h2>
                                        </div>
                                        <div className="intro-y box p-5 mt-5">
                                            <div className="grid grid-cols-12 gap-6">
                                                <div className="col-span-12 sm:col-span-12 xl:col-span-12 intro-y">
                                                    <div className="h-[400px]">
                                                        <LineChart chartId={'annualReportLineChart'} labels={annualReportChartLabels} datasets={annualReportDataForLineChart} />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="col-span-2 mt-12">
                                        <div
                                            className="intro-y flex justify-center items-center h-10"
                                        >
                                            <h2 className="text-lg font-medium truncate">
                                                Payment Recieved
                                            </h2>
                                        </div>
                                        <div className="intro-y box p-5 mt-5">
                                            <div className="grid grid-cols-12">
                                                <div
                                                    className="col-span-12 sm:col-span-12 xl:col-span-12 intro-y"
                                                >
                                                    <div className="h-[400px]">
                                                        <FormDatePicker
                                                            id="paymentReceivedDate"
                                                            name="paymentReceivedDate"
                                                            placeholder="Pick a month"
                                                            selectedDate={paymentReceivedDate ? new Date(paymentReceivedDate) : null}
                                                            onDateChange={handlePaymentReceivedDate}
                                                            viewMode="month"
                                                            className="w-full"
                                                            isClearable={true}
                                                        />

                                                        {paymentReceivedLoad ? (
                                                            <div className='col-span-12 flex justify-center mt-12'>
                                                                <Loader stroke='rgb(0, 0, 0)' />
                                                            </div>
                                                        ) : (
                                                            paymentReceivedPieChartData.chartLabels.length > 0 && paymentReceivedPieChartData.chartData.length > 0 ? (
                                                                <PieChart
                                                                    chartId="paymentReceivedPieChart"
                                                                    labels={paymentReceivedPieChartData.chartLabels}
                                                                    datasets={paymentReceivedPieChartData.chartData}
                                                                />
                                                            ) : (
                                                                <div className="alert border-0 show mt-5 pb-10" role="alert">
                                                                    <TriangleAlert size="80" className="mx-auto mt-5 text-slate-400" />
                                                                    <p className="text-slate-500 text-center">No data found.</p>
                                                                </div>
                                                            )
                                                        )}

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div >
        </div >
    )
}

export default Dashboard
