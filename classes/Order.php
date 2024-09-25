<?php

namespace  RINDA_DELIVERY_SERVICE\Order;
class Order {
    private $order_id;
    private $client_id;
    private $driver_id;
    private $status;
    private $address;
    private $contact_info;

    public function __construct($order_id, $client_id, $driver_id, $status, $address, $contact_info) {
        $this->order_id = $order_id;
        $this->client_id = $client_id;
        $this->driver_id = $driver_id;
        $this->status = $status;
        $this->address = $address;
        $this->contact_info = $contact_info;
    }

    public function updateStatus($status, $pdo) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->execute([$status, $this->order_id]);
    }
}
?>
