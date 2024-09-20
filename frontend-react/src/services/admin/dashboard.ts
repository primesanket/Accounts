import api from "../axiosService"

export const getGeneralData = async () => {
    return await api.get('/report/general');
}

export const getPaymentRecieved = async (date: any) => {
    return await api.get('/report/payment-recieved-by-reciever', { params: { date } });
} 