<div class="line" style="margin-top:10px;">
    <div class="menu_kanan" style="border:none;">
        <div style="font-size:12px;line-height:10px;background:none;">
            <img src="<?php echo $this->webroot?>img/icn_simulasi.png" style="float:left;margin:-4px 3px 0 -30px;" alt="icn_simulasi.png"/>
            <span class="kiri top3">SIMULASI KREDIT</span>
        </div>
        <?php echo $form->create("Product",array("url"=>array("controller"=>"Iklan","action"=>"SimulasiKredit")))?>
        <ul class="menu-right" style="padding-bottom:10px;">			
            <span class="line1 style1 white text11">Perhitungan kredit ini hanya merupakan estimasi harga tidak dapat dijadikan sebagai acuan/patokan harga. Untuk perhitungan sesungguhnya dapat ditanyakan langsung ke penjual.</span>
            <span class="line1">
                <span class="style1 white bold text12 top5 line">Harga Motor</span>
                <?php echo $form->input("harga",array("div"=>false,"label"=>false,"error"=>false,"maxlength"=>15,"class"=>"input2 style1 white text12 size90"))?>
                <span class="style1 white bold text12 top3 line">Uang muka</span>
                <?php echo $form->input("dppersen",array("div"=>false,"label"=>false,"error"=>false,"class"=>"input2 style1 white text12 size80","maxlength"=>5))?><span class="style1 white bold text17 top3 left5">%</span>
                <span class="style1 white bold text12 top3 line">Bunga/Tahun</span>
                <?php echo $form->input("bunga",array("div"=>false,"label"=>false,"error"=>false,"class"=>"input2 style1 white text12 size80","maxlength"=>5))?><span class="style1 white bold text17 top3 left5">%</span>
                
                <span class="style1 white bold text12 top3 line">Jangka waktu</span>
                <select id="abdul" class="rounded1 size95 style1 white text12 input2" style="cursor:pointer;" name="data[Product][tenor]">
                    <option value="1">1 Tahun</option>
                    <option value="2">2 Tahun</option>
                    <option value="3">3 Tahun</option>
                    <option value="4">4 Tahun</option>
                </select>
                <span class="style1 white bold text12 top3 line">Administrasi</span>
                <?php echo $form->input("administrasi",array("div"=>false,"label"=>false,"error"=>false,"maxlength"=>15,"class"=>"input2 style1 white text12 size90 bottom10"))?>
                
                <span class="top3 tengah size40" style="text-align:center; border:0px solid black;"> 
                    <input type="submit" name="data[Product][submit]" value="HITUNG" class="tombol1 tengah" />
                </span> 
            </span>                        
        </ul>
        
        <?php echo $form->end()?>
    </div>
</div>