<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ConfCorreoController;
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ConfiguracionCredencialesController;

use App\Http\Controllers\PruebaWhatsapp;

use App\Http\Controllers\DocumentosUsuarioController;
//logica negocio
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\DescargaController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\TemaController;

use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\ContenidoController;
use App\Http\Controllers\VerificaRegistroController;
use App\Http\Controllers\PreguntasController;
use App\Http\Controllers\RespuestasPreguntaController;
use App\Http\Controllers\MetodosPagoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ParaleloController;
use App\Http\Controllers\ApoderadoController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\AsistenciaEstudianteController;
use App\Http\Controllers\TipoPagoController;
use App\Http\Controllers\UserPersonalizacionController;
use App\Http\Controllers\WelcomeController;
use App\Models\UserPersonalizacion;

Route::get('/', function () {

});

Route::get('/', [WelcomeController::class, 'index'])->name('home');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::middleware(['auth', 'can:Administración de Usuarios', 'check.password.age'])->group(function () {

    Route::get('/usuarios', [UserController::class, 'index'])
        ->name('users.index')
        ->middleware('can:usuarios.ver');

    Route::get('/usuarios/crear', [UserController::class, 'create'])
        ->name('users.create')
        ->middleware('can:usuarios.crear');

    Route::post('/usuarios', [UserController::class, 'store'])
        ->name('users.store')
        ->middleware('can:usuarios.crear');

    Route::get('/usuarios/{user}', [UserController::class, 'show'])
        ->name('users.show')
        ->middleware('can:usuarios.ver');

    Route::get('/usuarios/edit/{id}', [UserController::class, 'edit'])
        ->name('users.edit')
        ->middleware('can:usuarios.editar');

    Route::put('/usuarios/{id}/{perfil}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('can:usuarios.editar');

    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('can:usuarios.eliminar');

    Route::get('/datos/usuario/{id}', [UserController::class, 'GetUsuario'])
        ->name('users.get')
        ->middleware('can:usuarios.ver');

    Route::get('/usuarios/exportar/excel', [UserController::class, 'exportExcel'])->name('usuarios.exportar_excel')->middleware(middleware: 'can:usuarios.exportar_excel');
    Route::get('/usuarios/exportar/pdf', [UserController::class, 'exportPDF'])->name('usuarios.exportar_pdf')->middleware('can:usuarios.exportar_pdf');


});



//Rutas para secciones
Route::resource('secciones', SeccionController::class)->except([
    'show',
])->middleware(['auth', 'role:admin', 'check.password.age']);
Route::post('/secciones/ordenar', [SeccionController::class, 'ordenar'])->name('secciones.ordenar');


Route::post('/api/sugerir-icono', [SeccionController::class, 'SugerirIcono']);

Route::post('obtener/dato/menu', [SeccionController::class, 'cambiarSeccion'])->middleware(['auth', 'role:admin']);
//Rutas para Menus
Route::resource('menus', MenuController::class)->except([
    'show',
])->middleware(['auth', 'role:admin', 'check.password.age']);


// Rutas para la configuracion de correo

Route::middleware(['auth', 'can:Configuración', 'check.password.age'])->group(function () {

    Route::get('/configuracion/correo', [ConfCorreoController::class, 'index'])
        ->name('configuracion.correo.index')
        ->middleware('can:configuracion_correo.ver');

    Route::post('/configuracion/correo/guardar', [ConfCorreoController::class, 'store'])
        ->name('configuracion.correo.store')
        ->middleware('can:configuracion_correo.actualizar');

    Route::get('/correo/prueba', [ConfCorreoController::class, 'enviarPrueba'])
        ->name('correo.prueba')
        ->middleware('can:correo.envio_prueba');

    Route::get('/correos/plantillas', [CorreoController::class, 'index'])
        ->name('correos.index')
        ->middleware('can:plantillas.ver');

    Route::put('/editar/plantilla/{id}', [CorreoController::class, 'update_plantilla'])
        ->name('plantilla.update')
        ->middleware('can:plantillas.actualizar');

    Route::get('/obtener/plantilla/{id}', [CorreoController::class, 'GetPlantilla'])
        ->name('obtener.correo');

});

//cambio de contraseña
Route::middleware(['auth'])->group(function () {

    Route::get('/usuario/contraseña', [PasswordController::class, 'ActualizarContraseña'])->name('user.actualizar.contraseña');
    Route::put('password/update', [PasswordController::class, 'update'])->name('password.actualizar');

    Route::get('/usuario/perfil', [UserController::class, 'Perfil'])
        ->name('perfil');

    Route::post('/perfil/documentos', [DocumentosUsuarioController::class, 'subirDesdePerfil'])
        ->name('perfil.documentos.subir');
});



Route::middleware(['auth', 'role:admin', 'check.password.age'])->group(function () {

    Route::get('/roles', [RoleController::class, 'index'])
        ->name('roles.index')
        ->middleware('can:roles.inicio');

    Route::get('/roles/create', [RoleController::class, 'create'])
        ->name('roles.create')
        ->middleware('can:roles.crear');

    Route::post('/roles', [RoleController::class, 'store'])
        ->name('roles.store')
        ->middleware('can:roles.guardar');

    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])
        ->name('roles.edit')
        ->middleware('can:roles.editar');

    Route::put('/roles/{id}', [RoleController::class, 'update'])
        ->name('roles.update')
        ->middleware('can:roles.actualizar');

    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])
        ->name('roles.destroy')
        ->middleware('can:roles.eliminar');

    Route::get('/permissions', [PermissionController::class, 'index'])
        ->name('permissions.index')
        ->middleware('can:permisos.inicio');

    Route::get('/permissions/create', [PermissionController::class, 'create'])
        ->name('permissions.create')
        ->middleware('can:permisos.crear');

    Route::post('/permissions', [PermissionController::class, 'store'])
        ->name('permissions.store')
        ->middleware('can:permisos.guardar');

    Route::get('/permissions/edit/{id}', [PermissionController::class, 'edit'])
        ->name('permissions.edit')
        ->middleware('can:permisos.editar');

    Route::put('/permissions/{id}', [PermissionController::class, 'update'])
        ->name('permissions.update')
        ->middleware('can:permisos.actualizar');

    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])
        ->name('permissions.destroy')
        ->middleware('can:permisos.eliminar');

    Route::get('/permissions/cargar/menu/{id}/{rol_id}', [RoleController::class, 'get_permisos_menu'])
        ->name('permissions.menu');

});




