<? 
/*
    Copyright (C) 2013-2015 xtr4nge [_AT_] gmail.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWifi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />
<style>
        .div0 {
                width: 350px;
         }
        .div1 {
                width: 120px;
                display: inline-block;
                text-align: right;
                margin-right: 10px;
        }
        .divEnabled {
                width: 63px;
                color: lime;
                display: inline-block;
                font-weight: bold;
        }
        .divDisabled {
                width: 63px;
                color: red;
                display: inline-block;
                font-weight: bold;
        }
        .divAction {
                width: 80px;
                display: inline-block;
                font-weight: bold;
        }
        .divDivision {
                width: 16px;
                display: inline-block;
        }
</style>
<script>
$(function() {
    $( "#action" ).tabs();
    $( "#result" ).tabs();
});

</script>

</head>
<body>

<? include "../menu.php"; ?>

<br>

<?
include "../../config/config.php";
include "_info_.php";
include "../../login_check.php";
include "../../functions.php";

include "includes/options_config.php";

// Checking POST & GET variables...
if ($regex == 1) {
    regex_standard($_POST["newdata"], "msg.php", $regex_extra);
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
    regex_standard($_GET["tempname"], "msg.php", $regex_extra);
}

$newdata = $_POST['newdata'];
$logfile = $_GET["logfile"];
$action = $_GET["action"];
$tempname = $_GET["tempname"];

// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "$bin_rm ".$mod_logs_history.$logfile.".log";
    exec_fruitywifi($exec);
}

?>

<div class="rounded-top" align="left"> &nbsp; <?=$mod_alias?> </div>
<div class="rounded-bottom">
    
    &nbsp;&nbsp;&nbsp;version <?=$mod_version?><br>
    <? 
    if (file_exists("$bin_mitmf")) { 
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$mod_alias <font style='color:lime'>installed</font><br>";
    } else {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$mod_alias <a href='includes/module_action.php?install=install_sslstrip' style='color:red'>install</a><br>";
    } 
    ?>

    <?
    $ismodup = exec($mod_isup);
    if ($ismodup != "") {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$mod_alias <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"includes/module_action.php?service=mitmf&action=stop&page=module\"><b>stop</b></a><br />";
    } else { 
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$mod_alias  <font color=\"red\"><b>disabled</b></font>. | <a href=\"includes/module_action.php?service=mitmf&action=start&page=module\"><b>start</b></a><br />"; 
    }
    ?>

</div>

<br>

<div class="rounded-top" align="left"> &nbsp; Plugins </div>
<div class="rounded-bottom">
    
    <div id="plugins"></div>

</div>

<script type='text/javascript'>
function sortObject(object) {
    return Object.keys(object).sort().reduce(function (result, key) {
        result[key] = object[key];
        return result;
    }, {});
}
function loadPlugins()
{
    $(document).ready(function() { 
        $.getJSON('includes/ws_action.php?method=getPlugins', function(data) {
            var div = document.getElementById('plugins');
            div.innerHTML = ""
            console.log(data);
            data = sortObject(data)
            $.each(data, function(key, val) {
                if (val == true) {
                    div.innerHTML = div.innerHTML + "<div class='div0'><div class='div1'>" + key + "</div><div class='divEnabled'>enabled</div><div class='divDivision'> | </div><div class='divAction'><a href='#' onclick=\"setPluginStatus('" + key + "',0)\">stop</a></div></div>";
                } else {
                    div.innerHTML = div.innerHTML + "<div class='div0'><div class='div1'>" + key + "</div><div class='divDisabled'>disabled</div><div class='divDivision'> | </div><div class='divAction'><a href='#' onclick=\"setPluginStatus('" + key + "',1)\">start</a></div></div>";
                }
                    
            });
        });    
    
    });
}
loadPlugins()

function setPluginStatus(plugin, action) {
    $(document).ready(function() { 
        $.getJSON('includes/ws_action.php?method=setPluginStatus&plugin=' + plugin + '&action=' + action, function(data) {
        });
        /*
        $.postJSON = function(url, data, func)
        {
            $.post(url, data, func, 'json');
        }
        */
    });
    setTimeout(loadPlugins, 500);
}

