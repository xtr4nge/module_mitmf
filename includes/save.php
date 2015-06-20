<?

//include "../login_check.php";
include "../../../config/config.php";
include "../_info_.php";
include "../../../functions.php";

include "options_config.php";

// Checking POST & GET variables...
if ($regex == 1) {
	regex_standard($_POST['type'], "../../../msg.php", $regex_extra);
	regex_standard($_POST['tempname'], "../../../msg.php", $regex_extra);
	regex_standard($_POST['action'], "../../../msg.php", $regex_extra);
	regex_standard($_GET['mod_action'], "../../../msg.php", $regex_extra);
	regex_standard($_GET['mod_service'], "../../../msg.php", $regex_extra);
	regex_standard($_POST['new_rename'], "../../../msg.php", $regex_extra);
	regex_standard($_POST['new_rename_file'], "../../../msg.php", $regex_extra);
}

$type = $_POST['type'];
$tempname = $_POST['tempname'];
$action = $_POST['action'];
$mod_action = $_GET['mod_action'];
$mod_service = $_GET['mod_service'];
$newdata = html_entity_decode(trim($_POST["newdata"]));
$newdata = base64_encode($newdata);
$new_rename = $_POST["new_rename"];
$new_rename_file = $_POST["new_rename_file"];

// ngrep options
if ($type == "opt_value") {

    $tmp = array_keys($opt_value);
    for ($i=0; $i< count($tmp); $i++) {
        
        $exec = "/bin/sed -i 's/opt_value\\[\\\"".$tmp[$i]."\\\"\\]\\[0\\].*/opt_value\\[\\\"".$tmp[$i]."\\\"\\]\\[0\\] = 0;/g' options_config.php";
        $output = exec_fruitywifi($exec);
        
        $exec = "/bin/sed -i 's/^".$tmp[$i].".*/".$tmp[$i]." = Off/g' Responder-master/Responder.conf";
        //$output = exec_fruitywifi($exec);
        
    }
	
    $tmp = $_POST["options"];
    for ($i=0; $i< count($tmp); $i++) {
        
        $exec = "/bin/sed -i 's/opt_value\\[\\\"".$tmp[$i]."\\\"\\]\\[0\\].*/opt_value\\[\\\"".$tmp[$i]."\\\"\\]\\[0\\] = 1;/g' options_config.php";
		//echo $exec . "<br>";
        $output = exec_fruitywifi($exec);
        
        $exec = "/bin/sed -i 's/^".$tmp[$i].".*/".$tmp[$i]." = On/g' Responder-master/Responder.conf";
        //exec_fruitywifi($exec);
        
    }

    header('Location: ../index.php?tab=2');
    exit;

}

if ($type == "config") {

    if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
        $exec = "$bin_echo '$newdata' | base64 --decode > $mod_path/includes/MITMf/config/mitmf.conf";
        exec_fruitywifi($exec);
        
        $exec = "$bin_dos2unix $mod_path/includes/MITMf/config/mitmf.conf";
        exec_fruitywifi($exec);
    }

    header('Location: ../index.php?tab=3');
    exit;

}

if ($type == "inject") {

    if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
        $exec = "$bin_echo '$newdata' | base64 --decode > $mod_path/includes/inject.txt";
        exec_fruitywifi($exec);
        
        $exec = "$bin_dos2unix $mod_path/includes/inject.txt";
        exec_fruitywifi($exec);
    }

    header('Location: ../index.php?tab=4');
    exit;

}

if ($type == "templates") {
	if ($action == "save") {
		
		if ($tempname != "0") {
			// SAVE TAMPLATE
			if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
				$template_path = "$mod_path/includes/MITMf/config/app_cache_poison_templates";
        		$exec = "$bin_echo '$newdata' | base64 --decode > $template_path/$tempname";
                exec_fruitywifi($exec);
                
                $exec = "$bin_dos2unix $template_path/$tempname";
                exec_fruitywifi($exec);
                
    		}
    	}
    	
	} else if ($action == "add_rename") {
	
		if ($new_rename == "0") {
			//CREATE NEW TEMPLATE
			if ($new_rename_file != "") {
				$template_path = "$mod_path/includes/MITMf/config/app_cache_poison_templates";
				$exec = "$bin_touch $template_path/$new_rename_file";
				//exec("$bin_danger \"$exec\"", $output); //DEPRECATED
                exec_fruitywifi($exec);

				$tempname=$new_rename_file;
			}
		} else {
			//RENAME TEMPLATE
			$template_path = "$mod_path/includes/MITMf/config/app_cache_poison_templates";
			$exec = "$bin_mv $template_path/$new_rename $template_path/$new_rename_file";
			//exec("$bin_danger \"$exec\"", $output); //DEPRECATED
            exec_fruitywifi($exec);

			$tempname=$new_rename_file;
		}
		
	} else if ($action == "delete") {
		if ($new_rename != "0") {
			//DELETE TEMPLATE
			$template_path = "$mod_path/includes/MITMf/config/app_cache_poison_templates";
			$exec = "$bin_rm $template_path/$new_rename";
			//exec("$bin_danger \"$exec\"", $output); //DEPRECATED
            exec_fruitywifi($exec);
		}
	}
	header("Location: ../index.php?tab=5&tempname=$tempname");
	exit;
}

if ($type == "filters") {
	if ($action == "save") {
		
		if ($tempname != "0") {
			// SAVE TAMPLATE
			if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
				$template_path = "$mod_path/includes/filters/resources/";
        		$exec = "$bin_echo '$newdata' | base64 --decode > $template_path/$tempname";
                exec_fruitywifi($exec);
                
                $exec = "$bin_dos2unix $template_path/$tempname";
                exec_fruitywifi($exec);
    		}
    	}
    	
	}
	header("Location: ../index.php?tab=6&tempname=$tempname");
	exit;
}

if($mod_service == "mod_sslstrip_filter") {
    $exec = "$bin_sed -i 's/mod_sslstrip_filter=.*/mod_sslstrip_filter=\\\"".$mod_action."\\\";/g' ../_info_.php";
    exec_fruitywifi($exec);
}

header('Location: ../index.php');

?>