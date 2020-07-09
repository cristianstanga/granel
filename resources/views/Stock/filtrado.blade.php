@extends('adminlte::page')

@section('title', 'Inventario')

@section('content_header')
<div class="row">
  <h1 class="m-0 text-dark">Reportes</h1>
  <div class="col-sm-12">
      <form action="{{route('ventas.filtrado')}}" method='POST'>
      <select name="maquina" class="form-control">
          @csrf
          <option value='t'>Total</option>
          @foreach($maquinas as $maquina)
             <option value='{{$maquina->id}}'>{{$maquina->nombre}}</option>
          @endforeach
      </select>
      <input type="submit" class="btn btn-primary" value="Seleccionar">
      </form>
  </div>
  <!--<a href="#" class="btn btn-primary" href="#">Recargar Máquina</a>-->
</div>
    <hr>
@stop


@section('content')
<div class="container" id="app">
    <div class="row">
        <div class="col-sm-6">
            {!! $grafico->container() !!}
        </div>
    
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>

        <div class="col-sm-6">
            {!!$chart->container() !!}
    
        </div>
    </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
       
    <div class="row">
        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">{!!$precio->container() !!}</div>
    </div>
    {{ $chart->script() }}
     {{ $precio->script() }}
</div>

{!! $grafico->script() !!}
@endsection