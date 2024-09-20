import { StrictMode, Suspense } from 'react'
import { createRoot } from 'react-dom/client'
import { QueryClient, QueryClientProvider, } from 'react-query'
import { Provider } from 'react-redux';
import App from './App'
import 'react-toastify/dist/ReactToastify.css';
import '@/assets/main.css'
import { persistor, store } from '@/store'
import { ToastContainer } from 'react-toastify';
import { PersistGate } from 'redux-persist/integration/react';
import PageLoader from './components/Atoms/Loader/PageLoader';

const queryClient = new QueryClient();

createRoot(document.getElementById('root')!).render(
  // <StrictMode>
  <Provider store={store}>
    <PersistGate loading={null} persistor={persistor}>
      <Suspense fallback={<PageLoader />}>
        <QueryClientProvider client={queryClient}>
          <App />
        </QueryClientProvider>
      </Suspense>
      <ToastContainer
        position="top-right"
        autoClose={5000}
        hideProgressBar={false}
        newestOnTop={false}
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
        theme="light"
      />
    </PersistGate>
  </Provider>
  // </StrictMode>
)
