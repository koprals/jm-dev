<?php
class GeneralHelper extends Helper
{
	function seoUrl($string)
	{
		//Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
		$string = strtolower($string);
		//Strip any unwanted characters
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}
	
	function DateConvert($date)
	{
		$output		=	"";
		
		$datetime	=	strtotime($date);
		$arr_days	=	array(1=>"Senin",2=>"Selasa",3=>"Rabu",4=>"Kamis",5=>"Jumat",6=>"Sabtu",7=>"Minggu");
		$arr_month	=	array(1=>"Januari",2=>"Februari",3=>"Maret",4=>"April",5=>"Mei",6=>"Juni",7=>"Juli",8=>"Agustus",
							9	=>	"September",10=>"Oktober",11=>"November",12=>"Desember"
						);
		$hari		=	$arr_days[intval(date("N",$datetime))];
		$bulan		=	$arr_month[intval(date("n",$datetime))];
		$tanggal	=	date("j",$datetime);
		$tahun		=	date("Y",$datetime);
		$jam		=	date("h:i:s",$datetime);
		$output		.=	$hari.", ".$tanggal."-".$bulan."-".$tahun." ".$jam." wib";
		
		return $output;
	}
}
?>