<?php

class vsipdetails {

    protected $ip;
    protected $api = "https://ipinfo.io/", $details, $curl, $map, $db = false, $nocache;

    /**
     *    IP Details Construct
     *    @access public
     *    @param String $ip IP Address Of which the details are to be located.
     * 	 @param String $db PDO instance to allow database storage.
     *    @return boolean
     */
    public function __construct($ipaddress, $db = false) {
        if (!($db instanceof PDO)) {
            $this->ip = $ipaddress;
            $this->curl = curl_init($this->api . $this->ip);
            // var_dump($this->curl);
            curl_setopt($this->curl, CURLOPT_POST, 0);
            curl_setopt($this->curl, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
            curl_setopt($this->curl, CURLOPT_NOBODY, 0); // set to 1 to eliminate body info from response
            curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // if necessary use HTTP/1.0 instead of 1.1
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
            curl_setopt($this->curl, CURLOPT_TIMEOUT, 300);
            return true;
        } else {
            $this->ip = $ipaddress;
            $this->db = $db;
            return true;
        }
    }

    /**
     * Scan for the details of the ip address
     * @access public
     * @return void
     */
    public function scan() {
        if ($this->db) {
            $bdd = $this->db;
            $query = $bdd->prepare('SELECT details FROM ipcache WHERE ip=:ip'); //get the id
            $query->bindValue(':ip', $this->ip, PDO::PARAM_STR);
            $query->execute();
            $ip = $query->fetch();
            $query->CloseCursor();
            if (!empty($ip)) {
                $this->details = json_decode($ip['details']);
                return (array) $this->details;
            } else {
                $this->nocache = true;
                $this->curl = curl_init($this->api . $this->ip);
                // var_dump($this->curl);
                curl_setopt($this->curl, CURLOPT_POST, 0);
                curl_setopt($this->curl, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
                curl_setopt($this->curl, CURLOPT_NOBODY, 0); // set to 1 to eliminate body info from response
                curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); // if necessary use HTTP/1.0 instead of 1.1
                curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
                curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
                curl_setopt($this->curl, CURLOPT_TIMEOUT, 300);
                $this->details = curl_exec($this->curl);
                $this->details = json_decode($this->details);
                return (array) $this->details;
            }
        } else {
            $this->details = curl_exec($this->curl);
            $this->details = json_decode($this->details);
            return (array) $this->details;
        }
    }

    /**
     * Scan for the details of the ip address
     * @access public
     * @return void
     * @param String $zoom zoom parameter for google map.
     * @param String $size map dimensions.
     */
    public function get_map($zoom = "9", $size = '640x200') {
        $array = (is_array($this->details)) ? $this->details : (array) $this->scan();
        // var_dump($array);
        $this->map = '<img class="map" src="https://maps.googleapis.com/maps/api/staticmap?center=' . $array["loc"] . '&amp;zoom=' . $zoom . '&amp;size=' . $size . '&amp;sensor=false" alt="' . $array["city"] . ', ' . $array["region"] . ', ' . $array["country"] . ' Map" title="' . $array["city"] . ', ' . $array["region"] . ', ' . $array["country"] . ' Map">';
        return $this->map;
    }

    /**
     * To close the curl connection
     * @access public
     * @return boolean
     */
    public function close() {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
            return true;
        } else {
            return false;
        }
    }

    /**
     * To close the curl connection
     * @access public
     * @return boolean
     */
    public function cacheThis() {
        if ($this->details && $this->db && $this->nocache) {
            $properties = array("ip" => $this->ip, "details" => $this->__toString());
            $req = $this->db->prepare("
						INSERT IGNORE INTO ipcache (ip,details) VALUES (:ip,:details) 
						");
            $req->execute($properties);
            $req->CloseCursor();
            return true;
        } else {
            return false;
        }
    }

    /**
     * return IP details in Json format. 
     * @access public
     * @return string
     */
    public function __toString() {
        return json_encode($this->details);
    }

}

?>