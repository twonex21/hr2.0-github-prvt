<?php

require_once LIB_DIR.'modules/core/QueryBuilder.php';
require_once LIB_DIR.'modules/core/Mailer.php';
require_once LIB_DIR.'modules/core/FrontendUtils.php';

class ShellModel extends Model
{
    public $mysql = null; //Holds the MySQLBean object
	public $qb = null;
	public $bcrypt = null;
	
    /**
     * Constructs a model
     *
     */
    function __construct()
    {
        $this->mysql = $this->getDBConnection();
		$this->qb = new QueryBuilder($this->mysql);
        $this->mailer = new Mailer();        
    }
    
    
    public function getNewVideos($interval) {
    	$videos = array();
    	$where_sql = "";
    	
    	if(MODERATION_STRICT_MODE) {    		
    		$where_sql = " AND v.status='ACCEPTED' ";
    	}
    	
    	$sql = "SELECT v.vid AS videoId, v.tag AS tagName, v.lat, v.lng, p.pid AS venueId, p.name AS venueName, CONCAT(ac.first_name, ' ', ac.last_name) AS accountName, ac.acid AS accountId
    			FROM rec_video v
    			INNER JOIN rec_product p ON v.pid=p.pid
    			INNER JOIN rec_account ac ON v.acid=ac.acid
    			WHERE v.push_sent=0 AND DATE_SUB(NOW(), INTERVAL ".$interval.") <= v.created_at".$where_sql."
    			GROUP BY v.vid";
    	
    	$result = $this->mysql->query($sql);
        
        while($row = $this->mysql->getNextResult($result)) {
        	$videos[] = $row;
        }
        
        return $videos;
    }
    
    
    public function getNearbyUsers($accountId, $lat, $lng, $radius, $activePeriod='2 HOUR') {
    	$users = array();    	

    	if($lat && $lng) {
    		$earthRadius = (GEO_IS_MI) ? GEO_EARTH_RADIUS_MI : GEO_EARTH_RADIUS_KM;

 			// Here we are using sphere Earth distance formula
	 		$distance = sprintf("ROUND(%1\$d * ACOS(SIN(RADIANS(ac.lat)) * SIN(RADIANS(%2\$f)) + COS(RADIANS(ac.lat)) * COS(RADIANS(%2\$f)) * COS(RADIANS(ac.lng) - RADIANS(%3\$f))), 1)", $earthRadius, $lat, $lng);
    	}
    	   	
    	$sql = "SELECT ac.acid AS accountId, CONCAT(ac.first_name, ' ', ac.last_name) AS accountName , GROUP_CONCAT(ad.device_type) AS device_types, GROUP_CONCAT(IFNULL(ad.device_token, '')) AS device_tokens
    			FROM rec_account ac
    			INNER JOIN rec_account_device ad ON ad.acid=ac.acid
    			LEFT JOIN rec_account_pushes ap ON ap.acid=ac.acid AND DATE_SUB(NOW(), INTERVAL 24 HOUR) <= ap.sent_at
    			WHERE ac.acid != $accountId AND $distance <= $radius AND DATE_SUB(NOW(), INTERVAL $activePeriod) <= ac.loc_updated_at
    			GROUP BY ac.acid
    			HAVING COUNT(DISTINCT ap.pshid) < 3";
    	
    	$result = $this->mysql->query($sql);
        
        while($row = $this->mysql->getNextResult($result)) {
        	$users[] = $row;
        }
        
        return $users;
    }
    
        
    public function storePushInfo($accountId, $pushType) {    	   
    	$sql = "INSERT INTO rec_account_pushes (acid, type, sent_at) VALUES (%d, '%s', NOW())";
    	$sql = $this->mysql->format($sql, array($accountId, $pushType));
    	$this->mysql->query($sql);        
    }
    
    public function setVideoPushSent($videoId) {    	   
    	$sql = "UPDATE rec_video SET push_sent=1 WHERE vid=".$videoId;
    	$this->mysql->query($sql);        
    }
    
    
    public function getDailyPushVideo($type) {
    	$video = array();
    	
    	$sql = "SELECT v.vid AS videoId, p.pid AS venueId, p.name AS venueName, ac.acid AS accountId, CONCAT(ac.first_name, ' ', ac.last_name) AS accountName, p.city AS pcity, v.city AS vcity, SUBSTRING(IF(v.city='' OR v.city='SOME_CITYNAME', p.city, v.city), 1, (INSTR(IF(v.city='' OR v.city='SOME_CITYNAME', p.city, v.city), ',') -1)) AS location
				FROM rec_temp_push_video pv
				INNER JOIN rec_video v ON pv.vid=v.vid
				INNER JOIN rec_product p ON v.pid=p.pid
				INNER JOIN rec_account ac ON v.acid=ac.acid
				WHERE sent=0 AND pv.type='%s'
				ORDER BY pv.pvid
				LIMIT 1";    	
    	$sql = $this->mysql->format($sql, array($type));
    	
    	$result = $this->mysql->query($sql);
    	if($row = $this->mysql->getNextResult($result)) {
        	$video = $row;
        }

        return $video;
    }

        
    public function setDailyPushSent($videoId) {    	   
    	$sql = "UPDATE rec_temp_push_video SET sent=1 WHERE vid=".$videoId;
    	$this->mysql->query($sql);        
    }
    
    
    public function markVideoAsNew($videoId) {    	   
    	$sql = "UPDATE rec_video SET is_new=0";
    	$this->mysql->query($sql);
    	
    	$sql = "UPDATE rec_video SET is_new=1 WHERE vid=".$videoId;
    	$this->mysql->query($sql);        
    }       
}


//EOF
?>