//Rutas configuracion general

Route::middleware(['auth', 'role:admin', 'can:Configuración General', 'check.password.age'])->group(function () {

    Route::get('/admin/configuracion', [ConfiguracionController::class, 'edit'])
        ->name('admin.configuracion.edit')
        ->middleware('can:configuracion.inicio');

    Route::put('/admin/configuracion', [ConfiguracionController::class, 'update'])
        ->name('admin.configuracion.update')
        ->middleware('can:configuracion.actualizar');

});

Route::middleware(['auth', 'role:admin', 'check.password.age'])->group(function () {

    Route::get('/configuracion/credenciales', [ConfiguracionCredencialesController::class, 'index'])->name('configuracion.credenciales.index')->middleware('can:configuracion.credenciales_ver');
    Route::post('/configuracion/credenciales/actualizar', [ConfiguracionCredencialesController::class, 'actualizar'])->name('configuracion.credenciales.actualizar')->middleware('can:configuracion.credenciales_actualizar');

});


//doble factor de autenticacion
Route::get('/2fa/verify', [TwoFactorController::class, 'index'])->name('verify.index');
Route::post('/2fa/verify', [TwoFactorController::class, 'store'])->name('verify.store');
Route::post('/2fa/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');

//Catalogo


Route::middleware(['auth', 'role:admin', 'can:Configuración General', 'check.password.age'])->group(function () {

});
Route::middleware(['auth', 'can:Administración y Parametrización', 'check.password.age'])->group(function () {

    // Rutas para catalogos
    Route::get('/catalogos', [CatalogoController::class, 'index'])->name('catalogos.index')->middleware('can:catalogo.ver');
    Route::get('/catalogos/create', [CatalogoController::class, 'create'])->name('catalogos.create')->middleware('can:catalogo.crear');
    Route::post('/catalogos', [CatalogoController::class, 'store'])->name('catalogos.store')->middleware('can:catalogo.guardar');
    Route::get('/catalogos/{id}', [CatalogoController::class, 'show'])->name('catalogos.show')->middleware('can:catalogo.ver_detalle');
    Route::get('/catalogos/{id}/edit', [CatalogoController::class, 'edit'])->name('catalogos.edit')->middleware('can:catalogo.editar');
    Route::put('/catalogos/{id}', [CatalogoController::class, 'update'])->name('catalogos.update')->middleware('can:catalogo.actualizar');
    Route::delete('/catalogos/{id}', [CatalogoController::class, 'destroy'])->name('catalogos.destroy')->middleware('can:catalogo.eliminar');

    // Rutas para categorias
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index')->middleware('can:categoria.ver');
    Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create')->middleware('can:categoria.crear');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store')->middleware('can:categoria.guardar');
    Route::get('/categorias/{id}', [CategoriaController::class, 'show'])->name('categorias.show')->middleware('can:categoria.ver_detalle');
    Route::get('/categorias/{id}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit')->middleware('can:categoria.editar');
    Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update')->middleware('can:categoria.actualizar');
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy')->middleware('can:categoria.eliminar');
});


