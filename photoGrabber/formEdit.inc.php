<a href="#" class="photoDownloader">Photos 📷</a>



<script type="text/javascript">
	const photoBTN = document.querySelector('.photoDownloader');

	photoBTN.addEventListener('click',(e)=>{
		e.preventDefault();
	window.open('<?php global $SITEURL; echo $SITEURL;global $GSADMIN; echo $GSADMIN?>/load.php?id=photoGrabber&cke',"","left=0,top=0,width=960,height=500");
});

	document.querySelector('.edit-nav').prepend(document.querySelector('.photoDownloader'));
	
</script>