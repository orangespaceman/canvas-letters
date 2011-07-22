<?php

	if (isset($_POST) && count($_POST) > 0) {
		
		// check what to do
		require_once("db.php");
		$model = new DB();
		$method = $_POST['method'];
		unset($_POST['method']);

		// 
		switch ($method) {

			// save post
			case "save":
				$result = $model->save($_POST);
				echo json_encode($result);
			break;
			
		}
	}
