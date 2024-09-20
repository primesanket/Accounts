import React from 'react'

const TopBar: React.FC = () => {
    return (
        <div className="top-bar">
            {/* BEGIN: Breadcrumb */}
            <nav aria-label="breadcrumb" className="-intro-x mr-auto hidden sm:flex">
                <ol className="breadcrumb">
                    <li className="breadcrumb-item active" aria-current="page">
                    </li>
                </ol>
            </nav>
            {/* END: Breadcrumb */}
            {/* BEGIN: Account Menu */}
            <div className="intro-x dropdown w-8 h-8">
                <div
                    className="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in"
                    role="button"
                    aria-expanded="false"
                    data-tw-toggle="dropdown"
                >
                    <img alt="Midone - HTML Admin Template" src="/src/assets/images/profile-5.jpg" />
                </div>
                <div
                    className="dropdown-menu w-56"
                    data-popper-placement="bottom-end"
                >
                    <ul className="dropdown-content bg-primary text-white">
                        <li className="p-2"> <div className="font-medium">Admin</div> </li>
                        <li> <hr className="dropdown-divider border-white/[0.08]" /> </li>
                        <li> <button className="dropdown-item hover:bg-white/5 w-full" > Logout </button> </li>
                    </ul>
                </div >
            </div >
            {/* END: Account Menu */}
        </div >
    )
}

export default TopBar;
