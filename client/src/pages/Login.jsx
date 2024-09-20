import React, { useState } from "react";
import { url } from "../path"
import alfetch from "../alFetch";

export default function Login() {
    const [mail, setMail] = useState('')
    const [password, setPassword] = useState('')
    function login() {
        if(mail === '' || password === '')return
        alfetch({
            url: url + 'login',
            method: 'POST',
            body: {
                email: mail,
                password: password
            },
            callBack: e=>{setUser(e)}
        })
    }
    function register() {
        if(mail === '' || password === '')return
        alfetch({
            url: url + 'register', 
            method: 'POST', 
            body: {
                email: mail,
                password: password
            },
            callBack: e=>{setUser(e)}
        })
    }
    function setUser(e){
        console.log(e)
        localStorage.setItem("id", e.user);
        localStorage.setItem("user", e.email);
        if(e.roles){if(e.roles.includes('ROLE_ADMIN')){localStorage.setItem("admin",'true');}}
        setMail('')
        setPassword('')
        let a = document.createElement('a')
        a.href = '/'
        a.click()
    }
    return (
        <section id="login">
            <input type="email" value={mail} onChange={e => { setMail(e.target.value) }} placeholder='email' />
            <input type="password" value={password} onChange={e => { setPassword(e.target.value) }} placeholder='password' />
            <div>
                <input type="submit" value="Login" onClick={login} />
                <input type="submit" value="Register" onClick={register} />
            </div>
        </section>
    )
}