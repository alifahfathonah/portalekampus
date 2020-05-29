<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\System\ConfigurationModel;
use App\Models\DMaster\TAModel;
use App\Models\DMaster\ProgramStudiModel;


class UIController extends Controller {    
    /**
     * digunakan untuk mendapatkan Identitas Perguruan Tinggi
     */
    public function frontend ()
    {
        $config = ConfigurationModel::getCache();
        $captcha_site_key = $config['CAPTCHA_SITE_KEY'];
        $tahun_pendaftaran = $config['DEFAULT_TAHUN_PENDAFTARAN'];
        $identitas['nama_pt']=$config['NAMA_PT'];
        $identitas['nama_pt_alias']=$config['NAMA_PT_ALIAS'];
        $identitas['bentuk_pt']=$config['BENTUK_PT'];
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'fetchdata',
                                    'captcha_site_key'=>$captcha_site_key,
                                    'tahun_pendaftaran'=>$tahun_pendaftaran,
                                    'identitas'=>$identitas,
                                    'message'=>'Fetch data ui untuk front berhasil diperoleh'
                                ],200);  
    }
    /**
     * digunakan untuk mendapatkan Identitas Perguruan Tinggi
     */
    public function admin ()
    {
        $config = ConfigurationModel::getCache();
        $daftar_ta=TAModel::all();        

        if ($this->hasRole('superadmin'))
        {
            $daftar_prodi=ProgramStudiModel::all();
            $prodi_id=$config['DEFAULT_PRODI'];        
        }
        elseif ($this->hasRole('mahasiswabaru'))
        {
            $formulir=\App\Models\SPMB\FormulirPendaftaranModel::find($this->guard()->user()->id);
            $daftar_prodi=ProgramStudiModel::where('id',$formulir->kjur1)->get();
            $prodi_id=$formulir->kjur1;
        }        
        $daftar_kelas=\App\Models\DMaster\KelasModel::select(\DB::raw('idkelas AS id,nkelas AS text'))
                                                    ->get();
        $idkelas='A';
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'fetchdata',  
                                    'daftar_ta'=>$daftar_ta,    
                                    'daftar_prodi'=>$daftar_prodi,
                                    'prodi_id'=>$prodi_id,                                    
                                    'daftar_kelas'=>$daftar_kelas,                              
                                    'idkelas'=>$idkelas,
                                    'message'=>'Fetch data ui untuk admin berhasil diperoleh'
                                ],200);  
    }
}