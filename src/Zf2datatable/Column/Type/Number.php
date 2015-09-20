<?php
namespace Zf2datatable\Column\Type;

use Zf2datatable\Filter;
use Locale;
use NumberFormatter;

class Number extends AbstractType
{

    /**
     * Locale to use instead of the default.
     *
     * @var string
     */
    protected $locale;

    /**
     * NumberFormat style to use.
     *
     * @var integer
     */
    protected $formatStyle;

    /**
     * NumberFormat type to use.
     *
     * @var integer
     */
    protected $formatType;

    protected $attributes = array();

    protected $prefix = '';

    protected $suffix = '';

    protected $pattern;

    public function __construct($formatStyle = NumberFormatter::DECIMAL, $formatType = NumberFormatter::TYPE_DEFAULT, $locale = null)
    {
        $this->setFormatStyle($formatStyle);
        $this->setFormatType($formatType);
        $this->setLocale($locale);
    }

    public function getTypeName()
    {
        return 'number';
    }

    public function setFormatStyle($style = NumberFormatter::DECIMAL)
    {
        $this->formatStyle = $style;
    }

    public function getFormatStyle()
    {
        return $this->formatStyle;
    }

    public function setFormatType($type = NumberFormatter::TYPE_DEFAULT)
    {
        $this->formatType = $type;
    }

    public function getFormatType()
    {
        return $this->formatType;
    }

    public function setLocale($locale = null)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        if ($this->locale === null) {
            $this->locale = Locale::getDefault();
        }

        return $this->locale;
    }

    /**
     * Set an attribute
     *
     * @link http://www.php.net/manual/en/numberformatter.setattribute.php
     * @param
     *            attr int <p>
     *            Attribute specifier - one of the
     *            numeric attribute constants.
     *            </p>
     * @param
     *            value int <p>
     *            The attribute value.
     *            </p>
     */
    public function addAttribute($attr, $value)
    {
        $this->attributes[] = array(
            'attribute' => $attr,
            'value' => $value
        );
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setSuffix($string = '')
    {
        $this->suffix = (string) $string;
    }

    public function getSuffix()
    {
        return $this->suffix;
    }

    public function setPrefix($string = '')
    {
        $this->prefix = (string) $string;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function getFilterDefaultOperation()
    {
        return Filter::EQUAL;
    }

    /**
     *
     * @return NumberFormatter
     */
    protected function getFormatter()
    {
        $formatter = new NumberFormatter($this->getLocale(), $this->getFormatStyle());
        if ($this->getPattern() !== null) {
            $formatter->setPattern($this->getPattern());
        }
        foreach ($this->getAttributes() as $attribute) {
            $formatter->setAttribute($attribute['attribute'], $attribute['value']);
        }

        return $formatter;
    }

    /**
     *
     * @param  string $val
     * @return string
     */
    public function getFilterValue($val)
    {
        $formatter = $this->getFormatter();

        if (strlen($this->getPrefix()) > 0 && strpos($val, $this->getPrefix()) === 0) {
            $val = substr($val, strlen($this->getPrefix()));
        }
        if (strlen($this->getSuffix()) > 0 && strpos($val, $this->getSuffix()) > 0) {
            $val = substr($val, 0, - strlen($this->getSuffix()));
        }

        try {
            $formattedValue = $formatter->parse($val);
        } catch (\Exception $e) {
            return $val;
        }

        if ($formattedValue === false) {
            return $val;
        }

        return $formattedValue;
    }

    /**
     * Convert the value from the source to the value, which the user will see
     *
     * @param  string $val
     * @return string
     */
    public function getUserValue($val)
    {
        $formatter = $this->getFormatter();

        $formattedValue = $formatter->format($val, $this->getFormatType());

        return (string) $this->getPrefix() . $formattedValue . $this->getSuffix();
    }
}
