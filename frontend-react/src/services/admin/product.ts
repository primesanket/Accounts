import api from '@/services/axiosService';

export const getProductList = async (page: number, search: string) => {
    const { data } = await api.get('/product', { params: { page, search } });
    return data;
};

export const getProduct = async (id: number) => {
    return await api.get(`/product/${id}`);
};

export const createProduct = async (payload: any) => {
    return await api.post('/product', payload);
};

export const updateProduct = async (id: number, payload: any) => {
    return await api.put(`/product/${id}`, payload);
};

export const deleteProduct = async (id: number) => {
    return await api.delete(`/product/${id}`);
};