<?php

namespace App\Http\Controllers;

use App\Models\TipoUnidades;
use App\Models\Unidades;
use App\Models\Comunas;
use App\Models\EvaluacionOperaciones;
use App\Models\IniciativasUnidades;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Psy\Util\Json;

class UnidadesController extends Controller
{

    //TODO: Sección de unidades
    public function Listarunidades(Request $request)
    {
        $unidades = null;
        if(count($request->all()) > 0){
            if($request->comuna !="" && $request->tipunidad == ""){
                $unidades = DB::table('unidades')
                    ->join('comunas', 'unidades.comu_codigo', '=', 'comunas.comu_codigo')
                    ->join('tipo_unidades', 'unidades.tuni_codigo', '=', 'tipo_unidades.tuni_codigo')
                    ->select('unidades.*', 'comunas.comu_nombre', 'tipo_unidades.tuni_nombre')
                    ->where('comunas.comu_codigo', $request->comuna)
                    ->get();

            }elseif($request->comuna !="" && $request->tipunidad != ""){
                $unidades = DB::table('unidades')
                    ->join('comunas', 'unidades.comu_codigo', '=', 'comunas.comu_codigo')
                    ->join('tipo_unidades', 'unidades.tuni_codigo', '=', 'tipo_unidades.tuni_codigo')
                    ->select('unidades.*', 'comunas.comu_nombre', 'tipo_unidades.tuni_nombre')
                    ->where('comunas.comu_codigo', $request->comuna)
                    ->where('unidades.tuni_codigo',$request->tipunidad)
                    ->get();
                }
            }else{
                $unidades = DB::table('unidades')
                    ->join('comunas', 'unidades.comu_codigo', '=', 'comunas.comu_codigo')
                    ->join('tipo_unidades', 'unidades.tuni_codigo', '=', 'tipo_unidades.tuni_codigo')
                    ->select('unidades.*', 'comunas.comu_nombre', 'tipo_unidades.tuni_nombre')
                    ->get();
            }

        return view(
            'admin.unidades.listar',
            [
                'tipoUnidades' => TipoUnidades::all(),
                'comunas' => Comunas::all(),
                'unidades' => $unidades
            ]
        );
    }

    public function crearUnidad()
    {
        return view('admin.unidades.crear', [
            'unidades' => Unidades::all(),
            'comunas' => Comunas::all(),
            'tipounidades' => TipoUnidades::all(),
        ]);
    }

