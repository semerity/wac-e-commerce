import React, { useState, useEffect } from "react";
import alfetch from "../alFetch";
import { url } from "../path"
import "./component.css"
import PropTypes from 'prop-types';

export default function Commandes() {
    const [commandes, setCommandes] = useState([])
    const [methods, setMethods] = useState([])
    useEffect(() => { getMethod() }, [])
    function getCommand() { alfetch({ url: url + 'commandes/user/' + localStorage.getItem("id"), callBack: e => { setCommandes(e) } }) }
    function getMethod() { alfetch({ url: url + 'delMethod', callBack: e => { setMethods(e); getCommand() } }) }
    if (commandes.length === 0) return (<></>)
    return (<table id='commandes'>
        <caption>
            Your commands
        </caption>
        <thead>
            <tr>
                <th>Id</th>
                <th>Price</th>
                <th>Method</th>
                <th>State</th>
                <th>Articles</th>
            </tr>
        </thead>
        <tbody>
            {commandes.map((e, key) => {
                let art = JSON.parse(e.articles).map(e => (e.name.substring(0, 8) + '...')).join(',')
                // console.log(methods[e.method - 1].name)
                return <tr key={key} className="commande">
                    <td>{e.id}</td>
                    <td>{e.prix + '€'}</td>
                    <td>{methods[e.method - 1].name}</td>
                    <td>{e.etat}</td>
                    <td>{art.substring(0, 40)}</td>
                </tr>
            })}
        </tbody>
    </table>)
}
