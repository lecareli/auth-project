<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function auth(Request $request)
    {
        try
        {
            //Validação de dados
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required',
                'remember' => 'boolean'
            ]);

            //Busca o usuário pelo e-mail
            $user = $this->user->where('email', $request->email)->first();

            // dd($user);

            //Verifica se o usuário existe e se a senha está correta
            if(!$user || !Hash::check($request->password, $user->password))
            {
                return response()->json([
                    'message' => 'As credenciais fornecidas estão incorretas.',
                ], 401); //Retorna status 401 (Unauthorized)
            }

            //Defindo a validade do token
            //2 semanas se habilitar 'lembre-se de mim', senão 2 horas.
            $tokenAbilities = ['*'];
            $expiration = $request->remember ? now()->addWeeks(2) : now()->addHours(2);

            //Cria um token de autenticação
            $token = $user->createToken('auth_token', $tokenAbilities, $expiration)->plainTextToken;

            //Retorna a resposta
            return response()->json([
                'message' => 'Login realizado com sucesso',
                'token' => $token,
                'user' => $user
            ], 200); //Retorna status 200 (OK)

            //1|5HO8zRr4myIbzHnGWQ5BHeG7qHIWLS1O3FSuikQo4e3d85ab
        }
        catch(ValidationException $e)
        {
            //Retorna erros de validação
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422); //Retorna status 422 (Unprocessable Entity)
        }
        catch(\Exception $e)
        {
            //Retorna erros inesperados
            return response()->json([
                'message' => 'Erro ao realizar login',
                'error' => $e->getMessage()
            ], 500); //Retorna status 500 (Internal Server Error)
        }
    }

    public function me(Request $request)
    {
        try
        {
            //Se não houver usuário autenticado, lançar exceção manualmente
            if(!$request->user())
            {
                throw new AuthenticationException();
            }

            return response()->json([
                'user' => $request->user()
            ], 200);
        }
        catch(AuthenticationException $e)
        {
            return response()->json([
                'message' => 'Faça login para continuar'
            ], 401);
        }
        catch(\Exception $e)
        {
            //Retorna erros inesperados
            return response()->json([
                'message' => 'Erro ao buscar usuário autenticado',
                'error' => $e->getMessage()
            ], 500); //Retorna status 500 (Internal Server Error)
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if($status === Password::RESET_LINK_SENT)
        {
            return response()->json([
                'message' => 'Link de redefinição de senha enviado para o e-mail.'
            ], 200);
        }

        return response()->json([
            'message' => 'Erro ao enviar o e-mail'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function($user, $password){
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if($status === Password::PASSWORD_RESET)
        {
            return response()->json([
                'message' => 'Senha redefinida com sucesso'
            ], 200);
        }

        return response()->json([
            'message' => 'Erro ao redefinir a senha',
            'status' => $status
        ], 500);
    }

    public function register(Request $request)
    {
        try
        {
            $validate = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|confirmed',
            ]);

            $user = $this->user->create($validate);

            return response()->json([
                'message' => 'Usuário criado com sucesso',
                'user' => $user,
            ], 200);
        }
        catch(ValidationException $e)
        {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'message' => 'Erro ao registrar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //6|z32IwRbI8ZPeCSiAPCnOftmZn8NUJGeZWcGw0Ni5032ac825
}
