<?php

class Db
{
    public $instance;

    /**
     * @param $config
     * DB constructor.
     */
    public function __construct($config)
    {
        $this->instance = new PDO(sprintf('mysql:host=%s;dbname=%s', $config['host'], $config['db']), $config['username'], $config['password']);
    }

    /**
     * Add or Update
     * @param $data
     */
    function save($data)
    {

        $query = $this->instance->prepare("SELECT * FROM info WHERE ip_address=:ip_address AND user_agent=:user_agent AND page_url=:page_url");
        $query->execute(['ip_address' => $data['ip_address'], 'page_url' => $data['page_url'], 'user_agent' => $data['user_agent']]);
        $row = $query->fetch();
        if ($row) {
            $sql = "UPDATE info SET view_date=:view_date, views_count=:views_count WHERE id=:id";
            $stmt = $this->instance->prepare($sql);
            $stmt->execute(['view_date' => date('Y-m-d H:i:s'), 'views_count' => $row['views_count'] + 1, 'id' => $row['id']]);

        } else {

            $sql = "INSERT INTO info (ip_address, user_agent, view_date, page_url,views_count) VALUES (:ip_address, :user_agent, :view_date, :page_url, :views_count)";
            $stmt = $this->instance->prepare($sql);
            $stmt->execute($data);
        }
    }
}
