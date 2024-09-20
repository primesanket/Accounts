import Cookies from 'js-cookie'

export const setTokenCookie = (token: string): void => {
    Cookies.set('token', token, { secure: false, sameSite: 'Strict', expires: 1 / 12 });
};

export const getTokenCookie = (): string | undefined => {
    return Cookies.get('token');
};

export const removeTokenCookie = (): void => {
    Cookies.remove('token');
};