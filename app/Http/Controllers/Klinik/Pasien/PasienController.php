<?php

namespace App\Http\Controllers\klinik\Pasien;

use App\Http\Controllers\Controller;
use App\Models\AnggotaPersonil;
use App\Models\AnggotaSiswa;
use App\Models\Pasien;
use App\Utils\ApiResponse;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{
   use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $x['siswa'] =  AnggotaSiswa::get();
      $x['personil'] =  AnggotaPersonil::get();

      $x['anggota'] = $x['siswa']->toBase()->merge($x['personil'])->sortBy('nama');;

      $data = Pasien::with('personil', 'siswa');

      if (request()->ajax()) {
         return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
               if ($data->anggota_jenis == 'personil') {
                  $anggota =  $data->personil;
               } else {
                  $anggota =  $data->siswa;
               }
               return view('app.pasien.action', compact('data', 'anggota'));
            })
            ->addColumn('nama', function ($data) {

               if ($data->anggota_jenis == 'personil') {
                  return $data->personil->nama;
               } else {
                  return $data->siswa->nama;
               }
            })
            ->addColumn('jenis_kelamin', function ($data) {
               if ($data->anggota_jenis == 'personil') {
                  return $data->personil->jenis_kelamin;
               } else {
                  return $data->siswa->jenis_kelamin;
               }
            })
            ->addColumn('tgl_lahir', function ($data) {
               if ($data->anggota_jenis == 'personil') {
                  return Carbon::parse($data->personil->tgl_lahir)->format('d-m-Y');
               } else {
                  return  Carbon::parse($data->siswa->tgl_lahir)->format('d-m-Y');
               }
            })
            ->rawColumns(['action'])
            ->make(true);
      }

      return view('app.pasien.index', $x);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      try {
         DB::beginTransaction();
         if ($request->jenis_anggota == 'personil') {
            $anggota =  AnggotaPersonil::where('id', $request->select_user)->first();
         }
         if ($request->jenis_anggota == 'siswa') {
            $anggota =  AnggotaSiswa::where('id',  $request->select_user)->first();
         }


         $kodeRm = Pasien::generateKodeRm();


         $pasien = Pasien::create([
            "kode_rm" => $kodeRm,
            "anggota_id" => $anggota->id,
            "anggota_jenis" => $request->jenis_anggota,
         ]);


         DB::commit();
         return $this->success(__('trans.crud.success'));
      }catch (QueryException $e){
         $errorCode = $e->errorInfo[1];
         if($errorCode == 1062){
            return $this->error("Pasien Sudah Pernah Terdaftar, Silahkan Cari dihalaman Pencarian Pasien", 400);
         }
     }
      catch (\Throwable $th) {
         DB::rollBack();
         return $this->error(__('trans.crud.error') . $th, 400);
      }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pasien $pasien)
    {
        return $this->success('data pasien detail', $pasien);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasien $pasien)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pasien $pasien)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pasien $pasien)
    {
      try {
         DB::beginTransaction();
         $pasien->delete();
         DB::commit();

         return $this->success(__('trans.crud.delete'));
      } catch (\Throwable $th) {
         DB::rollBack();

         return $this->error(__('trans.crud.error') . $th, 400);
      }
    }
}
