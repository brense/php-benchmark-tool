<?php

class Benchmark {
	
	private $_cases = array();
	private $_time = 0;
	private $_memory = 0;
	
	public function __construct(){
		
	}
	
	public function start(){
		$this->_time = microtime(true);
		$this->_memory = memory_get_usage();
	}
	
	public function finish(){
		$case = array();
		$case['start'] = $this->_time;
		$case['end'] = microtime(true);
		$case['time'] = $case['end'] - $case['start'];
		$case['memory'] = memory_get_usage() - $this->_memory;
		$this->_cases[] = $case;
	}
	
	public function flush(){
		$this->_cases = array();
		$this->_time = 0;
		$this->_memory = 0;
	}
	
	public function get_time(){
		return $this->trueMean('time') * 1000;
	}
	
	public function get_memory(){
		return round($this->trueMean('memory'));
	}
	
	private function trueMean($index){
		$arr = array();
		$total = 0;
		foreach($this->_cases as $case){
			$arr[] = $case[$index];
			$total += $case[$index];
		}
		sort($arr);
		$median = (string)array_slice($arr, (count($arr) / 2), 1);
		$avarage = $total / count($arr);
		return ($avarage + $median) / 2;
	}
	
}