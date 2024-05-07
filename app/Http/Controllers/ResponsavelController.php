<?php

namespace App\Http\Controllers;

use App\Services\Responsavel\ResponsavelService;
use Illuminate\Http\Request;

class ResponsavelController extends Controller
{
    private $responsavelService;

    public function __construct() {
        $this->responsavelService = new ResponsavelService();
    }

    public function index(){
        return $this->responsavelService->getAllResponsaveis();
    }

    public function show(int $idresponsavel){
        return $this->responsavelService->getResponsavelById($idresponsavel);
    }

    public function store(Request $request){
        return $this->responsavelService->novo(
                                                        $request->input("nome"),
                                                        $request->input("email"),
                                                        $request->input("cpf"),
                                                        $request->input("senha"),
                                                        $request->input("senha2"),
                                                        $request->input("telefone")
                                                    );
        
    }

    public function update($idresonsavel, Request $request){
        return $this->responsavelService->atualiza($idresonsavel,
                                                        $request->input("nome"),
                                                        $request->input("email"),
                                                        $request->input("cpf"),
                                                        $request->input("telefone")
                                                    );
        
    }
    public function alterasenha($idresonsavel, Request $request){
        return $this->responsavelService->alteraSenha($idresonsavel,$request->input("senhaatual"),
                                                      $request->input("senha"),
                                                      $request->input("senha2"));
    }


}
