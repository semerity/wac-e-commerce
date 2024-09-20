# Produit
## produit object
```json
{
	"id": 1,
	"id_theme": 1,
	"nom": "nom",
	"description": "description",
	"nb_piece": 0,
	"age": 0,
	"dimension": "",
	"Prix": 0,
	"petite_desc": "",
	"img": "img,img,img,...",
	"popularite": 0,
	"stock": 0
}
```
## List
### route 
- "/"
### method
- get
### parameters 
- none
### body
- none
### response
Array d'objets produits
```JSON
[
	{...},
	{...},
	{...},
	...
]
```
## Detail
### route 
- "/produit/{id}"
### method
- get
### parameters
- id du produit
### body
- none
### reponse
```JSON
{
	"id": 1,
	"id_theme": 1,
	"nom": "nom",
	"description": "description",
	"nb_piece": 0,
	"age": 0,
	"dimension": "",
	"Prix": 0,
	"petite_desc": "",
	"img": "img,img,img,...",
	"popularite": 0,
	"stock": 0
}
```
## Add
### route 
- "/produit"
### method
- post
### parameters
- none
### body
- product object
- id user
```js
body = JSON.stringify({
	"id user":...,
	// "id produit": ... is not needed,
	"...":...,
	...
})
```
### reponse
```JSON
"Rôle présent et produit ajouté",
{
	"id": 1,
	"id_theme": 1,
	"nom": "nom",
	"description": "description",
	"nb_piece": 0,
	"age": 0,
	"dimension": "",
	"Prix": 0,
	"petite_desc": "",
	"img": "img,img,img,...",
	"popularite": 0,
	"stock": 0
}
```
**ou**
```JSON
"Vous n'avez pas les rôles pour faire ça."
```
## delete
### route 
- "/produit"
### method
- delete
### parameters
- none
### body
- id produit
- id user
```js
body = JSON.stringify({
	"id user":...,
	"id produit":...
})
```
### reponse
```JSON
"Rôle présent et produit supprimé",
{...}
```
**ou**
```JSON
"Vous n'avez pas les rôles pour faire ça."
```
## update
### route 
- "/produit"
### method
- patch
### parameters
- none
### body
- product object
- id user
```js
body = JSON.stringify({
	"id user":...,
	"id produit": ...,
	"...":...,
	...
})
```
### reponse
```JSON
"Produit modifié !",
```
**ou**
```JSON
"Vous n'avez pas les rôles pour faire ça."
