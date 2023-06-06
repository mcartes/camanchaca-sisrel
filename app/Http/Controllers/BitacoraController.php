<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asistentes;
use App\Models\AsistentesActividades;
use Illuminate\Http\Request;
use App\Models\Organizaciones;
use App\Models\Actividades;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Dirigentes;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\type;

class BitacoraController extends Controller
{

    public function ListarActividad(Request $request) {
        if (count($request->all()) > 0) {
            if ($request->orga_codigo!='' && $request->orga_codigo!='-1') {
                return view('admin.bitacora.listar', [
                    'actividades' => DB::table('actividades')
                        ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                        ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                        ->where('organizaciones.orga_codigo', $request->orga_codigo)
                        ->get(),
                    'organizaciones' => DB::table('organizaciones')
                        ->join('actividades', 'actividades.orga_codigo', '=', 'organizaciones.orga_codigo')
                        ->select('organizaciones.orga_codigo', 'orga_nombre')
                        ->distinct()
                        ->get()
                ]);
            }
        }

        return view('admin.bitacora.listar', [
            'actividades' => DB::table('actividades')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->select('orga_nombre', 'acti_codigo', 'acti_nombre', 'acti_fecha', 'acti_fecha_cumplimiento', 'acti_avance', 'acti_vigente')
                ->get(),
            'organizaciones' => DB::table('organizaciones')
                ->join('actividades', 'actividades.orga_codigo', '=', 'organizaciones.orga_codigo')
                ->select('organizaciones.orga_codigo', 'orga_nombre')
                ->distinct()
                ->get()
        ]);
    }

    public function MostrarActividad($acti_codigo) {
        $actividad = Actividades::where('acti_codigo', $acti_codigo)->first();
        if (!$actividad) return redirect()->route('admin.actividad.listar')->with('errorActividad', 'La actividad seleccionada no se encuentra registrada en el sistema.');

        return view('admin.bitacora.mostrar', [
            'actividad' => Actividades::select('*', 'organizaciones.orga_nombre')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->where('acti_codigo', $acti_codigo)
                ->first(),
            'participantes' => DB::table('asistentes')
                ->join('asistentes_actividades', 'asistentes_actividades.asis_codigo', '=', 'asistentes.asis_codigo')
                ->where('acti_codigo', $acti_codigo)
                ->get()
        ]);
    }


    public function CrearActividad() {
        return view('admin.bitacora.crear', [
            'organizaciones' => Organizaciones::where('orga_vigente', 'S')->get()
        ]);
    }

    public function GuardarActividad(Request $request) {
        $request->validate(
            [
                'organizacion' => 'required|exists:organizaciones,orga_codigo',
                'nombre' => 'required|max:255',
                'realizacion' => 'required|date',
                'acuerdos' => 'required|max:65535',
                'cumplimiento' => 'required|date',
                'avance' => 'required'
            ],
            [
                'organizacion.required' => 'La organización es requerida.',
                'organizacion.exists' => 'La organización no se encuentra registrada.',
                'nombre.required' => 'El nombre de la actividad es requerido.',
                'nombre.max' => 'El nombre de la actividad excede el máximo de caracteres permitidos (255).',
                'realizacion.required' => 'La fecha de realización es requerida.',
                'realizacion.date' => 'La fecha de realización debe estar en un formato válido.',
                'acuerdos.required' => 'Los acuerdos de la actividad son requeridos.',
                'acuerdos.max'=> 'Los acuerdos excede el máximo de caracteres permitidos (65535).',
                'cumplimiento.required' => 'La fecha de cumplimiento es requerida.',
                'cumplimiento.date' => 'La fecha de cumplimiento debe estar en un formato válido.',
                'avance.required' => 'El avance de la actividad es requerido.'
            ]
        );

        $actiCrear = Actividades::insertGetId([
            'orga_codigo' => $request->organizacion,
            'acti_nombre' => $request->nombre,
            'acti_fecha' => $request->realizacion,
            'acti_acuerdos' => $request->acuerdos,
            'acti_fecha_cumplimiento' => $request->cumplimiento,
            'acti_avance' => $request->avance,
            'acti_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'acti_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'acti_vigente' => 'S',
            'acti_rut_mod' => Session::get('admin')->usua_rut,
            'acti_rol_mod' => Session::get('admin')->rous_codigo,
        ]);
        if (!$actiCrear) return redirect()->back()->with('errorActividad', 'Ocurrió un error al registrar la actividad, intente más tarde.');
        return redirect()->route('admin.actividad.participantes.editar', $actiCrear)->with('exitoActividad', 'Los datos de la actividad fueron registrados correctamente.');
    }


