<?php
prado::using ('Application.MainPageM');
class KHSEkstension extends MainPageM {	
	public $nilai_semester_lalu;
	public $total_sks_nm_saat_ini;
	public function onLoad ($param) {
		parent::onLoad($param);	 		
        $this->showSubMenuAkademikNilai=true;
        $this->showKHSEkstension=true;
        
        $this->createObj('Nilai');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageKHSEkstension'])||$_SESSION['currentPageKHSEkstension']['page_name']!='m.nilai.KHSEkstension') {
				$_SESSION['currentPageKHSEkstension']=array('page_name'=>'m.nilai.KHSEkstension','page_num'=>0,'search'=>false,'iddosen_wali'=>'none','tahun_masuk'=>$_SESSION['tahun_masuk']);
			}   
			$_SESSION['currentPageKHSEkstension']['search']=false;
            $this->RepeaterS->PageSize=10;
            
            $this->tbCmbPs->DataSource=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');
            $this->tbCmbPs->Text=$_SESSION['kjur'];			
            $this->tbCmbPs->dataBind();	
            
            $tahun_masuk=$this->getAngkatan ();			            
			$this->tbCmbTahunMasuk->DataSource=$tahun_masuk	;					
			$this->tbCmbTahunMasuk->Text=$_SESSION['currentPageKHSEkstension']['tahun_masuk'];						
			$this->tbCmbTahunMasuk->dataBind();
            
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
            
            $this->tbCmbOutputCompress->DataSource=$this->setup->getOutputCompressType();
            $this->tbCmbOutputCompress->Text= $_SESSION['outputcompress'];
            $this->tbCmbOutputCompress->DataBind();
            
            $this->setInfoToolbar();
            
