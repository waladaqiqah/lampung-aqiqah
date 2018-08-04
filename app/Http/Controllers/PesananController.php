<?php

namespace App\Http\Controllers;

use Indonesia;
use App\Pesanan;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PesananController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
          'nama_pemesan' => 'required',
          'alamat' => 'required',
          'provinsi'  => 'required',
          'kabupaten'  => 'required',
          'kecamatan'  => 'required',
          'kelurahan'  => 'required',
          'handphone'  => 'required',
          'email' => 'required|string|email|max:255|unique:users',
          'sumber_informasi' => 'required',
          'catatan' => 'required',
          'nama_peserta' => 'required',
          'tempat_tanggal_lahir' => 'required',
          'jenis_kelamin_peserta' => 'required',
          'nama_ayah' => 'required',
          'nama_ibu' => 'required',
          'tempat_lahir' =>  'required',
      ]);

      if(Auth::check()){
         $pelanggan_id = Auth::User()->id;
      }else{
         
        $user = User::create([
            'name' => $request->nama_pemesan,
            'email' => $request->email,
            'password' => bcrypt('123456'),
        ]);

        $memberRole = Role::where('name' , 'member')->first();
        $user->attachRole($memberRole);

        $pelanggan_id = $user->id;
      }

      $update_alamat_user = User::find($pelanggan_id);
      $update_alamat_user->update([
        'provinsi' => $request->provinsi, 'kabupaten' => $request->kabupaten, 'kecamatan' => $request->kecamatan, 'kelurahan' => $request->kelurahan, 'alamat' => $request->alamat, 'no_telp' => $request->handphone
      ]);

      $new_pesanan = Pesanan::create([
        'pelanggan_id' => $pelanggan_id,
        'sumber_informasi' => $request->sumber_informasi,
        'catatan' => $request->catatan,
        'nama_peserta' => $request->nama_peserta,
        'tempat_tanggal_lahir' => $request->tempat_tanggal_lahir,
        'jenis_kelamin' => $request->jenis_kelamin_peserta,
        'nama_ayah' => $request->nama_ayah,
        'nama_ibu' => $request->nama_ibu,
        'tempat_lahir' => $request->tempat_lahir,
        'total' => $request->total,
        'metode_pembayaran' => $request->metode_pembayaran
      ]);

      return $new_pesanan;
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function provinsi(){
        $provinsi = Indonesia::allProvinces();
        return response()->json($provinsi);
    }

    public function pilih_wilayah($id,$type){

   # Tarik ID_wilayah & tipe_wilayah
        $id_wilayah   = $id;
        $type_wilayah = $type;

        # Buat pilihan "Switch Case" berdasarkan variabel "type" dari dari data yg dikirim
        switch ($type_wilayah):
    # untuk kasus "kabupaten"
        case 'kabupaten':
        $kabupaten = Indonesia::allCities()->where('province_id', $id);
        return response()->json($kabupaten);
        break;
    # untuk kasus "kecamatan"
        case 'kecamatan':
        $kecamatan = Indonesia::allDistricts()->where('city_id', $id);
        return response()->json($kecamatan);
        break;
    # untuk kasus "kelurahan"
        case 'kelurahan':
        $kelurahan = Indonesia::allVillages()->where('district_id', $id);
        return response()->json($kelurahan);
        break;
        # pilihan berakhir
        endswitch;
    }
}
