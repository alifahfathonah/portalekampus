<?php
prado::using ('Application.pagecontroller.k.pembayaran.CDetailPembayaranSemesterPendek');
class DetailPembayaranSemesterPendek Extends CDetailPembayaranSemesterPendek {		
	public function onLoad($param) {
		parent::onLoad($param);							
    }
    public function checkPembayaranSemesterLalu () { 
        $datamhs=$_SESSION['currentPagePembayaranSemesterPendek']['DataMHS'];		
        $tahun_masuk=$datamhs['tahun_masuk'];
        $semester_masuk=$datamhs['semester_masuk'];
        $ta=$datamhs['ta'];         

        $this->Finance->setDataMHS(array('no_formulir'=>$datamhs['no_formulir']));
        $idkelas=$this->Finance->getKelasFromTransaksi($ta,2);
        $datamhs['idkelas']=$idkelas===false?$datamhs['idkelas']:$idkelas;            
        if ($idkelas!='C') {                
            $this->Finance->setDataMHS(array('no_formulir'=>$datamhs['no_formulir'],'nim'=>$datamhs['nim'],'idkelas'=>$datamhs['idkelas'],'tahun_masuk'=>$tahun_masuk,'idsmt'=>$semester_masuk,'perpanjang'=>$datamhs['perpanjang']));
            $totalbiaya=$this->Finance->getTotalBiayaMhsPeriodePembayaran ('lama');
            $this->Finance->setDataMHS($datamhs);
            $totalbayar=$this->Finance->getTotalBayarMhs($ta,2);                
            $sisa=$totalbiaya-$totalbayar;                
            $datadulang=$this->Finance->getDataDulang(2,$ta);
            if ($sisa>0 && $datadulang['k_status'] != 'C') {
                $sisa=$this->Finance->toRupiah($sisa);
                $tasmt="T.A ".$this->DMaster->getNamaTA($ta).' semester '.$this->setup->getSemester(2);
                throw new Exception ('Mahasiswa a.n '.$datamhs['nama_mhs']." Memiliki tunggakan sebesar ($sisa) pada $tasmt, harap untuk dilunasi terlebih dahulu.");
            }
        }
	}
}