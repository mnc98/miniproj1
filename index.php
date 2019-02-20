<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>CSV HTML Reader</title>
</head>
<body>
<h1>IS601 Mini Project 1</h1>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<?php

main::start("example.csv");
class main  {
    static public function start($filename) {
        $records = csv::getRecords($filename);
        html::buildTable($records);
    }
}

class csv {
    static public function getRecords($filename) {
        $file = fopen($filename, "r");
        $fieldNames = array();
        $count = 0;
        while(! feof($file))
        {
            $record = fgetcsv($file);
            if($count == 0) {
                $fieldNames = $record;
            } else {
                $records[] = recordF::create($fieldNames, $record);
            }
            $count++;
        }
        fclose($file);
        return $records;
    }
}

class record {
    public function __construct(Array $fieldNames = null, $values = null ) {
        $record = array_combine($fieldNames, $values);
        foreach ($record as $property => $value) {
            $this->createProperty($property, $value);
        }
    }
    public function returnArray() {
        return (array) $this;
    }
    public function createProperty($field, $value) {
        $this->{$field} = $value;
    }
}

class recordF {
    public static function create(Array $fieldNames = null, Array $values = null) {
        return new record($fieldNames, $values);
    }
}

class html {

    public static function buildTable($records) {
        $html_str = "<table class=\"table\" border=\"1\"><thead class=\"thead-dark\"><tr>";
        $headers = array_keys($records[0]->returnArray());
        foreach ($headers as $header) {
            $html_str .= "<th>";
            $html_str .= $header;
            $html_str .= "</th>";
        }
        $html_str .= "</tr></thead>";
        $html_str .= "<tbody>";
        foreach ($records as $row) {
            $row = array_values($row->returnArray());
            $html_str .= "<tr>";
            foreach ($row as $col) {
                $html_str .= "<td>";
                $html_str .= $col;
                $html_str .= "</td>";
            }
            $html_str .= "</tr>";
        }
        $html_str .= "</tbody></table>";
        print_r($html_str);
    }
}
?>
</body>
</html>