<?php
class Order
{
    public int $id;
    public int $user_id;
    public int $admin_id;
    public int $car_id;
    public int $start_parking_id;
    public int $end_parking_id;
    public string $status;
    public float $total_price;
    public string $start_time;
    public string $end_time;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        int $id = 0,
        int $user_id = 0,
        int $admin_id = 0,
        int $car_id = 0,
        int $start_parking_id = 0,
        int $end_parking_id = 0,
        string $status = 'pending',
        float $total_price = 0.0,
        string $start_time = '',
        string $end_time = '',
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->admin_id = $admin_id;
        $this->car_id = $car_id;
        $this->start_parking_id = $start_parking_id;
        $this->end_parking_id = $end_parking_id;
        $this->status = $status;
        $this->total_price = $total_price;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
?>
