<?php
  namespace App\Services\Responsavel;

use App\Models\Parentesco;
use App\Models\Responsavel;
use App\Models\Responsavel_aluno;
use App\Models\Usuario;
use App\Services\Autenticacao\LoginService;
use Exception;
use Ramsey\Uuid\Type\Integer;

  class ResponsavelService 
  {

    public function getAllResponsaveis(){
        return Responsavel::all();
    }

    public function getResponsavelById($idresponsavel){
        return  Responsavel::whereIdresponsavel($idresponsavel)->first();
    }

    public function novo($nome,$email,$cpf, $senha, $senha2, $telefone){
            $senha_ = LoginService::criptografaSenha($senha,$senha2);

            if (! self::validaCPF($cpf)){
                throw new Exception("Esse CPF é inválido");
            }

            $resposnasvelExiste = Responsavel::where(function ($query) use ($email,$cpf) {
                $query->where('email', '=', $email)
                      ->orWhere('cpf', '=', $cpf);
            })->first();

            if (! empty($resposnasvelExiste)){
                throw new Exception("Já existe um resposável com esse CPF ou e-mail!");
            }

            $responsavel = new Responsavel();
            $responsavel->nome = $nome;
            $responsavel->email = $email;
            $responsavel->senha = $senha_;
            $responsavel->telefone = $telefone;
            $responsavel->cpf = $cpf;
            $responsavel->bloqueado = false;
            return $responsavel->save();
    }

    
    public function atualiza($idresponsavel, $nome,$email,$cpf, $telefone){
        
        $responsavel = $this->getResponsavelById($idresponsavel);
 
        if (! self::validaCPF($cpf)){
            throw new Exception("Esse CPF é inválido");
        }

        $resposnasvelExiste =Responsavel::where('idresponsavel', '<>', $idresponsavel)
                                    ->where(function($query) use ($email, $cpf) {
                                        $query->where('email', $email)
                                            ->orWhere('cpf', $cpf);
                                    })->first();

        if (! empty($resposnasvelExiste)){
            throw new Exception("Já existe outro resposável com esse CPF ou e-mail!");
        }

        if (! empty($nome)){
            $responsavel->nome = $nome;
        }
        if (! empty($email)){
            $responsavel->email = $email;
        }
        if (! empty($telefone)){
            $responsavel->telefone = $telefone;
        }
        if (! empty($cpf)){
            $responsavel->cpf = $cpf;
        }
        return $responsavel->save();
    }

    public function bloqueia($idresponsavel){
        $responsavel = $this->getResponsavelById($idresponsavel);       
        $responsavel->bloqueado = true;
        return $responsavel->save();
    }

    public function alteraSenha($idresponsavel,$senhaAtual, $senha, $senha2){
        $senhaCrip = LoginService::criptografaSenha($senha,$senha2);
        $senhaAtualCrip = LoginService::criptografaSenha($senhaAtual,$senhaAtual);

        $responsavel = $this->getResponsavelById($idresponsavel);    
        if ($responsavel->senha !== $senhaAtualCrip){
            throw new Exception("A senha atual está incorreta!");
        }

        $responsavel->senha = $senhaCrip;
        return $responsavel->save();
    }

    public static function validaCPF($cpf){
         // Extrai somente os números
            $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
            
            // Verifica se foi informado todos os digitos corretamente
            if (strlen($cpf) != 11) {
                return false;
            }

            // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
            if (preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }

            //evita números repetidos
            for($n = 0 ; $n <=9; $n++){
                $cpfMesmoNumero = "";
                for($i =0; $i < 11; $i ++){
                    $cpfMesmoNumero .= $n; 
                }

                if ($cpfMesmoNumero == $cpf) return false;
            }

            // Faz o calculo para validar o CPF
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
            return true;
    }

    public static function listParentesco(){
        return Parentesco::all();
    }
    
    public function vinculaAluno(int $idresponsavel,string $cpf_Aluno, int $idparentesco):bool {

        $aluno = Usuario::where("cpf", '=',$cpf_Aluno)->where("idperfil",'=','5')->first();
        if ( empty($aluno) ){
            throw new Exception("Aluno não encontrado");            
        }

        $responsavel = $this->getResponsavelById($idresponsavel);    
        if ( empty($responsavel) ){
            throw new Exception("Responsável não encontrado");
        }

        $aluno_responsavel = Responsavel_aluno::where("idresponsavel", '=',$idresponsavel)
                                              ->where("idaluno", '=',$aluno->idusuario)->first();
        if ( empty($aluno_responsavel) ){
            $aluno_responsavel = new Responsavel_aluno();
            $aluno_responsavel->idresponsavel = $idresponsavel;
            $aluno_responsavel->idaluno = $aluno->idusuario;
        }         
        
        $aluno_responsavel->idparentesco = $idparentesco;
        return $aluno_responsavel->save();

    }

    public function desvinculaAluno(int $idvinculo):bool {

        $aluno_responsavel = Responsavel_aluno::where("idresponsavel_aluno", '=',$idvinculo);
        if ( ! empty($aluno_responsavel) ){
            return $aluno_responsavel->delete();
        }         
        
        return false;

    }

  }