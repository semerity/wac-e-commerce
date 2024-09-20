import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import React from 'react'
import { Toaster } from 'react-hot-toast'

import Layout from "./pages/Layout";
import Home from "./pages/Home";
import NoPage from "./pages/NoPage"
import Profile from "./pages/Profile"
import Login from "./pages/Login"
import Product from "./pages/Product"
import Admin from "./pages/Admin"
import Cart from "./pages/Cart";

import './index.css'

export default function App() {
    return (
        <>
            <div><Toaster
                position="bottom-center"
                reverseOrder={false}
            /></div>
            <BrowserRouter>
                <Routes>
                    <Route path="/" element={<Layout />}>
                        <Route index element={<Home />} />
                        <Route path="profile" element={<Profile />} />
                        <Route path="product/*" element={<Product />} />
                        <Route path="admin" element={<Admin />} />
                        <Route path="login" element={<Login />} />
                        <Route path="cart" element={<Cart />} />
                        <Route path="*" element={<NoPage />} />
                    </Route>
                </Routes>
            </BrowserRouter>
        </>
    );
}

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(<App />);