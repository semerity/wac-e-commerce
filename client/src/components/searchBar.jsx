import React, { useState } from "react"
import "./component.css"
import PropTypes from 'prop-types';

SearchBar.propTypes = {
    all: PropTypes.array.isRequired,
    set: PropTypes.func.isRequired,
    id: PropTypes.number
}
export default function SearchBar(props) {
    const [display, setDisplay] = useState(false)
    const [search, setSearch] = useState({ name: '', theme: '', age: '' })

    function change(obj) {
        setSearch(obj)  
        const filtered = props.all.filter((e) => {
            if (e.theme) {
                return e.nom.toLowerCase().includes(obj.name.toLowerCase())
                    && e.theme.toLowerCase().includes(obj.theme.toLowerCase())
                    && e.age >= obj.age
            } else return e.nom.toLowerCase().includes(obj.name.toLowerCase()) && e.age >= obj.age
        })
        props.set(filtered)
        return
    }

    return (
        <div id="searchBar">
            <button onClick={() => { setDisplay(!display) }}>Search</button>
            {display ? <>
                <input type="text" placeholder='name' value={search.name} onChange={e => change({ ...search, name: e.target.value })} />
                <input type="text" placeholder='theme' value={search.theme} onChange={e => change({ ...search, theme: e.target.value })} />
                <input type="number" placeholder='minimal age' value={search.age} onChange={e => change({ ...search, age: e.target.value })} />


                {
                    search.name !== '' || search.theme !== '' || search.age !== '' ?
                        <div id='suggestedDiv'>
                            {props.all.filter(e => {
                                if (e.theme) {
                                    return e.nom.toLowerCase().includes(search.name.toLowerCase())
                                        && e.theme.toLowerCase().includes(search.theme.toLowerCase())
                                        && e.age >= search.age
                                } else return e.nom.toLowerCase().includes(search.name.toLowerCase()) && e.age >= search.age
                            }).slice(0, 3).map((e, key) => (
                                <a href={'/product/' + e.id} id="basketRecap" key={key}>
                                    <div className="suggested" key={key}>
                                        <img src={e.img.split(',')[0]} alt={e.nom} />
                                        <div>
                                            <p>{e.nom}</p>
                                            <p>{e.Prix}€</p>
                                        </div>
                                    </div>
                                </a>
                            ))
                            }
                        </div>
                        : null
                }
            </> : null
            }
        </div >
    )
}