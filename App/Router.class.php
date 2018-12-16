<?php

namespace TestApp;

class Router
{
    /**
     * список разрешенных инструкций
     * @var array
     */
    private $allowedCommandsList = [
        'walk', 'turn', 'start'
    ];

    /**
     * @var array
     */
    private $point = [
        'x' => 0.0,
        'y' => 0.0
    ];

    /**
     * @var array
     */
    private $points = [];

    /**
     * @var float
     */
    private $course = 0.0;

    /**
     * @var array
     */
    private $commandList = [];

    /**
     * Router constructor.
     * @param string $commandString
     */
    public function __construct($commandString = '')
    {
        $this->commandList = $this->parse($commandString);
    }

    /*public function findPages(array $page = [1], integer $limit  = null) {

    }*/

    /**
     * @return array
     * @throws \Exception
     */
    public function Route()
    {

        while(!empty($this->commandList)) {

            $actionBloc = array_shift($this->commandList);

            $action = trim(array_shift($actionBloc));
            $value  = trim(array_shift($actionBloc));

            if (is_null($value)) {
                throw new \Exception("Value for action '$action' is not defined");
            }

            if (!in_array($action, $this->allowedCommandsList)) {
                throw new \Exception("Action '$action' is not supported");
            }

            $this->{$action}(floatval($value));
        }

        return [
            'x' => $this->point['x'],
            'y' => $this->point['y']
        ];
    }

    /**
     * @param string $commandString
     * @return array
     * @throws \Exception
     */
    private function parse($commandString = '')
    {

        $commandString = preg_replace("/  +/", " ", $commandString);
        $commandRows = explode(' ', $commandString);

        try {
            $commandRows = array_chunk($commandRows, 2);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $startingPoint = array_shift($commandRows);
        $this->point['x'] = floatval(array_shift($startingPoint));
        $this->point['y'] = floatval(array_shift($startingPoint));

        if (count($commandRows) < 2) {
            throw new \Exception("Command '$commandString' is short");
        }

        if (count($commandRows) > 26) {
            throw new \Exception("Command '$commandString' contains more than 25 instructions");
        }

        return $commandRows;
    }

    /**
     * задаем направление движения от точки опроса
     * @param $course
     * @return $this
     */
    private function start($course)
    {
        $this->course = $course;
        return $this;
    }

    /**
     * считаем сколько чего прошли
     * @param $distance
     * @return $this
     */
    private function walk($distance)
    {
        $this->point['x'] += $distance * cos($this->course * M_PI / 180);
        $this->point['y'] += $distance * sin($this->course * M_PI / 180);

        $this->points[] = [
            'x' => $this->points,
            'y' => $this->point['y']
        ];

        return $this;
    }

    /**
     * учитывем поворот
     * @param $course
     * @return $this
     */
    private function turn($course)
    {
        $this->course += $course;
        return $this;
    }
}