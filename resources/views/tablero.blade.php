@extends('layout.general')

@section('breadcumb')
<li class="breadcrumb-item" ><a href="/tablero">Tablero</a></li>
@endsection
@section('content')

@switch(Auth::user()->rol)
    @case( 'Supervisor' )
    <div class="card-columns">
        <div class="card">
          <a href="/Usuarios">
            <img class="card-img-top" src="/fotos/usuarios.png" alt="Card image cap">
          </a>
            <div class="card-body">
              <h5 class="card-title">Usuarios registrados</h5>
              <p class="card-text">Clientes: {{$clientes ?? ''}} </p>
              <p class="card-text">Empleados: {{$empleados}}</p>
            </div>
        </div>

        <div class="card">
          <a href="/Productos">
            <img class="card-img-top" src="/prods/productos.png" alt="Card image cap">
          </a>
            <div class="card-body">
              <h5 class="card-title">Productos</h5>
              <p class="card-text">Registrados: {{$productos}}</p>
              <p class="card-text">Concesionados: {{$concesionados}}</p>
            </div>
          </div>

          <div class="card">
            <a href="/Categorias">
            <img class="card-img-top" src="/secciones/categorias.png"  height="300" alt="Card image cap">
            </a>
            <div class="card-body">
              <h5 class="card-title">Categorias</h5>
              <p class="card-text">Registradas: {{$categorias}} </p>
            </div>
          </div>
      </div>
        @break
    @case('Encargado')
    <div class="card-columns">
      <div class="card">
        <a href="/Revisiones">
          <img class="card-img-top" src="/prods/productos.png" alt="Card image cap">
        </a>
          <div class="card-body">
            <h5 class="card-title">Propuestas</h5>
            <p class="card-text">
              A revisar: {{$propuestas ?? ''}}
            </p>
          </div>
      </div>

      <div class="card">
        <a href="/Preguntas">
          <img class="card-img-top" src="/prods/preguntas.png"  height="300" alt="Card image cap">
        </a>
          <div class="card-body">
            <h5 class="card-title">Dudas</h5>
            <p class="card-text">
              Preguntas por revisar: {{$preguntas ?? '' ?? ''}}
            </p>
            <p class="card-text">
              Respuestas por revisar: {{$respuestas ?? ''}}
            </p>
          </div>
      </div>

    </div>
      @break
    @case('Contador')
    <div class="container">
      <div class="card rounded-0">
          <div class="card-body">
            <h4>VENTAS REALIZADAS</h4>
            <table class="table">
              <thead>
                <td style="color:#FF7126 ">
                  PRODUCTO
              </td>
              
              
              <td>
                COMPRADOR
            </td>
            <td>
              VENDEDOR
          </td>
            <td>
              ESTADO
          </td>
           
          <td>
            TOTAL
        </td>
       
            
         
          
          
        
     
          </tr>
              </thead>
              <tbody>
             
                <tr>
                  @foreach ($ventas as $venta)
                  <tr>
                     
                      <td>
                         

                          {{ $productos->find($venta->product_id)->nombre }}
                      </td>
                     
                      
                      <td>
                       {{ $comprador->find($venta->comprador_id)->nombre }}</a> 
                    </td>
                    <td>
                      <a href="{{route('pagousuario', $comprador->find($venta->vendedor_id)->id)}}" class="btn btn-info">{{  $comprador->find($venta->vendedor_id)->nombre }}</a> 

                    
                  </td>
                    <td>
                  
                      <a href="" data-id="{{$venta->id}}" data="{{$venta->comprador_id}}" class="approved btn btn-{{$venta->estado=='pendiente'? "warning": "success"}} btn-sm  btn-round" data-toggle="modal" data-target="#confirmarestado">{{$venta->estado}}</a>
                     
                      
                  </td>
                  <td>
                  
                    {{$venta->total}}
                   
                    
                </td>
                  
                
                 <div class="modal fade" id="confirmasrestado-{{$venta->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog  modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Porque fue rechazado?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form action="{{route('comentrechazado')}}" method="post">
                          @csrf
                        <textarea name="comentario" id="" class="form-control"></textarea>
                        <input type="hidden" value="{{$venta->id}}" name="id">
                      </div>
                      <div class="modal-footer">
                        
                      <button  type="submit" class=" btn btn-success" >Enviar Comentario</button>
                    </form>
                      </div>
                    </div>
                  </div>
                </div>
                
            
           
       
                      
                  </tr>
                  @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <script>



      document.querySelectorAll(".approved").
    forEach(link=>link.addEventListener("click", function(){
    
    var id=link.getAttribute("data-id");
    var user=link.getAttribute("data");
    $.ajax({
      method: "POST",
      url: "{{URL::to("/")}}/dashboard/venta/cambiarestado/"+id,
      data:{'_token': '{{csrf_token()}}','id':id,'user':user}
    })
      .done(function( approved ) {
       if(approved=="entregado"){
         $(link).removeClass('btn-success');
         $(link).addClass('btn-warning');
         $( link).text("pendiente")
       }
       else if(approved=="pendiente"){
         $(link).removeClass('btn-warning');
         $(link).addClass('btn-success');
         $( link).text("entregado")
       
       }
     
      });
    }))
    
    
    
        
    
      </script>
        @break
    @case('Cliente')
    <div class="card-columns">

      <div class="card">
        <a href="/Productos">
          <img class="card-img-top" src="/prods/productos.png" alt="Card image cap">
        </a>
          <div class="card-body">
            <h5 class="card-title">Productos</h5>
            <p class="card-text">Registrados: {{$productos ?? ''}}</p>
            <p class="card-text">Concesionados: {{$concesionados ?? ''}}</p>
          </div>
        </div>

        <div class="card">
          <a href="/Preguntas">
            <img class="card-img-top" src="/prods/preguntas.png"  height="300" alt="Card image cap">
          </a>
            <div class="card-body">
              <h5 class="card-title">Dudas</h5>
              <p class="card-text">
                Preguntas por contestar: {{$preguntas ?? ''}}
              </p>
              <p class="card-text">
                Respuestas recibidas: {{$respuestas ?? ''}}
              </p>
            </div>
        </div>
  
        
    </div>
      @break
    
@endswitch

        

@endsection