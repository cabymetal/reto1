<HTML>
	<!-- author: Carlos Murillo Martinez
		 date: 14-02-2013
		 function: this page uploads a file to a folder if the path does not exist it creates the directory
		 version: 1.0 -->
	<head>
		<title>File Upload</title>
	</head>
	<body>
		<center>
			<h2>File On Server</h2><br><br>
		</center>
		<?php
			/*
			 * This funtion reads a configuration file to get the users
			 */
			function readUsers(){
				$file = fopen('myApp.conf','r') or die ("there is an error opening the configuration file");
				$users_passwords = array();

				while(!feof($file)){
					$line = fgets($file);
					list($user, $pass) = explode("->",$line);
					array_push($users_passwords,"$user:$pass");
				}
				return $users_passwords;
			}
			/*
			 * This function has to go to the DB to validate the user name and password
			 * at the moment we supose that only exist two users on the app so this version (1.0)
			 * does not go and search the user on the DB two users burned in code
			 * choco => choco
			 * carlos => CAMM
			 * function version 1.0
			 */
			function validateUser($userName, $password){
				$usr_pass=readUsers();
				$isvalid=false;
				foreach($usr_pass as $usr){
					list($user, $pass) = explode(":",$usr);
					if(strcmp($userName,$user) && strcmp($password,$pass)){
						$isvalid=true;
					}
				}
				return $isvalid;
			}
			$asd= readUsers();
			$userName = $_POST['name'];
			$password = $_POST['pwd'];
			//TODO: verify if the name if filled but this when it works with DB
			//load the path where the images are stored
			
			$dest_path = "$userName/";		// is on the actual path where the project is running
			$ori_path = $_FILES['upfile']['tmp_name'];
			
			
			if(validateUser($userName,$password)){
				
				//if the directory does not exist create it
				if(!is_dir($dest_path)){
					mkdir($dest_path);
				}
				
				$file_error = $_FILES['upfile']['error']; //returns a number with the error code if it's different from cero bad
				$file_name = $_FILES['upfile']['name']; //returns the filename including extension
				$allow_exten = array('jpg','jpeg','png', 'gif');// create an array with the extensions
				$filename_array = explode(".",$file_name); //like split on java returns an array of strings from one delimiter
				$extension = end($filename_array);
				/*
				 * Verify the file extension
				 */
				if(in_array($extension,$allow_exten)){
					/*
					* verify if there is an error uploading the file
					*/
					if( $file_error > 0 ) {
						echo "There is an error uploading file : ".$file_error. "<br>";
					} else{
						echo "<h3>The file was uploaded succesfully!</h3><br>";
						/*
						 * Now the image is loaded on the server we have to move it to the folder where it will be stored
						 */
						if(move_uploaded_file($ori_path,$dest_path.basename($file_name))){
							echo "the image is at folder: ".$dest_path.$file_name."<br>";
						}else{
							echo "error";
						}
					}
				}else{
					echo "<h2>Invalid file</h2>";
				}
			}else{
				echo "<h2>NOT A VALID USER</h2>";
			}
		?>
		<a href="upload.html">Upload a new file</a><br>
		<a href="select_option.html"> main page</a>
	</body>
</HTML>