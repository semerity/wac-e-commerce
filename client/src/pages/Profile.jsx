import { useState } from "react"
import alfetch from "../alFetch"
import { url } from "../path"
import React from "react"
import Commandes from "../components/commandes"

export default function Profile() {
    const [email, setEmail] = useState(localStorage.getItem('user'))
    const [password, setPassword] = useState('')

    if (localStorage.getItem("user") === 'false') {
        let a = document.createElement('a')
        a.href = '/login'
        a.click()
    }
    async function unlog() {
        await fetch(url + 'logout', {
            credentials: 'include',
        }).then(e => e.json()).then(e => console.log(e))

        localStorage.removeItem("id");
        localStorage.removeItem("user");
        localStorage.removeItem("admin");
        
        localStorage.removeItem('basketArr')

        let a = document.createElement('a')
        a.href = '/login'
        a.click()
    }

    function updateUser() {
        alfetch({
            url: url + 'user/' + localStorage.getItem("id"),
            method: 'PATCH',
            body: {
                email: email,
                password: password
            },
            callBack: e => {
                localStorage.setItem("user", email);

                let a = document.createElement('a')
                a.href = '/profile'
                a.click()
            }
        })
    }




    return (
        <section id="profile">
            <div id="hello">
                <p>Hello</p>
                <p id='email'>{email}</p>
            </div>

            <div id="form">
                <h2>Change your infos</h2>
                <input type="text" value={email} onChange={e => { setEmail(e.target.value) }} />
                <input type="text" value={password} onChange={e => { setPassword(e.target.value) }} placeholder="new Password" />
                <button onClick={updateUser}>Update</button>
            </div>
            <button onClick={unlog}>Unlog</button>

            <Commandes />

        </section>
    )
}