    public function Guardarunidad(Request $request)
    {
        $request->validate(
            [
                'unid_nombre' => 'required|max:50',
                'unid_nombre_cargo' => 'required|max:50',
                'unid_descripcion' => 'required|max:200',
                'unid_responsable' => 'required|max:50',
                'tuni_codigo' => 'required',
                'comu_codigo' => 'required',


            ],
            [
                'unid_nombre.required' => 'El nombre de la unidad es requerido.',
                'unid_nombre_cargo.required' => 'El nombre del cargo de la unidad es requerido.',
                'unid_descripcion.required' => 'La descripción de las actividades de la unidad es requerida.',
                'unid_responsable.required' => 'El nombre la persona a cargo requerido.',
                'tuni_codigo.required' => 'Seleccione el codigo del tipo de unidad adjunta.',
                'comu_codigo.required' => 'Seleccione la comuna adjuna a la unidad.'
            ]
        );


        if (!$request) {
            return redirect()->back()->withErrors($request)->withInput();
        }

        $unidad = Unidades::create([
            'tuni_codigo' => $request->tuni_codigo,
            'comu_codigo' => $request->comu_codigo,
            'unid_nombre' => $request->unid_nombre,
            'unid_descripcion' => $request->unid_descripcion,
            'unid_responsable' => $request->unid_responsable,
            'unid_nombre_cargo' => $request->unid_nombre_cargo,
            'unid_geoubicacion' => Json::encode(['lat' => $request->lat, 'lng' => $request->lng]),
            'unid_rol_mod' => Session::get('admin')->rous_codigo,
            'unid_rut_mod' => Session::get('admin')->usua_rut,
            'unid_vigente' => 'S',
            'unid_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'unid_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        if ($unidad) {
            return redirect()->route('admin.unidades.listar')->with('exitoUnidad', 'La unidad se guardo correctamente.');
        }

        return redirect()->back()->with('errorUnidad', 'Ocurrio un error durante el registro.');
    }

    public function editarunidad($unidades)
    {
        return view('admin.unidades.editar', [
            'unid' => Unidades::where(['unid_codigo' => $unidades])->select('unid_codigo','tuni_codigo','comu_codigo', 'unid_nombre', 'unid_descripcion','unid_responsable','unid_nombre_cargo', 'unid_vigente', 'unid_geoubicacion->lat as lat', 'unid_geoubicacion->lng as lng')
            ->first(),
            'tuni' => TipoUnidades::all(),
            'comu' => Comunas::all()
        ]);
    }

    public function actualizarunidad(Request $request, $unid)
    {
        $request->validate(
            [
                'unid_nombre' => 'required|max:50',
                'unid_nombre_cargo' => 'required|max:50',
                'unid_descripcion' => 'required|max:200',
                'unid_responsable' => 'required|max:50',
                'tuni_codigo' => 'required',
                'comu_codigo' => 'required',
                'unid_vigente' => 'required',

            ],
            [
                'unid_nombre.required' => 'El nombre de la unidad es requerido.',
                'unid_nombre_cargo.required' => 'El nombre de la unidad a cargo es requerido.',
                'unid_descripcion.required' => 'La descripcion de las actividades de la unidad es requerida.',
                'unid_responsable.required' => 'El nombre la persona a cargo requerido.',
                'tuni_codigo.required' => 'seleccione el codigo del tipo de unidad adjunta.',
                'comu_codigo.required' => 'seleccione la comuna adjuna a la unidad.',
                'unid_vigente.required' => 'seleccione la vigencia de la unidad.',
            ]
        );


        if (!$request) {
            return redirect()->back()->withErrors($request)->withInput();
        }

        $Unidad = Unidades::where(['unid_codigo' => $unid])->update([
            'unid_nombre' => $request->unid_nombre,
            'unid_nombre_cargo' => $request->unid_nombre_cargo,
            'unid_descripcion' => $request->unid_descripcion,
            'unid_responsable' => $request->unid_responsable,
            'tuni_codigo' => $request->tuni_codigo,
            'comu_codigo' => $request->comu_codigo,
            'unid_geoubicacion' => Json::encode(['lat' => $request->lat, 'lng' => $request->lng]),
            'unid_rol_mod' => Session::get('admin')->rous_codigo,
            'unid_rut_mod' => Session::get('admin')->usua_rut,
            'unid_vigente' => $request->unid_vigente,
            'unid_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),

        ]);

        if ($Unidad) {
            return redirect()->route('admin.unidades.listar')->with('exitoUnidad', 'La unidad se actualizó correctamente.');
        }

        return redirect()->back()->with('errorUnidad', 'Ocurrio un error durante la actualización.');
    }

    public function ObtenerTiposUnidades(Request $request)
    {
        if (isset($request->comuna)) {
            $tipoUnidades = TipoUnidades::select('tipo_unidades.tuni_codigo', 'tuni_nombre','comunas.comu_codigo')
                ->join('unidades', 'unidades.tuni_codigo', '=', 'tipo_unidades.tuni_codigo')
                ->join('comunas', 'comunas.comu_codigo', '=', 'unidades.comu_codigo')
                ->where('comunas.comu_codigo', $request->comuna)
                ->distinct()
                ->get();
            return json_encode(['success' => true, 'tuni' => $tipoUnidades]);
        }

        return json_encode(['success' => false]);
    }

    public function eliminarUnidad($codigo)
    {
        $unidadUsuarios = Usuarios::where('unid_codigo', $codigo)->get();
        if (sizeof($unidadUsuarios) > 0) return redirect()->back()->with('errorUnidad', 'La unidad no se puede eliminar porque tiene usuarios asociados.');

        $unidadOperaciones = EvaluacionOperaciones::where('unid_codigo', $codigo)->get();
        if (sizeof($unidadOperaciones) > 0) return redirect()->back()->with('errorUnidad', 'La unidad no se puede eliminar porque tiene evaluaciones de operación asociadas.');

        $unidadIniciativas = IniciativasUnidades::where('unid_codigo', $codigo)->get();
        if (sizeof($unidadIniciativas) > 0) return redirect()->back()->with('errorUnidad', 'La unidad no se puede eliminar porque tiene iniciativas asociadas.');

        $unidEliminar = Unidades::where('unid_codigo',$codigo)->delete();
        if(!$unidEliminar) return redirect()->back()->with('errorUnidad', 'Ocurrió un error al eliminar la unidad.');
        return redirect()->route('admin.unidades.listar')->with('exitoUnidad', 'La unidad fue eliminada correctamente.');
    }
//TODO: Fin sección de unidades
}
