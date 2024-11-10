<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10); // Paginar resultados
        return response()->json($users);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'uid' => 'required|string|unique:users',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cc' => 'required|string|max:20',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:users',
            'saldo_acumulado' => 'nullable|numeric|min:0',
        ]);

        $user = User::create([
            'uid' => $validatedData['uid'],
            'nombre' => $validatedData['nombre'],
            'apellido' => $validatedData['apellido'],
            'cc' => $validatedData['cc'],
            'telefono' => $validatedData['telefono'],
            'email' => $validatedData['email'],
            'saldo_acumulado' => $validatedData['saldo_acumulado'] ?? 0,
        ]);

        return response()->json(['message' => 'Usuario creado exitosamente.', 'user' => $user], 201);
    }



    /**
     * Filter by CC
     */
    public function filterByCC(Request $request)
    {
        $validatedData = $request->validate([
            'cc' => 'required|string|max:20',
        ]);

        $user = User::where('cc', $validatedData['cc'])->first();

        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }
    }

    /**
     * Filter by UID.
     */
    public function filterByUID(Request $request)
    {
        $validatedData = $request->validate([
            'uid' => 'required|string',
        ]);

        $user = User::where('uid', $validatedData['uid'])->first();

        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cc)
    {
        // Buscar el usuario por "cc"
        $user = User::where('cc', $cc)->firstOrFail();

        // Validar los datos recibidos
        $validatedData = $request->validate([
            'uid' => 'required|string|unique:users,uid,' . $user->id, // Validar el UID pero excluir el actual usuario
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cc' => 'required|string|max:20', // Puedes quitar esta validación si no quieres actualizar el "cc"
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id, // Validar el email pero excluir el actual usuario
            'saldo_acumulado' => 'nullable|numeric|min:0',
        ]);

        // Actualizar los datos del usuario
        $user->update($validatedData);

        // Retornar una respuesta exitosa
        return response()->json(['message' => 'Usuario actualizado exitosamente.', 'user' => $user]);
    }




    /**
     * Update saldo_acumulado for the specified user.
     */
    public function updateSaldoAcumulado(Request $request, $uid)
    {
        // Validar el saldo adicional
        $validatedData = $request->validate([
            'saldo_adicional' => 'required|numeric|min:0',
        ]);

        // Buscar al usuario por ID
        $user = User::where('uid', $uid)->first();

        // Sumar el saldo adicional al saldo actual
        $user->saldo_acumulado += $validatedData['saldo_adicional'];

        // Guardar los cambios
        $user->save();

        // Retornar la respuesta con el nuevo saldo
        return response()->json([
            'message' => 'Saldo actualizado exitosamente.',
            'saldo_acumulado' => $user->saldo_acumulado,
        ]);
    }


    /**
     * Search user by UID or CC.
     */
    public function searchByUIDOrCC(Request $request)
    {
        // Validamos el input
        $validatedData = $request->validate([
            'query' => 'required|string',
        ]);

        $query = $validatedData['query'];

        // Buscamos por UID o por CC
        $user = User::where('uid', $query)
            ->orWhere('cc', $query)
            ->first();

        // Verificamos si se encontró el usuario
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
