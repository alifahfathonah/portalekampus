<?php
prado::using ('Application.MainPageSA');
class CExportData extends MainPageSA {    
    public function onLoad($param) {        
        parent::onLoad($param);                     
        $this->showVariable=true;       
        if (!$this->IsPostBack&&!$this->IsCallBack) {              
            if (!isset($_SESSION['currentPageExportData'])||$_SESSION['currentPageExportData']['page_name']!='sa.settings.ExportData') {
                $_SESSION['currentPageExportData']=array('page_name'=>'sa.settings.ExportData','page_num'=>0);                                              
            }            
        }
    }     
    public function cekNIM ($sender,$param) {     
        $nim=addslashes($param->Value);     
        if ($nim != '') {
            try {
                $str = "SELECT nim FROM v_datamhs vdm  WHERE vdm.nim='$nim'";
                $this->DB->setFieldTable(array('nim'));
                $r=$this->DB->getRecord($str);             
                if (!isset($r[1])) {
                    throw new Exception ("Mahasiswa Dengan NIM ($nim) tidak terdaftar di Portal.");
                }
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }   
        }   
    }
    public function exportData ($sender,$param) {
        if ($this->IsValid) {
            try {
                switch ($sender->getId()) {
                    case 'btnSaveExportPerMHS' :
                        $nim=addslashes($this->txtNIM->Text);
                        $this->exportDataMHS($nim);
                    break;
                }   
            }catch (Exception $e) {
                echo $e->getMessage();
            }
            
        }
    }
    private function buildSqlInsert ($tablename,$clausa='') {
        $result=$this->DB->query('SELECT * FROM '.$tablename.$clausa);        
        $fields_amount  =   $result->field_count;  
        $rows_num=$this->DB->getCountRowsOfTable ($tablename.$clausa);     
        $res            =   $this->DB->query('SHOW CREATE TABLE '.$tablename); 
        $TableMLine     =   $res->fetch_row();
        $content='';
        for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
            while($row = $result->fetch_row())  { //when started (and every after 100 command cycle):
                if ($st_counter%100 == 0 || $st_counter == 0 )  
                {
                        $content .= "INSERT INTO ".$tablename." VALUES";
                }
                $content .= "\n(";
                for($j=0; $j<$fields_amount; $j++)  
                { 
                    $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
                    if (isset($row[$j]))
                    {
                        $content .= '"'.addslashes($row[$j]).'"' ; 
                    }
                    else 
                    {   
                        $content .= '""';
                    }     
                    if ($j<($fields_amount-1))
                    {
                            $content.= ',';
                    }      
                }
                $content .=")";
                //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) 
                {   
                    $content .= ";";
                } 
                else 
                {
                    $content .= ",";
                } 
                $st_counter=$st_counter+1;
            }
        } 
        return $content .="\n\n";
    }
    private function exportDataMHS ($value,$mode='nim') {
        switch ($mode) {
            case 'nim' :               
                $str = "SELECT no_formulir,nim,nirm FROM register_mahasiswa WHERE nim='$value'";
                $this->DB->setFieldTable(array('no_formulir','nim','nirm'));
                $r=$this->DB->getRecord($str);

                $no_formulir=$r[1]['no_formulir']; 
                $nim=$r[1]['nim'];    
                $nirm=$r[1]['nirm'];                
                $filename=BASEPATH."exported/sql/$value.sql";
                $fp = fopen ($filename,'w');
                $sql='';
                if ($fp) {
                    $sql = $sql."--\n";
                    $sql =$sql. "-- Table: formulir_pendaftaran\n";
                    $sql =$sql. "--\n";
                    $sql =$sql.$this->buildSqlInsert('formulir_pendaftaran'," WHERE no_formulir='$no_formulir'");  
                    
                    $sql =$sql. "--\n";
                    $sql =$sql. "-- Table: bipend\n";
                    $sql =$sql. "--\n";
                    $sql =$sql.$this->buildSqlInsert('bipend'," WHERE no_formulir='$no_formulir'");  

                    $sql = $sql."--\n";
                    $sql =$sql. "-- Table: kartu_ujian\n";
                    $sql =$sql. "--\n";
                    $sql =$sql.$this->buildSqlInsert('kartu_ujian'," WHERE no_formulir='$no_formulir'"); 

                    $sql = $sql."--\n";
                    $sql =$sql. "-- Table: jawaban_ujian\n";
                    $sql =$sql. "--\n";
                    $sql =$sql.$this->buildSqlInsert('jawaban_ujian'," WHERE no_formulir='$no_formulir'");

                    $sql =$sql."--\n";
                    $sql =$sql. "-- Table: register_mahasiswa\n";
                    $sql =$sql. "--\n";
                    $sql =$sql.$this->buildSqlInsert('register_mahasiswa'," WHERE no_formulir='$no_formulir'");                           

                    $sql =$sql."--\n";
                    $sql =$sql. "-- Table: profiles_mahasiswa\n";
                    $sql =$sql. "--\n";
                    $sql=$sql.$this->buildSqlInsert('profiles_mahasiswa'," WHERE no_formulir='$no_formulir'");  

                    $sql =$sql."--\n";
                    $sql =$sql. "-- Table: data_konversi\n";
                    $sql =$sql. "--\n";
                    $sql=$sql.$this->buildSqlInsert('data_konversi'," WHERE nim='$nim'");  

                    $sql =$sql."--\n";
                    $sql =$sql. "-- Table: dulang\n";
                    $sql =$sql. "--\n";
                    $sql=$sql.$this->buildSqlInsert('dulang'," WHERE nim='$nim'"); 

                    $sql = $sql."--\n";
                    $sql =$sql. "-- Table: krs\n";
                    $sql =$sql. "--\n";
                    $sql =$sql.$this->buildSqlInsert('krs'," WHERE nim='$nim'");

                    $sql = $sql."--\n";
                    $sql =$sql. "-- Table: krsmatkul\n";
                    $sql =$sql. "--\n";
                    $str = "SELECT idkrs FROM krs WHERE nim='$nim'";
                    $r=$this->DB->query($str);
                    while ($row=$r->fetch_assoc()) {           
                        $idkrs=$row['idkrs'];
                        $sql =$sql.$this->buildSqlInsert('krsmatkul'," WHERE idkrs='$idkrs'");
                    }   
                    
                    $sql = $sql."--\n";
                    $sql =$sql. "-- Table: kbmdetail\n";
                    $sql =$sql. "--\n";
                    $str = "SELECT idkrsmatkul FROM krs k, krsmatkul km,kbm_detail WHERE k.nim=km.nim AND km.idkrsmatkul=kbm.idkrsmatkul AND km.nim='$nim'";
                    $r=$this->DB->query($str);
                    while ($row=$r->fetch_assoc()) {           
                        $idkrs=$row['idkrs'];
                        $sql =$sql.$this->buildSqlInsert('krsmatkul'," WHERE idkrs='$idkrs'");
                    }   

                    $sql =$sql."--\n";
                    $sql =$sql. "-- Table: gantinim\n";
                    $sql =$sql. "--\n";
                    $sql=$sql.$this->buildSqlInsert('gantinim'," WHERE nim_lama='$nim'"); 

                    $sql =$sql."--\n";
                    $sql =$sql. "-- Table: gantinirm\n";
                    $sql =$sql. "--\n";
                    $sql=$sql.$this->buildSqlInsert('gantinirm'," WHERE nirm_lama='$nirm'"); 

                    $sql =$sql."--\n";
                    $sql =$sql. "-- Table: jadwalsidang\n";
                    $sql =$sql. "--\n";
                    $sql=$sql.$this->buildSqlInsert('jadwalsidang'," WHERE nim='$nim'"); 
                   
                    fwrite($fp,$str);
                    $this->txtOutput->Text=$sql;
                    fclose($fp);
                }else{
                    throw new Exception("File ($filename) open failed.");
                }                       
                
            break;
            default :
                throw new Exception ('Mode Export data berdasarkan mahasiswa hanya tersedia (nim|no_formulir|nirm');
        }
    }

}
// class CExportData extends MainPageSA {    
// 	public function onLoad($param) {		
// 		parent::onLoad($param);				        
// 		$this->showVariable=true;       
// 		if (!$this->IsPostBack&&!$this->IsCallBack) {	           
//             if (!isset($_SESSION['currentPageExportData'])||$_SESSION['currentPageExportData']['page_name']!='sa.settings.ExportData') {
// 				$_SESSION['currentPageExportData']=array('page_name'=>'sa.settings.ExportData','page_num'=>0);												
// 			}            
// 		}
// 	}     
//     public function cekNIM ($sender,$param) {     
//         $nim=addslashes($param->Value);     
//         if ($nim != '') {
//             try {
//                 $str = "SELECT nim FROM v_datamhs vdm  WHERE vdm.nim='$nim'";
//                 $this->DB->setFieldTable(array('nim'));
//                 $r=$this->DB->getRecord($str);             
//                 if (!isset($r[1])) {
//                     throw new Exception ("Mahasiswa Dengan NIM ($nim) tidak terdaftar di Portal.");
//                 }
//             }catch (Exception $e) {
//                 $param->IsValid=false;
//                 $sender->ErrorMessage=$e->getMessage();
//             }   
//         }   
//     }
//     public function exportData ($sender,$param) {
//         if ($this->IsValid) {
//             switch ($sender->getId()) {
//                 case 'btnSaveExportNIM' :
//                     $nim=addslashes($this->txtNIM->Text);
//                     $str = "";
//                 break;
//             }   
//         }
//     }


