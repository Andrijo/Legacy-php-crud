function ocultarTabla() {
    document.getElementById('myTable').style.display = 'none';
    document.getElementById('myPagination').style.display = 'none';
    document.getElementById('myBoton2').style.display = 'none';
    document.getElementById('myBoton').style.display = '';
}

function mostrarTabla() {
    document.getElementById('myTable').style.display = '';
    document.getElementById('myPagination').style.display = '';
    document.getElementById('myBoton2').style.display = '';
    document.getElementById('myBoton').style.display = 'none';
}


