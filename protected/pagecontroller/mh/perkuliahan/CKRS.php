<?php
prado::using ('Application.MainPageMHS');
class CKRS extends MainPageMHS {
	/**
	* total SKS
	*/
	static $totalSKS=0;
	 /**
	* total SKS Batal
	*/
	public static $totalSKSBatal=0;	
	/**
	* jumlah matakuliah
	*/
	static $jumlahMatkul=0;	
     /**
	* total Matakuliah Batal
	*/
	public static $jumlahMatkulBatal=0;
	public function onLoad($param) {
		parent::onLoad($param);	
        $this->showSubMenuAkademikPerkuliahan=true;
        $this->showKRS = true;   
        
        $this->createObj('KRS');
		if (!$this->IsPostBack&&!$this->IsCallback) {	
            if (!isset($_SESSION['currentPageKRS'])||$_SESSION['currentPageKRS']['page_name']!='mh.perkuliahan.KRS') {
				$_SESSION['currentPageKRS']=array('page_name'=>'mh.perkuliahan.KRS','page_num'=>0,'DataKRS'=>array());
			} 
            $this->lblModulHeader->Text=$this->getInfoToolbar();
            
            $this->tbCmbTA->DataSource=$this->DMaster->removeIdFromArray($this->DMaster->getListTA($this->Pengguna->getDataUser('tahun_masuk')),'none');
			$this->tbCmbTA->Text=$_SESSION['ta'];
			$this->tbCmbTA->dataBind();			
            
            $semester=$this->DMaster->removeIdFromArray($this->setup->getSemester(),'none');  				
			$this->tbCmbSemester->DataSource=$semester;
			$this->tbCmbSemester->Text=$_SESSION['semester'];
			$this->tbCmbSemester->dataBind();
            
            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();
            
            $this->populateData();				
				
		}				
	}
    public function getInfoToolbar() {                
		$ta=$this->DMaster->getNamaTA($_SESSION['ta']);
		$semester=$this->setup->getSemester($_SESSION['semester']);
		$text="TA $ta Semester $semester";
		return $text;
	}
	public function changeTbTA ($sender,$param) {
		$_SESSION['ta']=$this->tbCmbTA->Text;		
		$this->redirect('perkuliahan.KRS',true);        
	}	
	public function changeTbSemester ($sender,$param) {
		$_SESSION['semester']=$this->tbCmbSemester->Text;		
		$this->redirect('perkuliahan.KRS',true);
	}	
    public function itemBound ($sender,$param) {
        $item=$param->Item;
        if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem') {    
            if ($item->DataItem['batal']) {
                $item->cmbKelas->Enabled=false;
                CKRS::$totalSKSBatal+=$item->DataItem['sks'];
                CKRS::$jumlahMatkulBatal+=1;
            }else{
                $idkrsmatkul=$item->DataItem['idkrsmatkul'];
                $idpenyelenggaraan=$item->DataItem['idpenyelenggaraan'];
                $idkelas=$_SESSION['currentPageKRS']['DataKRS']['krs']['kelas_dulang'];
                $str = "SELECT km.idkelas_mhs,km.nama_kelas,vpp.nama_dosen,vpp.nidn,km.idruangkelas FROM kelas_mhs km JOIN v_pengampu_penyelenggaraan vpp ON (km.idpengampu_penyelenggaraan=vpp.idpengampu_penyelenggaraan) WHERE vpp.idpenyelenggaraan=$idpenyelenggaraan AND km.idkelas='$idkelas'  ORDER BY hari ASC,idkelas ASC,nama_dosen ASC";            
                $this->DB->setFieldTable(array('idkelas_mhs','nama_kelas','nama_dosen','nidn','idruangkelas'));
                $r = $this->DB->getRecord($str);
                
                $str = "SELECT idkelas_mhs FROM kelas_mhs_detail WHERE idkrsmatkul=$idkrsmatkul";            
                $this->DB->setFieldTable(array('idkelas_mhs'));
                $r_selected = $this->DB->getRecord($str);
                
                if (isset($r_selected[1])) {
                    $idkelas_mhs_selected=$r_selected[1]['idkelas_mhs'];
                    $result = array();
                }else{
                    $idkelas_mhs_selected='none';
                    $result = array('none'=>' ');
                }                
                while (list($k,$v)=each($r)) { 
                    $idkelas_mhs=$v['idkelas_mhs'];
                    $jumlah_peserta_kelas = $this->DB->getCountRowsOfTable ("kelas_mhs_detail WHERE idkelas_mhs=$idkelas_mhs",'idkelas_mhs');
                    $kapasitas=(int)$this->DMaster->getKapasitasRuangKelas($v['idruangkelas']);
                    $keterangan=($jumlah_peserta_kelas <= $kapasitas) ? '' : ' [PENUH]';
                    $result[$idkelas_mhs]=$this->DMaster->getNamaKelasByID($idkelas).'-'.chr($v['nama_kelas']+64) . ' ['.$v['nidn'].']'.$keterangan;   
                }                
                $item->cmbKelas->DataSOurce=$result;            
                $item->cmbKelas->DataBind();        
                $item->cmbKelas->Enabled=!$this->DB->checkRecordIsExist('idkrsmatkul','nilai_matakuliah',$idkrsmatkul);
                $item->cmbKelas->Text=$idkelas_mhs_selected;

                CKRS::$totalSKS+=$item->DataItem['sks'];
                CKRS::$jumlahMatkul+=1;
            }
        }
    }
	protected function populateData () {
        try {			
            $datamhs=$this->Pengguna->getDataUser();  
            $this->KRS->setDataMHS($datamhs);
            $datakrs=$this->KRS->getKRS($_SESSION['ta'],$_SESSION['semester']);           
            
            $datadulang=$this->KRS->getDataDulang($datakrs['krs']['idsmt'],$datakrs['krs']['tahun']);
            $datakrs['krs']['kelas_dulang']=$datadulang['idkelas'];
            $_SESSION['currentPageKRS']['DataKRS']=$datakrs;
            
            $this->RepeaterS->DataSource=$this->KRS->DataKRS['matakuliah'];
            $this->RepeaterS->dataBind();
        }catch (Exception $e) {
            $this->idProcess='view';	
			$this->errorMessage->Text=$e->getMessage();	
        }

	}	
   
    public function prosesKelas ($sender,$param) {
        $idkelas_mhs=$sender->Text;
        $idkrsmatkul=$this->getDataKeyField($sender, $this->RepeaterS);
        $this->DB->query('BEGIN');
        if ($idkelas_mhs=='none') {
            $this->DB->deleteRecord("kelas_mhs_detail WHERE idkrsmatkul=$idkrsmatkul");
            $this->DB->deleteRecord("kuesioner_jawaban WHERE idkrsmatkul=$idkrsmatkul");
            $this->DB->updateRecord("UPDATE nilai_matakuliah SET telah_isi_kuesioner=0,tanggal_isi_kuesioner='' WHERE idkrsmatkul=$idkrsmatkul");
        
            $this->DB->query('COMMIT');
            $this->redirect('perkuliahan.KRS', true); 
        }else {
            $jumlah_peserta_kelas = $this->DB->getCountRowsOfTable ("kelas_mhs_detail WHERE idkelas_mhs=$idkelas_mhs",'idkelas_mhs');
            $str = "SELECT kapasitas FROM kelas_mhs km,ruangkelas rk WHERE rk.idruangkelas=km.idruangkelas AND idkelas_mhs=$idkelas_mhs";
            $this->DB->setFieldTable(array('kapasitas'));
            $result=$this->DB->getRecord($str);
            $kapasitas=$result[1]['kapasitas'];
            if ($jumlah_peserta_kelas <= $kapasitas) {
                if ($this->DB->checkRecordIsExist('idkrsmatkul','kelas_mhs_detail',$idkrsmatkul)) {
                    $this->DB->updateRecord("UPDATE kelas_mhs_detail SET idkelas_mhs=$idkelas_mhs WHERE idkrsmatkul=$idkrsmatkul");
                    $this->DB->deleteRecord("kuesioner_jawaban WHERE idkrsmatkul=$idkrsmatkul");
                    $this->DB->updateRecord("UPDATE nilai_matakuliah SET telah_isi_kuesioner=0,tanggal_isi_kuesioner='' WHERE idkrsmatkul=$idkrsmatkul");
                }else{
                     $this->DB->insertRecord("INSERT INTO kelas_mhs_detail SET idkelas_mhs=$idkelas_mhs,idkrsmatkul=$idkrsmatkul");
                }
                $this->DB->query('COMMIT');
                $this->redirect('perkuliahan.KRS', true);
            }else{
                $this->modalMessageError->show();
                $this->lblContentMessageError->Text="Tidak bisa bergabung dengan kelas ini, karena kalau ditambah dengan Anda akan melampau kapasitas kelas ($kapasitas). Silahkan Refresh Web Browser Anda.";					
            }
        }
    }
	public function printKRS ($sender,$param) {
        $this->createObj('reportkrs');
        $this->linkOutput->Text='';
        $this->linkOutput->NavigateUrl='#';
        switch ($_SESSION['outputreport']) {
            case  'summarypdf' :
                $messageprintout="Mohon maaf Print out pada mode summary pdf tidak kami support.";                
            break;
            case  'summaryexcel' :
                $messageprintout="Mohon maaf Print out pada mode summary excel tidak kami support.";                
            break;
            case  'excel2007' :
                $messageprintout="Mohon maaf Print out pada mode excel 2007 belum kami support.";                
            break;
            case  'pdf' :                
                $messageprintout='';                
                $tahun=$_SESSION['ta'];
                $semester=$_SESSION['semester'];
                $nama_tahun = $this->DMaster->getNamaTA($tahun);
                $nama_semester = $this->setup->getSemester($semester);

                $dataReport=$this->Pengguna->getDataUser();
                $dataReport['krs']=$_SESSION['currentPageKRS']['DataKRS']['krs'];        
                $dataReport['matakuliah']=$_SESSION['currentPageKRS']['DataKRS']['matakuliah'];        
                $dataReport['nama_tahun']=$nama_tahun;
                $dataReport['nama_semester']=$nama_semester;        
                
                $kaprodi=$this->KRS->getKetuaPRODI($dataReport['kjur']);                  
                $dataReport['nama_kaprodi']=$kaprodi['nama_dosen'];
                $dataReport['jabfung_kaprodi']=$kaprodi['nama_jabatan'];
                $dataReport['nipy_kaprodi']=$kaprodi['nipy'];
                
                $dataReport['linkoutput']=$this->linkOutput;                 
                $this->report->setDataReport($dataReport); 
                $this->report->setMode($_SESSION['outputreport']);
                $this->report->printKRS();				

                
            break;
        }
        $this->lblMessagePrintout->Text=$messageprintout;
        $this->lblPrintout->Text="Kartu Rencana Studi T.A $nama_tahun Semester $nama_semester";
        $this->modalPrintOut->show();
	}
}

?>