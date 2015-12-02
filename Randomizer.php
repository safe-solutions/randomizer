<?php
class Randomizer
{
	private $__text;
	private $__dir;
	
	public static function get_instance()
	{
		return new self;
	}
	
	public function content($text)
	{
		$this->__text = $text;
		return $this;
	}
	
	public function dir($dir)
	{
		$this->__dir = $dir;
	}
	
	public function make()
	{
		do {
			preg_match_all('|\{([^\{\}]+)\}|U', $this->__text, $out, PREG_PATTERN_ORDER);
			foreach ($out[1] as $t)
			{
				$command = substr($t, 0, 2);
				if (($command == '%Q') && (preg_match('|^%Q=\((\d*),([^\)]+)\) (.*)$|', $t, $matches)))
				{
					$count = $matches[1];
					$delim = $matches[2];
					$ts = explode('|', $matches[3]);
					$words = array();
					for ($x = 0; $x < $count; $x++)
					{
						do {
							$word = $ts[rand(0, count($ts) - 1)];
						} while (in_array($word, $words));
						$words[] = $word;
					}
					$this->__text = preg_replace('/\{' . preg_quote($matches[0]) . '\}/', implode($delim, $words), $this->__text, 1);
				}
				elseif (($command == '%R') && (preg_match('|^%R=\(([^\)]+)\) (.*)$|', $t, $matches)))
				{
					$delim = $matches[1];
					$ts = explode('|', $matches[2]);
					shuffle($ts);
					$this->__text = preg_replace('/\{' . preg_quote($matches[0]) . '\}/', implode($delim, $ts), $this->__text, 1);
				}
				else
				{
					$ts = explode('|', $t);
					$this->__text = preg_replace('/\{' . preg_quote($t) . '\}/', $ts[rand(0, count($ts) - 1)], $this->__text, 1);
				}
			}
		}
		while ($out[1]);
		
		return $this;
	}
	
	public function __toString()
	{
		return $this->__text;
	}
}
?>
