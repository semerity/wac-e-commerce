import React, { useEffect, useState } from "react";
import alfetch from "../alFetch";
import { url } from "../path"
import "./component.css"
import PropTypes from 'prop-types';
import tartine from '../tartine.js'

export default function Pays() {
    const [pays, setPays] = useState([])

    useEffect(() => { getPays() }, [])
    function getPays() { alfetch({ url: url + 'pays', callBack: e => { setPays(e) } }) }

    return (<div id='pays'>
        {pays.map((e, key) => <OnePays callBack={getPays} pays={e} key={key} />)}
        <AddPays callBack={getPays} />
    </div>)
}


OnePays.propTypes = {
    callBack: PropTypes.func.isRequired,
    pays: PropTypes.object.isRequired
}
function OnePays(props) {
    const [pays, setPays] = useState({ nom: props.pays.nom, tarif: props.pays.tarif })
    function update() { alfetch({ url: url + 'admin/pays/' + props.pays.id, method: 'PATCH', body: { nom: pays.nom, tarif: pays.tarif }, callBack: () => { tartine(pays.nom + ' updated'); props.callBack() } }) }
    function del() { alfetch({ url: url + 'admin/pays/' + props.pays.id, method: 'DELETE', callBack: () => { tartine(pays.nom + ' removed'); props.callBack() } }) }
    return (<div className="onePays">
        <input type="text" value={pays.nom} onChange={e => { setPays({ ...pays, nom: e.target.value }) }} placeholder="pays" />
        <input className="price" type="text" value={pays.tarif} onChange={e => { setPays({ ...pays, tarif: e.target.value }) }} placeholder="price" />
        <button onClick={update}>Update !</button>
        <button onClick={del} className="delete">Delete</button>
    </div>)
}



AddPays.propTypes = {
    callBack: PropTypes.func.isRequired
}
function AddPays(props) {
    const [pays, setPays] = useState({ nom: '', tarif: '' })
    function add() { alfetch({ url: url + 'admin/pays', method: 'POST', body: { nom: pays.nom, tarif: Number(pays.tarif) }, callBack: () => { tartine(pays.nom + ' created'); setPays({ nom: '', tarif: '' }); props.callBack() } }) }
    return (<div id='addPays'>
        <input type="text" value={pays.nom} onChange={e => { setPays({ ...pays, nom: e.target.value }) }} placeholder="pays" />
        <input className="price" type="text" value={pays.tarif} onChange={e => { setPays({ ...pays, tarif: e.target.value }) }} placeholder="price" />
        <button onClick={add}>Add !</button>
    </div>)
}