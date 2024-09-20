import api from '@/services/axiosService';

export const createExpenseType = async (payload: any) => {
    return await api.post('/expense-types', payload);
}

export const getExpenseType = async () => {
    return await api.get(`/drop-down/expense_types`);
};

export const getExpenseAccount = async () => {
    return await api.get(`/drop-down/expense_accounts`);
};