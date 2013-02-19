<HTML>
	<!-- author: Carlos Murillo Martinez
		 date: 14-02-2013
		 function: this page list all the images from all the users and displays the images for download
		 version: 1.0 -->
	<head>
		<title>File Download</title>
	</head>
	<body>
		<center>
			<h2>Files From Server</h2><br><br>

		
		<table border="1">
		<?php
		/*
		 * This function list all the images recursively the allowed extensions to retrieve are:
		 * jpg, jpeg, png, gif
		 */
		function listImagesRecursively($directory,$recursive){
			$allow_exten = array('jpg','jpeg','png', 'gif');
			$array_images = array();
			
			if($handler = opendir($directory)){
				while((false !== ($entry = readdir($handler)))){
					if($entry!="." && $entry!=".."){
						if(is_dir("$directory/$entry")){
							if($recursive){
								$array_images = array_merge($array_images, listImagesRecursively("$directory/$entry",$recursive));
							}
							$extension = explode(".",$entry);
							$file_extension = end($extension);
							if(in_array($file_extension, $allow_exten)){
								$entry = "$directory/$entry";
								$array_images[] = preg_replace("/\/\//si", "/", $entry);
							}
						}else{
							$extension = explode(".",$entry);
							$file_extension = end($extension);
							if(in_array($file_extension, $allow_exten)){
								$entry = "$directory/$entry";
								$array_images[] = preg_replace("/\/\//si", "/", $entry);
							}
						}
					}
				}
				closedir($handler);
			}
			return $array_images;
		}
		/*----------------------------END FUNCTION LIST IMAGES RECURSIVELY---------------------*/
		
		//primero abrir el directorio actual
		$dest_path = "./";
		$array_images = listImagesRecursively($dest_path,true);
		$image_list = array();
		foreach($array_images as $file){
			$image = explode("/",$file);
			$actual_file = $image[count($image)-2]."/".$image[count($image)-1]; //creates the path ./where/image/is/stored.asd
			array_push($image_list, $actual_file);//added to array
		}

		$counter = 0;
		
		foreach($image_list as $image){
			if ($counter != 0 && $counter%3==0){
				echo "</tr><tr>";
			}
			echo "<td>";
			echo "<a href='$image' download='myimage' target ='_blank'>";
			echo "<img border='0' src='$image' alt='' width='300' height='300'></a>";
			echo "</td>";
			$counter++;
		}
		echo "</tr>";
		?>
		</table>
		<a href="upload.html">Upload a new file</a><br>
		<a href="select_option.html"> main page</a>
		</center>
	</body>
</HTML>