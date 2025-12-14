<?php

class directw {

var $mpdf = null;

function directw(&$mpdf) {
	$this->mpdf = $mpdf;
}


function Write($h,$txt,$currentx=0,$link='',$directionality='ltr',$align='') {
	if (!$align) { 
		if ($directionality=='rtl') { $align = 'R'; }
		else { $align = 'L'; }
	}
	if ($h == 0) { $this->mpdf->SetLineHeight(); $h = $this->mpdf->lineheight; }
	//Output text in flowing mode
	$w = $this->mpdf->w - $this->mpdf->rMargin - $this->mpdf->x; 

	$wmax = ($w - ($this->mpdf->cMarginL+$this->mpdf->cMarginR));
	$s=str_replace("\r",'',$txt);
	if ($this->mpdf->usingCoreFont)  { $nb=strlen($s); }
	else {
		$nb=mb_strlen($s, $this->mpdf->mb_enc );
		// handle single space character
		if(($nb==1) && $s == " ") {
			$this->mpdf->x += $this->mpdf->GetStringWidth($s);
			return;
		}
	}
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	if (!$this->mpdf->usingCoreFont) {
		if (preg_match("/([".$this->mpdf->pregRTLchars."])/u", $txt)) { $this->mpdf->biDirectional = true; }	// *RTL*
		$checkCursive=false;
		if ($this->mpdf->biDirectional) {  $checkCursive=true; }	// *RTL*
		else if (isset($this->mpdf->CurrentFont['indic']) && $this->mpdf->CurrentFont['indic']) {  $checkCursive=true; }	// *INDIC*
		while($i<$nb) {
			//Get next character
			$c = mb_substr($s,$i,1,$this->mpdf->mb_enc );
			if($c == "\n") {
				// WORD SPACING
				$this->mpdf->ResetSpacing();
				//Explicit line break
				$tmp = rtrim(mb_substr($s,$j,$i-$j,$this->mpdf->mb_enc));
				if ($directionality == 'rtl' && $align == 'J') { $align = 'R'; }	// *RTL*
				$this->mpdf->magic_reverse_dir($tmp, true, $directionality);	// *RTL*
				$this->mpdf->Cell($w, $h, $tmp, 0, 2, $align, $fill, $link);
				$i++;
				$sep = -1;
				$j = $i;
				$l = 0;
				if($nl == 1) {
					if ($currentx != 0) $this->mpdf->x=$currentx;
					else $this->mpdf->x=$this->mpdf->lMargin;
					$w = $this->mpdf->w - $this->mpdf->rMargin - $this->mpdf->x;
					$wmax = ($w - ($this->mpdf->cMarginL+$this->mpdf->cMarginR));
				}
				$nl++;
				continue;
			}
			if($c == " ") { $sep= $i; }
			$l += $this->mpdf->GetCharWidthNonCore($c);	// mPDF 5.3.04
			if($l > $wmax) {
				//Automatic line break (word wrapping)
				if($sep == -1) {
					// WORD SPACING
					$this->mpdf->ResetSpacing();
					if($this->mpdf->x > $this->mpdf->lMargin) {
						//Move to next line
						if ($currentx != 0) $this->mpdf->x=$currentx;
						else $this->mpdf->x=$this->mpdf->lMargin;
						$this->mpdf->y+=$h;
						$w=$this->mpdf->w-$this->mpdf->rMargin-$this->mpdf->x;
						$wmax = ($w - ($this->mpdf->cMarginL+$this->mpdf->cMarginR));
						$i++;
						$nl++;
						continue;
					}
					if($i==$j) { $i++; }
					$tmp = rtrim(mb_substr($s,$j,$i-$j,$this->mpdf->mb_enc));
					if ($directionality == 'rtl' && $align == 'J') { $align = 'R'; }	// *RTL*
					$this->mpdf->magic_reverse_dir($tmp, true, $directionality);	// *