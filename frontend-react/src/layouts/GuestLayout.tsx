import React from 'react'
import { Outlet } from 'react-router-dom'

const GuestLayout: React.FC<{}> = () => {
    return (
        <div>
            <main>
                <Outlet />
            </main>
        </div>
    )
}

export default GuestLayout
