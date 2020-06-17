<?php

namespace App\Http\Controllers\SPMB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SPMB\SoalPMBModel;

class SoalPMBController extends Controller {  
    /**
     * daftar soal
     */
    public function index(Request $request)
    {
        $this->hasPermissionTo('SPMB-PMB-SOAL_BROWSE');

        $soal=SoalPMBModel::where('ta',2020)
                            ->where('semester',1)
                            ->get();

        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'fetchdata',  
                                    'soal'=>$soal,                                                                                                                                   
                                    'message'=>'Fetch data soal pmb berhasil.'
                                ],200);     
    }  
    /**
     * simpan soal baru
     */
    public function store(Request $request)
    {
        $this->hasPermissionTo('SPMB-PMB-SOAL_STORE');

        $this->validate($request, [           
            'soal'=>'required',
            'gambar'=>'required',
            'jawaban1'=>'required',
            'jawaban2'=>'required',
            'jawaban3'=>'required',
            'jawaban4'=>'required',
            'tahun_pendaftaran'=>'required',
            'semester_pendaftaran'=>'required'
        ]);
        
        
        
    }
}