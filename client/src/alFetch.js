export default function alfetch(obj) {
    let callBack = obj.callBack || function (e) { }
    let method = obj.method || 'GET'
    let body = obj.body || {}
    let header = obj.header || null
    let errorDisplay = obj.errorDisplay || false

    if(!isJson(body)){body = JSON.stringify(body)}

    let argument = {
        method: method,
        mode: 'cors',
        credentials: 'include',
        headers: { ...{'Content-Type': 'application/json'}, ...header },
    }

    if (method !== 'GET') {
        argument.body = body
    }
    fetch(obj.url, argument).then(e => e.json()).then(e => { callBack(e) }).catch(e => { if (errorDisplay) console.log(e) })
}

function isJson(str) {
    try {JSON.parse(str);} 
    catch (e) {return false;}
    return true;
}