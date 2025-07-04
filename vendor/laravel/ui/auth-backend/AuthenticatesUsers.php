<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use \App\Models\ConfCorreo;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\DB;
trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    /*
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }*/

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $this->credentials($request);


        if (Auth::validate($credentials)) {
            $user = Auth::getProvider()->retrieveByCredentials($credentials);


            $limite = DB::table('configuracion')->value('limite_de_sesiones');


            $sesionesActivas = DB::table('sessions')
                ->where('user_id', $user->id)
                ->count();

            if ($sesionesActivas >= $limite) {
                return back()->withErrors([
                    'email' => 'Has alcanzado el número máximo de sesiones permitidas.',
                ]);
            }

            // Si no ha superado el límite, intentamos login normalmente
            if ($this->guard()->attempt($credentials, $request->filled('remember'))) {

                $user = Auth::user();
                $user->usuario_fecha_ultimo_acceso = now();
                $user->save();

                return $this->sendLoginResponse($request);
            }
        }

        // Si fallan las credenciales
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    /*
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->boolean('remember')
        );
    }
*/
    protected function attemptLogin(Request $request)
    {
        // Intentamos autenticar normalmente
        $credentials = $this->credentials($request);

        if (Auth::validate($credentials)) {
            $user = Auth::getProvider()->retrieveByCredentials($credentials);

            // Obtener el límite desde la configuración
            $limite = DB::table('configuracion')->value('limite_de_sesiones');

            // Contar sesiones activas del usuario
            $sesionesActivas = DB::table('sessions')
                ->where('user_id', $user->id)
                ->count();

            if ($sesionesActivas >= $limite) {
                return false; // No permitir login
            }

            // Si no ha superado el límite, iniciamos sesión
            return Auth::attempt($credentials, $request->filled('remember'));
        }

        return false;
    }
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {


        $conf = ConfCorreo::first();
        if (!$conf) {
            return response()->json(['error' => 'Configuración no encontrada'], 404);
        }


        if (twoFactorGlobalEnabled()) {
            $user->generateTwoFactorCode();
            Mail::to($user->email)->send(new TwoFactorCodeMail($user));

            auth()->logout();
            session(['two_factor' => $user->id]);

            return redirect()->route('verify.index');
        }

        return redirect()->intended('/home');
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
            'limite' => ['Has alcanzado el número máximo de sesiones activas permitidas.'],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {

        $user = Auth::user();
        $user->usuario_fecha_ultimo_acceso = now();
        $user->save();


        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
