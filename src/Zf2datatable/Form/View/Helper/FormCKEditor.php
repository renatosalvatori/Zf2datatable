<?php
namespace Zf2datatable\Form\View\Helper;

use \Zend\Form\View\Helper\FormTextarea as FormTextarea;

class FormCKEditor extends FormTextarea
{
    /**
     * @var \Zend\View\Helper\EscapeJs
     */
    protected $escapeJsHelper;
    protected $placeHolder = null;
    /**
     * @see \Zend\Form\View\Helper\FormTextarea::render()
     * @param \Zend\Form\ElementInterface $element
     * @return string
     */
    public function render(\Zend\Form\ElementInterface $oElement){
        $this->getCustomPlaceHolder()->__invoke('bottomScripts')->append('<script language="JavaScript">CKEDITOR.replace('.\Zend\Json\Json::encode($oElement->getName()).');</script>');
        return parent::render($oElement);
        //.$this->getEscapeJsHelper()->__invoke('CKEDITOR.replace('.\Zend\Json\Json::encode($oElement->getName()).')');
    }
    /**
     * @see \Zend\Form\View\Helper\FormTextarea::__invoke()
     * @param ElementInterface|null $element
     * @return string|\CKEditorBundle\Form\View\HelperFormCKEditorHelper
     */
    public function __invoke(\Zend\Form\ElementInterface $oElement){
        return $oElement ? $this->render($oElement): $this;
    }
    /**
     * Retrieve the escapeJs helper
     * @return \Zend\View\Helper\EscapeJs
     */
    protected function getEscapeJsHelper(){
        if($this->escapeJsHelper)
            return $this->escapeJsHelper;
        if(method_exists($this->view, 'plugin'))
            $this->escapeJsHelper = $this->view->plugin('escapejs');
        if(!$this->escapeJsHelper instanceof \Zend\View\Helper\EscapeJs)
            $this->escapeJsHelper = new \Zend\View\Helper\EscapeJs();

        return $this->escapeJsHelper;
    }

    protected function getCustomPlaceHolder(){


        if(method_exists($this->view, 'plugin')){
            $this->placeHolder = $this->view->plugin('placeholder');
            return $this->placeHolder;
        }

        if(!$this->placeHolder instanceof \Zend\View\Helper\Placeholder)
            $this->placeHolder = new \Zend\View\Helper\Placeholder();


     return $this->placeholder;
    }
}

?>