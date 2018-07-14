<?php
//connect to database
        class usersOnline {

            
            var $count = 0;
            var $error;
            var $i = 0;

            function usersOnline () {
                $this->timestamp = time();
                $this->ip = $this->ipCheck();
                $this->new_user();
                $this->delete_user();
                $this->count_users();
                }

            function ipCheck() {
                if (getenv('HTTP_CLIENT_IP')) {
                    $ip = getenv('HTTP_CLIENT_IP');
                    }
                elseif (getenv('HTTP_X_FORWARDED_FOR')) {
                    $ip = getenv('HTTP_X_FORWARDED_FOR');
                    }
                elseif (getenv('HTTP_X_FORWARDED')) {
                    $ip = getenv('HTTP_X_FORWARDED');
                    }
                elseif (getenv('HTTP_FORWARDED_FOR')) {
                    $ip = getenv('HTTP_FORWARDED_FOR');
                    }
                elseif (getenv('HTTP_FORWARDED')) {
                    $ip = getenv('HTTP_FORWARDED');
                    }
                else {
                    $ip = $_SERVER['REMOTE_ADDR'];
                    }
                return $ip;
                }
            function new_user() {
                $cekIP = "SELECT ip FROM useronline WHERE ip='$this->ip'";
                $resultCekIp=mysql_query($cekIP);
                $countCekIp=mysql_num_rows($resultCekIp);
                if($countCekIp>0){
                    $insert1 = mysql_query ("UPDATE useronline SET timestamp='$this->timestamp', date=NOW(), ip='', distinct_ip='$this->ip'");
                    if (!$insert1) {
                        $this->error[$this->i] = "Unable to record new visitor\r\n";            
                        $this->i ++;
                        }
                    }
                else{
                    $insert2 = mysql_query ("INSERT INTO useronline (timestamp, date, ip, distinct_ip) VALUES ('$this->timestamp',NOW(), '$this->ip', '$this->ip')");
                    if (!$insert2) {
                        $this->error[$this->i] = "Unable to record new visitor\r\n";            
                        $this->i ++;
                        }
                    }
                }
            function delete_user() {
                $delete = mysql_query ("DELETE FROM useronline WHERE timestamp < ($this->timestamp - $this->timeout)");
                if (!$delete) {
                    $this->error[$this->i] = "Unable to delete visitors";
                    $this->i ++;
                    }
                }
            function count_users() {
                if (count($this->error) == 0) {
                    $count = mysql_num_rows ( mysql_query("SELECT DISTINCT ip FROM useronline"));
                    return $count;
                    }
                }
            }
        ?>
        
        
        <?php
error_reporting(0);
		$link=mysql_connect("*******","*********","*****") or die("Not found");
		if(!$link)
		{
			echo "Error :- Server is not found !".mysql_error();
		}
		$db=mysql_select_db("*********",$link);
		if(!$db)
		{
			echo "Error :- Database is not found !".mysql_error();
		}
    
    
    
    
			$jml_ol = new usersOnline();
if (count($jml_ol->error) == 0) {
    if ($jml_ol->count_users() == 1) {
        echo $jml_ol->count_users() . "<br />";
        echo "Your IP: " . $jml_ol->ipCheck();
        }
    else{
        echo $jml_ol->count_users() . "<br />";
        echo "Your IP: " . $jml_ol->ipCheck();
        }
}
?>
