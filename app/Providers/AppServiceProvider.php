<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserInterface;
use App\Repositories\UserRepository;

use App\Interfaces\RoleInterface;
use App\Repositories\RoleRepository;
use App\Interfaces\PermisoInterface;
use App\Repositories\PermisoRepository;
use App\Interfaces\MenuInterface;
use App\Repositories\MenuRepository;
use App\Interfaces\CorreoInterface;
use App\Repositories\CorreoRepository;
use App\Interfaces\CatalogoInterface;
use App\Repositories\CatalogoRepository;


use App\Interfaces\AsignacionInterface;
use App\Interfaces\EvaluacionInterface;
use App\Repositories\AsignacionRepository;
use App\Repositories\PreguntasRepository;
use App\Interfaces\PreguntasInterface;
use App\Interfaces\RespuestasInterface;
use App\Interfaces\VerificaInterface;
use App\Interfaces\ParalelosInterface;

use App\Interfaces\HorariosInterface;
use App\Interfaces\NotificationInterface;
use App\Interfaces\TareasInterface;

use App\Repositories\TareasRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\HorariosRepository;

use App\Repositories\ParalelosRepository;
use App\Repositories\EvaluacionRepository;
use App\Repositories\RespuestasRepository;
use App\Repositories\vRegistrosRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CatalogoInterface::class, CatalogoRepository::class);
        $this->app->bind(MenuInterface::class, MenuRepository::class);
        $this->app->bind(CorreoInterface::class, CorreoRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(RoleInterface::class, RoleRepository::class);
        $this->app->bind(PermisoInterface::class, PermisoRepository::class);

        $this->app->bind(AsignacionInterface::class, AsignacionRepository::class);
        $this->app->bind(PreguntasInterface::class, PreguntasRepository::class);
        $this->app->bind(RespuestasInterface::class, RespuestasRepository::class);
        $this->app->bind(EvaluacionInterface::class, EvaluacionRepository::class);
        $this->app->bind(VerificaInterface::class, vRegistrosRepository::class);
        $this->app->bind(ParalelosInterface::class, ParalelosRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(HorariosInterface::class, HorariosRepository::class);
        $this->app->bind(NotificationInterface::class, NotificationRepository::class);
        $this->app->bind(TareasInterface::class, TareasRepository::class);
        $this->app->bind(CorreoInterface::class, CorreoRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
