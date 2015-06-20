<?
$mod_name="mitmf";
$mod_version="1.0";
$mod_path="/usr/share/fruitywifi/www/modules/$mod_name";
$mod_logs="/usr/share/fruitywifi/www/modules/mitmf/includes/MITMf-master/logs/mitmf.log";
$mod_logs_history="$mod_path/includes/logs/";
$mod_sslstrip_inject=0;
$mod_sslstrip_tamperer=0;
$mod_sslstrip_filter="";
$mod_panel="show";
$mod_isup="ps aux|grep -E 'python mitmf.py' | grep -v grep | awk '{print $2}'";
$mod_alias="MITMf";
# EXEC
$bin_danger = "/usr/share/fruitywifi/bin/danger";
$bin_mitmf = "$mod_path/includes/mitmf";
$bin_python = "/usr/bin/python";
$bin_rm = "/bin/rm";
$bin_echo = "/bin/echo";
$bin_touch = "/bin/touch";
$bin_mv = "/bin/mv";
$bin_sed = "/bin/sed";
$bin_dos2unix = "/usr/bin/dos2unix";
$bin_iptables = "/sbin/iptables";
$bin_killall = "/usr/bin/killall";
$bin_cp = "/bin/cp";
?>
