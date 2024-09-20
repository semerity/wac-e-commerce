# user

## user object

```JSON
{
    "id": int,
    "email": string,
    "roles": array(json),
    "password": string
}
```

## List d'user

### route 

- "/admin/users"

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

- "/admin/user/add"

### method

- post

### parameters

- none

### body
- user object
```js
body = JSON.stringify({
	// "id user": ... is not needed,
	"...":...,
	...
})
```

### reponse

```JSON
{
    "success": "successfully registered"
}
```

## delete

### route 

- "/admin/user/{id}"

### method

- delete

### parameters

- id user

### body
- none

### reponse

```JSON
{
    "success": "successfully registered"
}
```
_(oups)_

## update

### route 

- "/user/{id}"

### method

- patch

### parameters

- id user

### body
- user object
```js
body = JSON.stringify({
	// "id user": ... is not needed / never used,
	"...":...,
	...
})
```

### reponse

```JSON
{
    "method": "patch",
    "id": int,
    "data": json (request user object),
    "user": json (user object after modification)
}
```

## replace / admin update

### route 

- "/admin/user/{id}"

### method

- put

### parameters

- id user

### body
- user object
```js
body = JSON.stringify({
	// "id user": ... is not needed,
	"...":...,
	...
})
```

### reponse

```JSON
{
    "method": "patch",
    "id": int,
    "data": json (request user object),
    "user": json (user object after modification)
}
```
