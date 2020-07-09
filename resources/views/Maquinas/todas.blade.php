@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
    <h1 class="m-0 text-dark">Inventario de Máquinas</h1>
    <hr>
@stop

@section('content')


<hr>
<br>
<table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nombre</th>
        <th scope="col">Ubicación</th>
        <th scope="col">Acción</th>

      </tr>
    </thead>
    <tbody>
        @foreach($maquinas as $maquina)
        <tr>
            <th scope="row">{{$maquina->id}}</th>
            <td>{{$maquina->nombre}}</td>
            <td>{{$maquina->ubicacion}}</td>

         <td>
          <a href="{{route('maquinas.detalle',$maquina->id)}}" class="btn btn-success">Stock</a>
         </td>
           
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection