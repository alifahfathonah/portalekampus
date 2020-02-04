<?php
prado::using ('Application.MainPagePMB');
class CDetailPembayaranFormulir Extends MainPagePMB {
    public static $TotalSudahBayar=0;
    public static $KewajibanMahasiswa=0;
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showPembayaranFormulir=true;                
        $this->createObj('Finance');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePembayaranFormulir'])||$_SESSION['currentPagePembayaranFormulir']['page_name']!='pmb.keuangan.PembayaranFormulir') {
				$_SESSION['currentPagePembayaranFormulir']=array('page_name'=>'pmb.keuangan.PembayaranFormulir','page_num'=>0,'search'=>false,'kelas'=>'none','semester_masuk'=>1,'DataMHS'=>array());												
			}        
            try {
                $no_formulir=addslashes($this->request['id']);
                $str = "SELECT no_formulir,idkelas,tahun_masuk,1 AS semester_masuk FROM pin WHERE no_formulir='$no_formulir'";
                $this->DB->setFieldTable(array('no_formulir','idkelas','tahun_masuk','semester_masuk'));
                $r=$this->DB->getRecord($str);
                if (!isset($r[1])) {                                
                    throw new Exception ("Calon Mahasiswa dengan Nomor Formulir ($no_formulir) tidak terdaftar di Database, silahkan ganti dengan yang lain.");		
                }
                $datamhs=$r[1];                
                $this->Finance->setDataMHS($datamhs);                
                $datamhs['nkelas']=$this->DMaster->getNamaKelasByID($datamhs['idkelas']);
                $this->Finance->setDataMHS($datamhs);                
                $datamhs['no_transaksi']=isset($_SESSION['currentPagePembayaranFormulir']['DataMHS']['no_transaksi']) ? $_SESSION['currentPagePembayaranFormulir']['DataMHS']['no_transaksi'] : 'none';
                $datamhs['nama_tahun_masuk']=$this->DMaster->getNamaTA($datamhs['tahun_masuk']);
				
				$_SESSION['currentPagePembayaranFormulir']['DataMHS']=$datamhs;          
				
                CDetailPembayaranFormulir::$KewajibanMahasiswa=$this->Finance->getBiayaPendaftaran ($datamhs['tahun_masuk'],$datamhs['semester_masuk'],$datamhs['idkelas']);
                $this->populateTransaksi();
            }catch (Exception $ex) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$ex->getMessage();
            }      
		}	
	}
    public function getDataMHS($idx) {              
        if (isset($_SESSION['currentPagePembayaranFormulir']['DataMHS']['no_formulir'])) {
            return $_SESSION['currentPagePembayaranFormulir']['DataMHS'][$idx];
        }        
    }
    public function populateTransaksi() {
        $datamhs=$_SESSION['currentPagePembayaranFormulir']['DataMHS'];        
        $no_formulir=$datamhs['no_formulir'];
        $tahun=$datamhs['tahun_masuk'];
        $idsmt=$datamhs['semester_masuk'];        
        $str = "SELECT t.no_transaksi,t.no_faktur,tanggal,t.commited,fpt.no_pendaftaran,t.date_added FROM transaksi t LEFT JOIN formulir_pendaftaran_temp fpt ON (fpt.no_formulir=t.no_formulir) WHERE t.tahun='$tahun' AND t.idsmt='$idsmt' AND t.no_formulir='$no_formulir' AND kjur=0";
        $this->DB->setFieldTable(array('no_transaksi','no_faktur','tanggal','commited','no_pendaftaran','date_added'));
        $r=$this->DB->getRecord($str);
        $result=array();
        while (list($k,$v)=each($r)) {
            $no_transaksi=$v['no_transaksi'];
			$v['no_pendaftaran']=$v['no_pendaftaran']>0?$v['no_pendaftaran']:'N.A';
            $v['total']=$this->DB->getSumRowsOfTable('dibayarkan',"transaksi_detail WHERE no_transaksi=$no_transaksi");
            $result[$k]=$v;
        }
        $this->ListTransactionRepeater->DataSource=$result;
        $this->ListTransactionRepeater->dataBind();        
    }
	public function dataBoundListTransactionRepeater ($sender,$param) {
		$item=$param->Item;
		if ($item->ItemType==='Item' || $item->ItemType==='AlternatingItem') {			
			if ($item->DataItem['commited']) {
                $item->btnDeleteFromRepeater->Enabled=false;				
                $item->btnEditFromRepeater->Enabled=false;			
                $item->btnEditFromRepeater->CssClass='table-link default';
                $item->btnDeleteFromRepeater->CssClass='table-link default';
			}else{
                $item->btnDeleteFromRepeater->Attributes->onclick="if(!confirm('Apakah Anda ingin menghapus Transaksi ini?')) return false;";
            }
            CDetailPembayaranFormulir::$TotalSudahBayar+=$item->DataItem['total'];
		}
	}	
	public function addTransaction ($sender,$param) {
        $datamhs=$_SESSION['currentPagePembayaranFormulir']['DataMHS'];        
        if ($datamhs['no_transaksi'] == 'none') {
            $no_formulir=$datamhs['no_formulir'];
            $ta=$datamhs['tahun_masuk'];                        
            $idsmt=$datamhs['semester_masuk'];
            $idkelas=$datamhs['idkelas'];
            $this->Finance->setDataMHS($datamhs);
            if ($this->Finance->getBiayaPendaftaran($ta,$idsmt,$idkelas)<=0) {
                $nama_semester=$this->setup->getSemester($idsmt);
                $this->lblContentMessageError->Text="Tidak bisa menambah Transaksi baru karena komponen biaya di Tahun Masuk $ta semester $nama_semester belum disetting.";
                $this->modalMessageError->show();
            }elseif ($this->Finance->getLunasPembayaranFormulir()) {
                $this->lblContentMessageError->Text='Tidak bisa menambah Transaksi baru karena sudah lunas.';
                $this->modalMessageError->show();
            }elseif ($this->DB->checkRecordIsExist('no_formulir','transaksi',$no_formulir," AND tahun='$ta' AND idsmt='$idsmt' AND commited=0")) {
                $this->lblContentMessageError->Text='Tidak bisa menambah Transaksi baru karena ada transaksi yang belum di Commit.';
                $this->modalMessageError->show();
            }else{
                $no_transaksi='10'.$ta.$idsmt.mt_rand(10000,99999);
                $no_faktur=$ta.$no_transaksi;        
                $idkelas=$datamhs['idkelas'];
                $userid=$this->Pengguna->getDataUser('userid');

                $this->DB->query ('BEGIN');
                $str = "INSERT INTO transaksi SET no_transaksi=$no_transaksi,no_faktur='$no_faktur',kjur=0,tahun='$ta',idsmt='$idsmt',idkelas='$idkelas',no_formulir='$no_formulir',nim=0,tanggal=NOW(),jumlah_sks=0,disc=0,userid='$userid',date_added=NOW(),date_modified=NOW()";
                if ($this->DB->insertRecord($str)) {
                    $str = "SELECT idkombi,SUM(dibayarkan) AS sudah_dibayar FROM v_transaksi WHERE no_formulir=$no_formulir AND tahun=$ta AND idsmt=$idsmt AND commited=1 GROUP BY idkombi ORDER BY idkombi+1 ASC";
                    $this->DB->setFieldTable(array('idkombi','sudah_dibayar'));
                    $d=$this->DB->getRecord($str);

                    $sudah_dibayarkan=array();
                    while (list($o,$p)=each($d)) {            
                        $sudah_dibayarkan[$p['idkombi']]=$p['sudah_dibayar'];
                    }
                    $str = "SELECT k.idkombi,kpt.biaya FROM kombi_per_ta kpt,kombi k WHERE k.idkombi=kpt.idkombi AND tahun=$ta AND idsmt=$idsmt AND kpt.idkelas='$idkelas' AND k.idkombi=1 ORDER BY periode_pembayaran,nama_kombi ASC";
                    $this->DB->setFieldTable(array('idkombi','biaya'));
                    $r=$this->DB->getRecord($str);

                    while (list($k,$v)=each($r)) {
                        $biaya=$v['biaya'];
                        $idkombi=$v['idkombi'];
                        $sisa_bayar=isset($sudah_dibayarkan[$idkombi])?$biaya-$sudah_dibayarkan[$idkombi]:$biaya;
                        $str = "INSERT INTO transaksi_detail (idtransaksi_detail,no_transaksi,idkombi,dibayarkan,jumlah_sks) VALUES(NULL,$no_transaksi,$idkombi,$sisa_bayar,0)";
                        $this->DB->insertRecord($str);
                    }
                    
                    $this->DB->query('COMMIT');
                    $_SESSION['currentPagePembayaranFormulir']['DataMHS']['no_transaksi']=$no_transaksi;            
                    $this->redirect('keuangan.TransaksiPembayaranFormulir',true);        
                }else{
                    $this->DB->query('ROLLBACK');
                }           
            }
        }else{            
            $this->redirect('keuangan.TransaksiPembayaranFormulir',true); 
        }
	}
    public function editRecord ($sender,$param) {	        
        $datamhs=$_SESSION['currentPagePembayaranFormulir']['DataMHS'];    
        if ($datamhs['no_transaksi'] == 'none') {
            $no_transaksi=$this->getDataKeyField($sender,$this->ListTransactionRepeater);		
            $_SESSION['currentPagePembayaranFormulir']['DataMHS']['no_transaksi']=$no_transaksi;
        }	
		$this->redirect('keuangan.TransaksiPembayaranFormulir',true);
	}	
	public function deleteRecord ($sender,$param) {	
        $datamhs=$_SESSION['currentPagePembayaranFormulir']['DataMHS']; 
        $no_formulir=$datamhs['no_formulir'];
		$no_transaksi=$this->getDataKeyField($sender,$this->ListTransactionRepeater);		
		$this->DB->deleteRecord("transaksi WHERE no_transaksi='$no_transaksi'");		
		$this->redirect('keuangan.DetailPembayaranFormulir',true,array('id'=>$no_formulir));
	}		
    public function closeDetail ($sender,$param) {
        unset($_SESSION['currentPagePembayaranFormulir']['DataMHS']);
        $this->redirect('keuangan.PembayaranFormulir',true);
    }
}