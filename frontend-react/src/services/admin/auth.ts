import api from "../axiosService"
import { AxiosResponse } from "axios";

interface SignInData {
    email: string,
    password: string
}

export const SignIn = async (data: SignInData): Promise<AxiosResponse | undefined> => {
    try {
        const response: AxiosResponse = await api.post('/login', data);
        return response;
    } catch (error) {
        throw error;
    }
};