<?php

namespace Zf2datatable\Filter;

use Zend\Filter\AbstractFilter;

class DateCalendar extends AbstractFilter {

	/**
	 * A valid format string accepted by date()
	 *
	 * @var string
	 */
	protected $format = 'Y-m-d';

	/**
	 * Sets filter options
	 *
	 * @param array|Traversable $options
	 */
	public function __construct($options = null)
	{
		if ($options) {
			$this->setOptions($options);
		}

	}

	/**
	 * Set the format string accepted by date() to use when formatting a string
	 *
	 * @param  string $format
	 * @return self
	 */
	public function setFormat($format)
	{
		$this->format = $format;

		return $this;
	}

	/**
	 * Filter a datetime string by normalizing it to the filters specified format
	 *
	 * @param  DateTime|string|integer $value
	 * @throws Exception\InvalidArgumentException
	 * @return string
	 */
	public function filter($value)
	{
		try {
			$result = $this->normalizeDateTime($value);
		} catch (\Exception $e) {
			// DateTime threw an exception, an invalid date string was provided
			//throw new \Exception('Invalid date string provided', $e->getCode(), $e);
		}

		if ($result === false) {
			return $value;
		}

		return $result;
	}

	/**
	 * Normalize the provided value to a formatted string
	 *
	 * @param  string|int|DateTime $value
	 * @return string
	 */
	protected function normalizeDateTime($value)
	{


	    $this->setFormat(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_OUT);


        if ($value === '' || $value === null) {
			return $value;
		}

		if (!is_string($value) && !is_int($value) && !$value instanceof \DateTime) {
			return $value;
		}

		if (is_int($value)) {
			//timestamp
			$value = new \DateTime('@' . $value);
			return $value->format($this->format);
		}
		elseif (!$value instanceof DateTime) {
			$value = new \DateTime($value);
			return $value->format($this->format);
		}
		elseif(is_string($value)){
		    return self::converDate($value);
		}


	}


	public static function converDate($value){

	    if($value instanceof \DateTime){
	        return $value->format(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_IN);
	    }

	    $match = array();
	    if(preg_match(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_IN_MASK, $value,$match)){
	        $dateOut = \DateTime::createFromFormat(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_IN,$value);
	        return $dateOut->format(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_OUT);
	    }
	    elseif(preg_match(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_OUT_MASK, $value,$match)){
	        $dateIn = \DateTime::createFromFormat(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_OUT,$value);
	        return $dateIn->format(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_IN);
	    }
	    elseif(null === $value){
	        return $value;
	    }
	    else{
	        throw new \Exception('Bad Date Format:value'.$value, $code, $previous);
	    }


	    return $value;
	}


}

?>