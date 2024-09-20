import React from 'react';
import { Outlet } from 'react-router-dom';
import TopBar from '@/components/Atoms/UI/TopBar';
import NavBar from '@/components/Atoms/UI/NavBar';

const AdminLayout: React.FC = () => {
    return (
        <div>
            <div className="flex mt-[4.7rem] md:mt-0">
                <NavBar />
                <div className="content">
                    <TopBar />
                    <div className="pt-5">
                        <Outlet />
                    </div>
                </div>
            </div>
            <main>

            </main>
        </div>
    );
};

export default AdminLayout;
