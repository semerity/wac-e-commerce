import  React,  { useEffect, useState } from "react"
import { url } from "../path"
import "./component.css"
import PropTypes from 'prop-types';
import tartine from "../tartine";



Avis.propTypes = {
    content: PropTypes.string,
    setAverage: PropTypes.func.isRequired,
    set: PropTypes.func.isRequired,
    id_product: PropTypes.number.isRequired
}
export default function Avis(props) {
    const [avis, setAvis] = useState([])

    useEffect(() => {getAvis()}, [])
    function getAvis(){
        fetch(url + 'avis?id_product=' + props.id_product, {
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json'
            },
        }).then(e => e.json()).then(e => {
            setAvis(e)
        }).catch(e => console.log(e))
    }

    if (avis.length === 0) {
        return (<></>)
    }else{
        let notes = []
        avis.forEach(e=>{notes.push(e.note)})
        props.setAverage(notes.reduce((a, b) => a + b) / notes.length)
        // console.log(notes.reduce((a, b) => a + b) / notes.length)
    }


    function del(id_avis) {
        fetch(url + 'avis', {
            mode: 'cors',
            method: 'DELETE',
            body: JSON.stringify({
                id_avis: parseInt(id_avis),
                id_user: parseInt(localStorage.getItem('id'))
            })
        }).then(()=>{tartine('Feedback deleted');getAvis()}).catch(e => console.log(e))
    }

    return (
        <div id='avisDiv'>
            <h2>Feebacks</h2>
            {avis.map((e, key) => {
                if (e.id_user === localStorage.getItem('id')) { props.set(true) }
                return (
                    <div className="avis" key={key}>
                        <p className="note">{e.note}/5★</p>
                        <p className="content">{e.content}</p>
                        {localStorage.getItem('admin') === 'true' ?
                        <button onClick={()=>{del(e.id)}} className='delete'>X</button>
                        : null}
                    </div>
                )
            })}
        </div>
    )
}