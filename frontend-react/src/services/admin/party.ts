import api from '@/services/axiosService';

export const getPartyList = async (page: number, search: string) => {
    const { data } = await api.get('/party', { params: { page, search } });
    return data;
};

export const getParty = async (id: number) => {
    return await api.get(`/party/${id}`);
};

export const createParty = async (payload: any) => {
    return await api.post('/party', payload);
};

export const updateParty = async (id: number, payload: any) => {
    return await api.put(`/party/${id}`, payload);
};

export const deleteParty = async (id: number) => {
    return await api.delete(`/party/${id}`);
};