</script>

<br>

<div id="msg" style="font-size:largest;">
Loading, please wait...
</div>

<div id="body" style="display:none;">

<div id="result" class="module">
    <ul>
        <li><a href="#result-1">Output</a></li>
        <li><a href="#result-2">History</a></li>
        <li><a href="#result-22">Options</a></li>
        <li><a href="#result-4">Config</a></li>
        <li><a href="#result-3">Inject</a></li>
        <li><a href="#result-5">Templates</a></li>
        <li><a href="#result-6">Filters</a></li>
		<li><a href="#result-7">About</a></li>
    </ul>
    
    <!-- OUTPUT -->
    
    <div id="result-1">
        <form id="formLogs-Refresh" name="formLogs-Refresh" method="GET" autocomplete="off" action="includes/save.php">
        <input type="submit" value="refresh">
        <input type="hidden" name="mod_service" value="mod_sslstrip_filter">
        <select style="module" name="mod_action" onchange='this.form.submit()'>
            <option value="" <? if ($mod_sslstrip_filter == "") echo 'selected'; ?> >-</option>
            <option value="LogEx.py" <? if ($mod_sslstrip_filter == "LogEx.py") echo 'selected'; ?>>LogEx.py</option>
            <option value="ParseLog.py" <? if ($mod_sslstrip_filter == "ParseLog.py") echo 'selected'; ?>>ParseLog.py</option>
        </select>
        <br><br>
        <?
            if ($logfile != "" and $action == "view") {
                $filename = $mod_logs_history.$logfile.".log";
            } else {
                $filename = $mod_logs;
            }
            
            if ($mod_sslstrip_filter == "LogEx.py") {
                $exec = "$bin_python $mod_path/includes/filters/LogEx.py $filename";
                $output = exec_fruitywifi($exec);
                
                //$data = implode("\n",$output);
                $data = $output;
            } else if ($mod_sslstrip_filter == "ParseLog.py") {
                $exec = "$bin_python $mod_path/includes/filters/ParseLog.py $filename $mod_path/includes/filters";
                $output = exec_fruitywifi($exec);
                        
                //$data = implode("\n",$output);
                $data = $output;
            } else {
            
                
                $data = open_file($filename);
                $data_array = explode("\n", $data);
                //$data = implode("\n",array_reverse($data_array));
                //$data = array_reverse($data_array);
                $data = $data_array;
                
                //exec("/usr/bin/tail -n 100 $filename", $data_array);
                //$data = $data_array;
            }
        
        ?>
        <textarea id="output" class="module-content" style="font-family: courier;"><?
            //htmlentities($data)
        
            for ($i=0; $i < count($data); $i++) {
                if (strlen($data[$i]) > 120) {
                    echo htmlentities(substr($data[$i], 0, 120)) . "... {truncated}\n";
                } else {
                    echo htmlentities($data[$i]) . "\n";
                }
            }
        
        ?></textarea>
        <input type="hidden" name="type" value="logs">
        </form>
    </div>
    
    <!-- OPTIONS -->

        <div id="result-22">
            <form id="formInject" name="formInject" method="POST" autocomplete="off" action="includes/save.php">
            <input type="submit" value="save">
            <br><br>
            
            <div class=" module-options" style="b-ackground-color:#000; b-order:1px dashed;">
            <table>
                <!-- // OPTION cachekill --> 
                <tr>
                    <? $opt = "cachekill"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($opt_value[$opt][0] == "1") echo "checked" ?> ></td>
                    <td><?=$opt?></td>
                    <td></td>
                </tr>
                <!-- // OPTION hsts --> 
                <tr>
                    <? $opt = "hsts"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($opt_value[$opt][0] == "1") echo "checked" ?> ></td>
                    <td><?=$opt?></td>
                    <td></td>
                </tr>
                <!-- // OPTION inject --> 
                <tr>
                    <? $opt = "inject"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($opt_value[$opt][0] == "1") echo "checked" ?> ></td>
                    <td><?=$opt?></td>
                    <td nowrap></td>
                </tr>
                <!-- // OPTION screen --> 
                <tr>
                    <? $opt = "screen"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($opt_value[$opt][0] == "1") echo "checked" ?> ></td>
                    <td><?=$opt?></td>
                    <td nowrap></td>
                </tr>
                <!-- // OPTION jskeylogger --> 
                <tr>
                    <? $opt = "jskeylogger"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($opt_value[$opt][0] == "1") echo "checked" ?> ></td>
                    <td style="padding-right:10px"><?=$opt?></td>
                    <td nowrap></td>
                </tr>
                <!-- // OPTION appoison --> 
                <tr>
                    <? $opt = "appoison"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($opt_value[$opt][0] == "1") echo "checked" ?> ></td>
                    <td style="padding-right:10px"><?=$opt?></td>
                    <td nowrap></td>
                </tr>
                <!-- // OPTION replace --> 
                <tr>
                    <? $opt = "replace"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($opt_value[$opt][0] == "1") echo "checked" ?> ></td>
                    <td style="padding-right:10px"><?=$opt?></td>
                    <td nowrap></td>
                </tr>
                <!-- // OPTION upsidedownternet --> 
                <tr>
                    <? $opt = "upsidedownternet"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($opt_value[$opt][0] == "1") echo "checked" ?> ></td>
                    <td style="padding-right:10px"><?=$opt?></td>
                    <td nowrap></td>
                </tr>
                <!-- // OPTION browserprofiler --> 
                <tr>
                    <? $opt = "browserprofiler"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($opt_value[$opt][0] == "1") echo "checked" ?> ></td>
                    <td style="padding-right:10px"><?=$opt?></td>
                    <td nowrap></td>
                </tr>
                <!-- // OPTION ferretng --> 
                <tr>
                    <? $opt = "ferretng"; ?>
                    <td><input type="checkbox" name="options[]" value="<?=$opt?>" <? if ($opt_value[$opt][0] == "1") echo "checked" ?> ></td>
                    <td style="padding-right:10px"><?=$opt?></td>
                    <td nowrap></td>
                </tr>
            </table>
            </div>

            <input type="hidden" name="type" value="opt_value">
            </form>
            <br>
            <?
                $filename = "$mod_path/includes/mode_d.txt";
                
                $data = open_file($filename);
                
            ?>
            
        </div>
    
    <!-- HISTORY -->
    
    <div id="result-2" class="history">
        <input type="submit" value="refresh">
        <br><br>
        
        <?
        $logs = glob($mod_logs_history.'*.log');
        print_r($a);

        for ($i = 0; $i < count($logs); $i++) {
            $filename = str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]));
            echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=delete&tab=1'><b>x</b></a> ";
            echo $filename . " | ";
            echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=view'><b>view</b></a>";
            echo "<br>";
        }
        ?>
        
    </div>
    
    <!-- INJECT -->
    
    <div id="result-3" >
        <form id="formInject" name="formInject" method="POST" autocomplete="off" action="includes/save.php">
        <input type="submit" value="save">
        <br><br>
        <?
            $filename = "$mod_path/includes/inject.txt";
            
            /*
            if ( 0 < filesize( $filename ) ) {
                $fh = fopen($filename, "r"); // or die("Could not open file.");
                $data = fread($fh, filesize($filename)); // or die("Could not read file.");
                fclose($fh);
            }
            */
            
            $data = open_file($filename);
            
        ?>
        <textarea id="inject" name="newdata" class="module-content" style="font-family: courier;"><?=htmlspecialchars($data)?></textarea>
        <input type="hidden" name="type" value="inject">
        </form>
    </div>
    
    <!-- CONFIG -->
    
    <div id="result-4" >
        <form id="formConfig" name="formTamperer" method="POST" autocomplete="off" action="includes/save.php">
        <input type="submit" value="save">
        <br><br>
        <?
            $filename = "$mod_path/includes/MITMf-master/config/mitmf.conf";
            
            $data = open_file($filename);
            
        ?>
        <textarea id="config" name="newdata" class="module-content" style="font-family: courier;"><?=htmlspecialchars($data)?></textarea>
        <input type="hidden" name="type" value="config">
        </form>
    </div>
    
    <!-- TEMPLATES -->
    
    <div id="result-5" >
        <form id="formTemplates" name="formTemplates" method="POST" autocomplete="off" action="includes/save.php">
        <input type="submit" value="save">       
        
        <br><br>
        <?
        	if ($tempname != "") {
            	$filename = "$mod_path/includes/MITMf-master/config/app_cache_poison_templates/".$tempname;
            	                
                $data = open_file($filename);
                
			} else {
				$data = "";
			}
			
            
            
        ?>
        <textarea id="inject" name="newdata" class="module-content" style="font-family: courier;"><?=htmlspecialchars($data)?></textarea>
        <input type="hidden" name="type" value="templates">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="tempname" value="<?=$tempname?>">
        </form>
        
    <br>
        
    <table border=0 cellspacing=0 cellpadding=0>
    	<tr>
    	<td class="general">
    		Template
    	</td>
    	<td>
        <form id="formTempname" name="formTempname" method="POST" autocomplete="off" action="includes/save.php">
    		<select name="tempname" onchange='this.form.submit()'>
        	<option value="0">-</option>
        	<?
        	//$template_path = "$mod_path/includes/app_cache_poison/templates/";
            $template_path = "$mod_path/includes/MITMf-master/config/app_cache_poison_templates/";
        	$templates = glob($template_path.'*');
        	//print_r($templates);

        	for ($i = 0; $i < count($templates); $i++) {
            	$filename = str_replace($template_path,"",$templates[$i]);
            	if ($filename == $tempname) echo "<option selected>"; else echo "<option>"; 
            	echo "$filename";
            	echo "</option>";
        	}
        	?>
        	</select>
        	<input type="hidden" name="type" value="templates">
        	<input type="hidden" name="action" value="select">
    	</form>
        </td>
        <tr>
        <td class="general">
        	Add/Rename
        </td>
        <td>
        <form id="formTempname" name="formTempname" method="POST" autocomplete="off" action="includes/save.php">
        	<select name="new_rename">
        	<option value="0">- add template -</option>
        	<?
        	$template_path = "$mod_path/includes/MITMf-master/config/app_cache_poison_templates/";
        	$templates = glob($template_path.'*');
        	//print_r($templates);

        	for ($i = 0; $i < count($templates); $i++) {
            	$filename = str_replace($template_path,"",$templates[$i]);
            	echo "<option>"; 
            	//if ($filename == $tempname) echo "<option selected>"; else echo "<option>";
            	echo "$filename";
            	echo "</option>";
        	}
        	?>
        	
        	</select>
        	<input class="ui-widget" type="text" name="new_rename_file" value="" style="width:150px">
        	<input type="submit" value="add/rename">
        	
        	<input type="hidden" name="type" value="templates">
        	<input type="hidden" name="action" value="add_rename">
        	
        </form>
        </td>
        </tr>
        
        <tr><td><br></td></tr>
        
        <tr>
        <td>
        	
        </td>
        <td>
        <form id="formTempDelete" name="formTempDelete" method="POST" autocomplete="off" action="includes/save.php">
        	<select name="new_rename">
        	<option value="0">-</option>
        	<?
        	$template_path = "$mod_path/includes/app_cache_poison/templates/";
        	$templates = glob($template_path.'*');
        	//print_r($templates);

        	for ($i = 0; $i < count($templates); $i++) {
            	//$filename = $templates[$i];
            	$filename = str_replace($template_path,"",$templates[$i]);
            	echo "<option>"; 
            	echo "$filename";
            	echo "</option>";
        	}
        	?>
        	
        	</select>

        	<input type="submit" value="delete">
        	
        	<input type="hidden" name="type" value="templates">
        	<input type="hidden" name="action" value="delete">
        	
        </form>
        </td>
        </tr>
    </table>
    </div>
    
    <!-- START FILTERS -->
    
    <div id="result-6" >
        <form id="formFilters" name="formFilters" method="POST" autocomplete="off" action="includes/save.php">
        <input type="submit" value="save"> [ParseLog.py]
        
        <br><br>
        <?
        	if ($tempname != "") {
            	$filename = "$mod_path/includes/filters/resources/$tempname";
                
                $data = open_file($filename);
                
			} else {
				$data = "";
			}
			
            
            
        ?>
        <textarea id="inject" name="newdata" class="module-content" style="font-family: courier;"><?=htmlspecialchars($data)?></textarea>
        <input type="hidden" name="type" value="filters">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="tempname" value="<?=$tempname?>">
        </form>
        
    <br>
        
    <table border=0 cellspacing=0 cellpadding=0>
    	<tr>
    	<td class="general" style="padding-right:10px">
    		Setup  
    	</td>
    	<td>
        <form id="formFilters" name="formFilters" method="POST" autocomplete="off" action="includes/save.php">
    		<select name="tempname" onchange='this.form.submit()'>
        	<option value="0">-</option>
        	<?
        	$template_path = "$mod_path/includes/filters/resources/";
        	$templates = glob($template_path.'*');
        	//print_r($templates);

        	for ($i = 0; $i < count($templates); $i++) {
            	$filename = str_replace($template_path,"",$templates[$i]);
            	if ($filename == $tempname) echo "<option selected>"; else echo "<option>"; 
            	echo "$filename";
            	echo "</option>";
        	}
        	?>
        	</select>
        	<input type="hidden" name="type" value="filters">
        	<input type="hidden" name="action" value="select">
    	</form>
        </td>
        
    </table>
    </div>
    
    <!-- END FILTERS -->
	
	<!-- ABOUT -->

	<div id="result-7" class="history">
		<? include "includes/about.php"; ?>
	</div>
	
	<!-- END ABOUT -->
    
    
