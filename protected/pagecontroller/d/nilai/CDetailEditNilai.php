<?php
prado::using ('Application.MainPageD');
class CDetailEditNilai extends MainPageD {    
   	public function onLoad($param) {
		parent::onLoad($param);							
		$this->showSubMenuAkademikNilai=true;
        $this->showEditNilai=true;    
        $this->createObj('Akademik');        
        $this->createObj('Nilai');        
        
		if (!$this->IsPostback&&!$this->IsCallback) {
            if (!isset($_SESSION['currentPageDetailEditNilai'])||$_SESSION['currentPageDetailEditNilai']['page_name']!='d.nilai.DetailEditNilai') {
				$_SESSION['currentPageDetailEditNilai']=array('page_name'=>'d.nilai.DetailEditNilai','page_num'=>0,'search'=>false,'DataNilai'=>array(),'jumlahrecord'=>50);
			}  
            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();            
            try {
                $idkelas_mhs=addslashes($this->request['id']);
                $this->Demik->getInfoKelas($idkelas_mhs);                
                if (!isset($this->Demik->InfoKelas['idkelas_mhs'])) {                                                
                    throw new Exception ("Kelas Mahasiswa dengan id ($idkelas_mhs) tidak terdaftar.");		
                }     
                if (!$this->Demik->InfoKelas['isi_nilai']) {
                    throw new Exception ("Masa pengisian nilai dari sisi Dosen telah berakhir, silahkan hubungi Operator Nilai di Prodi.");
                }
                $infokelas=$this->Demik->InfoKelas;
                $this->Demik->InfoKelas['namakelas']=$this->DMaster->getNamaKelasByID($infokelas['idkelas']).'-'.chr($infokelas['nama_kelas']+64);
                $this->Demik->InfoKelas['hari']=$this->TGL->getNamaHari($infokelas['hari']);
                
                $this->txtPersenQuiz->Text=$infokelas['persen_quiz'];
                $this->txtPersenTugas->Text=$infokelas['persen_tugas'];
                $this->txtPersenUTS->Text=$infokelas['persen_uts'];
                $this->txtPersenUAS->Text=$infokelas['persen_uas'];
                $this->txtPersenAbsen->Text=$infokelas['persen_absen'];
                
                $this->cmbJumlahRecord->Text=$_SESSION['currentPageDetailEditNilai']['jumlahrecord'];
                $this->RepeaterS->PageSize=$_SESSION['currentPageDetailEditNilai']['jumlahrecord'];
                $_SESSION['currentPageDetailEditNilai']['DataNilai']=$this->Demik->InfoKelas;
                $this->populateData();	             
            } catch (Exception $ex) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$ex->getMessage();
            }
		}
	}    
    public function changeJumlahRecord ($sender,$param) {
		$_SESSION['currentPageDetailEditNilai']['jumlahrecord']=$this->cmbJumlahRecord->Text;
        $this->RepeaterS->PageSize=$_SESSION['currentPageDetailEditNilai']['jumlahrecord'];
		$this->populateData();        
	}
    public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageDetailEditNilai']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageDetailEditNilai']['search']);
	}
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}
	protected function populateData() {	
        $datakelas=$_SESSION['currentPageDetailEditNilai']['DataNilai'];
        $idkelas_mhs=$datakelas['idkelas_mhs'];
        $str = "SELECT vkm.idkrsmatkul,vdm.nim,vdm.nama_mhs,n.persentase_quiz, n.persentase_tugas, n.persentase_uts, n.persentase_uas, n.persentase_absen, n.nilai_quiz, n.nilai_tugas, n.nilai_uts, n.nilai_uas, n.nilai_absen, n.n_kuan,n.n_kual FROM kelas_mhs_detail kmd LEFT JOIN nilai_matakuliah n ON (n.idkrsmatkul=kmd.idkrsmatkul) JOIN v_krsmhs vkm ON (vkm.idkrsmatkul=kmd.idkrsmatkul) JOIN v_datamhs vdm ON (vkm.nim=vdm.nim) WHERE kmd.idkelas_mhs=$idkelas_mhs AND vkm.sah=1 AND vkm.batal=0";        
        
        $jumlah_baris=$this->DB->getCountRowsOfTable(" kelas_mhs_detail kmd LEFT JOIN nilai_matakuliah n ON (n.idkrsmatkul=kmd.idkrsmatkul) JOIN v_krsmhs vkm ON (vkm.idkrsmatkul=kmd.idkrsmatkul) JOIN v_datamhs vdm ON (vkm.nim=vdm.nim) WHERE kmd.idkelas_mhs=$idkelas_mhs AND vkm.sah=1 AND vkm.batal=0",'vkm.idkrsmatkul');
        
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageDetailEditNilai']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$offset=$this->RepeaterS->CurrentPageIndex*$this->RepeaterS->PageSize;
		$limit=$this->RepeaterS->PageSize;
		if ($offset+$limit>$this->RepeaterS->VirtualItemCount) {
			$limit=$this->RepeaterS->VirtualItemCount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageDetailEditNilai']['page_num']=0;}
		$str = "$str ORDER BY vdm.nama_mhs ASC LIMIT $offset,$limit";				     
        $this->DB->setFieldTable(array('idkrsmatkul','nim','nama_mhs','persentase_quiz', 'persentase_tugas', 'persentase_uts', 'persentase_uas', 'persentase_absen', 'nilai_quiz', 'nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_absen','n_kuan','n_kual'));
        $r=$this->DB->getRecord($str);	           
        $result=array();
        $persentase_quiz=$datakelas['persen_quiz'] > 0 ?number_format($datakelas['persen_quiz']/100,2):0;
        $persentase_tugas=$datakelas['persen_tugas'] > 0 ?number_format($datakelas['persen_tugas']/100,2):0;
        $persentase_uts=$datakelas['persen_uts'] > 0 ?number_format($datakelas['persen_uts']/100,2):0;
        $persentase_uas=$datakelas['persen_uas'] > 0 ?number_format($datakelas['persen_uas']/100,2):0;
        $persentase_absen=$datakelas['persen_absen'] > 0 ?number_format($datakelas['persen_absen']/100,2):0;
        
        while (list($k,$v)=each($r)) {                
            $v['persentase_quiz']=$persentase_quiz;
            $v['persentase_tugas']=$persentase_tugas;
            $v['persentase_uts']=$persentase_uts;
            $v['persentase_uas']=$persentase_uas;
            $v['persentase_absen']=$persentase_absen;
            
            $v['am']=$this->Nilai->getAngkaMutu($v['n_kual']);
            $result[$k]=$v;
        }
        $this->RepeaterS->DataSource=$result;
        $this->RepeaterS->dataBind();	  
        
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS);
	}	
     public function updateDataPersentase ($sender,$param) {
        if ($this->IsValid) {
            $idkelas_mhs=$_SESSION['currentPageDetailEditNilai']['DataNilai']['idkelas_mhs'];
            $persentase_quiz=$this->txtPersenQuiz->Text;
            $persentase_tugas=$this->txtPersenTugas->Text;
            $persentase_uts=$this->txtPersenUTS->Text;
            $persentase_uas=$this->txtPersenUAS->Text;
            $persentase_absen=$this->txtPersenAbsen->Text;
            
            $str = "UPDATE kelas_mhs SET persen_quiz='$persentase_quiz',persen_tugas='$persentase_tugas',persen_uts='$persentase_uts',persen_uas='$persentase_uas',persen_absen='$persentase_absen' WHERE idkelas_mhs=$idkelas_mhs";
            $this->DB->updateRecord($str);
         
            $this->redirect("nilai.DetailEditNilai", true,array('id'=>$idkelas_mhs));
        }
     }
    public function saveData ($sender,$param) {
        if ($this->IsValid) {
            $idkelas_mhs=$_SESSION['currentPageDetailEditNilai']['DataNilai']['idkelas_mhs'];
            $userid=$this->Pengguna->getDataUser('iddosen');
            foreach ($this->RepeaterS->Items As $inputan) {
                if ($inputan->chkProcess->Checked) {
                    $item=$inputan->txtNilaiQuiz->getNamingContainer();
                    $idkrsmatkul=$this->RepeaterS->DataKeys[$item->getItemIndex()];
                    $persentase_quiz=$inputan->hiddenpersenquiz->Value;
                    $persentase_tugas=$inputan->hiddenpersentugas->Value;
                    $persentase_uts=$inputan->hiddenpersenuts->Value;
                    $persentase_uas=$inputan->hiddenpersenuas->Value;
                    $persentase_absen=$inputan->hiddenpersenabsen->Value;

                    $nilai_quiz=addslashes($inputan->txtNilaiQuiz->Text);
                    $nilai_tugas=addslashes($inputan->txtNilaiTugas->Text);
                    $nilai_uts=addslashes($inputan->txtNilaiUTS->Text);
                    $nilai_uas=addslashes($inputan->txtNilaiUAS->Text);
                    $nilai_absen=addslashes($inputan->txtNilaiAbsen->Text);                    
                    $n_kuan=($persentase_quiz*$nilai_quiz)+($persentase_tugas*$nilai_tugas)+($persentase_uts*$nilai_uts)+($persentase_uas*$nilai_uas)+($persentase_absen*$nilai_absen);
                    $n_kual=$this->Nilai->getRentangNilaiNKuan($n_kuan);
                    
                    $str = "REPLACE INTO nilai_matakuliah SET 
                                                            idkrsmatkul=$idkrsmatkul,
                                                            persentase_quiz='$persentase_quiz',
                                                            persentase_tugas='$persentase_tugas',
                                                            persentase_uts='$persentase_uts',
                                                            persentase_uas='$persentase_uas', 
                                                            persentase_absen='$persentase_absen', 
                                                            nilai_quiz='$nilai_quiz', 
                                                            nilai_tugas='$nilai_tugas', 
                                                            nilai_uts='$nilai_uts',
                                                            nilai_uas='$nilai_uas', 
                                                            nilai_absen='$nilai_absen', 
                                                            n_kuan='$n_kuan',
                                                            n_kual='$n_kual',
                                                            userid_input=$userid,
                                                            tanggal_input=NOW(),
                                                            userid_modif=$userid,
                                                            tanggal_modif=NOW(),
                                                            bydosen=1,
                                                            ket='bydosen',
                                                            telah_isi_kuesioner=0,
                                                            tanggal_isi_kuesioner='0000-00-00'";																				
                $this->DB->insertRecord($str);
                }
            }
            $this->redirect("nilai.DetailEditNilai", true,array('id'=>$idkelas_mhs));
        }
        
    }
    public function printOut ($sender,$param) {	
        $this->createObj('reportnilai');
        $this->linkOutput->Text='';
        $this->linkOutput->NavigateUrl='#';        
    }
}