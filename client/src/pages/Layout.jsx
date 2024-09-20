import { Outlet, Link } from "react-router-dom";
import Basket from "../components/basket";
import React from "react"

const Layout = () => {
    if (localStorage.getItem('user') === null) {
        localStorage.setItem("id", 'false');
        localStorage.setItem("user", 'false');
        localStorage.setItem("admin", 'false');
    }
    return (
        <>
            <nav>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/24/LEGO_logo.svg/2048px-LEGO_logo.svg.png" alt="Logo de Lego" />
                <ul>
                    <li><Link to="/">Home</Link></li>
                    {localStorage.getItem('user') && localStorage.getItem('user') !== 'false' ? <li><Link to="/profile">Profile</Link></li> : null}
                    {localStorage.getItem('user') && localStorage.getItem('user') !== 'false' ? null : <li><Link to="/login">Login</Link></li> }
                    {localStorage.getItem('admin') === 'true' ? <li><Link to="/admin">Admin</Link></li> : null}
                    <Basket />
                </ul>
            </nav>
            <Outlet />
        </>
    )
};

export default Layout;