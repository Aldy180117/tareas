<!DOCTYPE html>
<html>
<head>
    <title>Gestor de tareas</title>  
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <style>
        * {
    box-sizing: border-box;
    padding: 0;
    margin: 0;
}

:root {
   --white: #fafafb;
   --Black:#1C1D21;
   --blue-light:#2185A0;
   --blue-dark:#2a73c2;
   --green: #2c7b90;
   --green-light: #29FF16;
}

body {
    background: linear-gradient(to bottom,var(--Black),var(--blue-light));
    height: 100vh;   
    font-family: 'Prompt', sans-serif;
}

.container {
    max-width: 80%;
    width: 400px;
    height: 600px;
    margin: 0 auto;
}

/*PERFIL*/
.perfil h1 {
    color: var(--white);
}



#fecha {
    color: var(--white);
    padding: 50px 0 5px 0px;

}

/*AGREGAR TAREA*/
.agregar-tarea {
    background-color: var(--white) ;
    border-radius: 15px;
    height: 70px;
    display: flex;
    align-items: center;
    gap: 70px;
    padding: 10px;
    margin: 20px 0;
}

.agregar-tarea input {
    width: 100%;
    height: 100%;
    border-radius: 8px;
    border: none;
    background-color: var(--white);
    padding-left: 10px;

}
.agregar-tarea input::placeholder {
   font-size: 1.1rem;
   color: var(--Black);
}

.agregar-tarea i{
    font-size: 50px;
    color: var(--Black);

}
.agregar-tarea i:hover {
    transform: scale(1.1);
    cursor: pointer;
}



/*SECCION DE TAREA */
.seccion-tarea h3 {
    color: var(--white);
    margin-bottom: 10%;
}

.seccion-tarea li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(to bottom,var(--blue-dark),var(--green));
    border-radius: 15px;
    padding: 10px;
    color: var(--white);
    margin: 10px 0;

}
.seccion-tarea i {
    font-size: 25px;
}

.seccion-tarea > ul p {
    font-size: 1.2rem;

}


.seccion-tarea i:hover {
    color: var(--green-light);
    cursor: pointer;
}

.line-through{
    text-decoration: line-through;
    color : var(--green-light);
} 
    </style>
<div class="container">
    <div class="perfil">
        <div id="fecha"></div>
        <h1>Bienvenido, a tu gestor de tareas</h1>
    </div>

    <div class="agregar-tarea">
        <input type="text" id="input" placeholder="Agregar tarea">
        <i id="boton-enter"class="fas fa-plus-circle"></i>
    </div>

    <div class="seccion-tarea">
        <h3>Estas son tus tareas pendientes </h3>
        <ul id="lista">
          
        </ul>
    </div>   
</div>
<script>
const fecha = document.querySelector('#fecha')
const lista = document.querySelector('#lista')
const elemento = document.querySelector('#elemento')
const input = document.querySelector('#input')
const botonEnter = document.querySelector('#boton-enter')
const check = 'fa-check-circle'
const uncheck = 'fa-circle'
const lineThrough = 'line-through'
let LIST

let id // para que inicie en 0 cada tarea tendra un id diferente

//creacion de fecha actualizada 

const FECHA = new Date ()
fecha.innerHTML = FECHA.toLocaleDateString('es-MX',{weekday: 'long', month: 'long', day:'numeric'})

// funcion de agregar tarea 

function agregarTarea( tarea,id,realizado,eliminado) {
    if(eliminado) {return} // si existe eliminado es true si no es false 

    const REALIZADO = realizado ? check : uncheck // si realizado es verdadero check si no uncheck

    const LINE = realizado ? lineThrough : '' 

    const elemento = `
                        <li id="elemento">
                        <i class="far ${REALIZADO}" data="realizado" id="${id}"></i>
                        <p class="text ${LINE}">${tarea}</p>
                        <i class="fas fa-trash de" data="eliminado" id="${id}"></i> 
                        </li>
                    `
    lista.insertAdjacentHTML("beforeend",elemento)

}


// funcion de Tarea Realizada 
function tareaRealizada(element) {
    element.classList.toggle(check)
    element.classList.toggle(uncheck)
    element.parentNode.querySelector('.text').classList.toggle(lineThrough)
    LIST[element.id].realizado = LIST[element.id].realizado ?false :true 
  
}

function tareaEliminada(element){
    element.parentNode.parentNode.removeChild(element.parentNode)
    LIST[element.id].eliminado = true
    console.log(LIST)
}


// evento para escuchar el enter y para habilitar el boton 

botonEnter.addEventListener('click', ()=> {
    const tarea = input.value
    if(tarea){
        agregarTarea(tarea,id,false,false)
        LIST.push({
            nombre : tarea,
            id : id,
            realizado : false,
            eliminado : false
        })
        localStorage.setItem('TODO',JSON.stringify(LIST))
        id++
        input.value = ''
    }

})

document.addEventListener('keyup', function (event) {
    if (event.key=='Enter'){
        const tarea = input.value
        if(tarea) {
            agregarTarea(tarea,id,false,false)
        LIST.push({
            nombre : tarea,
            id : id,
            realizado : false,
            eliminado : false
        })
        localStorage.setItem('TODO',JSON.stringify(LIST))
     
        input.value = ''
        id++
        console.log(LIST)
        }
    }
    
})


lista.addEventListener('click',function(event){
    const element = event.target 
    const elementData = element.attributes.data.value
    console.log(elementData)
    
    if(elementData == 'realizado') {
        tareaRealizada(element)
    }
    else if(elementData == 'eliminado') {
        tareaEliminada(element)
        console.log("elimnado")
    }
    localStorage.setItem('TODO',JSON.stringify(LIST))
})


let data = localStorage.getItem('TODO')
if(data){
    LIST = JSON.parse(data)
    console.log(LIST)
    id = LIST.length
    cargarLista(LIST)
}else {
    LIST = []
    id = 0
}


function cargarLista(array) {
    array.forEach(function(item){
        agregarTarea(item.nombre,item.id,item.realizado,item.eliminado)
    })
}
</script>
</body>
</html>