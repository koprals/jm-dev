<?php echo $javascript->link("jquery.tools.min.js");?>
<?php echo $javascript->link("jquery.slideViewer");?>
<?php echo $javascript->link("jquery.timers.js");?>
<?php echo $html->css("svwp_style.css");?>
<style>
/* root element for scrollable */
.vertical {  
	
	/* required settings */
	position:relative;
	overflow:hidden;	

	/* vertical scrollers have typically larger height than width */	
	height: 665px;	 
	width: 700px;
	border-top:1px solid #ddd;	
}

/* root element for scrollable items */
.items {	
	position:absolute;
	
	/* this time we have very large space for height */	
	height:20000em;	
	margin: 0px;
}

/* single scrollable item */
.item {
	border-bottom:1px solid #ddd;
	margin:10px 0;
	padding:15px;
	font-size:12px;
	height:180px;
}

/* elements inside single item */
.item img {
	float:left;
	margin-right:20px;
	height:180px;
	width:240px;
}

.item h3 {
	margin:0 0 5px 0;
	font-size:16px;
	color:#456;
	font-weight:normal;
}

/* the action buttons above the scrollable */
#actions {
	width:700px;
	margin:30px 0 10px 0;	
}

#actions a {
	font-size:11px;		
	cursor:pointer;
	color:#666;
}

#actions a:hover {
	text-decoration:underline;
	color:#000;
}

.disabled {
	visibility:hidden;		
}

.next {
	float:right;
}	


</style>

<!-- HTML structures -->
<div id="actions">
	<a class="prev">&laquo; Back</a>

	<a class="next">More pictures &raquo;</a>
</div>

<div class="scrollable vertical kiri size100">
	<div class="items">
    	<div>
           
				<h3>1. Barcelona Pavilion</h3>
				<strong>
					The German Pavilion in Barcelona, designed by Ludwig Mies van der Rohe 
					and built for the International Exposition in 1929.
				</strong>
                <p>
					The Pavilion was not only a pioneer for construction forms with a fresh, disciplined
					understanding of space, but also for modeling new opportunities for an exciting 
					association of free art and architecture. 
				</p>
				<p>
					<a href="#">Read more</a> &nbsp; <a href="#">Show in map</a>
				</p>
		
        </div>
        <div>
           
				<h3>1. Barcelona Pavilion</h3>
				<strong>
					The German Pavilion in Barcelona, designed by Ludwig Mies van der Rohe 
					and built for the International Exposition in 1929.
				</strong>
                <p>
					The Pavilion was not only a pioneer for construction forms with a fresh, disciplined
					understanding of space, but also for modeling new opportunities for an exciting 
					association of free art and architecture. 
				</p>
				<p>
					<a href="#">Read more</a> &nbsp; <a href="#">Show in map</a>
				</p>
		
        </div>
        <div>
           
				<h3>1. Barcelona Pavilion</h3>
				<strong>
					The German Pavilion in Barcelona, designed by Ludwig Mies van der Rohe 
					and built for the International Exposition in 1929.
				</strong>
                <p>
					The Pavilion was not only a pioneer for construction forms with a fresh, disciplined
					understanding of space, but also for modeling new opportunities for an exciting 
					association of free art and architecture. 
				</p>
				<p>
					<a href="#">Read more</a> &nbsp; <a href="#">Show in map</a>
				</p>
		
        </div>
    </div>
</div>

<!-- javascript coding -->


<script>
// execute your scripts when DOM is ready. this is a good habit
$(function() {		
		
	// initialize scrollable with mousewheel support
	$(".scrollable").scrollable({ vertical: true, mousewheel: true });	
	
});
</script>
