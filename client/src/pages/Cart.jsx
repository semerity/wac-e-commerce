
import React, { useState, useEffect } from "react"
import { url } from "../path"
import alfetch from "../alFetch"
import { Link } from "react-router-dom";

export default function Cart() {
    const [content, setContent] = useState([])
    const [totalPrice, setTotalPrice] = useState(0)
    const [step, setStep] = useState(0)
    const [card, setCard] = useState({ nb: '', remember: false, name: '', surname: '', secret: '', month: '', year: '', remembered: false })
    const [cards, setCards] = useState([])
    const [mention, setMention] = useState(false)
    const [pays, setPays] = useState([])
    const [adresse, setAdresse] = useState({ adresse: '', ville: '', pays: 'France', postal: '', remember: false, remembered: false })
    const [adresses, setAdresses] = useState([])
    const [frais, setFrais] = useState(0)
    const [methods, setMethods] = useState([])
    const [method, setMethod] = useState({ mult: 1 })
    const [event, setEvent] = useState(false)
    const [embal, setEmbal] = useState(false)


    useEffect(() => {
        let array = []
        if (localStorage.getItem('basketArr') !== null && JSON.parse(localStorage.getItem('basketArr')).length > 0) { array = JSON.parse(localStorage.getItem('basketArr')) }
        else if (localStorage.getItem('user') && localStorage.getItem('user') !== 'false') {
            array = JSON.parse(localStorage.getItem('basketArr'))
        }

        setContent(setNb(array))
        getCards()
        getPays()
        getAdresses()
        getMethods()
        getEvents()
    }, [])
    useEffect(() => {
        let total = 0
        content.forEach(e => {
            if (e.reduction) total += (e.price - e.reduction) * e.nb
            else total += e.price * e.nb
        })
        setTotalPrice(total)
    }, [content])
    function getFrais(pays = null) {
        let names = []
        JSON.parse(localStorage.getItem('basketArr')).forEach(e => { for (let i = 0; i < e.nb; i++) { names.push(e.name) } })
        let body = { nom: pays ? pays : adresse.pays, products: names }
        alfetch({ url: url + 'frais/estimation', method: 'POST', body: body, callBack: e => { setFrais(e) } })
    }
    function getPays() { alfetch({ url: url + 'pays', callBack: e => { setPays(e); getFrais(e[0].nom) } }) }
    function getCards() { alfetch({ url: url + 'paiement/' + localStorage.getItem("id"), callBack: (e) => { setCards(e) } }) }
    function getAdresses() { alfetch({ url: url + 'adresse/' + localStorage.getItem("id"), callBack: (e) => { setAdresses(e) } }) }
    function getMethods() { alfetch({ url: url + 'delMethod', callBack: e => { setMethods(e); setMethod(e[0]) } }) }
    function getEvents() { alfetch({ url: url + 'evenement', callBack: e => { checkEvent(e) } }) }
    function checkEvent(events) {
        console.log(events)
        events.forEach(e => {
            const start = new Date(e.dateDebut).getTime();
            const end = new Date(e.dateFin).getTime();
            const today = new Date().getTime();
            if (today > start && today < end) {
                setEvent(e.nom)
            }
        })

    }
    function del(key, name) {
        if (localStorage.getItem('user') && localStorage.getItem('user') !== 'false') {
            JSON.parse(localStorage.getItem('basketArr')).forEach(e => {
                if (e.name === name) {
                    console.log(e)
                    alfetch({
                        url: url + 'panier/', method: 'DELETE',
                        body: { id: e.id },
                        callBack: () => {
                            content.splice(key, 1)
                            let base = JSON.parse(localStorage.getItem('basketArr'))
                            base = base.filter(e => e.name !== name)

                            localStorage.setItem('basketArr', JSON.stringify(base))
                            setContent(setNb(JSON.parse(localStorage.getItem('basketArr'))))
                            let total = 0
                            content.forEach(e => {
                                total += e.price * e.nb
                            })
                            setTotalPrice(total)
                        }
                    })
                }
            })
        } else {
            content.splice(key, 1)
            localStorage.setItem('basketArr', JSON.stringify(content))
            setContent(JSON.parse(localStorage.getItem('basketArr')))
            let total = 0
            content.forEach(e => {
                total += e.price
            })
            setTotalPrice(total)
        }
    }
    function changeCardNb() {
        let arg = document.getElementById('card0').value + document.getElementById('card1').value + document.getElementById('card2').value + document.getElementById('card3').value
        if (/^\d+$/.test(arg) || arg === '') { } else { return }
        if (arg.length > 16) return
        let newString = ''
        arg.split('').forEach((digit, key) => {
            newString += digit
            if ((key + 1) % 4 === 0) newString += '-'
            document.getElementById('card' + Math.floor(key / 4)).focus()
        })
        newString = newString.replace(/^-+|-+$/g, "")
        setCard({ ...card, nb: newString, remembered: false })
    }
    function changeMonth(arg) {
        arg = Number(arg)
        if (arg < 10 && arg > 0) arg = '0' + arg
        else if (arg > 12) arg = 12
        if (arg === '00' || arg === 0) arg = ''
        setCard({ ...card, month: arg })
    }
    function changeYear(arg) {
        arg = Number(arg)
        if (arg < 10 && arg >= 0) arg = '0' + arg
        else if (arg > 99) arg = 99
        setCard({ ...card, year: arg })
    }
    function pay() {
        if (card.remember && Number(localStorage.getItem("id"))) {
            card.remember = false
            alfetch({
                url: url + 'paiement',
                method: 'POST',
                body: {
                    id_user: Number(localStorage.getItem("id")),
                    name: card.name + ' ' + card.surname,
                    code: card.nb,
                    date: card.month + '/' + card.year
                },
                callBack: () => { getCards() }
            })
        }
        if (adresse.remember && Number(localStorage.getItem("id"))) {
            adresse.remember = false
            alfetch({
                url: url + 'adresse',
                method: 'POST',
                body: {
                    id_user: Number(localStorage.getItem("id")),
                    pays: adresse.pays,
                    ville: adresse.ville,
                    code_postal: adresse.postal,
                    nom_de_rue: adresse.adresse
                },
                callBack: () => { getAdresses() }
            })
        }

        alfetch({
            url: url + 'commandes/new',
            method: 'POST',
            body: {
                articles: localStorage.getItem('basketArr'),
                pays: adresse.pays,
                method: method.id,
                id_user: Number(localStorage.getItem("id")),
                adresse: adresse.adresse + ' ' + adresse.postal + ' ' + adresse.ville,
                embal: embal
            },
            callBack: e => {
                alert('your command is now in preparation')
                alfetch({ url: url + 'panier/' + localStorage.getItem("id"), method: 'DELETE' })
                localStorage.removeItem('basketArr')
                window.location.href = "/";
            }
        })
    }
    function selectCard(e) {
        setCard({ ...card, remember: false, nb: e.code, name: e.name.split(' ')[0], surname: e.name.split(' ')[1], month: e.date.split('/')[0], year: e.date.split('/')[1], remembered: true, secret: '' })
    }
    function delCard(id) {
        alfetch({
            url: url + 'paiement/' + id,
            method: 'DELETE',
            callBack: () => { getCards() }
        })
    }
    function selectAdresse(e) { setAdresse({ ...card, remember: false, adresse: e.nomDeRue, postal: e.codePostal, pays: e.pays, ville: e.ville, remembered: true }) }
    function delAdresse(id) {
        alfetch({
            url: url + 'adresse/' + id,
            method: 'DELETE',
            callBack: () => { getAdresses() }
        })
    }

    if (!mention && (step === 2) && (card.nb.length < 16 || card.name === '' || card.surname === '' || card.secret === '' || card.month === '' || card.year === '')) {
        setStep(1)
        setMention('Please fill all fields')
    } else if (step === 0 && mention) {
        setMention(false)
    } else if (mention && (step === 1) && (card.nb.length < 16 || card.name === '' || card.surname === '' || card.secret === '' || card.month === '' || card.year === '')) {
        // setMention(false)
    } else if (mention) {
        setMention(false)
    }

    return (
        <section id='cart'>
            {mention ? <p id='mention'>{mention}</p> : null}
            {step === 0 ? <div id='basketContent'>
                {content.length > 0 ? <h2>Your Cart:</h2> : <h2>Cart empty</h2>}
                {content.map((e, key) => (
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
                {content.length > 0 ? <h3>Total: {totalPrice}€</h3> : null}
            </div> : null}


            {step === 1 ? <div id='Payment_info'>
                {cards.length !== 0 ? <div id='yourCards'>
                    <h2>Your cards:</h2>
                    {cards.map((e, key) => (<div className='oneCard' key={key}>
                        <div className="info" onClick={() => { selectCard(e) }}>
                            <p className="code">{e.code}</p>
                            <div>
                                <p>{e.name}</p>
                                <p>{e.date}</p>
                            </div>
                        </div>
                        <img onClick={() => { delCard(e.id) }} src="https://www.freeiconspng.com/uploads/garbage-bin-png-1.png" alt=" trashcan bin" />
                    </div>))}

                </div> : null}
                <h2>Your card info</h2>
                <div id="CardNumber">
                    <input id='card0' type="text" value={card.nb.split('-')[0]} onChange={changeCardNb} placeholder="_ _ _ _" minLength='4' />
                    <input id='card1' type="text" value={card.nb.split('-')[1]} onChange={changeCardNb} placeholder="_ _ _ _" minLength='4' />
                    <input id='card2' type="text" value={card.nb.split('-')[2]} onChange={changeCardNb} placeholder="_ _ _ _" minLength='4' />
                    <input id='card3' type="text" value={card.nb.split('-')[3]} onChange={changeCardNb} placeholder="_ _ _ _" minLength='4' />
                </div>
                <div id='cardName'>
                    <input type="text" placeholder="name" value={card.name} onChange={e => { setCard({ ...card, name: e.target.value }) }} />
                    <input type="text" placeholder="surname" value={card.surname} onChange={e => { setCard({ ...card, surname: e.target.value }) }} />
                </div>
                <div id="datesecret">
                    <span className="expiration">
                        <input id='month' type="text" placeholder="MM" size="2" value={card.month} onChange={e => { changeMonth(e.target.value) }} />
                        <span>/</span>
                        <input id='year' type="text" placeholder="YY" size="2" value={card.year} onChange={e => { changeYear(e.target.value) }} />
                    </span>
                    <input id='secretInfo' type="text" maxLength={3} value={card.secret} onChange={e => setCard({ ...card, secret: e.target.value })} placeholder="_ _ _" />
                </div>
                {card.remembered ? null :
                    <div id="rememberCard">
                        <p>Remember this Card</p>
                        <input type="checkbox" value={card.remember} onChange={() => { setCard({ ...card, remember: !card.remember }) }} />
                    </div>
                }
            </div> : null}

            {step === 2 ? <div id='AdresseDiv'>
                {console.log(adresses)}
                {adresses.length !== 0 && adresses.map ? <div id='yourCards'>
                    <h2>Your adresses:</h2>
                    {adresses.map((e, key) => (<div className='oneCard' key={key}>
                        <div className="info" onClick={() => { selectAdresse(e) }}>
                            <p className="code">{e.pays}</p>
                            <div>
                                <p>{e.nomDeRue}</p>
                                <p>{e.ville}</p>
                            </div>
                        </div>
                        <img onClick={() => { delAdresse(e.id) }} src="https://www.freeiconspng.com/uploads/garbage-bin-png-1.png" alt=" trashcan bin" />
                    </div>))}

                </div> : null}
                <h2>Delivery Location</h2>
                <main>
                    <input type="text" placeholder="adress" value={adresse.adresse} onChange={e => { setAdresse({ ...adresse, adresse: e.target.value, remembered: false }) }} />
                    <input type="text" placeholder="city" value={adresse.ville} onChange={e => { setAdresse({ ...adresse, ville: e.target.value, remembered: false }) }} />
                    <input type="number" placeholder="postal code" value={adresse.postal} onChange={e => { setAdresse({ ...adresse, postal: e.target.value, remembered: false }) }} />
                    <select value={adresse.pays} onChange={e => { setAdresse({ ...adresse, pays: e.target.value, remembered: false }); getFrais(e.target.value) }}>
                        {pays.map((p, key) => <option key={key} value={p.nom}>{p.nom}</option>)}
                    </select>
                </main>
                {adresse.remembered ? null :
                    <div id="rememberAdresse">
                        <p>Remember this Adress</p>
                        <input type="checkbox" value={adresse.remember} onChange={() => { setAdresse({ ...adresse, remember: !adresse.remember }) }} />
                    </div>
                }
            </div> : null}


            {step === 3 ? <div id='RecapPay'>
                <div id="recapPrice">
                    <p id="basicPrice">Product price: <strong>{totalPrice}€</strong></p>
                    <p id="fraisPrice">Shipping cost: <strong>{method.mult * frais}€</strong></p>
                    <p id="totalPrice">Total: <strong>{totalPrice + method.mult * frais}€</strong></p>
                    <div>
                        <p>Method</p>
                        <select id="selectMethod" onChange={e => setMethod(JSON.parse(e.target.value))}>
                            {methods.map((e, key) => <option key={key} value={JSON.stringify(e)}>{e.name}</option>)}
                        </select>
                    </div>
                </div>
                <p id="cardFinishinf">Using your card finishing by <u>{card.nb.split('-')[3]}</u> </p>
                {event ? <div id='eventEmbalDiv'>
                    <p>It is </p><p>{event}</p><p> Do you want a gift wrapping ?</p>
                    <input type="checkbox" checked={embal} onChange={() => { setEmbal(!embal) }} />
                </div> : totalPrice > 1000 ? <div id='eventEmbalDiv'>
                    <p> Do you want a a gift wrapping ?</p>
                    <input type="checkbox" checked={embal} onChange={() => { setEmbal(!embal) }} />
                </div> : null}

                {localStorage.getItem('user') && localStorage.getItem('user') !== 'false' ? null : <div id='youShouldLogin'>
                    <p>If you want to make the website remember your cards, you should </p><Link to="/login">Login</Link>
                </div>}
                <button id='payButton' onClick={pay}>Pay</button>
            </div> : null
            }


            <div id="buttons">
                {step > 0 ? <button onClick={() => { setStep(step - 1) }}>Previous Step</button> : null}
                {content.length > 0 && step < 3 ? <button onClick={() => { setStep(step + 1) }}>Next step</button> : null}
            </div>
        </section >
    )

}

function setNb(array) {
    if (!array) return []
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