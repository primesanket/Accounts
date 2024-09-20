import React from 'react'
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom'

import GuestLayout from '@/layouts/GuestLayout';
import Login from '@/pages/Login';
import PageNotFound from '@/pages/PageNotFound';

const GuestRoutes: React.FC<{}> = () => {
    return (
        <div>
            <Router>
                <Routes>
                    <Route path="/" element={<GuestLayout />}>
                        <Route path="/" element={<Login meta={{ title: 'Login' }} />} />
                        <Route path="/register" element={<Login meta={{ title: 'Login' }} />} />
                        <Route path="/reset-password" element={<Login meta={{ title: 'Login' }} />} />
                        <Route path="*" element={<PageNotFound meta={{ title: '404 - Page Not Found' }} />} />
                    </Route>
                    <Route path="*" element={<Navigate to="/" replace />} />
                </Routes>
            </Router>
        </div>
    )
}

export default GuestRoutes
