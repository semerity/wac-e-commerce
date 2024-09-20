import React,  { useState } from "react"
import { url } from "../path"
import "./component.css"
import PropTypes from 'prop-types';
import tartine from "../tartine";


AddAvis.propTypes = {
    id_product: PropTypes.number.isRequired
}
export function AddAvis(props) {
    const [note, setNote] = useState(0)
    const [content, setContent] = useState('')

    function submit() {
        fetch(url + 'avis', {
            mode: 'cors',
            // headers: {
            //     'Content-Type': 'text/plain'
            // },
            method: 'POST',
            body: JSON.stringify({
                id_product: props.id_product,
                id_user: localStorage.getItem('id'),
                note: note,
                content: content,
            })
        }).then(e=>e.json()).then(e=>{
            let a = document.createElement('a')
            a.href = window.location.href
            a.click()
        }

        ).catch(e=>{console.log(e)})
    }

    return (
        <div id="addAv">
            <h2>Add a feedback !</h2>
            <textarea value={content} onChange={e => { setContent(e.target.value) }} placeholder="Your anonymous feedback"></textarea>
            <div className="duo">
                <div className="rate">
                    <input type="radio" id="star5" name="rate" value="5" onClick={() => { setNote(5) }} />
                    <label htmlFor="star5" title="text">5 stars</label>
                    <input type="radio" id="star4" name="rate" value="4" onClick={() => { setNote(4) }} />
                    <label htmlFor="star4" title="text">4 stars</label>
                    <input type="radio" id="star3" name="rate" value="3" onClick={() => { setNote(3) }} />
                    <label htmlFor="star3" title="text">3 stars</label>
                    <input type="radio" id="star2" name="rate" value="2" onClick={() => { setNote(2) }} />
                    <label htmlFor="star2" title="text">2 stars</label>
                    <input type="radio" id="star1" name="rate" value="1" onClick={() => { setNote(1) }} />
                    <label htmlFor="star1" title="text">1 star</label>
                </div>
                <button onClick={submit}>Send</button>
            </div>
        </div>
    )
}