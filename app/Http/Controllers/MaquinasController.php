<?php

namespace App\Http\Controllers;
use App\Maquinas;
use App\Stock;
use App\Ventas;
use App\User;
use DB;
use App\Charts\Productos;
use App\Charts\VentasGrafico;

use Illuminate\Http\Request;

class MaquinasController extends Controller
{
    public function AllMaquinas()
    {
        $maquinas = Maquinas::all();

        return view('Maquinas/todas',[
            'maquinas' => $maquinas
        ]);
    }
    
    public function AllUser()
    {
        $users = User::all();

        return view('User/todas',[
            'users' => $users
        ]);
    }

    public function detalle($id)
    {
        $grafico = new Productos();
        $grafico->title('Inventario', 18); // titulo y tamaño

        $registros = DB::table('stock')
        ->select('productos.nombre AS nombre', 'stock.cantidad')
        ->join('productos', 'productos.id', '=', 'stock.producto_id') 
        ->where('maquina_id', '=', $id)
        ->get();
        //var_dump($registros);die();
        $labels = collect();
        $valores = collect();
        $coloresFondo = collect();

        $cont = 0;
        foreach ($registros as $registro) {
            $labels->push(["$registro->nombre"]);
            $valores->push(["$registro->cantidad"]);
            $coloresFondo->push('blue');
        }

        $grafico->labels($labels);
        $dataset = $grafico->dataset('Conjunto datos', 'horizontalBar', $valores); // ‘pie’ es el tipo de gráfico
        $dataset->backgroundColor($coloresFondo);

        // Instanciamos el objeto gráfico 
        $chart = new VentasGrafico();
                
        $chart->title("Cantidad Vendida", 18); // titulo y tamaño

        $registros = DB::table('productos')
            ->join('ventas', 'productos.id', '=', 'ventas.producto_id')
            ->where('maquina_id', '=', $id)
            ->select('ventas.id', 'productos.nombre', 'ventas.cantidad')
            ->groupBy('productos.nombre')
            ->selectRaw('sum(ventas.cantidad) as cantidad' )
            ->get();
        
        
        $labels = collect();
        $valores = collect();
        $coloresFondo = collect();
        $totales = collect();

        $cont = 0;
        $colores = [
            'blue',
            'pink',
            'yellow',
            'red',
            'green',
            'black',
            
        ];
        foreach ($registros as $registro) 
        {
            if ($cont == 5){
                $cont = 0;
            }
            $labels->push($registro->nombre);
            $valores->push($registro->cantidad);
            $coloresFondo->push($colores[$cont]);
          //  $totales->push($registro->total);
            $cont++;
        }

        $chart->labels($labels);
        $dataset = $chart->dataset('Conjunto', 'pie', $valores); // ‘pie’ es el tipo de gráfico
        $dataset->backgroundColor($coloresFondo);
        
         $registros = DB::table('productos')
            ->join('ventas', 'productos.id', '=', 'ventas.producto_id')
            ->where('maquina_id', '=', $id)
            ->select('ventas.id', 'productos.nombre', 'ventas.costo')
            ->groupBy('productos.nombre')
            ->selectRaw('sum(ventas.costo) as costo' )
            ->get();
        
        
        $labels = collect();
        $valores = collect();
        $coloresFondo = collect();
        $totales = collect();

        $cont = 0;
        $colores = [
            'blue',
            'pink',
            'yellow',
            'red',
            'green',
            'black',
            
        ];
        foreach ($registros as $registro) 
        {
            if ($cont == 5){
                $cont = 0;
            }
            $labels->push($registro->nombre);
            $valores->push($registro->costo);
            $coloresFondo->push($colores[$cont]);
          //  $totales->push($registro->total);
            $cont++;
        }
        $precio = new VentasGrafico();
        $precio->title("Ganancias", 18); // titulo y tamaño
        $precio->labels($labels);
        $dataset = $precio->dataset('Conjunto', 'pie', $valores); // ‘pie’ es el tipo de gráfico
        $dataset->backgroundColor($coloresFondo);
                
                

        // colores de fondo será una collección de colores web (https://htmlcolorcodes.com/es/nombres-de-los-colores/)

        return view('Stock/detalle', [
            'grafico' => $grafico,
            'chart'   => $chart,
            'precio'  => $precio
         ]);
 
    }
    
