<?php

namespace TestApp;

class Navigator
{
    /**
     * @var array|string
     */
    protected $input = '';

    /**
     * @var array
     */
    protected $output = [];

    /**
     * Navigator constructor.
     * @param string $input
     */
    public function __construct($input = '')
    {
        $this->input = explode(PHP_EOL, $input);
    }

    /**
     * формируем массив значений для вывода
     */
    public function route()
    {
        $this->parse();

        foreach ($this->output as $key => $row) {

            $this->output[$key]['output'] = vsprintf("%.4f %.4f %.5f", $this->calculate($row['rowCommand']));

        }

        $this->output();

    }

    /**
     * разбиваем входящую простыню на массив блоков
     * результат складываем в $this->output
     */
    public function parse()
    {
        while(!empty($this->input)) {

            $line = mb_strtolower(trim(array_shift($this->input)));

            if ($line == 0) {
                return;
            }

            /**
             * 1≤n≤20
             * TODO - добавить проверку что в массиве есть нужное количество строк
             * или фильтровать пустые строки выборки array_slice
             **/
            if ($this->ifNextBlock($line)) {
                $output = array_slice($this->input, 0, (integer)$line);
                $this->output[] = [
                    'count' => (integer)$line,
                    'rowCommand' => $output
                ];
            }

        }
    }

    /**
     * проверяем не начало ли это нового блока команд
     * TODO - пересмотреть критерий поиска начала блока команд
     * @param int $int
     * @return bool|mixed
     */
    private function ifNextBlock($int = 0)
    {
        if (is_int($int)) {
            return false;
        }

        return filter_var(
            $int, FILTER_VALIDATE_INT,
            array(
                'options' => array(
                    'min_range' => 1,
                    'max_range' => 20
                )
            )
        );

    }

    /**
     * @param array $commandRows
     * @return array
     * @throws \Exception
     */
    private function calculate(array $commandRows)
    {
        $points = [];

        $summary = [
            'x' => 0.0,
            'y' => 0.0
        ];

        $distance = 0;

        while (!empty($commandRows)) {
            $commandLine = array_shift($commandRows);

            $points[] = $destination = (new Router($commandLine))->Route();

            $summary['x'] += $destination['x'];
            $summary['y'] += $destination['y'];
        }

        $avg = [
            'x' => $summary['x'] / count($points),
            'y' => $summary['y'] / count($points)
        ];

        foreach ($points as $point) {
            $distanceCommand = $this->distanceBetweenPoints($point, $avg);

            if ($distanceCommand > $distance) {
                $distance = $distanceCommand;
            }
        }

        $distance = sqrt($distance);

        return [
            $avg['x'],
            $avg['y'],
            $distance
        ];

    }

    /**
     * @param array $start
     * @param array $end
     * @return mixed
     */
    private function distanceBetweenPoints(array $start, array $end)
    {
        return (($start['x'] - $end['x']) ** 2) + (($start['y'] - $end['y']) ** 2);
    }

    private function output()
    {

        while(!empty($this->output)) {
            $row = array_shift($this->output);
            if (!empty($row['output'])) {
                echo $row['output'] . PHP_EOL;
            }
        }

    }
}