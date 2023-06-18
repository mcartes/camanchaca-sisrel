@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorOrganizacion'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorOrganizacion') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-2 col-md-2 col-lg-2"></div>

                        <div class="col-8 col-md-8 col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Nueva organización</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.guardar.org') }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="col-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Nombre</label> <label for=""
                                                        style="color: red;">*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-hotel"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="nombre"
                                                            name="nombre" value="{{ old('nombre') }}" autocomplete="off">
                                                    </div>
                                                    @if ($errors->has('nombre'))
                                                        <div
                                                            class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('nombre') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Tipo</label> <label for="" style="color: red;">*</label>
                                                    <div class="input-group">

                                                        <select class="form-control form-control-sm" name="tiporg"
                                                            id="tiporg">
                                                            <option value="" selected disabled>Seleccione...</option>
                                                            @foreach ($tipos as $tipo)
                                                                <option value="{{ $tipo->ento_codigo }}"
                                                                    {{ old('tiporg') == $tipo->ento_codigo ? 'selected' : '' }}>
                                                                    {{ $tipo->ento_nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('tiporg'))
                                                        <div
                                                            class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('tiporg') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Comuna</label> <label for=""
                                                        style="color: red;">*</label>
                                                    <div class="input-group">

                                                        <select class="form-control form-control-sm select2" name="comuna"
                                                            id="comuna" onchange="cargarCoordenadas()">
                                                            <option value="" disabled selected>Seleccione...
                                                            </option>
                                                            @foreach ($comunas as $comuna)
                                                                <option value="{{ $comuna->comu_codigo }}"
                                                                    {{ old('comuna') == $comuna->comu_codigo ? 'selected' : '' }}>
                                                                    {{ $comuna->comu_nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    @if ($errors->has('comuna'))
                                                        <div
                                                            class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('comuna') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div id="miDiv">
                                            <div class="row">
                                                <div class="col-6 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label>Geolocalización</label>
                                                        <div class="form-group">
                                                            <label>Latitud</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <i class="fas fa-map-marker-alt"></i>
                                                                    </div>
                                                                </div>
                                                                <input type="text" class="form-control" id="lat"
                                                                    name="lat" value="{{ old('lat') }}"
                                                                    autocomplete="off">
                                                            </div>
                                                            @if ($errors->has('lat'))
                                                                <div
                                                                    class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                                    <div class="alert-body">
                                                                        <button class="close"
                                                                            data-dismiss="alert"><span>&times;</span></button>
                                                                        <strong>{{ $errors->first('lat') }}</strong>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Longitud</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <i class="fas fa-map-marker-alt"></i>
                                                                    </div>
                                                                </div>
                                                                <input type="text" class="form-control" id="lng"
                                                                    name="lng" value="{{ old('lng') }}"
                                                                    autocomplete="off">
                                                            </div>
                                                            @if ($errors->has('lng'))
                                                                <div
                                                                    class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                                    <div class="alert-body">
                                                                        <button class="close"
                                                                            data-dismiss="alert"><span>&times;</span></button>
                                                                        <strong>{{ $errors->first('lng') }}</strong>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- TODO: Cliente solicita no contar con descripcion --}}
                                                {{-- <div class="col-6 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label>Descripción</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                            </div>
                                                            <textarea cols="100" rows="8" name="descripcion" id="descripcion"
                                                                placeholder="Ingrese una descripción para la organización" class="formbold-form-input"></textarea>
                                                        </div>
                                                        @if ($errors->has('descripcion'))
                                                            <div
                                                                class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                                <div class="alert-body">
                                                                    <button class="close"
                                                                        data-dismiss="alert"><span>&times;</span></button>
                                                                    <strong>{{ $errors->first('descripcion') }}</strong>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div> --}}

                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Cantidad de socios registrados</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-users"></i>
                                                            </div>
                                                        </div>
                                                        <input type="number" class="form-control" id="socios"
                                                            name="socios" value="{{ old('socios') }}"
                                                            autocomplete="off">
                                                    </div>
                                                    @if ($errors->has('socios'))
                                                        <div
                                                            class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('socios') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- nuevo campo -->
                                            <div class="col-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Domicilio (dirección de la sede)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-home"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="domicilio"
                                                            name="domicilio" value="{{ old('domicilio') }}"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- nuevo campo -->
                                            {{-- <div class="col-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Fecha de vinculación</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                        </div>
                                                        <input type="date" class="form-control" id="fecha"
                                                            name="fecha" value="{{ old('fecha') }}"
                                                            autocomplete="off">
                                                    </div>
                                                    @if ($errors->has('fecha'))
                                                        <div
                                                            class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('fecha') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div> --}}
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary waves-effect"><i
                                                    class="fa fa-save"></i> Registrar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2 col-md-2 col-lg-2"></div>

                        <div class="col-8 col-md-8 col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Seleccione la ubicación de la organización</h4>
                                </div>
                                <div class="card-body">
                                    <div id="map" style="height: 350px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <script src="{{ asset('public/js/organizaciones.js') }}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@latest/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('public/css/geocoder.css') }}" />
    <script src="https://unpkg.com/leaflet@latest/dist/leaflet-src.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        const csrftoken = document.head.querySelector(
            "[name~=csrf-token][content]"
        ).content;
        var map = L.map('map').setView([-33.42486299783035, -70.68914200046231], 5);

        var geocoder = L.Control.Geocoder.nominatim();
        if (typeof URLSearchParams !== 'undefined' && location.search) {
            // parse /?geocoder=nominatim from URL
            var params = new URLSearchParams(location.search);
            var geocoderString = params.get('geocoder');
            if (geocoderString && L.Control.Geocoder[geocoderString]) {
                console.log('Using geocoder', geocoderString);
                geocoder = L.Control.Geocoder[geocoderString]();
            } else if (geocoderString) {
                console.warn('Unsupported geocoder', geocoderString);
            }
        }

        var control = L.Control.geocoder({
            placeholder: 'Buscar ubicación...',
            geocoder: geocoder
        }).addTo(map);
        var marker;


        L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        map.on('click', function(e) {
            geocoder.reverse(e.latlng, map.options.crs.scale(map.getZoom()), function(results) {
                var r = results[0];
                if (r) {
                    if (marker) {
                        marker
                            .setLatLng(r.center)
                            .setPopupContent(r.html || r.name)
                            .openPopup();
                        // var coordendas = marker.getLatLng();
                        // document.getElementById("lat").value = coordendas.lat;
                        // document.getElementById("lng").value = coordendas.lng;
                    } else {
                        marker = L.marker(r.center)
                            .bindPopup(r.name)
                            .addTo(map)
                            .openPopup();
                        // marker.getLatLng();
                        // document.getElementById("lat").value = coordendas.lat;
                        // document.getElementById("lng").value = coordendas.lng;
                    }
                }
            });
        });


        function cargarCoordenadas() {
            var comuna = document.getElementById("comuna").value;

            fetch(window.location.origin + "/admin/organizaciones/comuna", {
                method: "POST",
                body: JSON.stringify({
                    comuna: comuna,
                }),
                headers: {
                    "Content-Type": "aplication/json",
                    "X-CSRF-TOKEN": csrftoken,
                },
            }).then(response => {
                return response.json();
            }).then(data => {
                for (let i in data.comuna) {
                    var coords = JSON.parse(data.comuna[i].comu_geoubicacion);
                    // map.flyTo([coords.lat,coords.lng],14);
                    map.setView([coords.lat, coords.lng], 14);
                }
            })
        }
    </script>

    <script>
        $(document).ready(function() {
            $("#miDiv").hide();
        });
    </script>
@endsection