    public function filtro(Request $request)
    {
        
       
        $valor = $request->input('maquina');

        $maquinas = Maquinas::all();
        if($valor == null || $valor == 't'){
           $grafico = new Productos();
            $grafico->title('Inventario', 18); // titulo y tamaño

            $registros = DB::table('stock')
            ->select('productos.nombre AS nombre', 'stock.cantidad')
            ->join('productos', 'productos.id', '=', 'stock.producto_id') 
            ->get();

            $labels = collect();
            $valores = collect();
            $coloresFondo = collect();
    
            $cont = 0;
            foreach ($registros as $registro) {
                $labels->push(["$registro->nombre"]);
                $valores->push(["$registro->cantidad"]);
                $coloresFondo->push('blue');
            }

            $grafico->labels($labels);
            $dataset = $grafico->dataset('Conjunto datos', 'horizontalBar', $valores); // ‘pie’ es el tipo de gráfico
            $dataset->backgroundColor($coloresFondo);
    
            // Instanciamos el objeto gráfico 
            $chart = new VentasGrafico();
                    
            $chart->title("Cantidad Vendida", 18); // titulo y tamaño
    
            $registros = DB::table('productos')
                ->join('ventas', 'productos.id', '=', 'ventas.producto_id')
                ->select('ventas.id', 'productos.nombre', 'ventas.cantidad')
                ->groupBy('productos.nombre')
                ->selectRaw('sum(ventas.cantidad) as cantidad' )
                ->get();
            
            
            $labels = collect();
            $valores = collect();
            $coloresFondo = collect();
            $totales = collect();
    
            $cont = 0;
            $colores = [
                'blue',
                'pink',
                'yellow',
                'red',
                'green',
                'black',
                
            ];
            foreach ($registros as $registro) 
            {
                if ($cont == 5){
                    $cont = 0;
                }
                $labels->push($registro->nombre);
                $valores->push($registro->cantidad);
                $coloresFondo->push($colores[$cont]);
              //  $totales->push($registro->total);
                $cont++;
            }
    
            $chart->labels($labels);
            $dataset = $chart->dataset('Conjunto', 'pie', $valores); // ‘pie’ es el tipo de gráfico
            $dataset->backgroundColor($coloresFondo);
            
             $registros = DB::table('productos')
                ->join('ventas', 'productos.id', '=', 'ventas.producto_id')
                ->select('ventas.id', 'productos.nombre', 'ventas.costo')
                ->groupBy('productos.nombre')
                ->selectRaw('sum(ventas.costo) as costo' )
                ->get();
            
            
            $labels = collect();
            $valores = collect();
            $coloresFondo = collect();
            $totales = collect();
    
            $cont = 0;
            $colores = [
                'blue',
                'pink',
                'yellow',
                'red',
                'green',
                'black',
                
            ];
            foreach ($registros as $registro) 
            {
                if ($cont == 5){
                    $cont = 0;
                }
                $labels->push($registro->nombre);
                $valores->push($registro->costo);
                $coloresFondo->push($colores[$cont]);
                $cont++;
            }
            $precio = new VentasGrafico();
            $precio->title("Ganancias", 18); // titulo y tamaño
            $precio->labels($labels);
            $dataset = $precio->dataset('Conjunto', 'pie', $valores); // ‘pie’ es el tipo de gráfico
            $dataset->backgroundColor($coloresFondo);
                     
        }else{
            $grafico = new Productos();
            $grafico->title('Inventario', 18); // titulo y tamaño
    
            $registros = DB::table('stock')
            ->select('productos.nombre AS nombre', 'stock.cantidad')
            ->join('productos', 'productos.id', '=', 'stock.producto_id') 
            ->where('maquina_id', '=', $valor)
            ->get();
            //var_dump($registros);die();
            $labels = collect();
            $valores = collect();
            $coloresFondo = collect();
    
            $cont = 0;
            foreach ($registros as $registro) {
                $labels->push(["$registro->nombre"]);
                $valores->push(["$registro->cantidad"]);
                $coloresFondo->push('blue');
            }
    
            $grafico->labels($labels);
            $dataset = $grafico->dataset('Conjunto datos', 'horizontalBar', $valores); // ‘pie’ es el tipo de gráfico
            $dataset->backgroundColor($coloresFondo);
    
            // Instanciamos el objeto gráfico 
            $chart = new VentasGrafico();
                    
            $chart->title("Cantidad Vendida", 18); // titulo y tamaño
    
            $registros = DB::table('productos')
                ->join('ventas', 'productos.id', '=', 'ventas.producto_id')
                ->where('maquina_id', '=', $valor)
                ->select('ventas.id', 'productos.nombre', 'ventas.cantidad')
                ->groupBy('productos.nombre')
                ->selectRaw('sum(ventas.cantidad) as cantidad' )
                ->get();
            
            
            $labels = collect();
            $valores = collect();
            $coloresFondo = collect();
            $totales = collect();
    
            $cont = 0;
            $colores = [
                'blue',
                'pink',
                'yellow',
                'red',
                'green',
                'black',
                
            ];
            foreach ($registros as $registro) 
            {
                if ($cont == 5){
                    $cont = 0;
                }
                $labels->push($registro->nombre);
                $valores->push($registro->cantidad);
                $coloresFondo->push($colores[$cont]);
              //  $totales->push($registro->total);
                $cont++;
            }
    
            $chart->labels($labels);
            $dataset = $chart->dataset('Conjunto', 'pie', $valores); // ‘pie’ es el tipo de gráfico
            $dataset->backgroundColor($coloresFondo);
            
             $registros = DB::table('productos')
                ->join('ventas', 'productos.id', '=', 'ventas.producto_id')
                ->where('maquina_id', '=', $valor)
                ->select('ventas.id', 'productos.nombre', 'ventas.costo')
                ->groupBy('productos.nombre')
                ->selectRaw('sum(ventas.costo) as costo' )
                ->get();
            
            
            $labels = collect();
            $valores = collect();
            $coloresFondo = collect();
            $totales = collect();
    
            $cont = 0;
            $colores = [
                'blue',
                'pink',
                'yellow',
                'red',
                'green',
                'black',
                
            ];
            foreach ($registros as $registro) 
            {
                if ($cont == 5){
                    $cont = 0;
                }
                $labels->push($registro->nombre);
                $valores->push($registro->costo);
                $coloresFondo->push($colores[$cont]);
              //  $totales->push($registro->total);
                $cont++;
            }
            $precio = new VentasGrafico();
            $precio->title("Ganancias", 18); // titulo y tamaño
            $precio->labels($labels);
            $dataset = $precio->dataset('Conjunto', 'pie', $valores); // ‘pie’ es el tipo de gráfico
            $dataset->backgroundColor($coloresFondo);
                    
        }
        
        return view('Stock/filtrado', [
            'grafico'  => $grafico,
            'chart'    => $chart,
            'precio'   => $precio,
            'maquinas' => $maquinas
         ]);
    }
    
