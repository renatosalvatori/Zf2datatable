<?php

namespace Zf2datatable\Form\View\Helper;

use Zend\Form\View\Helper\FormInput;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

class FormDateCalendar extends FormInput{


    /**
     * @var unknown
     */
    protected  $injectElement;

	/**
     * @return the $injectElement
     */
    public function getInjectElement()
    {
        return $this->injectElement;
    }

	/**
     * @param \Zf2datatable\Form\View\Helper\unknown $injectElement
     */
    public function setInjectElement($injectElement)
    {
        $this->injectElement = $injectElement;
    }


    public function __invoke(ElementInterface $element = null, $injectObject= null)
    {
        $this->setInjectElement(null);
        if (!$element) {
            return $this;
        }



        if ($injectObject) {
            $this->setInjectElement($injectObject);
        }

        return $this->render($element);
    }


	/**
	 * Render a form <input> element from the provided $element
	 *
	 * @param  ElementInterface $element
	 * @throws Exception\DomainException
	 * @return string
	 */
	public function render(\Zf2datatable\Form\Element\DateCalendar $element)
	{

		$name = $element->getName();
		if ($name === null || $name === '') {
			throw new Exception\DomainException(sprintf(
					'%s requires that the element has an assigned name; none discovered',
					__METHOD__
			));
		}

		$attributes          = $element->getAttributes();
		$attributes['name']  = $name;
		$attributes['type']  = $this->getType($element);
		$date=new \DateTime('NOW');
		$dateFormat=$date->format(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_IN);
		$elementValue = $element->getValue();


		if(preg_match(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_OUT_MASK, $elementValue,$match)){
		   $dateFormatValue = \DateTime::createFromFormat(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_OUT,$elementValue);

		}
		else
		  $dateFormatValue = \DateTime::createFromFormat(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_IN,$elementValue);


	    if($dateFormatValue instanceof \DateTime)
			$attributes['value'] = $dateFormatValue->format(\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_IN);
		else
			$attributes['value'] = '';



   	    return sprintf(
				"
				<div class='input-group date'  id='$name' data-date=$dateFormat	data-date-format='".\Zf2datatable\Form\Element\DateCalendar::$DATE_FORMAT_IN."'>
				<input %s%s",
				$this->createAttributesString($attributes),
				$this->getInlineClosingBracket()
		);

	}

	/**
	 * Get the closing bracket for an inline tag
	 *
	 * Closes as either "/>" for XHTML doctypes or ">" otherwise.
	 *
	 * @return string
	 */
	public function getInlineClosingBracket()
	{
		$doctypeHelper = $this->getDoctypeHelper();
		if ($doctypeHelper->isXhtml()) {
			$html= '/><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div>';
			$html.=$this->getInjectElement();
			$html.="</div>";
			return $html;
		}
		$html= '><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>';
		$html.=$this->getInjectElement();
		$html.="</div>";
		return $html;
	}



}

?>