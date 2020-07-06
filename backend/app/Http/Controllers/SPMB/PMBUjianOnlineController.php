<?php

namespace App\Http\Controllers\SPMB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SPMB\JadwalUjianPMBModel;
use App\Models\SPMB\PesertaUjianPMBModel;
use App\Models\SPMB\SoalPMBModel;
use App\Models\SPMB\JawabanUjianPMBModel;

use Ramsey\Uuid\Uuid;

class PMBUjianOnlineController extends Controller {  
    /**
     * daftar soal
     */
    public function index(Request $request,$id)
    {
        $this->hasPermissionTo('SPMB-PMB-UJIAN-ONLINE_BROWSE');
                
        $peserta=PesertaUjianPMBModel::find($id);
        if (is_null($peserta))
        {
            return Response()->json([
                                        'status'=>1,
                                        'pid'=>'fetchdata',                
                                        'message'=>["Peserta Ujian dengan ID ($id) gagal diperoleh, mungkin belum mendaftar"]
                                    ],422); 
        }
        else
        {
            $soal=SoalPMBModel::select(\DB::raw('id,soal'))
                            ->whereNotIn('id',function($query) use ($id){
                                $query->select('soal_id')
                                        ->from('pe3_jawaban_ujian')
                                        ->where('user_id',$id);
                            })
                            ->inRandomOrder()
                            ->first();
            
            if (is_null($soal))
            {
                $status=0;
                $soal='';
                $jawaban=[];
            }
            else
            {
                $status=1;
                $jawaban=$soal->jawabanUjian;
            }           
            return Response()->json([
                                    'status'=>$status,
                                    'pid'=>'fetchdata',  
                                    'soal'=>$soal,     
                                    'jawaban'=>$jawaban,                                                                                                                              
                                    'message'=>'Fetch data soal pmb berhasil.'
                                ],200);     
        }        
    }  
    /**
     * digunakan untuk mendapatkan daftar jadwal ujian
     */
    public function jadwal (Request $request)
    {   
        $this->hasPermissionTo('SPMB-PMB-JADWAL-UJIAN_BROWSE');

        $this->validate($request, [                       
            'tahun_pendaftaran'=>'required',
            'semester_pendaftaran'=>'required'
        ]);
        $tahun_pendaftaran=$request->input('tahun_pendaftaran');
        $semester_pendaftaran=$request->input('semester_pendaftaran');

        $jadwal_ujian=JadwalUjianPMBModel::select(\DB::raw('pe3_jadwal_ujian_pmb.id,
                                                pe3_jadwal_ujian_pmb.nama_kegiatan, 
                                                pe3_jadwal_ujian_pmb.jumlah_soal, 
                                                pe3_jadwal_ujian_pmb.tanggal_ujian, 
                                                pe3_jadwal_ujian_pmb.jam_mulai_ujian, 
                                                pe3_jadwal_ujian_pmb.jam_selesai_ujian, 
                                                pe3_jadwal_ujian_pmb.tanggal_akhir_daftar, 
                                                pe3_jadwal_ujian_pmb.durasi_ujian, 
                                                pe3_jadwal_ujian_pmb.status_pendaftaran, 
                                                pe3_jadwal_ujian_pmb.status_ujian,                                                 
                                                pe3_jadwal_ujian_pmb.ruangkelas_id,
                                                pe3_ruangkelas.namaruang,
                                                pe3_jadwal_ujian_pmb.created_at,
                                                pe3_jadwal_ujian_pmb.updated_at
                                            '))
                                            ->leftJoin('pe3_ruangkelas','pe3_ruangkelas.id','pe3_jadwal_ujian_pmb.ruangkelas_id')
                                            ->where('ta',$tahun_pendaftaran)
                                            ->where('idsmt',$semester_pendaftaran)
                                            ->whereRaw('CURRENT_DATE() <= pe3_jadwal_ujian_pmb.tanggaL_akhir_daftar')
                                            ->orderBy('tanggal_akhir_daftar','desc')
                                            ->get();

        $jumlah_bank_soal=SoalPMBModel::where('ta',$tahun_pendaftaran)
                                        ->where('semester',$semester_pendaftaran)
                                        ->count();
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'fetchdata',  
                                    'jadwal_ujian'=>$jadwal_ujian,      
                                    'jumlah_bank_soal'=>$jumlah_bank_soal,                                                                                                                             
                                    'message'=>'Fetch data jadwal ujian pmb berhasil.'
                                ],200);     
    }
    
    /**
     * digunakan untuk menyimpan jawaban ujian
     */
    public function store (Request $request)
    {
        $this->validate($request,[
                                  'user_id'=>'required|exists:pe3_peserta_ujian_pmb,id',
                                  'soal_id'=>'required|exists:pe3_soal,id',
                                  'jawaban_id'=>'required|exists:pe3_jawaban_soal,id',
                                ]);
        
        
        $jawaban_ujian = JawabanUjianPMBModel::create([
            'id'=>Uuid::uuid4()->toString(),
            'user_id'=>$request->input('user_id'),
            'soal_id'=>$request->input('soal_id'),
            'jawaban_id'=>$request->input('jawaban_id'),
        ]);
        
        return Response()->json([
                                    'status'=>1,
                                    'pid'=>'store',                                                                                                                                    
                                    'message'=>'Data Jawaban Ujian berhasil disimpan.'
                                ],200); 
    }    
}