    public function filtroCompleto()
    {
        
       $maquinas = Maquinas::all();
           $grafico = new Productos();
            $grafico->title('Inventario', 18); // titulo y tamaño
            $registros = DB::table('stock')
            ->select('productos.nombre AS nombre', 'stock.cantidad')
            ->join('productos', 'productos.id', '=', 'stock.producto_id') 
            ->get();

            $labels = collect();
            $valores = collect();
            $coloresFondo = collect();
    
            $cont = 0;
            foreach ($registros as $registro) {
                $labels->push(["$registro->nombre"]);
                $valores->push(["$registro->cantidad"]);
                $coloresFondo->push('blue');
            }

            $grafico->labels($labels);
            $dataset = $grafico->dataset('Conjunto datos', 'horizontalBar', $valores); // ‘pie’ es el tipo de gráfico
            $dataset->backgroundColor($coloresFondo);
    
            // Instanciamos el objeto gráfico 
            $chart = new VentasGrafico();
                    
            $chart->title("Cantidad Vendida", 18); // titulo y tamaño
            $registros = DB::table('productos')
                ->join('ventas', 'productos.id', '=', 'ventas.producto_id')
                ->select('ventas.id', 'productos.nombre', 'ventas.cantidad')
                ->groupBy('productos.nombre')
                ->selectRaw('sum(ventas.cantidad) as cantidad' )
                ->get();
            
            
            $labels = collect();
            $valores = collect();
            $coloresFondo = collect();
            $totales = collect();
    
            $cont = 0;
            $colores = [
                'blue',
                'pink',
                'yellow',
                'red',
                'green',
                'black',
                
            ];
            foreach ($registros as $registro) 
            {
                if ($cont == 5){
                    $cont = 0;
                }
                $labels->push($registro->nombre);
                $valores->push($registro->cantidad);
                $coloresFondo->push($colores[$cont]);
              //  $totales->push($registro->total);
                $cont++;
            }
    
            $chart->labels($labels);
            $dataset = $chart->dataset('Conjunto', 'pie', $valores); // ‘pie’ es el tipo de gráfico
            $dataset->backgroundColor($coloresFondo);
             $registros = DB::table('productos')
                ->join('ventas', 'productos.id', '=', 'ventas.producto_id')
                ->select('ventas.id', 'productos.nombre', 'ventas.costo')
                ->groupBy('productos.nombre')
                ->selectRaw('sum(ventas.costo) as costo' )
                ->get();
        
            
            $labels = collect();
            $valores = collect();
            $coloresFondo = collect();
            $totales = collect();
    
            $cont = 0;
            $colores = [
                'blue',
                'pink',
                'yellow',
                'red',
                'green',
                'black',
                
            ];
            foreach ($registros as $registro) 
            {
                if ($cont == 5){
                    $cont = 0;
                }
                $labels->push($registro->nombre);
                $valores->push($registro->costo);
                $coloresFondo->push($colores[$cont]);
                $cont++;
            }
            $precio = new VentasGrafico();
            $precio->title("Ganancias", 18); // titulo y tamaño
            $precio->labels($labels);
            $dataset = $precio->dataset('Conjunto', 'pie', $valores); // ‘pie’ es el tipo de gráfico
            $dataset->backgroundColor($coloresFondo);
                     
        
        
        return view('Stock/filtrado', [
            'grafico'  => $grafico,
            'chart'    => $chart,
            'precio'   => $precio,
            'maquinas' => $maquinas
         ]);
            
    }
}
