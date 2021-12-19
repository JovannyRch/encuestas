<?php



class Db
{
    function __construct()
    {

        $server = 'localhost';
        $username = 'root';
        $database = 'encuestas_db';
        $password = '';

        try {
            $this->db = new mysqli($server, $username, $password, $database);
        } catch (Exception $e) {
            die('Connection Failed: ' . $e->getMessage());
        }
    }


    function row($sql)
    {
        $arr = $this->array($sql);

        if (sizeof($arr) == 0) {
            return null;
        }
        return $arr[0];
    }

    function array($sql)
    {
        try {
            $query = mysqli_query($this->db, $sql);

            $res = array();
            while ($row = mysqli_fetch_array($query)) {
                $res[] = $row;
            }
            return $res;
        } catch (\Throwable $th) {
            return array();
        }
    }

    function query($sql)
    {
        try{
            return $this->db->query($sql);
        }catch (\Throwable $th) {
            echo $th;
            return array();
        }
    }

    function insert($sql){
        $this->query($sql);
        return $this->db->insert_id;
    }
}