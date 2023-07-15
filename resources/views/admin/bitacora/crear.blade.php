@extends('admin.panel_admin')

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
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalCrearOrga"><i class="fas fa-plus"></i> Nueva Organización</button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (isset($actividad))
                                <form action="{{route('admin.actividad.actualizar', $actividad->acti_codigo)}}" method="POST">
                                    @method('PUT')
                            @else
                                <form action="{{route('admin.actividad.guardar')}}" method="POST">
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
                                            <option value="En avance conforme a plazo" {{ old('avance')=='En avance conforme a plazo' ? 'selected' : '' }}>En avance conforme a plazo</option>
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
                                        <a href="{{route('admin.dbgeneral.index')}}" type="button" class="btn btn-warning" title="Ir a inicio"><i class="fas fa-home"></i> Volver</a>
                                        <a href="{{route('admin.actividad.listar')}}" type="button" class="btn btn-success" title="Ir a lista"><i class="fas fa-backward"></i> Ir a actividades</a>
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
    <div class="modal fade" id="modalCrearOrga" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nueva Organización</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.actividad.orga.crear') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="nombre">Nombre de la organización</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-hotel"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" autocomplete="off" min="0">
                            </div>
                            @if ($errors->has('nombre'))
                            <div class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <strong>{{ $errors->first('nombre') }}</strong>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="form-group ">
                            <label for="comuna">Comuna</label>
                            <div class="input-group">
                                <select class="form-control" name="comuna" id="comuna">
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($comunas as $comuna)
                                    <option value="{{ $comuna->comu_codigo }}" {{ old('comuna') == $comuna->comu_codigo ? 'selected' : '' }}>
                                        {{ $comuna->comu_nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('comuna'))
                            <div class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <strong>{{ $errors->first('comuna') }}</strong>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="tiporg">Tipo de organización</label>
                            <div class="input-group">

                                <select class="form-control form-control-sm" name="tiporg" id="tiporg">
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->ento_codigo }}" {{ old('tiporg') == $tipo->ento_codigo ? 'selected' : '' }}>
                                        {{ $tipo->ento_nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('tiporg'))
                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <strong>{{ $errors->first('tiporg') }}</strong>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-save"></i> Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/admin/bitacora/bitacora.js') }}"></script>

@endsection
