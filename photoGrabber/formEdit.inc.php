<a href="#" class="photoDownloader"><?php echo i18n_r('photoGrabber/LANG_Photos') ;?></a>

<script type="text/javascript">
	const photoBTN = document.querySelector('.photoDownloader');

	photoBTN.addEventListener('click',(e)=>{
		e.preventDefault();
		window.open('<?php global $SITEURL; echo $SITEURL;global $GSADMIN; echo $GSADMIN?>/load.php?id=photoGrabber&cke',"","left=10,top=10,width=960,height=500");
	});

	document.querySelector('.edit-nav').prepend(document.querySelector('.photoDownloader'));
</script>