//     function Export_Database($host,$user,$pass,$name,  $tables=false, $backup_name=false )     {
//         $mysqli = new mysqli($host,$user,$pass,$name); 
//         $mysqli->select_db($name); 
//         $mysqli->query("SET NAMES 'utf8'");

//         $queryTables    = $mysqli->query('SHOW TABLES'); 
//         while($row = $queryTables->fetch_row()) 
//         { 
//             $target_tables[] = $row[0]; 
//         }   
//         if($tables !== false) 
//         { 
//             $target_tables = array_intersect( $target_tables, $tables); 
//         }
//         foreach($target_tables as $table)
//         {
//             $result         =   $mysqli->query('SELECT * FROM '.$table);  
//             $fields_amount  =   $result->field_count;  
//             $rows_num=$mysqli->affected_rows;     
//             $res            =   $mysqli->query('SHOW CREATE TABLE '.$table); 
//             $TableMLine     =   $res->fetch_row();
//             $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

            // for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) 
            // {
            //     while($row = $result->fetch_row())  
            //     { //when started (and every after 100 command cycle):
            //         if ($st_counter%100 == 0 || $st_counter == 0 )  
            //         {
            //                 $content .= "\nINSERT INTO ".$table." VALUES";
            //         }
            //         $content .= "\n(";
            //         for($j=0; $j<$fields_amount; $j++)  
            //         { 
            //             $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
            //             if (isset($row[$j]))
            //             {
            //                 $content .= '"'.$row[$j].'"' ; 
            //             }
            //             else 
            //             {   
            //                 $content .= '""';
            //             }     
            //             if ($j<($fields_amount-1))
            //             {
            //                     $content.= ',';
            //             }      
            //         }
            //         $content .=")";
            //         //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
            //         if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) 
            //         {   
            //             $content .= ";";
            //         } 
            //         else 
            //         {
            //             $content .= ",";
            //         } 
            //         $st_counter=$st_counter+1;
            //     }
            // } $content .="\n\n\n";
        // }
//         //$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
//         $backup_name = $backup_name ? $backup_name : $name.".sql";
//         header('Content-Type: application/octet-stream');   
//         header("Content-Transfer-Encoding: Binary"); 
//         header("Content-disposition: attachment; filename=\"".$backup_name."\"");  
//         echo $content; exit;
//     }
// }