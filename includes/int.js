function mostrar(web)
{
divResultado = document.getElementById('mostrar');

ajax=Ajax();
ajax.open("GET", "user.php"+web,true);
ajax.onreadystatechange=
    function() {
    if (ajax.readyState==4) {
        divResultado.innerHTML = ajax.responseText

    }
}
ajax.send(null)
}