Route::get('prueba', [PruebaWhatsapp::class, 'prueba'])->name('prueba.wa');


Route::post('/usuario/configuracion', [UserController::class, 'guardarConfiguracion'])->middleware('auth');







//--------------------- Logica de negocio -------------------------------------------------------------

// Cursos
Route::middleware(['auth', 'check.password.age'])->group(function () {


    Route::get('materias/ver', [AsignacionController::class, 'index'])->name('asignacion.index')->middleware('can:asignacion.ver');
    Route::get('materias/crea', [AsignacionController::class, 'create'])->name('asignacion.create')->middleware('can:asignacion.crear');
    Route::get('materias/editar/{id}', [AsignacionController::class, 'edit'])->name('asignacion.edit')->middleware('can:asignacion.editar');
    Route::put('materias/guardar', [AsignacionController::class, 'update'])->name('asignacion.guardar')->middleware('can:asignacion.actualizar');
    Route::post('materias/crear', [AsignacionController::class, 'store'])->name('asignacion.store')->middleware('can:asignacion.guardar');
    Route::get('materias/vermat', [AsignacionController::class, 'showJ'])->name('asignacion.showJ')->middleware('can:asignacion.ver_materias');
    Route::delete('materias/asignacion/{id}', [AsignacionController::class, 'destroy'])->name('asignacion.delete')->middleware('can:asignacion.eliminar');
});

Route::get('/mails/filtra', [CorreoController::class, 'filtraIndex'])->name('emails.index')->middleware('can:email.ver');
;

// Ruta para mostrar el formulario de filtro
Route::get('/mails/filter', [CorreoController::class, 'showFilterForm'])->name('mails.filter.form');
// Ruta para procesar el filtro y devolver los resultados en JSON
Route::get('/mails/results', [CorreoController::class, 'getMails'])->name('mails.filter');
Route::get('/mails/dat', [CorreoController::class, 'prueba']);

Route::post('/add-email', [CorreoController::class, 'addEmail'])->name('add.email');
Route::post('/filter-mails', [CorreoController::class, 'filterMails'])->name('filter.mails');



// Registro de cambios
Route::group(['prefix' => '/registro', 'middleware' => 'auth'], function () {
    Route::get('/estudiantes', [VerificaRegistroController::class, 'storeEstudiante'])->name('cambioEstudiantes')->middleware('can:configuracion.estado_registros_pagina_principal');
    Route::get('/profesores', [VerificaRegistroController::class, 'storeProfesor'])->name('cambioProfesores')->middleware('can:configuracion.estado_registros_pagina_principal');
    Route::get('/admin', [VerificaRegistroController::class, 'storeAdmin'])->name('cambioAdmin')->middleware('can:configuracion.estado_registros_pagina_principal');
});

