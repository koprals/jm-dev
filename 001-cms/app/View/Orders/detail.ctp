<?php echo $this->start("script");?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdLH1ENn7rsYD5ou5DHGk_7Y4hnx8yVeQ&callback=initMap"></script>
<script type="text/javascript" src="<?php echo $this->webroot ?>js/fancybox/jquery.fancybox.js?v=2.1.5"></script>
<script>
$(document).ready(function(){
	$("#totalStaffInvited").html($('input[rel^=maid_]:checked').length);
	$("#totalStaff").html($('input[rel^=maid_]').length);
});
function SearchMaid(searchKey)
{
	$("div[tag=DivMaid]").each(function(){
		var staffName	=	$(this).attr("rel");
		staffName		=	staffName.toLowerCase();
		if(staffName.search(searchKey.toLowerCase()) > -1)
		{
			$(this).show();
		}
		else
		{
			$(this).hide();
			
		}
	});
}
function ChangeStatusPaymentConfirmation(status)
{
	if(status == "1")
	{
		$("#maid_layout").show(300);
	}
	else
	{
		$("#maid_layout").hide(300);
	}
}

var map;
var marker;
function initMap()
{
	var myLatlng = new google.maps.LatLng(<?php echo $detail["Order"]["lat"]?>,<?php echo $detail["Order"]["lng"]?>);
	map = new google.maps.Map(document.getElementById('map'), {
		center: myLatlng,
		zoom: 15
	});
	var infowindow = new google.maps.InfoWindow({
		content: "Order Location"
	  });

	marker = new google.maps.Marker({
		map: map,
		draggable: false,
		title: "asdadasd",
		position: myLatlng
	});
	
	marker.addListener('click', function() {
		infowindow.open(map, marker);
	});
	infowindow.open(map, marker);
}
</script>

<?php echo $this->end();?>

<?php echo $this->start("css");?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />
<?php echo $this->end();?>
<!-- Title area -->
<div class="titleArea">
    <div class="wrapper">
        <div class="pageTitle">
            <h5>Order Detail</h5>
        </div>
        <div class="middleNav">
	        <ul>
				<li class="mUser">
					<a href="<?php echo $settings["cms_url"].$ControllerName ?>" title="View List"><span class="list"></span></a>
				</li>
	        </ul>
	    </div>
    </div>
    <div class="wrapper">
      <div class="bc">
  	        <ul id="breadcrumbs" class="breadcrumbs">
  	             <li>
  	                  <a href="<?php echo $this->Html->url(array('action' => 'Index')) ?>">Order</a>
  	             </li>
  	             <li class="current">
  	                  <a href="javascript:void(0)">Detail</a>
  	             </li>
  	        </ul>
  	    </div>
    </div>
</div>
<div class="line"></div>

