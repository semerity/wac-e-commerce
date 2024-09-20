import "./component.css"
import React from "react"

export default function productCard(props) {
    if (props.skeleton) {
        return (
            <div className="cards skeleton" >
                <div className="blocks">
                    <div />
                    <div />
                </div>
            </div>)
    }
    let color =  props.color ? props.color + '80' : "#efef25"
    // console.log(props)
    return (
        <a href={'/product/' + props.id} className="cards" style={{ backgroundColor: color }}>
            <div className="blocks">
                <div style={{ backgroundColor: color }} />
                <div style={{ backgroundColor: color }} />
            </div>
            <img src={props.img} alt={'an photo of ' + props.name} />
            <h3>{props.name}</h3>
            <p className="marque">{props.marque}</p>
            <p className="desc">{props.desc}</p>
            {props.nouveau ? <p className="nouveau">New</p> : null}
            {props.reduction ?
                <div className="price">
                    <div className="raye">
                        <p className="nb">{props.price}</p>
                        <p className="currency">€</p>
                    </div>
                    <p className="nb">{props.price - props.reduction}</p>
                    <p className="currency">€</p>
                </div> :
                <div className="price">
                    <p className="nb">{props.price}</p>
                    <p className="currency">€</p>
                </div>
            }
        </a>
    )
}