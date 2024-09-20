import React, {useState} from 'react'
import "./component.css"
import PropTypes from 'prop-types';




Carroussel.propTypes = {
    imgs: PropTypes.array
}
export default function Carroussel(props) {
    const [pos, setPos] = useState(0)
    if(props.imgs === undefined)return<></>
    if(pos === props.imgs.length)setPos(0)
    return (
        <div className="CarrousselComponent" onClick={e=>setPos(pos+1)}>
            {props.imgs.length > 0 ? props.imgs.map((e, key) => <img key={key} src={e} alt='alt'  className={key === pos ? 'visible' : null}/>) : null}
        </div>
    )
}