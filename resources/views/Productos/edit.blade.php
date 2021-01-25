@extends('layout.general')

@section('breadcumb')
<li class="breadcrumb-item" ><a href="/tablero">Tablero</a></li>
<li class="breadcrumb-item"><a href="/Productos">Productos</a></li>
<li class="breadcrumb-item active" aria-current="page">Editar</li>
@endsection

@section('content')
@if (session('error'))
<div>
    {{ session('error') }}
</div>
<br>
@endif
<form action="/Productos/{{$producto->id}}" method="post" enctype="multipart/form-data" >
    @csrf
    @method('PUT')

    <div class="form-group">
      <label>Nombre:</label>
     <input type="text" name="nombre" class="form-control" value="{{$producto->nombre}}">
    </div>

  @can('cambios', $producto)
    <div class="form-group">
        <label>Descripcion: </label>
        <textarea class="form-control" name="descripcion" rows="3">{{$producto->descripcion}}</textarea>
    </div>

    <div class="input-group">
      <label >Precio:</label>
      <div class="input-group-prepend">
        <span class="input-group-text">$</span>
      </div>
      <input type="text" name="precio" class="form-control" value="{{$producto->precio}}">
      <div class="input-group-append">
        <span class="input-group-text">.00</span>
      </div>
    </div>
  @else
    <div class="form-group">
        Descripcion: {{$producto->descripcion}}
    </div>

    <div class="input-group">
      Precio: ${{$producto->precio}}.00
    </div>

@endcan
  
    
      <input type="hidden" name="usuario_id" value="{{Auth::id()}}">
      <div class="form-group">
        <label>Categoria:</label>
        <select name="categoria_id">
          @foreach ($categorias as $categoria)
              <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
          @endforeach
        </select>
    </div>
    @if (!is_null($producto->concesionado) && $producto->concesionado==0)
    <div class="alert alert-danger" role="alert">
      Motivo por el cual no fue aceptado: {{$producto->motivo}}
    </div>
    @endif
  
    <input type="submit" class="btn btn-primary" value="Enviar">    
</form>
<br>
<br>
<form action="{{route("producto.image",$producto)}}" method="POST" enctype="multipart/form-data">
  @csrf
     
       <input type="file" name="image" id="" class="form-control ">
 
     
     
       <input type="submit" class="btn btn-success btn-round " value="SUBIR IMAGEN">
 
     
   
 
 
 </form>

 @if ($producto->images!=null)


 <div class="card">
   <div class="card-header card-header-info">
       <h4 class="card-title">
        Imagenes del Producto
       </h4>
      
   </div>
   <div class="card-body">
 <div class="row mt-3">
   @foreach ($producto->images as $image)
   <div class="col-mt-3 col-lg-3 card">
     <div class=" card-profile">
       <div class="card-avatar">
   <img class="w-100 img" src="{{asset('images')}}/{{$image->image}}" alt="">
       </div>
     </div>
   
   <div class="row">
     <div class="col-6">
       <a href="{{route('producto.imageDownload',$image->id)}}" class="btn-block  btn btn-success btn-sm mt-2" >Editar</i></a>
 
     </div>
 
     <div class="col-6">
       <form action="{{route('producto.imagedelete',$image->id)}}" method="POST">
         @method("DELETE")
         @csrf
       <button  class="btn-block  btn btn-danger btn-sm mt-2 type="submit">Eliminar</button>
     </form>
     </div>
    
   </div>
 
   </div>
 @endforeach
 </div>
   </div>
 </div>
        
        
 
 @endif
@endsection