            $this->populateData();

		}
		
	}
    public function setInfoToolbar() {        
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
        $ta=$this->DMaster->getNamaTA($_SESSION['ta']);		
        $semester = $this->setup->getSemester($_SESSION['semester']);
		$tahunmasuk=$_SESSION['currentPageKHSEkstension']['tahun_masuk'] == 'none'?'':'Tahun Masuk '.$this->DMaster->getNamaTA($_SESSION['currentPageKHSEkstension']['tahun_masuk']);		        
		$this->lblModulHeader->Text="Program Studi $ps T.A $ta Semester $semester $tahunmasuk";        
	}
    public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageKHSEkstension']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageKHSEkstension']['search']);
	}
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function changeTbTA ($sender,$param) {				
		$_SESSION['ta']=$this->tbCmbTA->Text;		        
		$_SESSION['currentPageKHSEkstension']['tahun_masuk']=$_SESSION['ta'];
		$this->tbCmbTahunMasuk->DataSource=$this->getAngkatan();
		$this->tbCmbTahunMasuk->Text=$_SESSION['currentPageKHSEkstension']['tahun_masuk'];
		$this->tbCmbTahunMasuk->dataBind();		
        $this->setInfoToolbar();
		$this->populateData();
	}
	public function changeTbTahunMasuk($sender,$param) {				
		$_SESSION['currentPageKHSEkstension']['tahun_masuk']=$this->tbCmbTahunMasuk->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}
	public function changeTbPs ($sender,$param) {		
		$_SESSION['kjur']=$this->tbCmbPs->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}	
	public function changeTbSemester ($sender,$param) {		
		$_SESSION['semester']=$this->tbCmbSemester->Text;        
        $this->setInfoToolbar();
		$this->populateData();
	}	
	public function changeDW($sender,$param){
		$_SESSION['currentPageKHSEkstension']['iddosen_wali']=$this->cmbDosenWali->Text;				
		$this->populateData();
	}	
    public function searchRecord ($sender,$param) {
		$_SESSION['currentPageKHSEkstension']['search']=true;
		$this->populateData($_SESSION['currentPageKHSEkstension']['search']);
	}
    public function populateData($search=false) {				
		$ta=$_SESSION['ta'];
		$idsmt=$_SESSION['semester'];
        if ($search) {
            $str = "SELECT k.idkrs,k.tgl_krs,vdm.no_formulir,k.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.kjur,vdm.tahun_masuk,vdm.semester_masuk,vdm.idkelas,k.sah,k.tgl_disahkan FROM krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND vdm.idkelas='C' AND tahun='$ta' AND idsmt='$idsmt'";
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {                
                case 'nim' :
                    $clausa="AND vdm.nim='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND vdm.idkelas='C' AND tahun='$ta' AND idsmt='$idsmt' $clausa",'vdm.nim');
                    $str = "$str $clausa";
                break;
                case 'nirm' :
                    $clausa="AND vdm.nirm='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND vdm.idkelas='C' AND tahun='$ta' AND idsmt='$idsmt' $clausa",'vdm.nim');
                    $str = "$str $clausa";
                break;
                case 'nama' :
                    $clausa="AND vdm.nama_mhs LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND vdm.idkelas='C' AND tahun='$ta' AND idsmt='$idsmt' $clausa",'vdm.nim');
                    $str = "$str $clausa";
                break;
            }
        }else{
            $kjur=$_SESSION['kjur'];
            $tahun_masuk=$_SESSION['currentPageKHSEkstension']['tahun_masuk'];	
            $str_tahun_masuk=$tahun_masuk == 'none' ?'':"AND vdm.idkelas='C' AND vdm.tahun_masuk=$tahun_masuk";
            $iddosen_wali=$_SESSION['currentPageKHSEkstension']['iddosen_wali'];
            $str_dosen_wali=$iddosen_wali == 'none' ?'':"AND vdm.idkelas='C' AND vdm.iddosen_wali=$iddosen_wali";
            $str = "SELECT k.idkrs,k.tgl_krs,vdm.no_formulir,k.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.kjur,vdm.tahun_masuk,vdm.semester_masuk,vdm.idkelas,k.sah,k.tgl_disahkan FROM krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND vdm.idkelas='C' AND tahun='$ta' AND idsmt='$idsmt' AND kjur=$kjur $str_tahun_masuk $str_dosen_wali";
            
            $jumlah_baris=$this->DB->getCountRowsOfTable("krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND vdm.idkelas='C' AND tahun='$ta' AND idsmt='$idsmt' AND kjur=$kjur $str_tahun_masuk $str_dosen_wali",'k.idkrs');
        }
		
		$this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageKHSEkstension']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$offset=$this->RepeaterS->CurrentPageIndex*$this->RepeaterS->PageSize;
		$limit=$this->RepeaterS->PageSize;
		if ($offset+$limit>$this->RepeaterS->VirtualItemCount) {
			$limit=$this->RepeaterS->VirtualItemCount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageKHSEkstension']['page_num']=0;}
		$str = "$str ORDER BY vdm.nama_mhs ASC LIMIT $offset,$limit";				        
		$this->DB->setFieldTable(array('idkrs','tgl_krs','no_formulir','nim','nirm','nama_mhs','jk','kjur','tahun_masuk','semester_masuk','idkelas','sah','tgl_disahkan'));
		$result=$this->DB->getRecord($str);
		$this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();
        
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS);
	}
	
	public function itemBound ($sender,$param) {
		$item=$param->Item;
		if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem') {			
			$nim=$item->DataItem['nim'];						
			$this->Nilai->setDataMHS(array('nim'=>$nim));
            $bool=true;
            $ip='0.00';            
            $sks=0;
            $status='-';
            $trstyle='';
            $dataipk=array('ipk'=>'0.00','sks'=>0);
			if ($this->Nilai->isKrsSah($_SESSION['ta'],$_SESSION['semester'])) {                                                            
				$this->Nilai->getKHS($_SESSION['ta'],$_SESSION['semester']);
				$ip=$this->Nilai->getIPS ();
				$sks=$this->Nilai->getTotalSKS ();                
                $dataipk=$this->Nilai->getIPKSampaiTASemester($_SESSION['ta'],$_SESSION['semester'],'ipksks');	                				
			}else {
                $bool=false;
                $trstyle=' class="danger"';
                $status='<span class="label label-info">Belum disahkan</span>';				
			}            
            $item->literalTRStyle->Text=$trstyle;
            $item->literalStatus->Text=$status;
            $item->literalIP->Text=$ip;
            $item->literalIPK->Text=$dataipk['ipk'];
            $item->literalSKS->Text=$sks;
            $item->literalSKSTotal->Text=$dataipk['sks'];
            $item->btnPrintOutR->Enabled=$bool;
            $item->anchorDetailKHS->Enabled=$bool;
		}
	}
	
	public function printOut ($sender,$param) {		
        $this->createObj('reportnilai');
        $this->linkOutput->Text='';
        $this->linkOutput->NavigateUrl='#';
		switch ($sender->getId()) {
			case 'btnPrintOutR' :
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
                        $idkrs = $this->getDataKeyField($sender,$this->RepeaterS);	
                        $str = "SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.kjur,vdm.nama_ps,vdm.idkonsentrasi,k.nama_konsentrasi,iddosen_wali FROM krs LEFT JOIN v_datamhs vdm ON (krs.nim=vdm.nim) LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) WHERE krs.idkrs='$idkrs'";
                        $this->DB->setFieldTable(array('nim','nirm','nama_mhs','jk','kjur','nama_ps','idkonsentrasi','nama_konsentrasi','iddosen_wali'));
                        $r=$this->DB->getRecord($str);	           
                        $dataReport=$r[1];

                        $dataReport['nama_dosen']=$this->DMaster->getNamaDosenWaliByID ($dataReport['iddosen_wali']);

                        $tahun=$_SESSION['ta'];
                        $semester=$_SESSION['semester'];
                        $nama_tahun = $this->DMaster->getNamaTA($tahun);
                        $nama_semester = $this->setup->getSemester($semester);

                        $dataReport['ta']=$tahun;
                        $dataReport['semester']=$semester;
                        $dataReport['nama_tahun']=$nama_tahun;
                        $dataReport['nama_semester']=$nama_semester;        

                        $dataReport['nama_jabatan_khs']=$this->setup->getSettingValue('nama_jabatan_khs');
                        $dataReport['nama_penandatangan_khs']=$this->setup->getSettingValue('nama_penandatangan_khs');
                        $dataReport['jabfung_penandatangan_khs']=$this->setup->getSettingValue('jabfung_penandatangan_khs');
                        $dataReport['nidn_penandatangan_khs']=$this->setup->getSettingValue('nidn_penandatangan_khs');

                        $kaprodi=$this->Nilai->getKetuaPRODI($dataReport['kjur']);
                        $dataReport['nama_kaprodi']=$kaprodi['nama_dosen'];
                        $dataReport['jabfung_kaprodi']=$kaprodi['nama_jabatan'];
                        $dataReport['nidn_kaprodi']=$kaprodi['nidn'];

                        $dataReport['linkoutput']=$this->linkOutput; 
                        $this->report->setDataReport($dataReport); 
                        $this->report->setMode($_SESSION['outputreport']);

                        $messageprintout="Kartu Hasil Studi {$dataReport['nim']} : <br/>";
                        $this->report->printKHS($this->Nilai,true);	
                    break;
                }                
			break;			
            case 'btnPrintKHSAll' :
                switch ($_SESSION['outputreport']) {
                    case  'summarypdf' :
                        $messageprintout="Mohon maaf Print out pada mode summary pdf belum kami support.";                
                    break;
                    case  'summaryexcel' :
                        $tahun=$_SESSION['ta'];
                        $semester=$_SESSION['semester'];
                        $nama_tahun = $this->DMaster->getNamaTA($tahun);
                        $nama_semester = $this->setup->getSemester($semester);
                        
                        $dataReport['ta']=$tahun;
                        $dataReport['tahun_masuk']=$_SESSION['currentPageKHSEkstension']['tahun_masuk'];
                        $dataReport['semester']=$semester;
                        $dataReport['nama_tahun']=$nama_tahun;
                        $dataReport['nama_semester']=$nama_semester; 
                        $dataReport['kjur']=$_SESSION['kjur'];
                        $dataReport['nama_ps']=$_SESSION['daftar_jurusan'][$_SESSION['kjur']];
                        
                        $dataReport['nama_jabatan_khs']=$this->setup->getSettingValue('nama_jabatan_khs');
                        $dataReport['nama_penandatangan_khs']=$this->setup->getSettingValue('nama_penandatangan_khs');
                        $dataReport['jabfung_penandatangan_khs']=$this->setup->getSettingValue('jabfung_penandatangan_khs');
                        $dataReport['nidn_penandatangan_khs']=$this->setup->getSettingValue('nidn_penandatangan_khs');
                        
                        $kaprodi=$this->Nilai->getKetuaPRODI($dataReport['kjur']);
                        $dataReport['nama_kaprodi']=$kaprodi['nama_dosen'];
                        $dataReport['jabfung_kaprodi']=$kaprodi['nama_jabatan'];
                        $dataReport['nidn_kaprodi']=$kaprodi['nidn'];
                        
                        $dataReport['linkoutput']=$this->linkOutput; 
                        $this->report->setDataReport($dataReport); 
                        $this->report->setMode('excel2007');
                        
                        $messageprintout="Summary Kartu Hasil Studi: <br/>";
                        $this->report->printSummaryKHS($this->Nilai,true);
                        
                    break;
                    case  'excel2007' :
                        $messageprintout="Mohon maaf Print out pada mode excel 2007 tidak kami support.";                
                    break;
                    case  'pdf' :
                        $messageprintout="Mohon maaf Print out pada mode pdf tidak kami support.";                
                    break;                    
                }
            break;
		}		
        $this->lblMessagePrintout->Text=$messageprintout;
        $this->lblPrintout->Text='Kartu Hasil Studi';
        $this->modalPrintOut->show();
	}
}
?>