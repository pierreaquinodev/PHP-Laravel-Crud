<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables as DataTables;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usuarios = Usuarios::latest()->get();
        
        if($request->ajax()) {
            $data = Usuarios::latest()->get();
            return DataTables::of($data)->addIndexColumn()->addColumn('action', function($row){
                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Editar</a>';
                $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Excluir</a>';
                return $btn;
            })->rawColumns(['action'])->make(true);
        }
        return view('layouts.usuario',compact('usuarios'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Usuarios::updateOrCreate(['id' => $request->id],
                ['data_criacao' => $request->created_at,
                 'nome' => $request->nome,
                 'email' => $request->email,
                 'data' => $request->data,
                 'senha' => $request->senha,
                ]);
        return response()->json(['success'=>'Salvo com sucesso.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuarios = Usuarios::find($id);
        return response()->json($usuarios);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $usuarios = Usuarios::find($id);

        $usuarios->nome = $request->get('nome');
        $usuarios->email = $request->get('email');
        $usuarios->senha = $request->get('senha');
        $usuarios->data_nasc = $request->get('data_nasc');
        $usuarios->data_criacao = date('Y-m-d H:i:s');
        
        $usuarios->save();
        return redirect('/usuarios');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Usuarios::find($id)->delete();
        return response()->json(['success'=>'Excluido com sucesso.']);
    }
}
