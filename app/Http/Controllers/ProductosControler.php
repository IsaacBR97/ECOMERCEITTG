<?php

namespace App\Http\Controllers;

use App\Models\Venta;

use App\Models\Usuario;


use App\Models\Producto;
use App\Models\Categoria;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductosControler extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Auth::user()->rol=="Supervisor") $productos = Producto::all();
        else $productos = Producto::where('usuario_id',Auth::id())->get();

        /*Aqui podemos hacer algunas cosas, como seleccionar que productos son los que cumplen cierta 
        condicion y los listaremos por ejemplo*/

        return view('Productos.index',compact('productos'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('Productos.create',compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $valores = $request->all();
        $request->validate([
            'imagen' => 'required|mimes:jpeg,bmp,png|max:10240'
           ]);
           $filename= time().".".$request->imagen->extension();
           $request->imagen->move(public_path('images'),$filename);
    
          
     
        $valores['usuario_id']=Auth::id();
            $valores['imagen']=$filename;
        $registro = new Producto();
        $registro->fill($valores);
        $registro->save();
      
        ProductImage::create(['image'=>$filename, 'product_id'=>$registro->id]);
        return redirect("/Productos")->with('mensaje','Producto agregado correctamente');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::find($id);
        return view('Productos.show',compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $producto = Producto::find($id);

        $categorias = Categoria::all();
        return view('Productos.edit',compact('producto','categorias'));
    }


    public function comprar(Producto $producto)
    {
     
     //DB::table('comments')->insert(
     // ['titulo'=> 'comentario','message' => $request->message, 'product_id' => $request->idproducto, 'user_id'=>$request->idcliente]
  //);
  
      Venta::create(['product_id'=> $producto->id
      ,'comprador_id' => auth()->user()->id, 'total'=>$producto->precio ,'vendedor_id'=>$producto->usuario_id]);
     

      return back()->with('status','Compra Exitosa!');

      
    }

    
    public function cambiarestado(Venta $estad)
    {
        if($estad->estado=="pendiente")
        {
            
          DB::table('ventas')
          ->where('id', $estad->id)
          ->update(['estado' => "entregado"]);
          DB::table('usuarios')->where('id','=',$estad->vendedor_id)->increment('cash',$estad->total);
  
        }
        else if($estad->estado=="entregado"){
          DB::table('ventas')
          ->where('id', $estad->id)
          ->update(['estado' => "pendiente"]);
          DB::table('usuarios')->where('id','=',$estad->vendedor_id)->decrement('cash',$estad->total);
        }
        
         return response()->json($estad->estado);
    }

    public function pagos($id)
    {
        $ventas=Venta::where('vendedor_id',$id)->where('estado','pendiente')->get();
        $productos=Producto::all();
        $usuario=Usuario::find($id)->nombre;
        $usuarios=Usuario::all();
        return view('Productos.tablerocontador',compact('ventas','usuario','productos','usuarios'));

        
    }

    public function check(Request $request)
    {
        $acumulado=0;
        if($request->id!=null)
        {
            foreach($request->id as $id)
{
    
    DB::table('ventas')
    
    ->where('id', $id)
    ->update(['estado' => "entregado"]);
    $vendedor=Venta::find($id)->vendedor_id;
    $total=Venta::find($id)->total;
    $acumulado=$acumulado+$total;
    DB::table('usuarios')->where('id','=',$vendedor)->increment('cash',$total);
}
return back()->with('status','Pago realizado con exito, Monto total:'.$acumulado);
        }
        else
        {
            return back()->with('error','No hay productos seleccionados');

        }

    }
    public function image(Request $request, Producto $producto)
    {
       
       $request->validate([
        'image' => 'required|mimes:jpeg,bmp,png|max:10240'
       ]);
       $filename= time().".".$request->image->extension();
       $request->image->move(public_path('images'),$filename);

       ProductImage::create(['image'=>$filename, 'product_id'=>$producto->id]);
       return back()->with('status','Imagen cargada con exito!');
    }

    public function imageDownload(ProductImage $image){
       
        $pathImage=public_path('images/').$image->image;
        return response()->download($pathImage);
    }
    public function imagedelete(ProductImage $image){
       $image->delete();
        $pathImage=public_path('images/').$image->image;
        unlink($pathImage);
        return back()->with('status','imagen '.$image->id.' eliminada');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $valores = $request->all();
        $imagen = $request->file('imagen');
        if(!is_null($imagen)){
            $ruta_destino = public_path('fotos/');
            $nombre_de_archivo = $imagen->getClientOriginalName();
            $imagen->move($ruta_destino, $nombre_de_archivo);        
            $valores['imagen']=$nombre_de_archivo;
        }
        $valores['usuario_id']=Auth::id();
        $registro = Producto::find($id);
        if($registro->concesionado==0)$registro->concesionado=null;
        $registro->fill($valores);
        $registro->save();


        return redirect("/Productos")->with('mensaje','Producto modificado correctamente');

    }
   //    return;

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         //podemos hacer validaciones para borrar o no
        try {
            $registro = Producto::find($id);
            $registro->delete();
            return redirect("/Productos")->with('mensaje','Producto modificado correctamente');
        }catch (\Illuminate\Database\QueryException $e) {
            return redirect("/Productos")->with('error',$e->getMessage());
        }
       
    }
}
