import React, { useState, useEffect } from "react";
import alfetch from "../alFetch";
import { url } from "../path"
import "./component.css"
import PropTypes from 'prop-types';
import tartine from "../tartine";



export default function DeliveryMethods() {
    const [methods, setMethods] = useState([])

    useEffect(() => { getMethods() }, [])
    function getMethods() { alfetch({ url: url + 'delMethod', callBack: e => { setMethods(e) } }) }
    return (<div id='methods'>
        {methods.map((e, key) => (<OneMethod key={key} method={e} callBack={() => { getMethods() }} />))}
        <AddMethod callBack={() => { getMethods() }} />
    </div>)
}

OneMethod.propTypes = {
    callBack: PropTypes.func.isRequired,
    method: PropTypes.object.isRequired
}
function OneMethod(props) {
    const [method, setMethod] = useState({ name: props.method.name, mult: props.method.mult })

    function update() {
        alfetch({
            url: url + 'delMethod/' +  props.method.id, method: 'PATCH', body: { name: method.name, mult: method.mult }, callBack: () => {
                tartine(method.name + ' updated')
                props.callBack()
            }
        })
     }
    function del() { alfetch({ url: url + 'delMethod/' + props.method.id, method: 'DELETE', callBack: () => { props.callBack(); tartine(method.name + ' deleted') } }) }

    return (<div className="oneMethod">
        <input type="text" value={method.name} onChange={e => { setMethod({ ...method, name: e.target.value }) }} placeholder="name" />
        <input className="price" type="text" value={method.mult} onChange={e => { setMethod({ ...method, mult: e.target.value }) }} placeholder="mult" />
        <button onClick={update}>Update !</button>
        <button onClick={del} className="delete">Delete</button>
    </div>)
}
AddMethod.propTypes = {
    callBack: PropTypes.func.isRequired
}
function AddMethod(props) {
    const [method, setMethod] = useState({ name: '', mult: '' })

    function create() {
        alfetch({
            url: url + 'delMethod', method: 'POST', body: { name: method.name, mult: method.mult }, callBack: () => {
                tartine(method.name + ' added')
                props.callBack()
                setMethod({ name: '', mult: '' })
            }
        })
    }

    return (<div className="oneMethod">
        <input type="text" value={method.name} onChange={e => { setMethod({ ...method, name: e.target.value }) }} placeholder="name" />
        <input className="price" type="text" value={method.mult} onChange={e => { setMethod({ ...method, mult: e.target.value }) }} placeholder="mult" />
        <button onClick={create}>Add !</button>
    </div>)
}