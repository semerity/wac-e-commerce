# Theme

## Theme object

```JSON
{
    "id": int,
    "theme": string,
    "color": string
}
```

## List d'Theme

### route 

- "/theme"

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
	{...},
	{...},
	{...},
	...
]
```

## Add

### route 

- "/theme"

### method

- post

### parameters

- none

### body

- Theme object in body
- id user
```js
body = JSON.stringify({
	"id user":...,
	// "id theme": ... is not needed,
	"...":...,
	...
})
```

### reponse

```JSON
"Thème ajoutée.",
```

## delete

### route 

- "/theme"

### method

- delete

### parameters

- none

### body
- id theme
- id user
```js
body = JSON.stringify({
	"id user":...,
	"id theme":...
})
```

### reponse

```JSON
"Thème supprimé."
```

## update

### route 

- "/theme"

### method

- patch

### parameters

- none

### body
- theme object
- id user
```js
body = JSON.stringify({
	"id user":...,
	// "id theme": ... is not needed,
	"...":...,
	...
})
```

### reponse

```JSON
"Theme modifié !"
```
