<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Reporte Camanchaca</title>
    <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/app.min.css') }}">
</head>

<body class="mt-4">
    <div class="section-body p-4">
        <div class="row d-print-none">
            <div class="col-xl-8"></div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-action">
                            <button id="btnGuardar" class="btn btn-primary d-print-none">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">

                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-2 l-bg-orange">
                            <div class="card-icon l-bg-cyan">
                                <i class="fab fa-slack"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="padding-20">
                                    <div class="text-right">
                                        <h3 class="font-light mb-0">
                                            {{-- Aca va el valor numerico a represnetar --}}
                                            {{ count($iniciativasCantidad) }}
                                        </h3>
                                        <h6 class="font-light">
                                            {{-- aca va el texto que representa el numero de arriba --}}
                                            Iniciativas
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">

                        <div class="card card-statistic-2 l-bg-orange">
                            <div class="card-icon l-bg-red">
                                <i class="fas fa-hotel"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="padding-20">
                                    <div class="text-right">
                                        <h3 class="font-light mb-0">
                                            {{-- Aca va el valor numerico a represnetar --}}
                                            {{ count($organizacionesCantidad) }}
                                        </h3>
                                        <h6 class="font-light">
                                            {{-- aca va el texto que representa el numero de arriba --}}
                                            N° organizaciones involucradas en iniciativas
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-2 l-bg-green">
                            <div class="card-icon l-bg-red">
                                <i class="fas fa-clipboard"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="padding-20">
                                    <div class="text-right">
                                        <h3 class="font-light mb-0">
                                            {{-- Aca va el valor numerico a represnetar --}}
                                            {{ count($actividadesCantidad) }}
                                        </h3>
                                        <h6 class="font-light">
                                            {{-- aca va el texto que representa el numero de arriba --}}
                                            Actividades en bitácora
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">

                        <div class="card card-statistic-2 l-bg-green">
                            <div class="card-icon l-bg-cyan">
                                <i class="fas fa-hotel"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="padding-20">
                                    <div class="text-right">
                                        <h3 class="font-light mb-0">
                                            {{-- Aca va el valor numerico a represnetar --}}
                                            {{ count($organizacionesCantidadActividades) }}
                                        </h3>
                                        <h6 class="font-light">
                                            {{-- aca va el texto que representa el numero de arriba --}}
                                            N° organizaciones involucradas en actividades
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">

                        <div class="card card-statistic-2 l-bg-red">
                            <div class="card-icon l-bg-cyan">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="padding-20">
                                    <div class="text-right">
                                        <h3 class="font-light mb-0"
                                            title="{{ '$' . number_format($costosDonaciones, 0, ',', '.') }}">
                                            {{-- Aca va el valor numerico a represnetar --}}
                                            @if ($costosDonaciones > 1000000)
                                                {{ number_format($costosDonaciones / 1000000, 1) . ' M' }}
                                            @else
                                                {{ '$' . number_format($costosDonaciones, 0, ',', '.') }}
                                            @endif
                                        </h3>
                                        <h6 class="font-light">
                                            {{-- aca va el texto que representa el numero de arriba --}}
                                            Monto de donaciones
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="table-responsive">

                    <table class="table table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Comuna</th>
                                <th>Organizaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($organizacionesByComunas->groupBy('comu_nombre') as $comuna => $organizaciones)
                                <tr>
                                    <td>{{ $comuna }}</td>
                                    <td>
                                        @foreach ($organizaciones as $key => $organizacion)
                                            {{ $organizacion->orga_nombre }}{{ $key < count($organizaciones) - 1 ? ',' : '' }}
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('btnGuardar').addEventListener('click', function() {
            // Configurar las opciones de impresión
            var printOptions = {
                headersAndFooters: false, // Desmarcar la opción de encabezados y pies de página
                background: true // Marcar la opción de gráficos en segundo plano
            };

            // Imprimir la página con las opciones configuradas
            window.print(printOptions);
        });
    </script>

</body>

</html>