// Módulos
Route::group(['prefix' => '/modulos', 'middleware' => 'auth'], function () {
    Route::get('/ver/{id_a}', [ModuloController::class, 'index'])->name('modulos.materia.show')->middleware('can:modulos.ver');
    Route::get('/editar', [ModuloController::class, 'edit'])->name('modulos.materia.edit')->middleware('can:modulos.editar');
    Route::put('/editado', [ModuloController::class, 'update'])->name('modulos.materia.guardar')->middleware('can:modulos.actualizar');
    Route::delete('/eliminar', [ModuloController::class, 'delete'])->name('modulos.materia.delete')->middleware('can:modulos.eliminar');
    Route::post('/crear', [ModuloController::class, 'create'])->name('modulos.create')->middleware('can:modulos.crear');
    Route::post('/guardar', [ModuloController::class, 'store'])->name('modulo.store')->middleware('can:modulos.guardar');
    Route::get('/asignar/aprobados/{id_pm}/{id_m}', [ModuloController::class, 'AsignarEstudiantesAprobados'])->name('asignar.aprobados')->middleware('can:modulos.asignar_aprobados_automatico');
    Route::get('/asignar/aprobados/{id}/{id_m}/{id_p}', [ModuloController::class, 'AsignarEstudiantesIndividual'])->name('asignacion.individual')->middleware('can:modulos.asignar_aprobados_manual');

    Route::get('/genera/certificado/{user_id}/{id_a}', [ModuloController::class, 'GenerarCertificado'])->name('generar_certificados')->middleware('can:modulos.generar_certificado');
    Route::get('/genera/certificado/seguro/{user_id}/{id_a}', [ModuloController::class, 'GenerarCertificado_seguro'])->name('generar_certificados_seguro');

});

// Temas

Route::group(['prefix' => '/tema', 'middleware' => 'auth'], function () {
    Route::get('/admin/{id_a}/{id_m}/{id_p}', [TemaController::class, 'admin'])->name('modulos.temas.admin')->middleware('can:modulos.modulos.temas_administrar');
    Route::get('/contenido/{id}', [TemaController::class, 'Temacontenido'])->name('tema.ver')->middleware('can:modulos.modulos.temas_contenido');
    Route::get('/ver/{id_pm}/{id_m}', [TemaController::class, 'show'])->name('modulos.temas.show')->middleware('can:modulos.modulos.temas_detalles');
    Route::get('/finalizar/{id_pm}', [TemaController::class, 'finalizar'])->name('modulos.temas.finalizar')->middleware('can:modulos.modulos.temas_finalizar');
    Route::get('/editar', [TemaController::class, 'edit'])->name('modulos.temas.edit')->middleware('can:modulos.modulos.temas_editar');
    Route::put('/editado', [TemaController::class, 'update'])->name('modulos.temas.guardar')->middleware('can:modulos.modulos.temas_actualizar');
    Route::delete('/eliminar', [TemaController::class, 'delete'])->name('modulos.temas.delete')->middleware('can:modulos.modulos.temas_eliminar');
    Route::get('/crear/{id_m}', [TemaController::class, 'create'])->name('temas.create')->middleware('can:modulos.modulos.temas_crear');
    Route::post('/guardar', [TemaController::class, 'store'])->name('temas.store')->middleware('can:modulos.modulos.temas_guardar');
    Route::get('/contenidos/{id_t}/{id_pm}', [TemaController::class, 'storeTemasContenidos'])->name('temas.contenidos.store')->middleware('can:modulos.modulos.temas_guardar_contenido');
    Route::get('/obtener/{id_m}', [TemaController::class, 'obtenerTemas'])->name('temas.obtener')->middleware('can:modulos.modulos.temas_obtener');
    Route::delete('/tema/eliminar/{id}', [TemaController::class, 'eliminarTema'])->name('eliminar.tema')->middleware('can:modulos.modulos.temas_eliminar_contenido');
});

