<?php
class Login extends MainPage { 
    public function OnPreInit ($param) {	
		parent::onPreInit ($param);	
		$this->MasterClass="Application.layouts.LoginTemplate";				
        $this->Theme='nifty';
	}
	public function onLoad($param) {		
		parent::onLoad($param);				
		if (!$this->IsPostBack&&!$this->IsCallBack) { 
            $this->loggerlogin->Visible=$this->setup->getSettingValue('jslogger');            
		}
	}
    private function getGoingToPage () {		
		switch ($this->cmbGoingTo->Text) {
            case 'sa' :
				$page = 'SuperAdmin';
			break;
			case 'm' :
				$page = 'Manajemen';
			break;
			case 'k' :
				$page = 'Keuangan';
			break;  
            case 'on' :
				$page = 'OperatorNilai';
			break;
            case 'd' :
				$page = 'Dosen';
			break;
			case 'dw' :
				$page = 'DosenWali';
			break;
			case 'mh' :
				$page = 'Mahasiswa';
			break;
            case 'al' :
				$page = 'Alumni';
			break;
			case 'mb' :
				$page = 'MahasiswaBaru';
			break;		
			case 'ot' :
				$page = 'OrangtuaWali';
			break;
		}		
		return $page;
	}
	public function checkUsernameAndPassword($sender,$param) {		
        $username=addslashes($param->Value);
        if ($username != '') {
            try {  
                $auth = $this->Application->getModule ('auth');	
                $page=$this->getGoingToPage ();		
                $password = trim(addslashes($this->txtPassword->Text));
                $auth->login ($username.'/'.$page,$password);	
            }catch (Exception $e) {		
                $message='<br /><div class="alert alert-danger">
                    <strong>Error!</strong>
                    '.$e->getMessage().'</div>';
				$sender->ErrorMessage=$message;					
				$param->IsValid=false;		
			}
        }							
		
	}    
    public function checkUsernameFormat($sender,$param) {		
        $username=addslashes($param->Value);
        if ($username != '') {
            try {  
                if (!preg_match('/^[a-z\d_]{1,20}$/i', $username)||filter_var($username,FILTER_VALIDATE_EMAIL)) {			                    
                    throw new Exception ("Gagal. Silahkan masukan username dan password dengan benar.");
                }
            }catch (Exception $e) {		
                $message='<br /><div class="alert alert-danger">
                    <strong>Error!</strong>
                    '.$e->getMessage().'</div>';
				$sender->ErrorMessage=$message;					
				$param->IsValid=false;		
			}
        }							
		
	}
    public function doLogin ($sender,$param) {
        if ($this->IsValid) {                        
            $pengguna=$this->getLogic('Users');      
            $setup=$this->getLogic('Setup');
            $dmaster=$this->getLogic('DMaster');
            switch ($pengguna->getTipeUser()) {
                case 'sa' :
                    //daftar prodi diload saat awal, tujuannya supaya tidak terus2an diload.
                    $_SESSION['daftar_jurusan']=$dmaster->getListProgramStudi(2);
                    $_SESSION['kjur']=$setup->getSettingValue('default_kjur');
                    $_SESSION['ta']=$setup->getSettingValue('default_ta');
                    $_SESSION['tahun_masuk']=$_SESSION['ta'];
                    $_SESSION['tahun_pendaftaran']=$setup->getSettingValue('default_tahun_pendaftaran');
                    $_SESSION['semester']=$setup->getSettingValue('default_semester');
                    
                    $foto='resources/userimages/no_photo.png';
                break; 
                case 'dw' :
                    //daftar prodi diload saat awal, tujuannya supaya tidak terus2an diload.
                    $_SESSION['daftar_jurusan']=$dmaster->getListProgramStudi(2);
                    $_SESSION['kjur']=$setup->getSettingValue('default_kjur');           
                    $_SESSION['ta']=$setup->getSettingValue('default_ta'); 
                    $_SESSION['tahun_masuk']=$_SESSION['ta'];
                    $_SESSION['tahun_pendaftaran']=$setup->getSettingValue('default_tahun_pendaftaran');
                    $_SESSION['semester']=$setup->getSettingValue('default_semester');
                    
                    $foto='resources/userimages/no_photo.png';
                break;
                case 'd' :
                    //daftar prodi diload saat awal, tujuannya supaya tidak terus2an diload.
                    $_SESSION['daftar_jurusan']=$dmaster->getListProgramStudi(2);
                    $_SESSION['kjur']=$setup->getSettingValue('default_kjur');           
                    $_SESSION['ta']=$setup->getSettingValue('default_ta'); 
                    $_SESSION['tahun_masuk']=$_SESSION['ta'];
                    $_SESSION['tahun_pendaftaran']=$setup->getSettingValue('default_tahun_pendaftaran');
                    $_SESSION['semester']=$setup->getSettingValue('default_semester');
                    
                    $foto='resources/userimages/no_photo.png';
                break;
                case 'm' :                    
                    //daftar prodi diload saat awal, tujuannya supaya tidak terus2an diload.
                    $_SESSION['daftar_jurusan']=$dmaster->getListProgramStudi(2);
                    $_SESSION['kjur']=$setup->getSettingValue('default_kjur');           
                    $_SESSION['ta']=$setup->getSettingValue('default_ta'); 
                    $_SESSION['tahun_masuk']=$_SESSION['ta'];
                    $_SESSION['tahun_pendaftaran']=$setup->getSettingValue('default_tahun_pendaftaran'); 
                    $_SESSION['semester']=$setup->getSettingValue('default_semester');
                    
                    $foto=$pengguna->getDataUser('photo_profile');
                break;                
                case 'mh' :
                    $_SESSION['ta']=$setup->getSettingValue('default_ta') < $pengguna->getDataUser('tahun_masuk') ? $pengguna->getDataUser('tahun_masuk') :$setup->getSettingValue('default_ta');
                    $_SESSION['semester']=$setup->getSettingValue('default_semester');
                    $_SESSION['tahun_masuk']=$pengguna->getDataUser('tahun_masuk');
                    $_SESSION['tahun_pendaftaran']=$_SESSION['tahun_masuk'];
                    $foto=$pengguna->getDataUser('photo_profile');
                break;
                case 'k' :
                    //daftar prodi diload saat awal, tujuannya supaya tidak terus2an diload.
                    $_SESSION['daftar_jurusan']=$dmaster->getListProgramStudi(2);
                    $_SESSION['kjur']=$setup->getSettingValue('default_kjur');
                    $_SESSION['ta']=$setup->getSettingValue('default_ta');  
                    $_SESSION['tahun_masuk']=$_SESSION['ta'];
                    $_SESSION['tahun_pendaftaran']=$setup->getSettingValue('default_tahun_pendaftaran'); 
                    $_SESSION['semester']=$setup->getSettingValue('default_semester');
                    
                    $foto='resources/userimages/no_photo.png';
                break; 
                case 'on' :
                    $group_id=$pengguna->getDataUser('group_id');
                    if ($group_id==3) {//prodi
                        $kjur=$pengguna->getDataUser('kjur');
                        $daftar_jurusan=$dmaster->getListProgramStudi(2);
                        $_SESSION['daftar_jurusan']=array($kjur=>$daftar_jurusan[$kjur]);
                        $_SESSION['kjur']=$kjur;
                        $_SESSION['ta']=$setup->getSettingValue('default_ta'); 
                    }else{
                        $_SESSION['daftar_jurusan']=$dmaster->getListProgramStudi(2);
                        $_SESSION['kjur']=$setup->getSettingValue('default_kjur');
                        $_SESSION['ta']=$setup->getSettingValue('default_ta');
                    }                                
                    $_SESSION['semester']=$setup->getSettingValue('default_semester');
                    $_SESSION['tahun_masuk']=$_SESSION['ta'];
                    $_SESSION['tahun_pendaftaran']=$setup->getSettingValue('default_tahun_pendaftaran'); 
                    $foto='resources/userimages/no_photo.png';
                break; 
                case 'mb' :
                    $_SESSION['ta']=$pengguna->getDataUser('tahun_masuk');
                    //daftar prodi diload saat awal, tujuannya supaya tidak terus2an diload.
                    $_SESSION['daftar_jurusan']=$dmaster->getListProgramStudi(2); 
                    
                    $_SESSION['tahun_masuk']=$pengguna->getDataUser('tahun_masuk');
                    $_SESSION['tahun_pendaftaran']=$_SESSION['tahun_masuk'];
                    $_SESSION['semester']=$pengguna->getDataUser('semester_masuk');
                    
                    $foto=$pengguna->getDataUser('photo_profile');
                break; 
            }
            $_SESSION['kelas']='none';
            $_SESSION['foto']=$foto;
            $_SESSION['theme']=$pengguna->getDataUser('theme');
            
            $_SESSION['outputreport']='pdf';
            $_SESSION['outputcompress']='none';
            
            $this->redirect('Home',true);
        }
    }    
}