<div class="wrapper">
	<!-- Progress bars -->
	<?php
		echo $this->Session->flash();
	?>
 	<div class="fluid">
		<div class="span6">
			<div class="widget" style="margin-top:20px;">
				<div class="title">
					<img src="<?php echo $this->webroot ?>img/icons/dark/loading.png" alt="" class="titleIcon" />
					<h6>Order information</h6>
				</div>
				<div class="formRow">
					<span class="span4">Order ID:</span>
					<span class="span4" style="font-weight:bold;">
						<?php
						  echo $detail['Order']['order_id_display'];
						?>
					</span>
				</div>
				<div class="formRow">
					<span class="span4">Order Created:</span>
					<span class="span4">
						<?php
						  echo date("d M Y H:i:s",strtotime($detail['Order']['created']));
						?>
					</span>
				</div>
				<div class="formRow">
					<span class="span4">Order Type:</span>
					<span class="span4">
						<?php
						  echo $detail['OrderType']['name'];
						?>
					</span>
				</div>
				<div class="formRow">
					<span class="span4">Service Type:</span>
					<span class="span4">
						<?php
						  echo $detail['Service']['name'];
						?>
					</span>
				</div>
				<div class="formRow">
					<span class="span4">Service Date:</span>
					<span class="span4">
						<?php
						  echo date("d M Y",strtotime($detail['Order']['date_order']));
						?>
					</span>
				</div>
				<div class="formRow">
					<span class="span4">Service Time:</span>
					<span class="span4">
						<?php
						  echo $detail['Order']['time_order'];
						?>
					</span>
				</div>
				
				<div class="formRow">
					<span class="span4">Location:</span>
					<span class="span4">
						<?php
						  echo $detail['Order']['address'];
						?>
					</span>
				</div>
				
				<div class="formRow">
					<span class="span4">Detail Location:</span>
					<span class="span4">
						<?php
						  echo $detail['Order']['detail_address'];
						?>
					</span>
				</div>
				
				<div class="formRow">
					<span class="span4">Detail of Damage:</span>
					<span class="span4">
						<?php
						  echo $detail['Order']['detail_damage'];
						?>
					</span>
				</div>
				
				<div class="formRow">
					<span class="span4">Total:</span>
					<span class="span4" style="font-weight:bold;">
						<?php
						  echo "Rp ".number_format($detail['Order']['total'],0,null,",");
						?>
					</span>
				</div>
				<div class="formRow">
					<span class="span4">Order Status:</span>
					<span class="span4" style="font-weight:bold;">
						<?php
						  echo $detail['OrderStatus']['name'] ." (". $detail['OrderType']['name'].")";
						?>
						
					</span>
					<?php if($detail["Order"]["status"] == 1):?>
					<span class="span4" style="font-weight:bold;">
						<input type="button" value="See Confirmation" class="blackB" onclick="location.href = '<?php echo $settings["cms_url"]?>PaymentConfirmations/Detail/<?php echo $detail['PaymentConfirmation']['id']?>'"/>
					</span>
					<?php endif;?>
				</div>
				<div class="formRow">
					<span class="span4">Task Status:</span>
					<span class="span4" style="font-weight:bold;">
						<?php
						  echo $detail['TaskStatus']['name'];
						?>
						
					</span>
					<?php if($detail["Order"]["task_status_id"] == 2 or $detail["Order"]["task_status_id"] == 3):?>
						<span class="span4" style="font-weight:bold;">
							<input type="button" value="See Survey Result" class="blackB" onclick="location.href = '<?php echo $settings["cms_url"]?>Surveys/Detail/<?php echo $detail['OrderSurvey']['id']?>'"/>
						</span>
					<?php endif;?>
				</div>
			</div>
		</div>
		<div class="span6">
			<div class="widget" style="margin-top:20px;">
				<div class="title">
					<img src="<?php echo $this->webroot ?>img/icons/dark/loading.png" alt="" class="titleIcon" />
					<h6>User information</h6>
				</div>
				<div class="formRow">
					<span class="span4">Name:</span>
					<span class="span4">
						<?php
						  echo $detail['User']['fullname'];
						?>
					</span>
				</div>
				<div class="formRow">
					<span class="span4">Email:</span>
					<span class="span4">
						<?php
						  echo $detail['User']['email'];
						?>
					</span>
				</div>
				<div class="formRow">
					<span class="span4">Address:</span>
					<span class="span4">
					  <?php
						echo $detail['User']['address'];
					  ?>
					</span>
				</div>
				<div class="formRow">
					<span class="span4">Mobile Phone:</span>
					<span class="span4">
					  <?php echo $detail['User']['phone'];?>
					</span>
				</div>
			</div>
			<div class="widget" style="margin-top:20px;">
				<div class="title">
					<img src="<?php echo $this->webroot ?>img/icons/dark/loading.png" alt="" class="titleIcon" />
					<h6>Photo</h6>
				</div>
				<div class="formRow" style="text-align:center">
					<img src="<?php echo $detail["Image"]["host"].$detail["Image"]["url"]?>" height="277"/>
				</div>
			</div>
		</div>
		
	</div>
	
	<div class="fluid">
		<div class="span12">
			<div class="widget" style="margin-top:20px;">
				<div class="title">
					<img src="<?php echo $this->webroot ?>img/icons/dark/loading.png" alt="" class="titleIcon" />
					<h6>Maps</h6>
				</div>
				<div class="formRow" id="map" style="height:500px;">
				</div>
			</div>
		</div>
	</div>
</div>
