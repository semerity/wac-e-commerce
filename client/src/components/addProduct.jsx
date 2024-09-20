import  React, { useEffect, useState } from "react";
import { url } from "../path"
import alfetch from "../alFetch";
import PropTypes from 'prop-types';
import tartine from "../tartine";

export default function AddProduct() {
    const [product, setProduct] = useState({ id_theme: 1, nom: '', description: '', nb_piece: '', age: '', dimension: '', prix: '', petite_desc: '', img: '', stock: '' })
    const [themes, setThemes] = useState([])

    useEffect(() => { alfetch({ url: url + 'theme', callBack: th => { setThemes(th) } }) }, [])

    function submit(e) {
        e.preventDefault()
        alfetch({
            url: url + 'produit',
            method: 'POST',
            body: {
                ...product,
                id_user: localStorage.getItem('id')
            },
            callBack: e => {
                tartine(product.nom + ' added')
                setProduct({ id_theme: 1, nom: '', description: '', nb_piece: '', age: '', dimension: '', prix: '', petite_desc: '', img: '', stock: '' })
            }
        })
    }
    function removePick(e, key) {
        let max = product.img.split(',').length
        let nouvelle = product.img.replace(e, '')
        if (max === 1) nouvelle = ''
        else if (max === key+1) nouvelle = nouvelle.slice(0, max -1)
        else if (key === 0) nouvelle = nouvelle.slice(1)
        else nouvelle = nouvelle.replace(',,', ',')

        setProduct({ ...product, img: nouvelle })
    }
    function addImg() {
        let nouvelle = prompt('Please put you new image url')
        if (product.img.length > 2) setProduct({ ...product, img: product.img + ',' + nouvelle })
        else setProduct({ ...product, img: nouvelle })
    }

    return (
        <>
            <form onSubmit={e => submit(e)}>
                {/* <h1>Add a product</h1> */}
                <div className="imgs">
                    {product.img.length > 2 ? product.img.split(',').map((e, key) => <img key={key} src={e} onClick={j => removePick(e, key)} alt='alt' />) : null}
                </div>
                <button onClick={addImg}>Add Image</button>
                <select value={product.id_theme} onChange={e => { setProduct({...product, id_theme: e.target.value}) }}>
                    {themes.map((th, key) => (<option key={key} value={th.id}>{th.theme}</option>))}
                </select>
                <input type="text" value={product.nom} onChange={e => setProduct({...product, nom: e.target.value})} placeholder='nom' required/>
                <textarea type="text" value={product.description} onChange={e => setProduct({...product, description: e.target.value})} placeholder='description' required />
                <div>
                    <input type="number" value={product.nb_piece} onChange={e => setProduct({...product, nb_piece: e.target.value})} placeholder='Nb_piece' required/>
                    <input type="number" value={product.age} onChange={e => setProduct({...product, age: e.target.value})} placeholder='age' required/>
                </div>
                <div className="trio">
                    <input type="text" value={product.dimension} onChange={e => setProduct({...product, dimension: e.target.value})} placeholder='dimension' required/>
                    <input type="number" value={product.prix} onChange={e => setProduct({...product, prix: e.target.value})} placeholder='prix' required/>
                    <input type="number" value={product.stock} onChange={e => setProduct({...product, stock: e.target.value})} placeholder='stock' required/>
                </div>
                <input type="text" value={product.petite_desc} onChange={e => setProduct({...product, petite_desc: e.target.value})} placeholder='petite_desc' required/>
                <input type="submit" value='add !' />
            </form>
        </>
    )
}