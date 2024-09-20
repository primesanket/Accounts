import api from '@/services/axiosService';

export const getExpenseList = async (page: number, search: string) => {
    const { data } = await api.get('/expense', { params: { page, search } });
    return data;
};

export const getExpense = async (id: number) => {
    return await api.get(`/expense/${id}`);
};

export const createExpense = async (payload: any) => {
    return await api.post('/expense', payload);
};

export const updateExpense = async (id: number, payload: any) => {
    return await api.put(`/expense/${id}`, payload);
};

export const deleteExpense = async (id: number) => {
    return await api.delete(`/expense/${id}`);
};