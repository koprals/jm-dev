<!DOCTYPE html "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title><?php echo $title_for_layout; ?></title>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA8yhzO290x2yvu-ZkKAaqXxS3j-2W9j6l-efqP9QMSB0CsF-OyhQp0_KuKz6HQBfUlhkRZl0MOChltg&sensor=true" type="text/javascript"></script>
<?PHP echo $javascript->link('jquery.latest')?>
<?php echo $javascript->link("extinfowindow")?>
<?php echo $html->css("redInfoWindow")?>
<?PHP echo $html->css('main_css')?>
<script type="text/javascript">
$(document).ready(function(){
	
});
var w_marker;
var geocoder;
function initialize() {
	if (GBrowserIsCompatible())
	{
		var map 		=	new GMap2(document.getElementById("map_canvas"));
		var address		=	( parent.$("#UserAddress").val()!=="") ?  parent.$("#UserAddress").val() : "";
		var province	=	( parent.$("#UserProvince option:selected").val()!=="") ?  parent.$("#UserProvince option:selected").text() : "";
		var city		=	( parent.$("#UserCity option:selected").val()!=="") ?  parent.$("#UserCity option:selected").text() : "";
		geocoder 		= 	new GClientGeocoder();
		map.setMapType(G_NORMAL_MAP);//tipe peta
		map.addControl(new GLargeMapControl());
		
		geocoder.getLatLng(address + " " + province + " " + city ,function(point)
		{
			if (point)
			{
				map.setCenter(point, <?php echo $zoom?>);
			}
			else
			{
				var center 	= new GLatLng(<?php echo $latitude?>, <?php echo $longitude?>);
				map.setCenter(center, 8);
			}
			//ADD MARKER TO MAP
			var letteredIcon		=	new GIcon(G_DEFAULT_ICON);
			letteredIcon.image		=	"<?php echo $this->webroot?>img/icn_paku.png";
			letteredIcon.iconSize	=	new GSize(33, 31);
			w_marker 			=	new GMarker(new GLatLng(map.getCenter().lat(), map.getCenter().lng()),{clickable:true, draggable:true, autoPan:false,icon:letteredIcon,title:"Pindahkan saya"});
			map.addOverlay(w_marker);
			
			//MARKER EVENT CLICK
			GEvent.addListener(w_marker,"click", function() {
				geocoder.getLocations(w_marker.getLatLng(), function(response)
				{
					if (!response || response.Status.code != 200)
					{
						w_marker.openExtInfoWindow(
						map,
						"custom_info_window_red",
						out(place.address),
						{
							beakOffset: 3
						});
					} 
					else 
					{
						var place = response.Placemark[0];
						w_marker.openExtInfoWindow(
						map,
						"custom_info_window_red",
						out(place.address),
						{
							beakOffset: 3
						});
					}
				});
			});
			
			// MARKER EVENT DRAGSTART
			GEvent.addListener(w_marker, "dragstart", function() {
				map.closeExtInfoWindow();
			});
			
			// MARKER EVENT DRAGEND
			GEvent.addListener(w_marker, "dragend", function() {
				geocoder.getLocations(w_marker.getLatLng(), function(response)
				{
					if (!response || response.Status.code != 200)
					{
						w_marker.openExtInfoWindow(
						map,
						"custom_info_window_red",
						out(place.address),
						{
							beakOffset: 3
						});
					} 
					else 
					{
						var place = response.Placemark[0];
						w_marker.openExtInfoWindow(
						map,
						"custom_info_window_red",
						out(place.address),
						{
							beakOffset: 3
						});
					}
				});
			});
			
			//CLOSE POPUP
			GEvent.addListener(map, 'extinfowindowopen', function(){
				$("#custom_info_window_red_close").click(function(){
					map.closeExtInfoWindow();
				});
			});
		});

		map.enableScrollWheelZoom();
		GEvent.addListener(map,'zoomend',function(){
		
		});
	}
}

function showAddress(address)
{
	if (geocoder)
	{
		geocoder.getLatLng(address,function(point)
		{
			if (point)
			{
				latlon	=	point;
			}			
		});
	}
}

function GetLatLon()
{
	var lat	=	w_marker.getPoint().lat();
	var lot	=	w_marker.getPoint().lng();
	parent.$("#UserLat").val(lat);
	parent.$("#UserLng").val(lot);
	parent.$("#span_lat_lng").html("("+lat+", "+lot+")");
	parent.$.prettyPhoto.close();
}

function out(AddressByMap)
{
	var valLocation	= $("#location_1").val();
	var a='';
	a +='<div class="line0" style="margin:10px;width:220px">';
	a +='<div class="line0">';
	a +='<div class="line0">';
	a +='<div class="line0">';
	a +='<div class="left" style="width:65%;">';
	a +='<div class="text4" style="color:#000;"> '+AddressByMap+'</div>';
	a +='</div>';
	a +='</div>';
	a += '</div>';
	return a;
}
</script>
</head>
<body onload="initialize()" onunload="GUnload()">
<div style="width:95%; margin:auto; float:none;">
    <div class="box_panel" style="background-color:#dfeffc;">
        <div class="line1">
            <div class="line4" style="border:0px solid black;">
                <span class="text3">Silahkan pilih lokasi anda</span>
            </div>
        </div>
        <div class="line1">
            <div id="map_canvas" style="width: 500px; height: 300px; float:left; display:block; margin:5px;">&nbsp;</div>
        </div>
        <div style="margin:5px auto; float:none; width:150px;">
        	<div class="line1" style="margin-bottom:10px;">
                <div class="bungkus_cari"  style="width:60px;">
                    <input name="" type="button" class="btn_search" value="Ok" style="width:60px;" onclick="GetLatLon()"/>
                </div>
                <div class="bungkus_cari"  style="width:60px;">
                    <input name="" type="button" class="btn_search" value="Cancel" style="width:60px;" onclick="javascript:parent.$.prettyPhoto.close()"/>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>