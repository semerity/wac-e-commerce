import React, { useEffect, useState } from "react";
import alfetch from "../alFetch";
import { url } from "../path"
import "./component.css"
import PropTypes from 'prop-types';
import tartine from "../tartine";

export default function Event() {
    const [events, setEvents] = useState([])
    useEffect(() => { getEvents() }, [])
    function getEvents() { alfetch({ url: url + 'evenement', callBack: e => setEvents(e) }) }
    return (<div id='events'>
        {events.map((e, key) => <OneEvent callBack={getEvents} event={e} key={key} />)}
        <AddEvent callBack={getEvents} />
    </div>)
}

OneEvent.propTypes = {
    callBack: PropTypes.func.isRequired,
    event: PropTypes.object.isRequired
}
function OneEvent(props) {
    function del() { alfetch({ url: url + 'evenement/' + props.event.id, method: 'DELETE', callBack: ()=>{tartine(props.event.nom + ' deleted');props.callBack()} }) }
    return (<div className="oneEvent">
        <p className="p nom">{props.event.nom}</p>
        <p className="p">{props.event.dateDebut}</p>
        <p className="p">{props.event.dateFin}</p>
        <button onClick={del} className="delete">Delete</button>
    </div>)
}

AddEvent.propTypes = {
    callBack: PropTypes.func.isRequired
}
function AddEvent(props) {
    const [event, setEvent] = useState({ nom: '', date_debut: null, date_fin: null })
    function add() { alfetch({ url: url + 'evenement', method: 'POST', body: event, callBack: () => {tartine(event.nom + ' added');setEvent({ nom: '', date_debut: null, date_fin: null }); props.callBack() } }) }
    return (<div id='addEvent'>
        <input type="text" value={event.nom} onChange={e => { setEvent({ ...event, nom: e.target.value }) }} placeholder="name" />
        <input className="start" type="date" value={event.tarif} onChange={e => { setEvent({ ...event, date_debut: e.target.value }) }} placeholder="start" />
        <input className="end" type="date" value={event.tarif} onChange={e => { setEvent({ ...event, date_fin: e.target.value }) }} placeholder="end" />
        <button onClick={add}>Add !</button>
    </div>)
}