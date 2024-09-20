import { combineReducers } from 'redux';
import authReducer from './authReducer';

const reducers = combineReducers<any>({
    auth: authReducer,
});

export default reducers;
