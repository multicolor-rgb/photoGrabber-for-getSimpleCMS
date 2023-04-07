<style>
	.foto-grid{
		width: 100%;
		flex-wrap: wrap;
		display: grid;
		grid-template-columns: 1fr 1fr 1fr;
		box-sizing: border-box;
		gap: 5px;
	}

	.foto-item{
		display: flex;
		flex-direction: column;
	}

	.searchbar{
		width: 100%;
		display:grid;
		grid-template-columns: 1fr;
		gap:10px;
		background: #fafafa;
		border:solid 1px #ddd;
		padding: 10px;
		box-sizing: border-box;
	}

	.searchbar :is(select,input){
		width: 100%;
		padding: 10px;
		margin:0;
		box-sizing: border-box;
	}

	.searchbar :is(input[name="submit"]){
		width:200px;
		background: #000;
		border:none;
		color:#fff;
		cursor: pointer;
	}

	.foto-item-btn{
		width: 100%;
		padding: 10px;
		margin:0;
		background: #000;
		color:#fff;
		margin:3px;
		border:none;
		cursor: pointer;
	}

	.foto-item form{
		margin:0;
		padding: 0;
	}

	.foto-item{
		width: 100%;
		padding:10px;
		box-sizing: border-box;
		border:solid 1px #ddd;
	}
</style>

<?php

	echo '<h3>'. i18n_r('photoGrabber/LANG_Title') .'</h3>';
 
	if(isset($_GET['cke'])==false){
		echo '
		<style>
			.useCKE{display:none !important};
		</style>';
	}else{
		echo '
		<style>
			.header,#sidebar,#footer,.buttons{display:none !important;}
			#maincontent{width:100%;}
		</style>';
	}
;?>

<form method="post" class="searchbar">
    <label for="query"><?php i18n_r('photoGrabber/LANG_Search') ;?></label>
    <input type="text" required <?php
		if (isset($_POST['query'])) {
			echo 'value="' . $_POST['query'] . '" ';
		};; ?> name="query" placeholder="<?php echo i18n_r('photoGrabber/LANG_Key_words') ;?>">

	<label for="page"><?php echo i18n_r('photoGrabber/LANG_Page_Number') ;?></label>

	<input type="text" <?php
	if (isset($_POST['page'])) {
		echo 'value="' . $_POST['page'] . '" ';
	}else{ 
		echo 'value="1"';};; ?> 
	name="page" placeholder="<?php echo i18n_r('photoGrabber/LANG_Page_Number') ;?>">

	<label for="resolution"><?php echo i18n_r('photoGrabber/LANG_Image_Size') ;?></label>
	<select name="resolution">
		<option value="640" <?php
			if(isset($_POST['resolution'])){
				if($_POST['resolution']=='small'){echo 'selected';};};?> ><?php echo i18n_r('photoGrabber/LANG_Small') ;?></option>
		<option value="1920" <?php
			if (isset($_POST['resolution'])) {
				if($_POST['resolution']=='1920'){echo 'selected';};};?> ><?php echo i18n_r('photoGrabber/LANG_Medium') ;?></option>
		<option value="2400" <?php
			if (isset($_POST['resolution'])) {
				if($_POST['resolution']=='2400'){echo 'selected';};} ;?> ><?php echo i18n_r('photoGrabber/LANG_Large') ;?></option>
	</select>

    <input type="submit" value="<?php echo i18n_r('photoGrabber/LANG_Search') ;?>" name="submit">
</form>

<?php
	if (isset($_POST['submit'])) {

		$page = 1;

		if (isset($_POST['page'])) {
			$page = $_POST['page'] ?? 1;
		};

		$url = 'https://api.unsplash.com/search/photos?page='.$page.'&per_page=30&query=' . str_replace(' ','+',$_POST['query']) . '&client_id=y5fZ83-viHmyRH-Rfe0OkZfh-zOvWtK08BoDQKoZX70&w=1920';

		$curl = curl_init();
		if ($_SERVER['HTTP_HOST'] == 'localhost') {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		}
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$data = curl_exec($curl);
		curl_close($curl);

		$json = json_decode($data, true);

		echo '<div class="foto-grid">';

		for ($i = 0; $i < 30; $i++) {
			echo '<div class="foto-item">';
			echo '
			<a href="'.$json['results'][$i]['links']['html'].'">
			<img style="width:100%;height:200px;display:block;object-fit:contain;margin:0 auto;" src="' . $json['results'][$i]['urls']['thumb'] . '"></a>
			';

			echo '
			<p style="text-align:center;">'. i18n_r('photoGrabber/LANG_Photo_by') .' <i>'.$json['results'][$i]['user']['username'].'</i> '. i18n_r('photoGrabber/LANG_On') .' <a href="https://unsplash.com/" style="text-decoration:none;" target="_blank">Unsplash</a></p>

			<form method="POST" action="#">
				<input type="hidden" name="downloadurl" value="'.$json['results'][$i]['urls']['full'].'&w='.$_POST['resolution'].'">
				<input type="hidden" name="downloadurlthumb" value="'.$json['results'][$i]['urls']['full'].'&w=80">
				<input type="hidden" name="downloadid" value="'.$json['results'][$i]['id'].'-'.$_POST['resolution'].'">
				<input type="submit" class="foto-item-btn" value="'. i18n_r('photoGrabber/LANG_Download') .'" name="download">
			</form>
			';

			echo '
			<button class="useCKE foto-item-btn" data-url="'.$json['results'][$i]['urls']['full'].'&w='.$_POST['resolution'].'">'. i18n_r('photoGrabber/LANG_Use_directly') .'</button>';

			echo '</div>';
		};

		echo '</div>';
	};

	if(isset($_POST['download'])){
		$urlImage = $_POST['downloadurl'];
		$urlImageThumb = $_POST['downloadurlthumb'];

		$base = basename($urlImage);

		if(
			file_put_contents(GSDATAUPLOADPATH.$_POST['downloadid'].'.jpg', file_get_contents($urlImage)) &&
			file_put_contents(GSTHUMBNAILPATH.'thumbsm.'.$_POST['downloadid'].'.jpg', file_get_contents($urlImageThumb)) 
		){
			echo '
			<div style="box-sizing:border-box; width:100%; background:green; padding:10px; text-align:center; color:#fff;">'. i18n_r('photoGrabber/LANG_Download_Success') .'</div>';
		}
		else
		{
			echo '
			<div style="box-sizing:border-box; width:100%; background:red; padding:10px; text-align:center; color:#fff;">'. i18n_r('photoGrabber/LANG_Download_Fail') .'</div>';
		}
	};
;?>

<?php if(isset($_GET['cke'])):?>

<script>
	document.querySelectorAll('.useCKE').forEach((x,i)=>{
		x.addEventListener('click',(e)=>{
			e.preventDefault();
			window.opener.CKEDITOR.instances['post-content'].insertHtml('<img src="'+x.dataset.url+'"/>');
			window.close();
		});
	});
</script>

<?php endif;?>