    public function EditarActividad($acti_codigo) {
        return view('admin.bitacora.crear', [
            'actividad' => Actividades::where('acti_codigo', $acti_codigo)->first(),
            'organizaciones' => Organizaciones::where('orga_vigente', 'S')->get()
        ]);
    }

    public function ActualizarActividad(Request $request, $acti_codigo) {
        $request->validate(
            [
                'organizacion' => 'required|exists:organizaciones,orga_codigo',
                'nombre' => 'required|max:255',
                'realizacion' => 'required|date',
                'acuerdos' => 'required|max:65535',
                'cumplimiento' => 'required|date',
                'avance' => 'required'
            ],
            [
                'organizacion.required' => 'La organización es requerida.',
                'organizacion.exists' => 'La organización no se encuentra registrada.',
                'nombre.required' => 'El nombre de la actividad es requerido.',
                'nombre.max' => 'El nombre de la actividad excede el máximo de caracteres permitidos (255).',
                'realizacion.required' => 'La fecha de realización es requerida.',
                'realizacion.date' => 'La fecha de realización debe estar en un formato válido.',
                'acuerdos.required' => 'Los acuerdos de la actividad son requeridos.',
                'acuerdos.max'=> 'Los acuerdos excede el máximo de caracteres permitidos (65535).',
                'cumplimiento.required' => 'La fecha de cumplimiento es requerida.',
                'cumplimiento.date' => 'La fecha de cumplimiento debe estar en un formato válido.',
                'avance.required' => 'El avance de la actividad es requerido.'
            ]
        );

        $actiActualizar = Actividades::where('acti_codigo', $acti_codigo)->update([
            'orga_codigo' => $request->organizacion,
            'acti_nombre' => $request->nombre,
            'acti_fecha' => $request->realizacion,
            'acti_acuerdos' => $request->acuerdos,
            'acti_fecha_cumplimiento' => $request->cumplimiento,
            'acti_avance' => $request->avance,
            'acti_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'acti_rut_mod' => Session::get('admin')->usua_rut,
            'acti_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$actiActualizar) return redirect()->back()->with('errorActividad', 'Ocurrió un error al actualizar los datos de la actividad, intente más tarde.');
        return redirect()->route('admin.actividad.participantes.editar', $acti_codigo)->with('exitoActividad', 'Los datos de la actividad fueron actualizados correctamente.');
    }

    public function EliminarActividad($acti_codigo) {
        $asisConsultar = DB::table('asistentes')
            ->join('asistentes_actividades', 'asistentes_actividades.asis_codigo', '=', 'asistentes.asis_codigo')
            ->select('asistentes.asis_codigo')
            ->where('acti_codigo', $acti_codigo)
            ->get();
        $asisCodigos = [];
        foreach ($asisConsultar as $asac) {
            array_push($asisCodigos, $asac->asis_codigo);
        }

        $asacEliminar = AsistentesActividades::where('acti_codigo', $acti_codigo)->delete();
        $asisEliminar = Asistentes::whereIn('asis_codigo', $asisCodigos)->delete();
        $actiEliminar = Actividades::where('acti_codigo', $acti_codigo)->delete();
        if (!$asacEliminar || !$asisEliminar || !$actiEliminar) return redirect()->back()->with('errorActividad', 'Ocurrió un error al eliminar la actividad o algunos de los datos asociados, por favor informar al encargado de registrar y monitorear datos.');
        return redirect()->route('admin.actividad.listar')->with('exitoActividad', 'La actividad fue eliminada correctamente.');
    }

    public function EditarParticipantes($acti_codigo) {
        return view('admin.bitacora.participantes', [
            'dirigentes' => DB::table('dirigentes')
                ->join('dirigentes_organizaciones', 'dirigentes_organizaciones.diri_codigo', '=', 'dirigentes.diri_codigo')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'dirigentes_organizaciones.orga_codigo')
                ->join('actividades', 'actividades.orga_codigo', '=', 'organizaciones.orga_codigo')
                ->select('dirigentes.diri_codigo', 'diri_nombre', 'diri_apellido')
                ->where('actividades.acti_codigo', $acti_codigo)
                ->get(),
            'actividad' => DB::table('actividades')
                ->join('organizaciones', 'organizaciones.orga_codigo', '=', 'actividades.orga_codigo')
                ->select('acti_codigo', 'acti_nombre', 'orga_nombre')
                ->where('acti_codigo', $acti_codigo)
                ->first()
        ]);
    }

    public function ListarParticipantes(Request $request) {
        $validacion = Validator::make($request->all(), [
            ['actividad' => 'exists:actividades,acti_codigo'],
            ['actividad.exists' => 'La actividad no se encuentra registrada.']
        ]);
        if ($validacion->fails()) return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);
        
        $asistentes = DB::table('asistentes_actividades')
            ->join('asistentes', 'asistentes_actividades.asis_codigo', '=', 'asistentes.asis_codigo')
            ->join('actividades', 'asistentes_actividades.acti_codigo', '=', 'actividades.acti_codigo')
            ->select('asistentes.asis_codigo','asistentes.asis_nombre', 'asistentes.asis_apellido', 'actividades.acti_codigo')
            ->where('actividades.acti_codigo', $request->actividad)
            ->get();

        if (sizeof($asistentes) == 0) return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $asistentes]);
    }

    public function AgregarParticipante(Request $request) {
        if ($request->dirigente == 0 && $request->diricodigo == 0) {
            $validacion = Validator::make(
                $request->all(),
                [
                    'nombre' => 'required',
                    'apellido' => 'required'
                ],
                [
                    'nombre.required' => 'Es necesario ingresar un nombre para el asistente',
                    'apellido.required' => 'Es necesario ingresar un apellido para el asistente'
                ]
            );

            if ($validacion->fails()) {
                return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);
            }
            $asisGuardar = Asistentes::insertGetId([
                'diri_codigo' => null,
                'asis_nombre' => $request->nombre,
                'asis_apellido' => $request->apellido,
                'asis_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'asis_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'asis_vigente' => 'S',
                'asis_rut_mod' => Session::get('admin')->usua_rut,
                'asis_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        } else {
            $asisGuardar = Asistentes::insertGetId([
                'diri_codigo' => $request->diricodigo,
                'asis_nombre' => $request->nombre,
                'asis_apellido' => $request->apellido,
                'asis_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'asis_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'asis_vigente' => 'S',
                'asis_rut_mod' => Session::get('admin')->usua_rut,
                'asis_rol_mod' => Session::get('admin')->rous_codigo,
            ]);

        }

        $asisActGuardar = AsistentesActividades::create([
            'acti_codigo' => $request->codigo,
            'asis_codigo' => $asisGuardar,
            'asac_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'asac_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'asac_vigente' => 'S',
            'asac_rut_mod' => Session::get('admin')->usua_rut,
            'asac_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if (!$asisActGuardar) return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al ingresar el participante, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El participante fue ingresado correctamente.']);
    }

    public function ObtenerDirigente(Request $request) {
        $dirigente = Dirigentes::select('diri_nombre', 'diri_apellido')->where('diri_codigo', $request->codigo)->first();
        return json_encode($dirigente);
    }

    public function EliminarParticipante(Request $request) {
        $verificar = Asistentes::where('asis_codigo', $request->codigo)->first();
        if(!$verificar) return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error, el participante no existe en la actividad.']);

        $eliminarAsisActivida = AsistentesActividades::where('asis_codigo', $request->codigo)->delete();
        $eliminarAsistente = Asistentes::where('asis_codigo', $request->codigo)->delete();
        if(!$eliminarAsisActivida && !$eliminarAsistente) return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar el participante de la actividad.']);
        return json_encode(['estado' => true, 'resultado' => 'El participante fue eliminado correctamente de la actividad.']);
    }
}
