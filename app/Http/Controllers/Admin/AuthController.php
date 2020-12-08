<?php

namespace LaraDev\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaraDev\Http\Controllers\Controller;
use LaraDev\User;
use function GuzzleHttp\Promise\all;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        if(Auth::check() === true){
            return redirect()->route('admin.home');
        }
        return view('admin.index');
    }

    public function home()
    {
        return view('admin.dashboard');
    }

    public function login(Request $request)
    {
        if (in_array('', $request->only('mail', 'password'))) {
            $json['message'] = $this->message->warning("Informe 'Login' e 'Senha' para efetuar o login!")->render();
            return response()->json($json);
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $json['message'] = $this->message->warning("Informe um email válido!")->render();
            return response()->json($json);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            $json['message'] = $this->message->warning("Email ou Senha não conferem! Por favor verifique e tente novamente.")->render();
            return response()->json($json);
        }

        $this->authenticated($request->getClientIp());
        $json['redirect'] = route('admin.home');
        return response()->json($json);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    private function authenticated(string $ip)
    {
        $user = User::where('id', Auth::user()->id);
        $data = [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $ip,
        ];

        $user->update($data);
    }
}
