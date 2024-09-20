import React, { useEffect, useState } from "react";
import alfetch from "../alFetch";
import { url } from "../path"
import "./component.css"
import PropTypes from 'prop-types';
import tartine from "../tartine";


export default function Themes() {
    const [themes, setThemes] = useState([])

    useEffect(() => { getThemes() }, [])

    function getThemes() {
        alfetch({
            url: url + 'theme',
            callBack: e => { setThemes(e) }
        })
    }


    return (
        <div id="themes">
            {/* <h2>Themes</h2> */}
            {themes.map((e, key) => <Theme id={e.id} theme={e.theme} color={e.color} key={key} callBack={() => { getThemes() }} />)}
            <AddTheme callBack={() => { getThemes() }} />
        </div>
    )
}
Theme.propTypes = {
    theme: PropTypes.string.isRequired,
    color: PropTypes.string,
    callBack: PropTypes.func.isRequired,
    id: PropTypes.number.isRequired
}
function Theme(props) {
    const [name, setName] = useState(props.theme)
    const [color, setColor] = useState(props.color ? props.color : "#efef25")

    function update() {
        alfetch({
            url: url + 'theme',
            body: {
                name: name,
                color: color,
                id_user: parseInt(localStorage.getItem('id')),
                id_theme: props.id
            }, method: 'PATCH', callBack: ()=>{
                tartine(name + ' updated')
                props.callBack()
            }
        })
    }

    function del() { alfetch({ url: url + 'theme', body: { id_user: parseInt(localStorage.getItem('id')), id_theme: props.id }, method: 'DELETE', callBack: ()=>{tartine(name + ' deleted');props.callBack()} }) }

    return (
        <div className="theme">
            <input type="text" value={name} onChange={e => { setName(e.target.value) }} />
            <input type="color" value={color} onChange={e => { setColor(e.target.value) }} />
            <button onClick={update}>Update</button>
            <button onClick={del} className="delete">Delete</button>
        </div>
    )
}
AddTheme.propTypes = {
    callBack: PropTypes.func.isRequired
}
function AddTheme(props) {
    const [name, setName] = useState('')
    const [color, setColor] = useState('#efef25')

    function add() {
        alfetch({
            url: url + 'theme',
            body: {
                name: name,
                color: color,
                id_user: parseInt(localStorage.getItem('id'))
            },
            method: 'POST',
            callBack: ()=>{
                tartine(name + ' added')
                props.callBack()
            }
        })
    }

    return (
        <div className="theme">
            <input type="text" value={name} onChange={e => { setName(e.target.value) }} />
            <input type="color" value={color} onChange={e => { setColor(e.target.value) }} />
            <button onClick={add}>Add</button>
        </div>
    )
}