<?php
class Car
{
    public int $id;
    public string $brand;
    public string $model;
    public string $country;
    public float $price_per_hour;
    public string $type;
    public string $status;
    public string $created_at;
    public string $updated_at;

    public function __construct(
        int $id = 0,
        string $brand = '',
        string $model = '',
        string $country = '',
        float $price_per_hour = 0.0,
        string $type = '',
        string $status = 'available',
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->brand = $brand;
        $this->model = $model;
        $this->country = $country;
        $this->price_per_hour = $price_per_hour;
        $this->type = $type;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
?>