Route::post('/crear/asistencia', [UserController::class, 'Generar_asistencia'])->name('asistencia.generar')->middleware('can:asistencia.generar');
Route::get('/lista_asistencia/{id_pm}', [AsistenciaEstudianteController::class, 'show'])->name('lista.asistencia')->middleware('can:asistencia.ver_detalle');
Route::put('/registra/asistencia/{id_pm}', [AsistenciaEstudianteController::class, 'registrar_asistencia'])->name('asistencia.editar')->middleware('can:asistencia.editar');

// Estudiantes  permisos
Route::group(['prefix' => '/estudiantes', 'middleware' => 'auth'], function () {
    Route::get('/asignar/{id}/{id_m}/{id_p}', [UserController::class, 'Asignar'])->name('estudiante.asignar.paralelo')->middleware('can:estudiante.asignar_paralelo');
    Route::get('/inactivos/ver/{UserId}', [UserController::class, 'EstudiantesInactivos'])->name('estudiantesinactivos.show')->middleware('can:estudiante.ver_inactivos');
    Route::get('/detalle/{id}/{id_m}', [UserController::class, 'detalleEstudiante'])->name('estudiante.detalle')->middleware('can:estudiante.ver_detalle');
    Route::get('/{id}/pagos', [UserController::class, 'EstudiantesMatPagos'])->name('estudiantes.pagos.materias')->middleware('can:estudiante.pagos_materias');
    Route::get('/cambiarEstado/{id}', [UserController::class, 'cambiarestado'])->name('cambiarestado')->middleware('can:estudiante.cambiar_estado');
    Route::get('/detalle/{id}/{id_m}/{id_p}', [UserController::class, 'EstudianteReporte'])->name('estudiante.reporte');
    Route::get('/{id}/ver', [UserController::class, 'estudentShow'])->name('estudiante.show');

    Route::get('/verE/{id_a}/{id_p}', [UserController::class, 'ver'])->name('studiantes.ver');
});

// Inscripciones
Route::group(['prefix' => '/inscripcion', 'middleware' => 'auth'], function () {
    Route::get('/s', [AsignacionController::class, 'showI'])->name('inscripciones.index');
    Route::get('/pago', [AsignacionController::class, 'inscripcionpago'])->name('inscripcionpago.store');
    Route::get('/M', [AsignacionController::class, 'inscripcion'])->name('inscripcionModal.show');

    Route::get('/estudiante', [AsignacionController::class, 'inscripcion_estudiante'])->name('inscripcion.index')->middleware('can:inscripcion.inscribir_estudiante');
    Route::post('/completar', [AsignacionController::class, 'inscripcion_adm'])->name('inscripcion_adm.store')->middleware('can:inscripcion.guardar_inscripcion_estudiante');

});

// Contenido
Route::group(['prefix' => '/contenido', 'middleware' => 'auth'], function () {
    Route::post('/Creadocumentos', [ContenidoController::class, 'storeDocumento'])->name('documentos.store');
    Route::post('/CreaVideos', [ContenidoController::class, 'storeVideo'])->name('Videos.store');
    Route::post('/CreadoEnlaces', [ContenidoController::class, 'storeenlace'])->name('enlaces.store');
    Route::get('/Crear/{id_t}', [ContenidoController::class, 'create'])->name('contenido.create');

    Route::post('/archivos', [ContenidoController::class, 'store'])->name('archivo.store');
    Route::delete('/{id}', [ContenidoController::class, 'destroy'])->name('eliminar.contenido')->middleware('can:contenido.contenido_tema_eliminar');
});

// Descargas
Route::group(['prefix' => '/descargar', 'middleware' => 'auth'], function () {
    Route::get('/documento/{id}', [DescargaController::class, 'descargarDocumento'])->name('descargar.documento');
    Route::get('/video/{id}', [DescargaController::class, 'descargarVideo'])->name('descargar.video');
});

