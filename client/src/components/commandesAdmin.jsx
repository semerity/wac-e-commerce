import React, { useEffect, useState } from "react";
import alfetch from "../alFetch";
import { url } from "../path"
import "./component.css"
import PropTypes from 'prop-types';
import tartine from "../tartine";

export default function CommandesAdmin() {
    const [commandes, setCommandes] = useState([])
    const [methods, setMethods] = useState([])
    const [s, setS] = useState([])
    useEffect(() => { getMethod() }, [])
    function getCommand() { alfetch({ url: url + 'commandes/user/' + localStorage.getItem("id"), callBack: e => { setCommandes(e) } }) }
    function getMethod() { alfetch({ url: url + 'delMethod', callBack: e => { setMethods(e); getCommand() } }) }
    return (<div id='commandesAdmin'>
        <Search callBack={setS} commandes={commandes} methods={methods} />
        {s.length !== 0 ?
            s.map((e, key) => <OneCommand callBack={getMethod} commande={e} methods={methods} key={key + Date.now()} />)
            :
            commandes.map((e, key) => <OneCommand callBack={getMethod} commande={e} methods={methods} key={key} />)}
    </div>)
}
Search.propTypes = {
    callBack: PropTypes.func.isRequired,
    commandes: PropTypes.array.isRequired,
    methods: PropTypes.array.isRequired
}
function Search(props) {
    const [obj, setObj] = useState({ id: '', adresse: '', prix: '', method: '', etat: '' })
    function change(obj) {
        setObj(obj)
        props.callBack(props.commandes.filter(e => e.id == obj.id))
    }
    return (<div className="onePays">
        <input type="text" value={obj.id} onChange={e => change({ ...obj, id: e.target.value })} id='searchP' placeholder="id" />
    </div>)
}
OneCommand.propTypes = {
    callBack: PropTypes.func.isRequired,
    commande: PropTypes.object.isRequired,
    methods: PropTypes.array.isRequired
}
function OneCommand(props) {
    const [commande, setCommande] = useState(props.commande)
    useEffect(() => { setCommande(props.commande) }, [])
    function update() { alfetch({ url: url + 'commandes/' + props.commande.id, method: 'PATCH', body: commande, callBack: () => { tartine('commande updated');props.callBack() } }) }
    function del() { alfetch({ url: url + 'commandes/' + props.commande.id, method: 'DELETE', callBack: ()=>{tartine('commande deleted'); props.callBack()} }) }
    return (<div className="onePays">
        <p>{commande.id}</p>
        <input type="text" value={commande.adresse} onChange={e => { setCommande({ ...commande, adresse: e.target.value }) }} placeholder="adresse" className="adresse" />
        <input type="text" value={commande.prix} onChange={e => { setCommande({ ...commande, prix: e.target.value }) }} placeholder="prix" className="prix" />
        <select value={commande.method} onChange={e => setCommande({ ...commande, method: e.target.value })}>
            {props.methods.map((e, key) => <option key={key} value={e.id}>{e.name}</option>)}
        </select>
        <select value={commande.etat} onChange={e => setCommande({ ...commande, etat: e.target.value })}>
            <option value="preparation">preparation</option>
            <option value="transfert">transfert</option>
            <option value="livraison">livraison</option>
            <option value="livré">livré</option>
        </select>
        <button onClick={update}>Update !</button>
        <button onClick={del} className="delete">Delete</button>
    </div>)
}