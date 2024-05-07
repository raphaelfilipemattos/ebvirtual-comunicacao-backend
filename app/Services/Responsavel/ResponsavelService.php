<?php
  namespace App\Services\Responsavel;  

  use App\Models\Responsavel;
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
            $senha_ = $this->criptografaSenha($senha,$senha2);

            $responsavel = new Responsavel();
            $responsavel->nome = $nome;
            $responsavel->email = $email;
            $responsavel->senha = $senha_;
            $responsavel->telefone = $telefone;
            $responsavel->cpf = $cpf;
            $responsavel->bloqueado = false;
            return $responsavel->save();
    }

    private function criptografaSenha($senha, $senha2){
        $senha =  $senha;
        $senha2 =  $senha2;
        if ($senha !== $senha2){
            throw new Exception("As senhas não conferem");
        }

        if (strlen($senha) < 8) {
           throw new Exception("As senha deve ter ao menos 8 dígitos");
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
        $senhaCrip = $this->criptografaSenha($senha,$senha2);
        $senhaAtualCrip = $this->criptografaSenha($senhaAtual,$senhaAtual);

        $responsavel = $this->getResponsavelById($idresponsavel);    
        if ($responsavel->senha !== $senhaAtualCrip){
            throw new Exception("A senha atual está incorreta!");
        }

        $responsavel->senha = $senhaCrip;
        return $responsavel->save();
    }


  }