</div>

<div id="loading" class="ui-widget" style="width:100%;background-color:#000; padding-top:4px; padding-bottom:4px;color:#FFF">
    Loading...
</div>

<script>
$('#formLogs').submit(function(event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'includes/ajax.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (data) {
            console.log(data);

            $('#output').html('');
            $.each(data, function (index, value) {
                $("#output").append( value ).append("\n");
            });
            
            $('#loading').hide();
        }
    });
    
    $('#output').html('');
    $('#loading').show()

});

$('#loading').hide();

</script>

<script>
$('#form1').submit(function(event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'includes/ajax.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (data) {
            console.log(data);

            $('#output').html('');
            $.each(data, function (index, value) {
                if (value != "") {
                    $("#output").append( value ).append("\n");
                }
            });
            
            $('#loading').hide();

        }
    });
    
    $('#output').html('');
    $('#loading').show()

});

$('#loading').hide();

</script>

<script>
$('#formInject2').submit(function(event) {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'includes/ajax.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (data) {
            console.log(data);

            $('#inject').html('');
            $.each(data, function (index, value) {
                $("#inject").append( value ).append("\n");
            });
            
            $('#loading').hide();
            
        }
    });
    
    $('#output').html('');
    $('#loading').show()

});

$('#loading').hide();

</script>

<?
if ($_GET["tab"] == 1) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 1 });";
	echo "</script>";
} else if ($_GET["tab"] == 2) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 2 });";
	echo "</script>";
} else if ($_GET["tab"] == 3) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 3 });";
	echo "</script>";
} else if ($_GET["tab"] == 4) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 4 });";
	echo "</script>";
} else if ($_GET["tab"] == 5) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 5 });";
	echo "</script>";
} else if ($_GET["tab"] == 6) {
	echo "<script>";
	echo "$( '#result' ).tabs({ active: 6 });";
	echo "</script>";
}
?>

</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#body').show();
    $('#msg').hide();
});
</script>

</body>
</html>
