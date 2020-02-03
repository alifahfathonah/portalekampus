<?php
prado::using ('Application.MainPageM');
class DetailDulangMHSEkstension Extends MainPageM {		
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showSubMenuAkademikDulang=true;
        $this->showDulangMHSEkstension=true;                
        $this->createObj('Akademik');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            try {
                if (isset($_SESSION['currentPageDulangMHSEkstension']['DataMHS']['no_formulir'])) {
                    $datamhs=$_SESSION['currentPageDulangMHSEkstension']['DataMHS'];
                    
                    $this->Demik->setDataMHS($datamhs);
                    $this->cmbAddDosenWali->DataSource=$this->DMaster->getListDosenWali();
                    $this->cmbAddDosenWali->Text=$datamhs['iddosen_wali'];
                    $this->cmbAddDosenWali->dataBind();	           
                    
                }else{
                    throw new Exception("No. Formulir belum ada di session.");
                }
            } catch (Exception $ex) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$ex->getMessage();
            }
		}	
	}
    public function getDataMHS($idx) {		        
        return $this->Demik->getDataMHS($idx);
    }    
    public function saveData ($sender,$param) {		
		if ($this->IsValid) {	
            $datamhs=$_SESSION['currentPageDulangMHSEkstension']['DataMHS'];						
			$ta=$datamhs['ta'];							
			$semester=$datamhs['idsmt'];
			$tanggal=date ('Y-m-d H:m:s');			
            $nim=  $datamhs['nim'];           
			$kelas=$datamhs['idkelas'];
            $iddosen_wali=$this->cmbAddDosenWali->Text;
			$str = "UPDATE register_mahasiswa SET iddosen_wali='$iddosen_wali',k_status='A' WHERE nim='$nim'";			
			$this->DB->query ('BEGIN');
			if ($this->DB->updateRecord($str)) {
                $status_sebelumnnya=$datamhs['k_status'];
                $tasmt=$ta.$semester;
				$str = "INSERT INTO dulang (iddulang,nim,tahun,idsmt,tasmt,tanggal,idkelas,status_sebelumnya,k_status) VALUES (NULL,'$nim','$ta','$semester','$tasmt','$tanggal','$kelas','$status_sebelumnnya','A')";
				$this->DB->insertRecord($str);				
				$this->DB->query('COMMIT');
                unset($_SESSION['currentPageDulangMHSEkstension']);
                $this->redirect('dulang.DulangMHSEkstension',true);
			}else {
				$this->DB->query('ROLLBACK');
			}
		}
	}
    public function closeDetailDulang ($sender,$param) {
        unset($_SESSION['currentPageDulangMHSEkstension']);
        $this->redirect('dulang.DulangMHSEkstension',true);
    }
}