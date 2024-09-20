import React, { useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { getTokenCookie } from './utils/cookies';
import { LOGIN_FAILURE } from './store/actions/authActions';
const GuestRoutes = React.lazy(() => import('@/routes/GuestRoutes'));
const AdminRoutes = React.lazy(() => import('@/routes/AdminRoutes'));

interface AuthState {
  auth: {
    token: string | null;
    role: string | null;
    authenticated: boolean;
    is_verified: boolean;
  };
}

const App: React.FC = () => {
  const { authenticated } = useSelector((state: AuthState) => state.auth);
  const tokenCookie = getTokenCookie();
  const dispatch = useDispatch();
  useEffect(() => {
    if (!tokenCookie) {
      dispatch({
        type: LOGIN_FAILURE,
      });
    }
  }, [])

  if (authenticated && tokenCookie) {
    return (
      <>
        <AdminRoutes />
      </>
    );
  }

  return (
    <>
      <GuestRoutes />
    </>
  );
};

export default App;
