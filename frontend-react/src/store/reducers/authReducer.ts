import { LOGIN_SUCCESS, LOGIN_FAILURE } from "@/store/actions/authActions";

const initialState = {
    token: null,
    user: null,
    role: null,
    authenticated: false,
    is_verified: false
};

const authReducer = (state = initialState, action: any) => {
    switch (action.type) {
        case LOGIN_SUCCESS:
            return {
                ...state,
                token: action.payload.token,
                user: action.payload.user,
                role: action.payload.role,
                authenticated: action.payload.authenticated,
                is_verified: action.payload.is_verified,
            };
        case LOGIN_FAILURE:
            return {
                token: null,
                user: null,
                role: null,
                authenticated: false,
                is_verified: false
            };
        default:
            return state;
    }
};

export default authReducer;
