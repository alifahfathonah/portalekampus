<?php
prado::using ('Application.MainPageM');
class CDetailDPNA extends MainPageM {    
   	public function onLoad($param) {
		parent::onLoad($param);							
		$this->showSubMenuAkademikNilai=true;
        $this->showDPNA=true;    
        $this->createObj('Akademik');        
        $this->createObj('Nilai');        
        
		if (!$this->IsPostback&&!$this->IsCallback) {             			
            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();            
            try {
                $idpenyelenggaraan=addslashes($this->request['id']);                                
                $this->Demik->getInfoMatkul($idpenyelenggaraan,'penyelenggaraan');
                if (!isset($this->Demik->InfoMatkul['idpenyelenggaraan'])) {                                                
                    throw new Exception ("Kode penyelenggaraan dengan id ($idpenyelenggaraan) tidak terdaftar.");		
                }                
                $this->Demik->InfoMatkul['nama_ps']=$_SESSION['daftar_jurusan'][$this->Demik->InfoMatkul['kjur']];
                $this->Demik->InfoMatkul['ta']=$this->DMaster->getNamaTA($this->Demik->InfoMatkul['tahun']);
                $this->Demik->InfoMatkul['nama_semester']=$this->setup->getSemester($this->Demik->InfoMatkul['idsmt']);                
                $_SESSION['currentPageDPNA']['DataDPNA']=$this->Demik->InfoMatkul;                
                //daftar kelas            
                $str = "SELECT km.idkelas_mhs,km.idkelas,km.nama_kelas FROM pengampu_penyelenggaraan pp,kelas_mhs km WHERE pp.idpengampu_penyelenggaraan=km.idpengampu_penyelenggaraan AND pp.idpenyelenggaraan=$idpenyelenggaraan";
                $this->DB->setFieldTable(array('idkelas_mhs','idkelas','nama_kelas'));
                $r=$this->DB->getRecord($str);
                if (isset($r[1])) {
                    $listkelas=$this->DMaster->getListKelas();
                    $daftar_kelas['none']=' ';
                    while (list($k,$v)=each($r)) {
                        $nama_kelas=$listkelas[$v['idkelas']].'-'.chr($v['nama_kelas']+64);
                        $daftar_kelas[$v['idkelas_mhs']]=$nama_kelas;
                    }                
                    $this->cmbDaftarKelas->Enabled=true;
                    $this->cmbDaftarKelas->DataSource=$daftar_kelas;                
                    $this->cmbDaftarKelas->Text=$_SESSION['currentPageDPNA']['idkelas_mhs'];
                    $this->cmbDaftarKelas->DataBind();
                    $this->btnFilter->Enabled=true;                
                }else{
                    $this->panelErrorKelasEmpty->Visible=true;
                }                
                $this->populateData();	                              
                $this->populateInfoKelas($_SESSION['currentPageDPNA']['idkelas_mhs']);
            } catch (Exception $ex) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$ex->getMessage();
            }
		}
	}    
    public function filterRecord ($sender,$param) {
        $this->Demik->InfoMatkul=$_SESSION['currentPageDPNA']['DataDPNA'];
		$_SESSION['currentPageDPNA']['idkelas_mhs']=$this->cmbDaftarKelas->Text;
		$this->populateData($_SESSION['currentPageDPNA']['search']);        
        $this->populateInfoKelas($_SESSION['currentPageDPNA']['idkelas_mhs']);
        $this->InfoKelasPanel->render($param->NewWriter);
	}
	protected function populateData() {		        
        $idpenyelenggaraan=addslashes($this->request['id']);
        $idkelas=$_SESSION['currentPageDPNA']['idkelas_mhs'];
        $str = $idkelas == 'none' ?"SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,n.n_kuan,n.n_kual FROM v_krsmhs vkm JOIN v_datamhs vdm ON(vdm.nim=vkm.nim) LEFT JOIN nilai_matakuliah n ON (n.idkrsmatkul=vkm.idkrsmatkul) WHERE vkm.idpenyelenggaraan=$idpenyelenggaraan AND vkm.sah=1 AND vkm.batal=0 ORDER BY vdm.nama_mhs ASC":"SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,n.n_kuan,n.n_kual FROM kelas_mhs_detail kmd LEFT JOIN nilai_matakuliah n ON (n.idkrsmatkul=kmd.idkrsmatkul) JOIN v_krsmhs vkm ON (vkm.idkrsmatkul=kmd.idkrsmatkul)  JOIN v_datamhs vdm ON (vkm.nim=vdm.nim) WHERE  kmd.idkelas_mhs=$idkelas AND vkm.sah=1 AND vkm.batal=0 ORDER BY vdm.nama_mhs ASC";        
        $this->DB->setFieldTable(array('nim','nirm','nama_mhs','jk','n_kuan','n_kual'));
        $r=$this->DB->getRecord($str);	           
        $result=array();
        $sks=$_SESSION['currentPageDPNA']['DataDPNA']['sks'];
        while (list($k,$v)=each($r)) {                
            $n_kuan='-';
            $n_kual='-';
            $am='-';
            $hm='-';
            if ($v['n_kual']!= '') {
                $n_kuan=$v['n_kuan'];
                $n_kual=$v['n_kual'];
                $am=$this->Nilai->getAngkaMutu($v['n_kual']);
                $hm=$am*$sks;
            }
            $v['n_kuan']=$n_kuan;
            $v['n_kual']=$n_kual;
            $v['am']=$am;
            $v['hm']=$hm;
            $result[$k]=$v;
        }
        $this->RepeaterS->DataSource=$result;
        $this->RepeaterS->dataBind();             
	}    
    protected function populateInfoKelas ($idkelas_mhs) {        
        $infokelas='';
        $dataDPNA=$_SESSION['currentPageDPNA']['DataDPNA'];
        $dataDPNA['idkelas_mhs']=$idkelas_mhs;
        if ($idkelas_mhs == 'none') {
            $dataDPNA['idjabatan_dosen_pengajar']=0;
        }
        else
        {
            $str = "SELECT idkelas,nama_kelas,hari,jam_masuk,jam_keluar,d.nidn AS nidn_dosen_pengajar,CONCAT(d.gelar_depan,' ',d.nama_dosen,' ',d.gelar_belakang) AS nama_dosen_pengajar,d.idjabatan AS idjabatan_dosen_pengajar FROM kelas_mhs km,pengampu_penyelenggaraan pp,dosen d WHERE km.idpengampu_penyelenggaraan=pp.idpengampu_penyelenggaraan AND d.iddosen=pp.iddosen AND idkelas_mhs=$idkelas_mhs";
            $this->DB->setFieldTable(array('idkelas','nama_kelas','hari','jam_masuk','jam_keluar','nidn_dosen_pengajar','nama_dosen_pengajar','idjabatan_dosen_pengajar'));
            $r=$this->DB->getRecord($str);
            $datakelas=$r[1];
            $datakelas['namakelas']=$this->DMaster->getNamaKelasByID($datakelas['idkelas']).'-'.chr($datakelas['nama_kelas']+64);
            $datakelas['hari']=$this->TGL->getNamaHari($datakelas['hari']);            
            $dataDPNA['idkelas']=$datakelas['idkelas'];
            $dataDPNA['nama_kelas']=$datakelas['nama_kelas'];
            $dataDPNA['namakelas']=$datakelas['namakelas'];
            $dataDPNA['hari']=$datakelas['hari'];
            $dataDPNA['jam_masuk']=$datakelas['jam_masuk'];
            $dataDPNA['jam_keluar']=$datakelas['jam_keluar'];
            $dataDPNA['nidn_dosen_pengajar']=$datakelas['nidn_dosen_pengajar'];
            $dataDPNA['nama_dosen_pengajar']=$datakelas['nama_dosen_pengajar'];
            $dataDPNA['idjabatan_dosen_pengajar']=$datakelas['idjabatan_dosen_pengajar'];                            
            $dataDPNA['nama_jabatan_dosen_pengajar']=$this->DMaster->getNamaJabfungByID($datakelas['idjabatan_dosen_pengajar']);                        
            $infokelas .= '<div class="col-lg-6">';
                $infokelas .= '<div class="form-horizontal">';            
                    $infokelas .= '<div class="form-group">';
                        $infokelas .= '<label class="col-sm-3 control-label"><strong>NAMA KELAS: </strong></label>';
                        $infokelas .= '<div class="col-sm-8">';
                        $infokelas .= '<p class="form-control-static">'.$datakelas['namakelas'].'</p>';
                        $infokelas .= '</div>';
                    $infokelas .= '</div>';
                    $infokelas .= '<div class="form-group">';
                        $infokelas .= '<label class="col-sm-3 control-label"><strong>HARI: </strong></label>';
                        $infokelas .= '<div class="col-sm-8">';
                        $infokelas .= '<p class="form-control-static">'.$datakelas['hari'].'</p>';
                        $infokelas .= '</div>';
                    $infokelas .= '</div>';
                    $infokelas .= '<div class="form-group">';
                        $infokelas .= '<label class="col-sm-3 control-label"><strong>JAM: </strong></label>';
                        $infokelas .= '<div class="col-sm-8">';
                        $infokelas .= '<p class="form-control-static">'.$datakelas['jam_masuk'].'-'.$datakelas['jam_keluar'].'</p>';
                        $infokelas .= '</div>';
                    $infokelas .= '</div>';
                    $infokelas .= '<div class="form-group">';
                        $infokelas .= '<label class="col-sm-3 control-label"><strong>DOSEN PENGAJAR: </strong></label>';
                        $infokelas .= '<div class="col-sm-8">';
                            $infokelas .= '<p class="form-control-static">'.$datakelas['nama_dosen_pengajar'].' ['.$datakelas['nidn_dosen_pengajar'].']</p>';
                        $infokelas .= '</div>';
                    $infokelas .= '</div>';
                $infokelas .= '</div>';
            $infokelas .= '</div>';
        }
        $_SESSION['currentPageDPNA']['DataDPNA']=$dataDPNA;
        $this->literalInfoKelas->Text=$infokelas;
    }
	public function printOut ($sender,$param) {	
        $this->Demik->InfoMatkul=$_SESSION['currentPageDPNA']['DataDPNA'];
        $this->createObj('reportnilai');
        $this->linkOutput->Text='';
        $this->linkOutput->NavigateUrl='#';
        switch ($_SESSION['outputreport']) {
            case  'summarypdf' :
                $messageprintout="Mohon maaf Print out pada mode summary pdf tidak kami support.";                
            break;
            case  'summaryexcel' :
                $messageprintout="Mohon maaf Print out pada mode summary excel tidak kami support.";                
            break;
            case 'pdf' :
                $dataReport=$_SESSION['currentPageDPNA']['DataDPNA'];        
                $nama_matakuliah=$dataReport['nmatkul'];
                
                $messageprintout="Matakuliah $nama_matakuliah";
                $dataReport['nama_pt_alias']=$this->setup->getSettingValue('nama_pt_alias');
                $dataReport['nama_jabatan_dpna']=$this->setup->getSettingValue('nama_jabatan_dpna');
                $dataReport['nama_penandatangan_dpna']=$this->setup->getSettingValue('nama_penandatangan_dpna');
                $dataReport['jabfung_penandatangan_dpna']=$this->setup->getSettingValue('jabfung_penandatangan_dpna');
                $dataReport['nidn_penandatangan_dpna']=$this->setup->getSettingValue('nidn_penandatangan_dpna');

                $dataReport['linkoutput']=$this->linkOutput; 
                $this->report->setDataReport($dataReport); 
                $this->report->setMode($_SESSION['outputreport']);

                $this->report->printDPNA($this->Nilai,true);
                
            break;
            case  'excel2007' :
                $dataReport=$_SESSION['currentPageDPNA']['DataDPNA'];        
                $nama_matakuliah=$dataReport['nmatkul'];
                
                $messageprintout="Matakuliah $nama_matakuliah";

                $dataReport['nama_pt_alias']=$this->setup->getSettingValue('nama_pt_alias');
                $dataReport['nama_jabatan_dpna']=$this->setup->getSettingValue('nama_jabatan_dpna');
                $dataReport['nama_penandatangan_dpna']=$this->setup->getSettingValue('nama_penandatangan_dpna');
                $dataReport['jabfung_penandatangan_dpna']=$this->setup->getSettingValue('jabfung_penandatangan_dpna');
                $dataReport['nidn_penandatangan_dpna']=$this->setup->getSettingValue('nidn_penandatangan_dpna');

                $dataReport['linkoutput']=$this->linkOutput; 
                $this->report->setDataReport($dataReport); 
                $this->report->setMode($_SESSION['outputreport']);

                $this->report->printDPNA($this->Nilai,true);
            break;
        }        
        $this->lblMessagePrintout->Text=$messageprintout;
        $this->lblPrintout->Text='Daftar Peserta dan Nilai Akhir';
        $this->modalPrintOut->show();
	}
}