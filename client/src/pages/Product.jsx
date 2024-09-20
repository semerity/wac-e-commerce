import React, { useEffect, useState } from "react";
import { url } from "../path"
import { AddAvis } from "../components/addAvis";
import Avis from "../components/avis"
import alfetch from "../alFetch";
import Carroussel from "../components/carroussel";
import PropTypes from 'prop-types';
import tartine from "../tartine";

export default function Product() {
    const [product, setProduct] = useState({})
    const [themes, setThemes] = useState([])
    const [avisPosted, setAvisPosted] = useState(false)
    const [averageNote, setAverageNote] = useState(false)
    const [nb, setNb] = useState(1)
    useEffect(() => {
        alfetch({
            url: url + 'produit/' + window.location.href.split('/')[4],
            callBack: e => {
                alfetch({
                    url: url + 'theme', callBack: themes => {
                        themes.forEach(th => {
                            if (th.id === e.id_theme) {
                                e.theme = th.theme
                                document.documentElement.style.setProperty('--background', th.color);
                            }
                        })
                        if (e.reduction === null) e.reduction = 0
                        setProduct(e)
                        setThemes(themes)
                        console.log(e)
                    }
                })
            }
        })
    }, [])


    function update() {
        alfetch({
            url: url + 'produit',
            method: 'PATCH',
            body: {
                ...product,
                prix: product.Prix,
                id_theme: parseInt(product.id_theme),
                id_product: parseInt(window.location.href.split('/')[4]),
                id_user: parseInt(localStorage.getItem('id'))
            },
            callBack: () => {
                tartine('Product updated')
            }
        })
    }
    function removePick(e, key) {
        let max = product.img.split(',').length
        let nouvelle = product.img.replace(e, '')
        if (max === 1) { nouvelle = '' }
        else if (max === key + 1) nouvelle = nouvelle.slice(0, max - 1)
        else if (key === 0) { nouvelle = nouvelle.slice(1) }
        else { nouvelle = nouvelle.replace(',,', ',') }
        setProduct({ ...product, img: nouvelle })
    }
    function addImg() {
        let nouvelle = prompt('Please put you new image url')
        if (nouvelle === null) return
        if (product.img.length === 0) { setProduct({ ...product, img: nouvelle }) }
        else { setProduct({ ...product, img: product.img + ',' + nouvelle }) }
    }
    function del() {
        alfetch({
            url: url + 'produit',
            method: 'DELETE',
            body: {
                id_product: parseInt(window.location.href.split('/')[4]),
                id_user: parseInt(localStorage.getItem('id'))
            },
            callBack: () => { goHome() }
        })
    }

    function searchTheme(theme, age = '') {
        localStorage.setItem("searchTheme", theme);
        localStorage.setItem("searchAge", age);
        goHome()
    }
    function goHome() {
        let a = document.createElement('a')
        a.href = '/'
        a.click()
    }
    function addToBasket() {
        let array = []
        if (localStorage.getItem('basketArr') !== null) { array = JSON.parse(localStorage.getItem('basketArr')) }
        if (localStorage.getItem('user') && localStorage.getItem('user') !== 'false') {
            for (let i = 0; i < nb; i++) {
                alfetch({
                    url: url + 'panier', method: 'POST',
                    body: {
                        id_product: parseInt(window.location.href.split('/')[4]),
                        id_user: parseInt(localStorage.getItem('id'))
                    },
                    callBack: (id) => {
                        array.push({ name: product.nom, img: product.img.split(',')[0], price: product.Prix, id: id, nb: 1, reduction: product.reduction })
                        localStorage.setItem('basketArr', JSON.stringify(array))
                        document.getElementById('nbInBasket').innerHTML = Number(document.getElementById('nbInBasket').innerHTML) + 1
                    }
                })
            }
        } else {
            array.push({ name: product.nom, img: product.img.split(',')[0], price: product.Prix, id: null, nb: nb, reduction: product.reduction })
            localStorage.setItem('basketArr', JSON.stringify(array))
            document.getElementById('nbInBasket').innerHTML = Number(document.getElementById('nbInBasket').innerHTML) + 1
        }
    }
    if (product.stock === '') product.stock = 0

    if (Object.keys(product).length === 0) { return (<section id='product' className="skeleton" />) }

    if (localStorage.getItem('admin') === 'false') {
        return (
            <section id='product'>
                <div id="breadCrumbs">
                    <p className='home' onClick={() => { goHome() }}>Home</p>
                    <p>&gt;</p>
                    <p className='theme' onClick={() => { searchTheme(product.theme) }}>{product.theme}</p>
                    <p>&gt;</p>
                    <p className='age' onClick={() => { searchTheme(product.theme, product.age) }}>{product.age + ' ans'}</p>
                    <p>&gt;</p>
                    <p className='age'>{product.nom}</p>
                </div>
                <Carroussel imgs={product.img.split(',')} />
                <h2 className='nom'>{product.nom}</h2>
                {averageNote ? <div id="note">{averageNote + '★'}</div> : null}
                <div className="trio just-left">
                    {product.nouveau ? <p className="nouveau">NEW</p> : null}
                    {product.stock === 0 ? <p className="stockStatus delete">OUT OF STOCK</p> : <p className="stockStatus green">IN STOCK</p>}
                </div>
                <Description desc={product.description} />
                <div id='boxes'>
                    <div className="box">
                        <label>Price</label>
                        {product.reduction ? <div className="reduc">
                            <div className="raye">
                                <p className="nb">{product.Prix}</p>
                                <p className="currency">€</p>
                            </div>
                            <p className='prix'>{(product.Prix - product.reduction) + '€'}</p>
                        </div> : <p className='prix'>{product.Prix + '€'}</p>
                        }
                    </div>
                    {product.dimension ?
                        <div className="box">
                            <label>Dimensions</label>
                            <p className='dimension'>{product.dimension}</p>
                        </div>
                        : null}
                    <div className="box">
                        <label>Pieces</label>
                        <p className='nb_piece'>{product.nb_piece}</p>
                    </div>
                    <div className="box">
                        <label>Stock</label>
                        <p className='stock'>{product.stock}</p>
                    </div>
                </div>

                <div id="cartButton">
                    <button className="addToCart" onClick={addToBasket}>Add to cart</button>
                    <select type='number' value={nb} onChange={e => { setNb(e.target.value) }}>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>

                {avisPosted || localStorage.getItem("id") === 'false' ? null : <AddAvis id_product={parseInt(window.location.href.split('/')[4])} />}
                <Avis set={e => { setAvisPosted(e) }} id_product={parseInt(window.location.href.split('/')[4])} setAverage={e => { setAverageNote(e) }} />
            </section>
        )
    }

    return (
        <section id='product'>
            <details>
                <summary>Images</summary>
                <div className="imgs">
                    {product.img.length > 2 ? product.img.split(',').map((e, key) => <img className="canDel" key={key} src={e} onClick={j => removePick(e, key)} alt='alt' />) : null}
                </div>
            </details>
            <button onClick={addImg}>Add Image</button>
            <label htmlFor="selectTheme">Theme</label>
            <select value={product.id_theme} onChange={e => { setProduct({ ...product, id_theme: e.target.value }) }} id="selectTheme">
                {themes ? themes.map((th, key) => (<option key={key} value={th.id}>{th.theme}</option>)) : null}
            </select>
            <label htmlFor="nom">Name</label>
            <input id="nom" className='nom' value={product.nom} onChange={e => setProduct({ ...product, nom: e.target.value })} />
            <label htmlFor="age">Age</label>
            <div>
                <input id='age' className='age' value={product.age} onChange={e => setProduct({ ...product, age: e.target.value })} />
                <label htmlFor="age">ans</label>
            </div>
            <label htmlFor="prix">Price</label>
            <div>
                <input id='prix' className='prix' value={product.Prix} onChange={e => setProduct({ ...product, Prix: e.target.value })} />
                <label htmlFor="prix">€</label>
            </div>
            <label htmlFor="pop">Popularity</label>
            <div>
                <input id='pop' value={product.popularite} onChange={e => setProduct({ ...product, popularite: e.target.value })} />
                <label htmlFor="pop">pop</label>
            </div>
            <label htmlFor="reduction">Reduction</label>
            <div>
                <input id='reduction' value={product.reduction} onChange={e => setProduct({ ...product, reduction: e.target.value })} />
                <label htmlFor="reduction">{'€ reduc (' + (Math.round((product.reduction / product.Prix) * 100)) + '%)'}</label>
            </div>
            <label htmlFor="nouveau">New</label>
            <input id='nouveau' checked={product.nouveau} onChange={e => setProduct({ ...product, nouveau: !product.nouveau })} type='checkbox' />
            <label htmlFor="petitedesc">Little description</label>
            <input className='petitedesc' value={product.petite_desc} onChange={e => setProduct({ ...product, petite_desc: e.target.value })} />
            <label htmlFor="description">Description</label>
            <textarea id="description" className='description' onChange={e => setProduct({ ...product, description: e.target.value })} value={product.description} />
            <label htmlFor="description">Dimension</label>
            <input id="description" className='dimension' value={product.dimension} onChange={e => setProduct({ ...product, dimension: e.target.value })} placeholder="dimensions" />
            <label htmlFor="nbpiece">Pieces</label>
            <input id='nbpiece' className='nb_piece' value={product.nb_piece} onChange={e => setProduct({ ...product, nb_piece: e.target.value })} />
            <label htmlFor="stock">Stock</label>
            <input className={product.stock === 0 ? 'delete' : 'green'} id='stock' value={product.stock} onChange={e => setProduct({ ...product, stock: e.target.value })} />
            <div className="duo">
                <button onClick={update}>Update</button>
                <button className='delete' onClick={del}>Delete</button>
            </div>
            <div id="cart">
                <button className="addToCart" onClick={addToBasket}>Add to cart</button>
                <select type='number' value={nb} onChange={e => { setNb(e.target.value) }}>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </div>
            {avisPosted ? null : <AddAvis id_product={parseInt(window.location.href.split('/')[4])} />}
            <Avis set={e => { setAvisPosted(e) }} id_product={parseInt(window.location.href.split('/')[4])} setAverage={e => { setAverageNote(e) }} />
        </section>
    )
}
Description.propTypes = { desc: PropTypes.string.isRequired }
function Description(props) {
    if (props.desc.includes("\n")) return <div id="desc">{props.desc.split("\n").map((e, key) => <p key={'descP' + key+ Date.now()} className='descP'>{e}</p>)}</div>
    else if (props.desc.includes(" – ")) return <div id="desc">{props.desc.split(" – ").map((e, key) => <p key={'descP' + key+ Date.now()} className='descP'>{e}</p>)}</div>
    else return <p className='descP'>{props.desc}</p>
}