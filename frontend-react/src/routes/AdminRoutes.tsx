import React from 'react'
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom'

import AdminLayout from '@/layouts/AdminLayout'
import Dashboard from '@/pages/Dashboard'
import PageNotFound from '@/pages/PageNotFound'
import PartyList from '@/pages/masters/party/PartyList'
import AddParty from '@/pages/masters/party/AddParty'
import EditParty from '@/pages/masters/party/EditParty'
import ProductList from '@/pages/masters/product/ProductList'
import AddProduct from '@/pages/masters/product/AddProduct'
import EditProduct from '@/pages/masters/product/EditProduct'
import ExpenseList from '@/pages/expense/ExpenseList'
import AddExpense from '@/pages/expense/AddExpense'
import EditExpense from '@/pages/expense/EditExpense'


const AdminRoutes: React.FC<{}> = () => {
    return (
        <div>
            <Router>
                <Routes>
                    <Route path="/admin" element={<AdminLayout />}>
                        <Route path="/admin" element={<Navigate to="/admin/dashboard" replace />} />
                        <Route path="/admin/dashboard" element={<Dashboard />} />

                        <Route path="/admin/masters/party" element={<PartyList />} />
                        <Route path="/admin/masters/add-party" element={<AddParty />} />
                        <Route path="/admin/masters/edit-party/:id" element={<EditParty />} />

                        <Route path="/admin/masters/product" element={<ProductList />} />
                        <Route path="/admin/masters/add-product" element={<AddProduct />} />
                        <Route path="/admin/masters/edit-product/:id" element={<EditProduct />} />

                        <Route path="/admin/expense" element={<ExpenseList />} />
                        <Route path="/admin/add-expense" element={<AddExpense />} />
                        <Route path="/admin/edit-expense/:id" element={<EditExpense />} />

                        <Route path="/admin/sales" element={<Dashboard />} />
                        <Route path="/admin/add-sale" element={<Dashboard />} />
                        <Route path="/admin/edit-sale" element={<Dashboard />} />

                        <Route path="/admin/payment-received" element={<Dashboard />} />

                        <Route path="/admin/reports/expense" element={<Dashboard />} />
                        <Route path="/admin/reports/payment-received" element={<Dashboard />} />
                        <Route path="/admin/reports/balance-sheet" element={<Dashboard />} />

                        <Route path="/admin/setting" element={<Dashboard />} />

                        <Route path="*" element={<PageNotFound meta={{ title: '404 - Page Not Found' }} />} />
                    </Route>
                    <Route path="*" element={<Navigate to="/admin" replace />} />
                </Routes>
            </Router>
        </div>
    )
}

export default AdminRoutes
