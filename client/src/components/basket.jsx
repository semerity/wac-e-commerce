import React, { useEffect, useState } from "react"
import PropTypes from 'prop-types';
import { url } from "../path"
import alfetch from "../alFetch"



export default function Basket() {
    const [display, setDisplay] = useState(false)
    const [content, setContent] = useState([])

    useEffect(() => {
        let array = []
        if (localStorage.getItem('basketArr') !== null && JSON.parse(localStorage.getItem('basketArr')).length > 0) { array = JSON.parse(localStorage.getItem('basketArr')) }
        else if (localStorage.getItem('user') && localStorage.getItem('user') !== 'false') {
            alfetch({
                url: url + 'panier/' + localStorage.getItem('id'), callBack: e => {
                    // console.log(e)
                    e.forEach(pro => {
                        array.push({ name: pro.nom, img: pro.img.split(',')[0], price: pro.Prix, nb: 1 })
                        // console.log(array)
                        localStorage.setItem('basketArr', JSON.stringify(array))
                        document.getElementById('nbInBasket').innerHTML = Number(document.getElementById('nbInBasket').innerHTML) + 1
                    })
                    setContent(setNb(array))
                }
            })
        }

        setContent(setNb(array))
    }, [display])




    return (<div id='basket'>
        <p onClick={() => { setDisplay(!display) }}>Cart</p>
        <p id="nbInBasket">{content.length > 0 ? content.length : 0}</p>
        {display && <BasketScreen content={content} set={e => setContent(e)} />}
    </div>)
}
function setNb(array) {
    let newArr = []

    array.forEach(e => {
        let find = false
        newArr.forEach(n => {
            if (e.name === n.name) {
                n.nb++
                find = true
            }
        })
        if (!find) { newArr.push(e) }
    })
    // localStorage.setItem('basketArr', JSON.stringify(newArr))
    document.getElementById('nbInBasket').innerHTML = newArr.length
    return newArr
}

BasketScreen.propTypes = {
    content: PropTypes.array.isRequired,
    set: PropTypes.func.isRequired
}
function BasketScreen(props) {
    const [totalPrice, setTotalPrice] = useState(0)

    function del(key, name) {
        if (localStorage.getItem('user') && localStorage.getItem('user') !== 'false') {
            JSON.parse(localStorage.getItem('basketArr')).forEach(e => {
                if (e.name === name) {
                    console.log(e)
                    alfetch({
                        url: url + 'panier/', method: 'DELETE',
                        body: { id: e.id },
                        callBack: () => {
                            props.content.splice(key, 1)
                            let base = JSON.parse(localStorage.getItem('basketArr'))
                            base = base.filter(e => e.name !== name)

                            localStorage.setItem('basketArr', JSON.stringify(base))
                            props.set(setNb(JSON.parse(localStorage.getItem('basketArr'))))
                            let total = 0
                            props.content.forEach(e => {
                                if (e.reduction) total += (e.price - e.reduction) * e.nb
                                else total += e.price * e.nb
                            })
                            setTotalPrice(total)
                        }
                    })
                }
            })
        } else {
            props.content.splice(key, 1)
            localStorage.setItem('basketArr', JSON.stringify(props.content))
            props.set(JSON.parse(localStorage.getItem('basketArr')))
            let total = 0
            props.content.forEach(e => {
                if (e.reduction) total += (e.price - e.reduction) * e.nb
                else total += e.price * e.nb
            })
            setTotalPrice(total)
        }
    }
    useEffect(() => {
        let total = 0
        props.content.forEach(e => {
            if (e.reduction) total += (e.price - e.reduction) * e.nb
            else total += e.price * e.nb
        })
        setTotalPrice(total)
    }, [])

    if (props.content.length < 1) {
        return (
            <div id='basketContent'>
                <p>Cart empty</p>
                <div id="basketRecap">
                    <p>Total: {totalPrice}€</p>
                    <a href="cart">See Cart</a>
                </div>
            </div>
        )
    }
    return (
        <>
            <div id='basketContent'>
                {props.content.map((e, key) => (
                    <div className="cartContent" key={key}>
                        <img src={e.img} alt={"an image of the " + e.name} />
                        <p className="name">{e.name}</p>
                        <p className="nb">x{e.nb}</p>
                        {e.reduction ?
                            <div className="reduc">
                                <p className="old">{e.price * e.nb + "€"}</p>
                                <p className="price">{(e.price - e.reduction) * e.nb + "€"}</p>
                            </div>
                            : <p className="price">{e.price * e.nb + "€"}</p>}
                        <img className="trash" onClick={() => { del(key, e.name) }} src="https://www.freeiconspng.com/uploads/garbage-bin-png-1.png" alt=" trashcan bin" />
                    </div>
                ))}
                <div id="basketRecap">
                    <p>Total: {totalPrice}€</p>
                    <a href="/cart">See Cart</a>
                </div>
            </div>
        </>
    )
}