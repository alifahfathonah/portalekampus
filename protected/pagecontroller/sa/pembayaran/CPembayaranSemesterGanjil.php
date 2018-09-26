<?php
prado::using ('Application.MainPageSA');
class CPembayaranSemesterGanjil Extends MainPageSA {		
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showSubMenuPembayaran=true;  
        $this->showPembayaranSemesterGanjil=true;                
        $this->createObj('Finance');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePembayaranSemesterGanjil'])||$_SESSION['currentPagePembayaranSemesterGanjil']['page_name']!='sa.pembayaran.PembayaranSemesterGanjil') {
				$_SESSION['currentPagePembayaranSemesterGanjil']=array('page_name'=>'sa.pembayaran.PembayaranSemesterGanjil','page_num'=>0,'search'=>false,'ta'=>$this->setup->getSettingValue('default_ta'),'semester'=>1,'kelas'=>'none','DataMHS'=>array());												
			}
            $_SESSION['currentPagePembayaranSemesterGanjil']['search']=false; 
            $bool=!isset($_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS']['nim']);
            $daftar_ps=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');            
			$this->tbCmbPs->DataSource=$daftar_ps;
			$this->tbCmbPs->Text=$_SESSION['kjur'];			
			$this->tbCmbPs->dataBind();	
            
            $ta=$_SESSION['currentPagePembayaranSemesterGanjil']['ta'];
            $this->tbCmbTA->DataSource=$this->DMaster->removeIdFromArray($this->DMaster->getListTA (),'none');
            $this->tbCmbTA->Enabled=$bool;
            $this->tbCmbTA->Text=$ta;
            $this->tbCmbTA->dataBind();
            
            $kelas=$this->DMaster->getListKelas();
            $kelas['none']='All';
			$this->tbCmbKelas->DataSource=$kelas;
			$this->tbCmbKelas->Text=$_SESSION['currentPagePembayaranSemesterGanjil']['kelas'];			
            $this->tbCmbKelas->dataBind();	
            
            $this->populateData();
            $this->setInfoToolbar();
		}	
	}	
    public function setInfoToolbar() {                
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
        $ta=$this->DMaster->getNamaTA($_SESSION['currentPagePembayaranSemesterGanjil']['ta']);        		
		$this->lblModulHeader->Text="Program Studi $ps T.A $ta";        
	}
    public function changeTbPs ($sender,$param) {		
		$_SESSION['kjur']=$this->tbCmbPs->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}	
    public function changeTbTA ($sender,$param) {				
		$_SESSION['currentPagePembayaranSemesterGanjil']['ta']=$this->tbCmbTA->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}   
    public function changeTbKelas ($sender,$param) {				
		$_SESSION['currentPagePembayaranSemesterGanjil']['kelas']=$this->tbCmbKelas->Text;
        $this->setInfoToolbar(); 
		$this->populateData();
	}
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePembayaranSemesterGanjil']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPagePembayaranSemesterGanjil']['search']);
	}	
    public function searchRecord ($sender,$param) {
		$_SESSION['currentPagePembayaranSemesterGanjil']['search']=true;
		$this->populateData($_SESSION['currentPagePembayaranSemesterGanjil']['search']);
	}
	public function populateData($search=false) {		
		$ta=$_SESSION['currentPagePembayaranSemesterGanjil']['ta'];
		$semester=$_SESSION['currentPagePembayaranSemesterGanjil']['semester'];
		$kjur=$_SESSION['kjur'];	
        
        $kelas=$_SESSION['currentPagePembayaranSemesterGanjil']['kelas'];
        $str_kelas = $kelas == 'none'?'':" AND t.idkelas='$kelas'";
        if ($search) {
            $str = "SELECT t.no_transaksi,no_faktur,t.tanggal,t.nim,vdm.nama_mhs,commited FROM transaksi t JOIN v_datamhs vdm ON (t.nim=vdm.nim) WHERE t.tahun='$ta' AND t.idsmt='$semester'";            
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'no_faktur' :
                    $clausa="AND t.no_faktur='$txtsearch'";                    
                    $jumlah_baris=$this->DB->getCountRowsOfTable("transaksi t JOIN v_datamhs vdm ON (t.nim=vdm.nim) WHERE t.tahun='$ta' AND t.idsmt='$semester' $clausa",'no_transaksi');	
                    $str = "$str $clausa";
                break;
                case 'nim' :                    
                    $clausa="AND t.nim='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable("transaksi t JOIN v_datamhs vdm ON (t.nim=vdm.nim) WHERE t.tahun='$ta' AND t.idsmt='$semester' $clausa",'no_transaksi');	                    
                    $str = "$str $clausa";
                break;
                case 'nama' :                    
                    $clausa="AND vdm.nama_mhs LIKE '%$txtsearch%'";                    
                    $jumlah_baris=$this->DB->getCountRowsOfTable("transaksi t JOIN v_datamhs vdm ON (t.nim=vdm.nim) WHERE t.tahun='$ta' AND t.idsmt='$semester' $clausa",'no_transaksi');	
                    $str = "$str $clausa";
                break;
            }
        }else{
            $str = "SELECT t.no_transaksi,no_faktur,t.tanggal,t.nim,vdm.nama_mhs,commited FROM transaksi t JOIN v_datamhs vdm ON (t.nim=vdm.nim) WHERE t.tahun='$ta' AND t.idsmt='$semester' AND t.kjur=$kjur $str_kelas";
            $jumlah_baris=$this->DB->getCountRowsOfTable(" transaksi t JOIN v_datamhs vdm ON (t.nim=vdm.nim) WHERE t.tahun='$ta' AND t.idsmt='$semester' AND t.kjur=$kjur $str_kelas",'no_transaksi');
        }
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPagePembayaranSemesterGanjil']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;   
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPagePembayaranSemesterGanjil']['page_num']=0;}
        $this->DB->setFieldTable(array('no_transaksi','no_faktur','tanggal','nim','nama_mhs','commited'));
        $str = "$str ORDER BY t.date_added DESC LIMIT $offset,$limit";	
        $r = $this->DB->getRecord($str,$offset+1);	        
        $result=array();		
		while (list($k,$v)=each($r)) {
			$no_transaksi=$v['no_transaksi'];				
			$str2 = "SELECT SUM(dibayarkan) AS dibayarkan FROM v_transaksi t WHERE no_transaksi=$no_transaksi";			
			$this->DB->setFieldTable(array('dibayarkan'));
			$r2=$this->DB->getRecord($str2);				
			$dibayarkan=$r2[1]['dibayarkan'];						
			$v['dibayarkan']=$this->Finance->toRupiah($dibayarkan);													
			$v['tanggal']=$this->TGL->tanggal('d/m/Y',$v['tanggal']);
			$result[$k]=$v;
		}
        $this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();     
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS);
    }
    public function setDataBound($sender,$param) {				
		$item=$param->Item;
		if ($item->ItemType==='Item' || $item->ItemType==='AlternatingItem') {			

		}		
	}	
    public function batalkanTransaksi ($sender,$param) {		
        $no_transaksi=$this->getDataKeyField($sender,$this->RepeaterS);		
        $str = "UPDATE transaksi SET commited=0 WHERE no_transaksi=$no_transaksi";
        $this->DB->updateRecord($str);
        $this->redirect ('pembayaran.PembayaranSemesterGanjil',true);        	
    }		
}