<?php
namespace App\Services\Autenticacao;

use App\Models\User;
use Exception;
use App\Models\Responsavel;

class LoginService
{
    public static function fazLogin($email_cpf, $senha){
        
        try {
            $senhaCript = self::criptografaSenha($senha,$senha);
        } catch (\Throwable $th) {
            throw new Exception("Senha incorreta ".$th->getMessage());
            
        }
        $responsavel = Responsavel::where(function ($query) use ($email_cpf) {
            $query->where('email', '=', $email_cpf)
                  ->orWhere('cpf', '=', $email_cpf);
        })->first();
        if (empty($responsavel)){
            throw new Exception("Usuário ou senha incorreta");
        }

        if ($responsavel->senha !== $senhaCript ){
            throw new Exception("Usuário ou senha incorreta(2)");
        }
        $user = new User($responsavel->toArray());
        
        return $user->createToken($responsavel->email)->plainTextToken;
    }

    public static function criptografaSenha(string $senha, string $senha2){
        $senha =  $senha;
        $senha2 =  $senha2;
        if ($senha !== $senha2){
            throw new Exception("As senhas não conferem");
        }
       
        $tamanho = strlen($senha);        
        if ( $tamanho < 8 )  {        
           throw new Exception("As senha deve ter ao menos 8 dígitos!");
        }
    
        // Verifica se a senha contém caracteres especiais
        if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $senha)) {
            throw new Exception("As senha deve ter caracteres especiais");
        }
    
        // Verifica se a senha contém letras minúsculas e maiúsculas
        if (!preg_match('/[a-z]/', $senha) || !preg_match('/[A-Z]/', $senha)) {
            throw new Exception("As senha deve ter letras minúsculas e maiúsculas");
        }
    
        // Verifica se a senha contém números
        if (!preg_match('/[0-9]/', $senha)) {
            throw new Exception("As senha deve ter números");
        }
    
        return md5($senha);
    } 

}