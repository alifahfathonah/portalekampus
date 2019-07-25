<?php
prado::using ('Application.MainPageM');
class CDetailDulangMHSLulus Extends MainPageM {		
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showSubMenuAkademikDulang=true;
        $this->showDulangMHSLulus=true;                
        $this->createObj('Nilai');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            try {
                if (isset($_SESSION['currentPageDulangMHSLulus']['DataMHS']['no_formulir'])) {
                    $datamhs=$_SESSION['currentPageDulangMHSLulus']['DataMHS'];
                    $nim=$datamhs['nim'];
                    $this->Nilai->setDataMHS($datamhs);
                    
                    $str = "SELECT MAX(tahun) AS tahun,MAX(idsmt) AS idsmt FROM dulang WHERE nim='$nim' GROUP BY tahun,idsmt ORDER BY tahun DESC,idsmt DESC LIMIT 1";
                    $this->DB->setFieldTable(array('tahun','idsmt'));
                    $datadulang=$this->DB->getRecord($str);	    
                    
                    $this->cmbAddTALulus->DataSource=$this->DMaster->removeIdFromArray($this->DMaster->getListTA($datamhs['tahun_masuk']),'none');
                    $this->cmbAddTALulus->Text=$datadulang[1]['tahun'];
                    $this->cmbAddTALulus->dataBind();
                     				
                    $this->cmbAddSMTLulus->DataSource=array(1=>'GANJIL',2=>'GENAP');
                    $this->cmbAddSMTLulus->Text=$datadulang[1]['idsmt'];
                    $this->cmbAddSMTLulus->dataBind();
                    
                    $this->Nilai->getTranskripFromKRS ();
                    $jumlah_sks=$this->Nilai->getTotalSKSAdaNilai();
                    $iddata_konversi=$datamhs['iddata_konversi'];
                    if ($iddata_konversi > 0) {
                        $jumlah_sks+=$this->DB->getSumRowsOfTable ('sks',"v_konversi2 WHERE iddata_konversi=$iddata_konversi");
                    }
                    $bool_sks=true;
                    if ($jumlah_sks >= 144) {
                        $ket_jumlah_sks='<span class="label label-success">PASSED</span>';
                    }else{
                        $ket_jumlah_sks="<span class='label label-danger'>FAIL</span> (saat ini baru $jumlah_sks SKS)";
                        $bool_sks=false;
                    }
                    
                    $this->literalJumlahSKS->Text=$ket_jumlah_sks;
                    $bool_bebas_keuangan=true;
                    
                    $this->literalBebasKeuangan->Text='<span class="label label-info">NOT YET IMPLEMENTED</span>';
                    $bool_bebas_perpustakaan=true;
                    $this->literalBebasPerpustakaan->Text='<span class="label label-info">NOT YET IMPLEMENTED</span>';
                    if ($bool_sks == false || $bool_bebas_keuangan==false || $bool_bebas_perpustakaan == false) {
                        $this->btnSave->Enabled=false;
                        $this->btnSave->CssClass='btn';
                    }
                    $this->cmbAddDosenWali->DataSource=$this->DMaster->getListDosenWali();
                    $this->cmbAddDosenWali->Text=$datamhs['iddosen_wali'];
                    $this->cmbAddDosenWali->dataBind();	           
                    
                    $_SESSION['semester']=$datadulang[1]['idsmt'];
                    $_SESSION['ta']=$datadulang[1]['tahun'];
                    $_SESSION['kjur']= $datamhs['kjur'];

                    $this->setInfoToolbar();
                }else{
                    throw new Exception("Data Mahasiswa belum ada di session.");
                }
            } catch (Exception $ex) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$ex->getMessage();
            }
		}	
	}
    public function setInfoToolbar() {        
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
        $ta=$this->DMaster->getNamaTA($_SESSION['ta']);		
        $semester = $this->setup->getSemester($_SESSION['semester']);
		$this->lblModulHeader->Text="Program Studi $ps T.A $ta Semester $semester";        
	}
    
    public function getDataMHS($idx) {		       
        if (isset($_SESSION['currentPageDulangMHSLulus']['DataMHS'][$idx])) {
            return $_SESSION['currentPageDulangMHSLulus']['DataMHS'][$idx];
        }
    }  
    public function checkDulang ($sender,$param) {
        $datamhs=$_SESSION['currentPageDulangMHSLulus']['DataMHS'];
        $ta=addslashes($param->Value);		       
        $semester=$this->cmbAddSMTLulus->Text;            
        try {            
            $nim=$datamhs['nim'];
            $this->Nilai->setDataMHS(array('nim'=>$nim));
            $datadulang=$this->Nilai->getDataDulang($semester,$ta);
            
            if (isset($datadulang['iddulang'])) {
                throw new Exception ("Mahasiswa Dengan NIM ($nim) telah daftar ulang di T.A dan Semester ini.");
            }          
        }catch (Exception $e) {
            $param->IsValid=false;
            $sender->ErrorMessage=$e->getMessage();
        }	    
    }  
    public function saveData ($sender,$param) {		
		if ($this->IsValid) {	
            $datamhs=$_SESSION['currentPageDulangMHSLulus']['DataMHS'];						
			$ta=$this->cmbAddTALulus->Text;							
			$semester=$this->cmbAddSMTLulus->Text;
            $_SESSION['semester']=$semester;
            $_SESSION['ta']=$ta;
            $_SESSION['kjur']= $datamhs['kjur'];
            $_SESSION['currentPageDulangMHSLulus']['tahun_masuk']=$datamhs['tahun_masuk'];
            $nim=  $datamhs['nim'];           
			$kelas=$datamhs['idkelas'];
            $iddosen_wali=$this->cmbAddDosenWali->Text;
			$str = "UPDATE register_mahasiswa SET iddosen_wali='$iddosen_wali',k_status='L' WHERE nim='$nim'";			
			$this->DB->query ('BEGIN');
			if ($this->DB->updateRecord($str)) {
                $status_sebelumnnya=$datamhs['k_status'];
                $tasmt=$ta.$semester;                              
                $str = "INSERT INTO dulang (iddulang,nim,tahun,idsmt,tasmt,tanggal,idkelas,status_sebelumnya,k_status) VALUES (NULL,'$nim','$ta','$semester','$tasmt',NOW(),'$kelas','$status_sebelumnnya','L')";
                $this->DB->insertRecord($str);            
				$this->DB->query('COMMIT');
                unset($_SESSION['currentPageDulangMHSLulus']['DataMHS']);
                $this->redirect('dulang.DulangMHSLulus',true);
			}else {
				$this->DB->query('ROLLBACK');
			}
		}
	}
    public function closeDetailDulang ($sender,$param) {
        unset($_SESSION['currentPageDulangMHSLulus']['DataMHS']);
        $this->redirect('dulang.DulangMHSLulus',true);
    }
}