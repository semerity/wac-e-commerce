import AddProduct from "../components/addProduct"
import Themes from "../components/themes"
import React from "react"
import Pays from "../components/pays"
import DeliveryMethods from "../components/deliveryMethodCrud"
import CommandesAdmin from "../components/commandesAdmin"
import Event from "../components/events"

export default function Admin() {
    if (localStorage.getItem("admin") === 'false') {
        let a = document.createElement('a')
        a.href = '/'
        a.click()
    }
    return (
        <section id='admin'>
            <details>
                <summary>Add Product</summary>
                <AddProduct />
            </details>
            <details>
                <summary>Themes</summary>
                <Themes />
            </details>
            <details>
                <summary>Pays</summary>
                <Pays />
            </details>
            <details>
                <summary>Delivery Methods</summary>
                <DeliveryMethods />
            </details>
            <details>
                <summary>Commands</summary>
                <CommandesAdmin />
            </details>
            <details>
                <summary>Events</summary>
                <Event />
            </details>
        </section>
    )
}

