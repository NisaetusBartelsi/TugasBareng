<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function create(Request $request)
    {
        $data = Barang::create([
            'nama_barang' => $request->nama_barang,
            'jumlah_barang' => $request->jumlah_barang,
            'harga_barang' => $request->harga_barang,
            'id_user' => $request->user()->id,
        ]);
        return response()->json(['message' => 'Succes to Adding', 'barang' => $data]);
    }
    public function read()
    {
        $user = auth('sanctum')->user();

        // Mendapatkan data post dan user terkait hanya untuk user yang sedang login
        $posts = Barang::with('User')->where('id_user', $user->id)->get();

        return response()->json([
            'data' => $posts
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = auth('sanctum')->user();
        $post = Barang::where('id', $id)->where('id_user', $user->id)->first();
        if (!$post) {
            return response()->json([
                'message' => 'User Belum Login'
            ], 404);
        }

        $post->nama_barang = $request->input('nama_barang');
        $post->jumlah_barang = $request->input('jumlah_barang');
        $post->harga_barang = $request->input('harga_barang');

        $post->save();

        return response()->json([
            'message' => 'Barang updated successfully',
            'data' => $post
        ], 200);
    }
    public function delete($id)
    {
        $user = auth('sanctum')->user();    
        $post = Barang::where('id', $id)->where('id_user', $user->id)->first();
        if (!$post) {
            return response()->json(['message' => 'Kamu Bukan Pemilik'], 200);
        }

        $post->delete();

        return response()->json(['message' => 'Barang delete successfully'], 200);
    }
}
