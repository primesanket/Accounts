import React, { useState, useEffect } from "react";
import { useLocation, Link } from "react-router-dom";
import {
    House,
    ChevronDown,
    Activity,
    Box,
    Landmark,
    HandCoins,
    BadgeCheck,
    ChartColumnIncreasing,
    Settings,
} from "lucide-react";

interface MenuItem {
    label: string;
    path: string;
    icon?: React.ElementType;
    active: boolean;
    open: boolean;
    pathNamesForActive: string[];
    children?: MenuItem[];
}

const NavBar: React.FC = () => {
    const [menuItems, setMenuItems] = useState<MenuItem[]>([
        {
            label: "Dashboard",
            path: "/admin/dashboard",
            icon: House,
            active: false,
            open: false,
            pathNamesForActive: [],
            children: [],
        },
        {
            label: "Masters",
            path: "/masters",
            icon: Box,
            active: false,
            open: false,
            pathNamesForActive: [],
            children: [
                {
                    label: "Party",
                    path: "/admin/masters/party",
                    icon: Activity,
                    active: false,
                    open: false,
                    pathNamesForActive: [],
                    children: [
                        {
                            label: "AddParty",
                            path: "/admin/masters/add-party",
                            active: false,
                            open: false,
                            pathNamesForActive: [],
                            children: [],
                        },
                        {
                            label: "EditParty",
                            path: "/admin/masters/edit-party/:id",
                            active: false,
                            open: false,
                            pathNamesForActive: [],
                            children: [],
                        },
                    ],
                },
                {
                    label: "Product",
                    path: "/admin/masters/product",
                    icon: Activity,
                    active: false,
                    open: false,
                    pathNamesForActive: [],
                    children: [
                        {
                            label: "AddProduct",
                            path: "/masters/add-product",
                            active: false,
                            open: false,
                            pathNamesForActive: [],
                            children: [],
                        },
                        {
                            label: "EditProduct",
                            path: "/masters/edit-product/:id",
                            active: false,
                            open: false,
                            pathNamesForActive: [],
                            children: [],
                        },
                    ],
                },
            ],
        },
        {
            label: "Expense",
            path: "/admin/expense",
            icon: Landmark,
            active: false,
            open: false,
            pathNamesForActive: ["add-expense", "edit-expense"],
            children: [],
        },
        {
            label: "Sale",
            path: "/admin/sales",
            icon: HandCoins,
            active: false,
            open: false,
            pathNamesForActive: ["add-sale", "edit-sale"],
            children: [],
        },
        {
            label: "Payment Received",
            path: "/admin/payment-received",
            icon: BadgeCheck,
            active: false,
            open: false,
            pathNamesForActive: [],
            children: [],
        },
        {
            label: "Reports",
            path: "/reports",
            icon: ChartColumnIncreasing,
            active: false,
            open: false,
            pathNamesForActive: [],
            children: [
                {
                    label: "Expense",
                    path: "/admin/reports/expense",
                    icon: Activity,
                    active: false,
                    open: false,
                    pathNamesForActive: [],
                    children: []
                },
                {
                    label: "Payment Received",
                    path: "/admin/reports/payment-received",
                    icon: Activity,
                    active: false,
                    open: false,
                    pathNamesForActive: [],
                    children: []
                },
                {
                    label: "Balance Sheet",
                    path: "/admin/reports/balance-sheet",
                    icon: Activity,
                    active: false,
                    open: false,
                    pathNamesForActive: [],
                    children: []
                },
            ],
        },
        {
            label: "Settings",
            path: "/admin/setting",
            icon: Settings,
            active: false,
            open: false,
            pathNamesForActive: [],
            children: [],
        },
    ]);

    const location = useLocation();

    const resetMenuItems = () => {
        setMenuItems((prevItems) =>
            prevItems.map((item) => ({
                ...item,
                active: false,
                open: false,
                children: item.children
                    ? item.children.map((subItem) => ({
                        ...subItem,
                        active: false,
                        children: subItem.children
                            ? subItem.children.map((childItem) => ({
                                ...childItem,
                                active: false,
                            }))
                            : [],
                    }))
                    : [],
            }))
        );
    };

    const setActiveMenuItem = () => {
        const currentRoutePath = location.pathname;
        const currentRouteName = location.pathname.split("/").pop();

        resetMenuItems();

        const setActiveRecursively = (item: MenuItem) => {
            if (item.pathNamesForActive.includes(currentRouteName || "")) {
                item.active = true;
                item.open = true;
            }

            if (item.children) {
                let childActive = false;

                item.children.forEach((subItem) => {
                    setActiveRecursively(subItem);
                    if (subItem.active) childActive = true;

                    if (subItem.children) {
                        subItem.children.forEach((childItem) => {
                            if (childItem.path === currentRoutePath) {
                                subItem.active = true;
                                childActive = true;
                            }
                        });
                    }
                });

                if (childActive) {
                    item.active = true;
                    item.open = true;
                }
            }

            if (item.path === currentRoutePath) {
                item.active = true;
                item.open = true;
            }
        };

        setMenuItems((prevItems) =>
            prevItems.map((item) => {
                setActiveRecursively(item);
                return item;
            })
        );
    };

    useEffect(() => {
        setActiveMenuItem();
    }, [location]);

    return (
        <nav className="side-nav">
            <Link to="" className="intro-x flex items-center pl-5 pt-4">
                <img alt="Logo" className="w-6" src="/src/assets/images/logo.svg" />
                <span className="hidden xl:block text-white text-lg ml-3">
                    Admin
                </span>
            </Link>
            <div className="side-nav__devider my-6"></div>
            <ul>
                {menuItems.map((item) => (
                    <li key={item.label}>
                        {item.children && item.children.length ? (
                            <>
                                <div
                                    className={`side-menu ${item.active ? "side-menu--active" : ""
                                        } ${item.open ? "side-menu--open" : ""}`}
                                    onClick={() =>
                                        setMenuItems((prevItems) =>
                                            prevItems.map((i) =>
                                                i.label === item.label
                                                    ? { ...i, open: !i.open }
                                                    : i
                                            )
                                        )
                                    }
                                >
                                    <div className="side-menu__icon">
                                        {item.icon && <item.icon />}
                                    </div>
                                    <div className="side-menu__title">
                                        {item.label}
                                        <div
                                            className={`side-menu__sub-icon ${item.open ? "transform rotate-180" : ""
                                                }`}
                                        >
                                            <ChevronDown />
                                        </div>
                                    </div>
                                </div>
                                <ul className={item.open ? "side-menu__sub-open" : ""}>
                                    {item.children.map((subItem) => (
                                        <li key={subItem.label}>
                                            <Link
                                                to={subItem.path}
                                                className={`side-menu ${subItem.active ? "side-menu--active" : ""
                                                    }`}
                                            >
                                                <div className="side-menu__icon">
                                                    {subItem.icon && <subItem.icon />}
                                                </div>
                                                <div className="side-menu__title">
                                                    {subItem.label}
                                                </div>
                                            </Link>
                                        </li>
                                    ))}
                                </ul>
                            </>
                        ) : (
                            <Link
                                to={item.path}
                                className={`side-menu ${item.active ? "side-menu--active" : ""
                                    }`}
                            >
                                <div className="side-menu__icon">
                                    {item.icon && <item.icon />}
                                </div>
                                <div className="side-menu__title">{item.label}</div>
                            </Link>
                        )}
                    </li>
                ))}
            </ul>
        </nav>
    );
};

export default NavBar;
