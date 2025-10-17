<?php
class ParkingZone
{
    public int $id;
    public string $address;
    public float $coordinate_x;
    public float $coordinate_y;
    public int $total_spaces;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        int $id = 0,
        string $address = '',
        float $coordinate_x = 0.0,
        float $coordinate_y = 0.0,
        int $total_spaces = 0,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->address = $address;
        $this->coordinate_x = $coordinate_x;
        $this->coordinate_y = $coordinate_y;
        $this->total_spaces = $total_spaces;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
?>
