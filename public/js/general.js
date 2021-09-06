//pagina web para aprender html etc... https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement/dataset
//vamor a ver si el usuario da a favoritos que pasa , vamos a trabajar con ayax y con jquery
//este es el ejemplo que al que accedo en index.html.twig
// <a href="#" 
// class="btn btn-link mg-3 favorito {{marcador.favorito ? 'activo'}}"
// data-id='{{marcador.id}}'
// data-url='{{path('app_editar_favoritos')}}'>
//   <span class="oi oi-star" title="Favorito" aria-hidden="true"></span>
// </a>


//los pasos que sigue esto es primero me crea la página y cuando el usuario haga clip sobre la estrella para realizar la acción de dar like entonces 
//Se lanza un evento que llama a esto , y ya este llama  a la url y demas
$('.favorito').on('click',function(e){
    e.preventDefault(); //eliminamos el evento que pudiera tener el objeto e , que en este caso seria el <a></a>
    //indicamos las variables que tenemos actualmente
    var $this = $(this),
        url = $this.data('url'),//recupero la url que tengo en mi data-url
        idMarcador = $this.data('id') //recupero el id que tengo en data-id

    //ahora añadimos una clase al marcador cuando se haga la accion - es decir indicamos que esta clase esta desabilitada
    $this.addClass('disabled')

    //ahora hacemos un post a la url que hemos obtenido pasandole como parametro el id  , cuando termine vamos a tratar la respuesta y el fail 
    $.post(url , {id : idMarcador})
                    .done(function(respuesta){
                        //en este caso nos devolvera una respuesta que indicara que hemos o no actualizado nuestra bbdd como hemos indicado en la ruta de url.. que hemos pasado como parametro
                        if(respuesta.actualizado == true){//Por nuestra ruta url hemos devuelto una respuesta , esta respuesta tiene el dato de actualizado indicando si es true o falso segun se haya realizado o no la actualización
                            $this.toggleClass('activo') //esto lo que hace es añadir o quitar el atributo activo dependiendo si lo tenia o no lo tenia
                        }
                        $this.removeClass('disabled') // eliminamos la clase disable
                    })
                    .fail(function(respuesta ){
                        $this.removeClass('disabled') // eliminamos la clase disable
                    });

})