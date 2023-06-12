@extends('digitador.panel_digitador')

@section('contenido')
    <section class="section">
        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorActividad'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorActividad') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-3 col-md-3 col-lg-3"></div>
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ isset($actividad) ? 'Formulario edición de actividad' : 'Formulario ingreso de actividad' }}</h4>
                        </div>
                        <div class="card-body">
                            @if (isset($actividad))
                                <form action="{{route('digitador.actividad.actualizar', $actividad->acti_codigo)}}" method="POST">
                                    @method('PUT')
                            @else
                                <form action="{{route('digitador.actividad.guardar')}}" method="POST">
                            @endif
                                @csrf
                                <div class="form-group">
                                    <label>Organización</label> <label for="" style="color: red;">*</label>
                                    @if (isset($actividad))
                                        <select class="form-control select2" id="organizacion" name="organizacion">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($organizaciones as $orga)
                                                <option value="{{ $orga->orga_codigo }}" {{ $actividad->orga_codigo==$orga->orga_codigo ? 'selected' : '' }}>{{ $orga->orga_nombre }}</option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                    @else
                                        <select class="form-control select2" id="organizacion" name="organizacion">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($organizaciones as $orga)
                                                <option value="{{ $orga->orga_codigo }}" {{ old('organizacion')==$orga->orga_codigo ? 'selected' : '' }}>{{ $orga->orga_nombre }}</option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                    @endif
                                    @if($errors->has('organizacion'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('organizacion') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Nombre</label> <label for="" style="color: red; display:inline-block;">*</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') ?? @$actividad->acti_nombre }}" autocomplete="off">
                                    @if($errors->has('nombre'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('nombre') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Fecha de realización</label> <label for="" style="color: red;">*</label>
                                    <input type="date" id="realizacion" name="realizacion" class="form-control datemask" value="{{ old('realizacion') ?? @\Carbon\Carbon::parse($actividad->acti_fecha)->format('Y-m-d') }}">
                                    @if($errors->has('realizacion'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('realizacion') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Acuerdos</label> <label for="" style="color: red;">*</label>
                                    <div class="input-group">
                                        <textarea class="formbold-form-input" id="acuerdos" name="acuerdos" rows="5" style="width: 100%;">{{ old('acuerdos') ?? @$actividad->acti_acuerdos }}</textarea>
                                    </div>
                                    @if($errors->has('acuerdos'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('acuerdos') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Fecha de cumplimiento</label> <label for="" style="color: red;">*</label>
                                    <input type="date" id="cumplimiento" name="cumplimiento" class="form-control datemask" value="{{ old('cumplimiento') ?? @\Carbon\Carbon::parse($actividad->acti_fecha_cumplimiento)->format('Y-m-d') }}">
                                    @if($errors->has('cumplimiento'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('cumplimiento') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Avance</label> <label for="" style="color: red;">*</label>
                                    @if (isset($actividad))
                                        <select class="form-control select2" id="avance" name="avance">
                                            <option value="" selected disabled>Seleccione...</option>
                                            <option value="Ejecutada" {{ $actividad->acti_avance=='Ejecutada' ? 'selected' : '' }}>Ejecutada</option>
                                            <option value="En avance conforme a plazo" {{ $actividad->acti_avance=='En avance conforme a plazo' ? 'selected' : '' }}>En avance conforme a plazo</option>
                                            <option value="En avance con retraso" {{ $actividad->acti_avance=='En avance con retraso' ? 'selected' : '' }}>En avance con retraso</option>
                                            <option value="Suspendida" {{ $actividad->acti_avance=='Suspendida' ? 'selected' : '' }}>Suspendida</option>
                                            <option value="Descartada" {{ $actividad->acti_avance=='Descartada' ? 'selected' : '' }}>Descartada</option>
                                        </select>
                                    @else
                                        <select class="form-control select2" id="avance" name="avance">
                                            <option value="" selected disabled>Seleccione...</option>
                                            <option value="Ejecutada" {{ old('avance')=='Ejecutada' ? 'selected' : '' }}>Ejecutada</option>
                                            <option selected value="En avance conforme a plazo" {{ old('avance')=='En avance conforme a plazo' ? 'selected' : '' }}>En avance conforme a plazo</option>
                                            <option value="En avance con retraso" {{ old('avance')=='En avance con retraso' ? 'selected' : '' }}>En avance con retraso</option>
                                            <option value="Suspendida" {{ old('avance')=='Suspendida' ? 'selected' : '' }}>Suspendida</option>
                                            <option value="Descartada" {{ old('avance')=='Descartada' ? 'selected' : '' }}>Descartada</option>
                                        </select>
                                    @endif
                                    @if($errors->has('avance'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('avance') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-12 col-lg-12 text-right">
                                        <a href="{{route('digitador.dbgeneral.index')}}" class="btn btn-icon btn-warning"><i class="fas fa-home"></i> Inicio</a>
                                        <button type="submit" class="btn btn-icon btn-primary"><i class="fas fa-save"></i> Siguiente</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/digitador/bitacora/bitacora.js') }}"></script>

@endsection
