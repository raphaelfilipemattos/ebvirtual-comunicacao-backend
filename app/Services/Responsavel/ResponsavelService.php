<?php
  namespace App\Services\Responsavel;  

  use App\Models\Responsavel;
use app\services\Autenticacao\LoginService;
use Exception;

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


  }