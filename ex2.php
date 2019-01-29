<a href="index.php">Retour</a> - <a href="ex1.php">Exercice 1</a> - <a href="https://github.com/TheDoudou/poo">Source</a><hr>
<?php

class Car {

    /*private $matriculation;
    private $doc; // Date of circulation;
    private $mileage; // km
    private $model;
    private $brand;
    private $color;
    private $size; // Kg
    private $catSize;*/

    private $data = ['matriculation' => '', 'doc' => '', 'mileage' => '', 'model' => '', 'brand' => '', 'color' => '', 'weight' => ''];
    private $calc = ['state' => 'free', 'type' => 'utilitaire', 'country' => '', 'use' => '', 'dateUse' =>'']; // Calculed data

    private $country = ['France' => 'F', 'Belgique' => 'B', 'Germany' => 'D']; // https://en.wikipedia.org/wiki/International_vehicle_registration_code

    function __construct() {
        for ($i=0; $i < count(func_get_args()); $i++) {
            $this->data[array_keys($this->data)[$i]] = func_get_args()[$i];
        }

        if ($this->data['brand'] == 'Audi')
            $this->calc['state'] = 'reserved';

        if ($this->data['weight'] > 3500)
            $this->calc['type'] = 'commercial';

        if (false !== $key = array_search($this->data['matriculation'][0], $this->country))
            $this->calc['country'] = $key;

        $this->updateUse($this->data['mileage']);

        $date1 = new DateTime();
        $date2 = new DateTime($this->data['doc']);
        $int = $date1->diff($date2);

        $this->calc['dateUse'] = $int->format('%y');
    }

    private function updateUse($mileage) {
        if ($mileage < 100000)
            $this->calc['use'] = 'low';
        else if ($mileage < 200000)
            $this->calc['use'] = 'middle';
        else
            $this->calc['use'] = 'high';
    }

    public function forward() {

        $this->data['mileage'] = $this->data['mileage']+100000;

        $this->updateUse($this->data['mileage']);

    }

    public function backward() {

        $this->data['mileage'] = $this->data['mileage']-100000;

        $this->updateUse($this->data['mileage']);

    }

    public function getValue() {

        return [$this->data['brand'],
                $this->data['model'],
                $this->data['doc'],
                $this->calc['dateUse'],
                $this->data['mileage'],
                $this->data['color'],
                $this->data['matriculation'],
                $this->calc['country']];
    }

    public function debugOnlyViewData() {
        return var_dump(array_merge(['data'=>$this->data, 'calculed'=>$this->calc]));
    }
}

//*
$test = new Car('B 001ABC001', '2145-12-25', '90000', 'Space Cruisader', 'DIY', 'metal', '3500');
$test->debugOnlyViewData();
$test->forward();
echo '<br><br>Car::forward()<br><br>';
$test->debugOnlyViewData();
echo '<br><br>';
//*/

$arrayCars = [
['B 0001', '2008-11-13', '150000', 'BD9', 'Aston Martin', 'White', '1950', 'https://www.cars-data.com/pictures/thumbs/350px/aston-martin/aston-martin-db9-volante_65_3.jpg'],
['F 0001', '1998-10-05', '100000', 'TT', 'Audi', 'Rouge', '1280', 'https://www.cars-data.com/pictures/thumbs/350px/audi/audi-tt-coupe_218_2.jpg'],
['D 0001', '2016-08-10', '75000', 'Model X P90D', 'Tesla', 'White', '2440', 'https://www.cars-data.com/pictures/thumbs/350px/tesla/tesla-model-x_3532_10.jpg'],
['B 0002', '1974-09-03', '50000', 'Robin', 'Reliant', 'Bleu', '450', 'https://www.historics.co.uk/media/1550924/1992_reliant_robin_lx_1.jpg?anchor=center&mode=crop&width=1000'],
['F 0002', '2016-12-01', '100000', 'Bentayga ', 'Bentley', 'Argent', '2340', 'https://www.cars-data.com/pictures/thumbs/350px/bentley/bentley-bentayga_3570_1.jpg'],
['D 0002', '2017-02-05', '50000', 'Seven', 'Caterham', 'Rouge', '650', 'http://www.caterham.fr/wp-content/uploads/2014/02/caterham485.jpg'],
['B 0003', '2013-11-21', '75000', 'RS7', 'Audi', 'Blanc', '1895', 'https://www.cars-data.com/pictures/audi/audi-rs7-sportback_3143_10.jpg'],
['F 0003', '2000-05-24', '100000', 'Exige', 'Lotus', 'Jaune', '780', 'https://www.cars-data.com/pictures/thumbs/350px/lotus/lotus-exige_1304_1.jpg'],
['D 0003', '1917-01-01', '25000', 'Brutus', 'BMW', 'Black', '720', 'https://blogautomobile.fr/wp-content/uploads/2010/09/brutus13-533x400.jpg'],
];

?>
<table style="undefined;table-layout: fixed; width: 817px">
    <colgroup>
        <col style="">
        <col style="">
        <col style="">
        <col style="">
        <col style="">
        <col style="">
        <col style="">
        <col style="">
    </colgroup>
    <tr>
        <th>Marque</th>
        <th>Modèle</th>
        <th>Année</th>
        <th>Age</th>
        <th>Km</th>
        <th>Couleur</th>
        <th>Imat</th>
        <th>Pays</th>
        <th>Image</th>
    </tr>
    <?php for ($i=0; $i<count($arrayCars); $i++):
            $cars[$i] = new Car($arrayCars[$i][0], $arrayCars[$i][1], $arrayCars[$i][2], $arrayCars[$i][3], $arrayCars[$i][4], $arrayCars[$i][5], $arrayCars[$i][6]);
    ?>
    <tr>
        <td><?= $cars[$i]->getValue()[0] ?></td>
        <td><?= $cars[$i]->getValue()[1] ?></td>
        <td><?= $cars[$i]->getValue()[2] ?></td>
        <td><?= $cars[$i]->getValue()[3] ?></td>
        <td><?= $cars[$i]->getValue()[4] ?></td>
        <td><?= $cars[$i]->getValue()[5] ?></td>
        <td><?= $cars[$i]->getValue()[6] ?></td>
        <td><?= $cars[$i]->getValue()[7] ?></td>
        <td><a href="<?= $arrayCars[$i][7] ?>" target="_blank">Image</a></td>
    </tr>
    <?php endfor; ?>
</table>

<?


//$id_view = intval($_GET['id']);

try {
    $pdo = new PDO('mysql:host=192.168.0.10;dbname=analyse;charset=utf8', 'analyse', '9BxrQUuS7L63wWRm');

    $data = $pdo->query("SELECT `ip`, `count` FROM `log_poo` WHERE `ip` LIKE '".$_SERVER['REMOTE_ADDR']."'")->fetch();

    if ($data)
        $pdo->query("UPDATE `log_poo` SET `last_connect` = '".date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])."', `agent` = '".$_SERVER['HTTP_USER_AGENT']."', `count` = '".($data['count']+1)."' WHERE `ip` LIKE '".$_SERVER['REMOTE_ADDR']."'");
    else
        $pdo->query("INSERT INTO `log_poo`
                        (`id`, `ip`, `agent`, `first_connect`, `last_connect`, `count`, `id_view`) VALUES
                        (NULL, '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', '".date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])."', '".date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])."', '1', '".$id_view."')");
    $pdo = null;
}

catch (Exception $e)
{
        //die('Erreur : ' . $e->getMessage());
}