// JavaScript Document<script>
function blinks(hide) {
    
	if(hide==1) {
        $('.error-message').css('visibility', 'visible');
            hide = 0;
    }
	else { 
		$('.error-message').css('visibility', 'hidden');
		hide = 1;
	}
	setTimeout("blinks("+hide+")",400);
}
$(document).ready(function(){
	blinks(1);
});