// Examenes  
Route::group(['prefix' => '/Evaluacion', 'middleware' => 'auth'], function () {
    Route::get('/ver/{id_a}/{id_m}', [EvaluacionController::class, 'show'])->name('evaluacion.show');
    Route::get('/editar', [EvaluacionController::class, 'edit'])->name('evaluacion.edit');
    Route::put('/guardar', [EvaluacionController::class, 'update'])->name('evaluacion.guardar');
    Route::delete('/eliminar/{evaluacion}/{id_pm}/{id_m}', [EvaluacionController::class, 'delete'])->name('evaluacion.delete');
    Route::get('/crear/{id_e}/{id_pm}/{id_m}', [EvaluacionController::class, 'create'])->name('evaluacion.create');
    Route::post('/guardar', [EvaluacionController::class, 'store'])->name('evaluacion.store');
    Route::get('/estudiantes/{id_pm}/{id_a}', [EvaluacionController::class, 'estudianteseval'])->name('evaluacion.estudiantes');
    Route::get('/Revision/{id}/{id_e}', [EvaluacionController::class, 'Revision'])->name('evaluacion.revision');
    Route::get('/publicar/{id_e}', [EvaluacionController::class, 'publicar'])->name('evaluacion.publicar');



});

// Preguntas
Route::group(['prefix' => '/preguntas', 'middleware' => 'auth'], function () {
    Route::get('/lista/{id_e}', [PreguntasController::class, 'list'])->name('preguntas.list');
    Route::get('/crear', [PreguntasController::class, 'create'])->name('preguntas.create');
    Route::post('/crear', [PreguntasController::class, 'store'])->name('preguntas.store');
    Route::delete('/eliminar/{id}', [PreguntasController::class, 'destroy'])->name('preguntas.destroy');
});

// Respuestas
Route::post('/respuestas/store/{id_pm}/{id_m}', [RespuestasPreguntaController::class, 'store'])->name('respuestas.store');
Route::get('/respuestas/estudiante/{id_e}', [RespuestasPreguntaController::class, 'listarPreguntasRespuestas'])->name('listarExamen');
Route::get('/notas/{id_a}/{id_m}/{id_p}', [EvaluacionController::class, 'notasEstudiantes'])->name('notasEstudiantes.ver');
Route::get('/incorrecta/{id_u}/{id_p}', [EvaluacionController::class, 'respIncorrecta'])->name('respuestaparrafo.incorrecta');
Route::get('/correcta/{id_u}/{id_p}/{id_e}', [EvaluacionController::class, 'respCorrecta'])->name('respuestaparrafo.correcta');
//configuracion 

