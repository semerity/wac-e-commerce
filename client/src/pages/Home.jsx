import Card from '../components/productCard'
import React, { useState, useEffect } from 'react';
import { url } from "../path"
import alfetch from "../alFetch"
import SearchBar from '../components/searchBar';
import PropTypes from 'prop-types';

export default function Home() {
    const [all, setall] = useState([])
    const [result, setResult] = useState([])
    const [maxDisplayed, setMaxDisplayed] = useState(9)

    useEffect(() => {
        scrollGestion()
        alfetch({
            url: url, callBack: products => {
                alfetch({
                    url: url + 'theme', callBack: themes => {
                        products.forEach(product => {
                            themes.forEach(j => {
                                if (j.id === product.id_theme) { product.theme = j.theme; product.color = j.color }
                            })
                        });
                        setall(products)
                    }
                })
            }
        })
    }, [])
    if (localStorage.getItem('searchTheme') !== null && all.length > 0) {
        setResult(all.filter(e =>
            e.theme.toLowerCase().includes(localStorage.getItem('searchTheme').toLowerCase()) && e.age >= localStorage.getItem('searchAge')))
        localStorage.removeItem('searchTheme')
        localStorage.removeItem('searchAge')
    }

    function scrollGestion() {
        let max = 9;
        let expanding = false;
        window.addEventListener("scroll", () => {
            const scrolledTo = window.scrollY + window.innerHeight
            if (document.body.scrollHeight - 300 <= scrolledTo && !expanding) {
                max += 9
                expanding = true
                setMaxDisplayed(max)
                setTimeout(() => { expanding = false }, 1000)
            }
        });
    }

    if (all.length === 0) {
        let skeleton = []
        for (let i = 0; i < 9; i++) {
            skeleton.push(i)
        }

        return (<section id='home'>
            <div id="cards">
                {skeleton.map((e, key) => (
                    <Card skeleton={true} key={key} />
                ))}
            </div>
        </section>)
    }
    return (
        <section id='home'>
            <SearchBar all={all} set={setResult} />
            <Products all={result.length > 0 ? result : all} max={maxDisplayed} />
        </section>
    )
}


function Products(props) {
    return (
        <div id="cards">
            {props.all.map((e, key) => {
                if (key < props.max) {

                    return (<Card
                        key={key}
                        name={e.nom}
                        desc={e.petite_desc}
                        img={e.img.split(',')[0]}
                        price={e.Prix}
                        marque={e.theme}
                        id={e.id}
                        color={e.color}
                        reduction={e.reduction}
                        nouveau={e.nouveau}
                    ></Card>)
                } else {
                    return <div key={key} />
                }
            })}
        </div>
    )
}
Products.propTypes = {
    all: PropTypes.array.isRequired,
    max: PropTypes.number.isRequired
}