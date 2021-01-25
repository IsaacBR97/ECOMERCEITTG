@extends('layout.general')
@section('breadcumb')
<div class="container">
    <div class="card rounded-0">
        <div class="card-body">
          <h4>Ventas aun sin pagar del usuario: {{$usuario}}</h4>
          <table class="table">
            <thead>
                <td>
                    MARCAR
                </td>
              <td style="color:#FF7126 ">
                PRODUCTO
            </td>
            
            
           
          <td>
            VENDIDO A:
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
           <form action="{{route('check')}}" method="post">
            @csrf
              <tr>
                @foreach ($ventas as $venta)
                <tr>
                    <td>
                        <input type="checkbox" name="id[{{$venta->id}}]" id="" value="{{$venta->id}}">
                    </td>
                   
                    <td>
                       

                        {{ $productos->find($venta->product_id)->nombre }}
                    </td>
                   
                    
                   
                  <td>
                    {{ $usuarios->find($venta->comprador_id)->nombre }}
                    <input type="hidden" value=" {{ $usuarios->find($venta->vendedor_id)->nombre }}" name="vendedor_id">
                </td>
                  <td>
                
                    {{$venta->estado}}
                   
                    
                </td>
                <td>
                
                  {{$venta->total}}
                  <input type="hidden" value=" {{$venta->total }}" name="total">

                  
              </td>
                
              
              
              
          
         
     
                    
                </tr>
                @endforeach
            </tbody>
      
          </table>
          <button type="submit" class="btn btn-success">Pagar</button>
        </form>
        </div>
        @if (session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{session('error')}}
        </div>
    @endif
      </div>
    </div>
  </div>


  

    @endsection