Route::group(['prefix' => '/configuracion', 'middleware' => 'auth'], function () {
    Route::get('/inicio', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::get('/ver/{id}', [ConfiguracionController::class, 'show'])->name('configuracion.show');
    Route::get('/editar', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
    Route::put('/guardar', [ConfiguracionController::class, 'update'])->name('configuracion.guardar');
    Route::delete('/eliminar', [ConfiguracionController::class, 'delete'])->name('configuracion.delete');
    Route::post('/crear', [ConfiguracionController::class, 'create'])->name('configuracion.create');
    Route::post('/guardar', [ConfiguracionController::class, 'store'])->name('configuracion.store');
});
//pagos
Route::group(['prefix' => '/Pago', 'middleware' => 'auth'], function () {
    Route::get('/inicio', [PagoController::class, 'index'])->name('Pago.index');
    Route::get('/ver/{id}', [PagoController::class, 'show'])->name('Pago.show');
    Route::get('/editar', [PagoController::class, 'edit'])->name('Pago.edit');
    Route::put('/guardar', [PagoController::class, 'update'])->name('Pago.guardar');
    Route::delete('/eliminar', [PagoController::class, 'delete'])->name('Pago.delete');
    Route::post('/crear', [PagoController::class, 'create'])->name('Pago.create');
    Route::post('/guardar', [PagoController::class, 'store'])->name('Pago.store');

    Route::get('/pendiente', [PagoController::class, 'PagoPendiente_noti'])->name('Pago.pendiente.index');
    Route::get('/pendientes', [PagoController::class, 'PagoPendiente'])->name('Pago.pendientes');

    Route::get('/reintento/{id}', [PagoController::class, 'PagoPendiente'])->name('pago.reintento');

});

//paralelos
Route::group([
    'prefix' => '/Paralelo',
    'middleware' => ['auth', 'role:admin|profesor']
], function () {
    Route::get('/inicio', [ParaleloController::class, 'index'])->name('Paralelos.index');
    Route::get('/horarios', [ParaleloController::class, 'horario'])->name('Paralelos.horarios');
    Route::get('/ver/{id}', [ParaleloController::class, 'show'])->name('Paralelos.show');
    Route::get('/editar/{id}', [ParaleloController::class, 'edit'])->name('Paralelos.edit');
    Route::post('/guardar/{id}', [ParaleloController::class, 'update'])->name('Paralelos.update');
    Route::delete('/eliminar/{id}', [ParaleloController::class, 'destroy'])->name('Paralelos.delete');
    Route::post('/guardar', [ParaleloController::class, 'store'])->name('Paralelos.store');
    Route::get('/Modulos/{id_a}/{id_m}', [ParaleloController::class, 'ShowParelelosModulos'])->name('Paralelos.modulos.show');
    Route::post('/Horario', [ParaleloController::class, 'storeParaleloModulo'])->name('paraleloModulo.store');
    Route::get('/horario/editar/{id}/{id_a}/{id_m}', [ParaleloController::class, 'editParaleloModulo'])->name('ParaleloHorario.edit');
    Route::post('/horario/guardar/{id}', [ParaleloController::class, 'updateParaleloModulo'])->name('ParaleloModulo.update');
    Route::delete('/eliminar/para_mod/{id}/{id_a}/{id_m}', [ParaleloController::class, 'destroy_para_mod'])->name('Paralelo_modulo.delete');
});
Route::group([

    'middleware' => ['auth']
], function () {
    Route::get('/get/{nombre}/{id_a}/{id_p}', [ParaleloController::class, 'GetParalelo'])->name('Paralelo.get');

});

//aporedados
Route::prefix('apoderados')->group(function () {
    //Route::get('/', [ApoderadoController::class, 'indexS'])->name('apoderados.index');
    Route::get('/ver', [ApoderadoController::class, 'verE'])->name('apoderados.index');
    Route::get('/create', [ApoderadoController::class, 'create'])->name('apoderados.create');
    Route::post('/', [ApoderadoController::class, 'store'])->name('apoderados.store');
    Route::get('/{apoderado}/edit', [ApoderadoController::class, 'edit'])->name('apoderados.edit');
    Route::put('/{apoderado}', [ApoderadoController::class, 'update'])->name('apoderados.update');
    Route::delete('/{apoderado}', [ApoderadoController::class, 'destroy'])->name('apoderados.destroy');
});

Route::get('/metodos-pago', [MetodosPagoController::class, 'index']);


Route::put('/metodos-pago/{id}', [MetodosPagoController::class, 'update']);


Route::post('/metodos/store', [MetodosPagoController::class, 'store']);
//notificaciones


Route::get('/notification/{notification}/markAsRead', [NotificationController::class, 'markAsRead'])->name('notification.markAsRead');


// Email
Route::get('/fetch-emails', [EmailController::class, 'fetchUnreadEmailsFromBCP'])->name('fetch.emails');


//aporedados

Route::prefix('personal')->group(function () {
    Route::get('/profesores', [ProfesorController::class, 'index'])->name('profesores.index');
    Route::get('/', [ProfesorController::class, 'indexS'])->name('profesores.indexS');
    Route::post('/profesion', [ProfesorController::class, 'storeProfesion'])->name('profesores.profesion.store');
    Route::post('/experiencia', [ProfesorController::class, 'storeExperiencia'])->name('profesores.experiencia.store');
    Route::post('/mensaje', [ProfesorController::class, 'storeMensaje'])->name('profesores.mensaje.store');

    Route::get('/{Profesor}/edit', [ProfesorController::class, 'edit'])->name('profesores.edit');
    Route::put('/{Profesor}', [ProfesorController::class, 'update'])->name('profesores.update');
    Route::delete('/{Profesor}', [ProfesorController::class, 'destroy'])->name('profesores.destroy');
    Route::get('horarios', [ProfesorController::class, 'horarios'])->name('profesores.horarios.index');


});


Route::prefix('Reporte')->group(function () {
    Route::post('/Estudiante/{id}/{id_m}/{id_p}', [PdfController::class, 'generarReporteEstudiante'])->name('reporte.estudiante');
    Route::get('/Horario', [PdfController::class, 'reporte_horarios'])->name('reporte.horarios');

});
//tareas
Route::prefix('tareas')->group(function () {
    Route::get('/', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('/create', [TareaController::class, 'create'])->name('tareas.create');
    Route::post('/', [TareaController::class, 'store'])->name('tareas.store');
    Route::get('/profesor/{id_pm}', [TareaController::class, 'showP'])->name('tareas.showP');
    Route::get('/estudiante/{id_pm}', [TareaController::class, 'showE'])->name('tareas.showE');
    Route::get('/{tarea}/edit', [TareaController::class, 'edit'])->name('tareas.edit');
    Route::put('/{tarea}', [TareaController::class, 'update'])->name('tareas.update');
    Route::delete('/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy');
    Route::post('/calificar/{id}', [TareaController::class, 'calificar'])->name('calificar');

    Route::post('/subir', [TareaController::class, 'storeTarea'])->name('tareas.store.estudiante');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//pagos

Route::group([

    'middleware' => ['auth', 'role:admin']
], function () {
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/auditoria/{id}', [PagoController::class, 'auditoria'])->name('auditoria.index');
    Route::get('/pagos/rechazo/{id}', [PagoController::class, 'pago_rechazo'])->name('pago.rechazo');

});
Route::post('/pago/estudiante', [PagoController::class, 'store_pago'])->name('admpagos.store');

Route::get('/pagos/detalle/{id}', [PagoController::class, 'detalle'])->name('admpagos.detalle');
Route::get('/datos/cuentas/{cuenta}', [PagoController::class, 'datos_cuenta'])->name('admpagos.detalle');

Route::delete('/estudiantes/{id}', [UserController::class, 'destroy'])->name('estudiante.destroy');



Route::get('/tipos_pagos', [TipoPagoController::class, 'index'])->name('tipos_pagos.index');


Route::post('/tipos_pagos', [TipoPagoController::class, 'store'])->name('tipos_pagos.store');
Route::get('/tipos_pago/estado/{id}', [TipoPagoController::class, 'estado'])->name('tipo_pago.estado');

Route::delete('/tipo_pago/{id}', [TipoPagoController::class, 'destroy'])->name('tipo_pago.destroy');



Route::get('/pago_automatico/{pago_id}', [PagoController::class, 'PagoAutomatico']);


Route::get('/editar/perfil', [UserController::class, 'Perfil'])->name('editar.perfil');
// web.php
Route::put('/perfil', [UserController::class, 'update_perfil'])->name('perfil.update');
Route::put('/perfil/password', [UserController::class, 'updatePassword'])->name('perfil.updatePassword');
Route::put('/perfil/actualizar-foto', [UserController::class, 'updatePhoto'])->name('perfil.updateFoto');


Route::post('/guardar-color-sidebar', [UserPersonalizacionController::class, 'guardarSidebarColor'])->middleware('auth');
// routes/web.php
Route::post('/user/personalizacion/sidebar-type', [UserPersonalizacionController::class, 'updateSidebarType'])->middleware('auth');
Route::post('/user/preferences', [UserPersonalizacionController::class, 'updateDark'])->middleware('auth');
