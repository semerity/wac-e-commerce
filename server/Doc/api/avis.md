# Avis

## Avis object

```JSON
{
    "id": int,
    "id_product": int,
    "note": int,
    "content": string,
    "id_user": string
}
```

## List d'avis d'un produit

### route 

- "/avis"

### method

- get

### parameters 

- none

### body

- none

### response

Array d'objets
```JSON
[
	{
		"id": 1,
	},
	{...},
	{...},
	...
]
```

## Add

### route 

- "/avis"

### method

- post

### parameters

- none

### body

- avis object as body
```js
body = JSON.stringify({
	"id":...,
	...,
	...
})
```

### reponse

```JSON
"Note ajoutée.",
```

## delete

### route 

- "/avis"

### method

- delete

### parameters

- none

### body

- id avis
- id user
```js
body = JSON.stringify({
	"id avis": ...,
	"id user": ...
})
```

### reponse

```JSON
"Avis supprimé"
```

## update

### route 

- "/avis"

### method

- patch

### parameters

- none

### body

- avis id and content
- id user
```js
body = JSON.stringify({
	"id user": ...,
	"id avis":...,
	"content":...
})
```


### reponse

```JSON
"Avis modifié !",
```
