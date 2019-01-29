<?php
ini_set('display_errors', 1);

class Html extends DOMDocument {

    protected $html;
    protected $head;
    protected $body;

    protected $id = 0;

    function __construct($lang  = 'en') {

        $this->html = new DOMDocument("1.0");

        $html = $this->html->createElement('html');

        $domAttribute = $this->html->createAttribute('lang');
        $domAttribute->value = $lang;
        
        $html->appendChild($domAttribute);

        $this->head = $this->html->createElement('head');
        $this->body = $this->html->createElement('body');

        $html->appendChild($this->head);
        $html->appendChild($this->body);

        $this->html->appendChild($html);
        
    }

    /**
     * 
     * Add Style to head
     * 
     * @param string $style Css format
     * 
     */ 
    public function addStyle($style) {

        $getCheck = explode('?', $style);

        if (is_file($getCheck[0])) {
            $styleDom = $this->html->createElement('link');
            $domAttribute = $this->html->createAttribute('rel');
            $domAttribute->value = 'stylesheet';
            $styleDom->appendChild($domAttribute);
            $domAttribute = $this->html->createAttribute('href');
            $domAttribute->value = $style;
            $styleDom->appendChild($domAttribute);

        } else
            $styleDom = $this->html->createElement('style', $style);


        $domAttribute = $this->html->createAttribute('type');
        $domAttribute->value = 'text/css';

        $styleDom->appendChild($domAttribute);

        $this->head->appendChild($styleDom);
    
    }

       /**
     * 
     * Add Javascript 
     * 
     * @param string $js Script js or js file
     * @param boolean $header (default TRUE)
     * 
     */ 
    public function addJS($js, $header = TRUE) {

        $getCheck = explode('?', $js);

        if (is_file($getCheck[0])) {
            $jsDom = $this->html->createElement('script');
            $domAttribute = $this->html->createAttribute('src');
            $domAttribute->value = $js;
            $jsDom->appendChild($domAttribute);

        } else
            $jsDom = $this->html->createElement('script', $js);


        $domAttribute = $this->html->createAttribute('type');
        $domAttribute->value = 'text/javascript';

        $jsDom->appendChild($domAttribute);

        if ($header)
            $this->head->appendChild($jsDom);
        else
            $this->body->appendChild($jsDom);
    
    }

    public function addMeta($name, $content) {

        $metaDom = $this->html->createElement('meta');

        $domAttribute = $this->html->createAttribute('name');
        $domAttribute->value = $name;
        $metaDom->appendChild($domAttribute);

        $domAttribute = $this->html->createAttribute('content');
        $domAttribute->value = $content;
        $metaDom->appendChild($domAttribute);

        $this->head->appendChild($metaDom);
    
    }

    public function addLink($href, $text, $target = 1) {

        $target = ($target)? '_blank' : '_self';

        $linkDom = $this->html->createElement('a', $text);

        $domAttribute = $this->html->createAttribute('href');
        $domAttribute->value = $href;
        $linkDom->appendChild($domAttribute);

        $domAttribute = $this->html->createAttribute('target');
        $domAttribute->value = $target;
        $linkDom->appendChild($domAttribute);

        $this->head->appendChild($linkDom);
    
    }

    public function addImg($src, $alt, $width = 0, $height = 0) {

        $imgDom = $this->html->createElement('img');

        $domAttribute = $this->html->createAttribute('src');
        $domAttribute->value = $src;
        $imgDom->appendChild($domAttribute);

        $domAttribute = $this->html->createAttribute('alt');
        $domAttribute->value = $alt;
        $imgDom->appendChild($domAttribute);

        if ($width) {
            $domAttribute = $this->html->createAttribute('width');
            $domAttribute->value = $width;
            $imgDom->appendChild($domAttribute);
        }
        if ($height) {
            $domAttribute = $this->html->createAttribute('height');
            $domAttribute->value = $height;
            $imgDom->appendChild($domAttribute);
        }

        $this->head->appendChild($imgDom);
    
    }

    public function addBr($howMany = 1) {
        $brDom = $this->html->createElement('br');
        $this->body->appendChild($brDom);
    }

    public function addDom($dom) {
        var_dump($dom->documentElement);
        /*$targetDom = new DOMDocument();
        $this->html->loadXML('<body>');

        $sourceDom = new DOMDocument();
        $sourceDom->loadXml('<items><child/>TEXT</items>');
*/
        $this->html->documentElement->appendChild($this->html->importNode($dom->documentElement, true));

        //var_dump($this->html);
    }

    protected function getId() {
        return $this->id;
    }
    protected function updateId() {
        $this->id++;
    }
    protected function getAndUpdateId() {
        $id = $this->id;
        $this->id++;
        return $id;
    }

    /**
     * 
     * @return DOMDocument Print html saved in $dom
     * 
     */
    public function RenderHtml() {
        return $this->html->saveHTML();
    }
}

/**
 * Class for create formulary
 * 
 * @package Form
 * @subpackage addInput
 * @subpackage RenderHtml
 */

class Form extends Html {

    /**
     * Formulary save
     */
    private $form;

    /**
     * @param string $action Action url for form (default / )
     * @param string $method Method for form (default post)
     * @param string $style Style for html
     */
    function __construct($action  = '/', $method = 'POST', $style = '') {

        $this->form = new DOMDocument("body"); // Not sure
        
        $formDom = $this->form->createElement('form');
        
        $domAttributeAction = $this->form->createAttribute('action');
        $domAttributeAction->value = $action;
        $domAttributeMethod = $this->form->createAttribute('method');
        $domAttributeMethod->value = $method;

        $formDom->appendChild($domAttributeAction);
        $formDom->appendChild($domAttributeMethod);

        $this->form->appendChild($formDom);
    }

    /**
     * Add Input
     * 
     * Add multi label/name/value/id with ; for type checkbox or radio
     * 
     * @param string $label Label for input (if empty no label)
     * @param string $name Name input (random unique id if empty (need change by ID))
     * @param string $value Value for input
     * @param string $id (deprecated) Id for input (and label) (take name and add random unique id if empty (need change by ID))
     * @param string $type Type for input (default text)
     * 
     * @return boolean If true input is added to form
     */
    public function addInput($label, $name, $value, $id, $type = 'text') {

        $label = explode(';', $label);
        $name = explode(';', $name);
        $value = explode(';', $value);
        //$id = explode(';', $id);

        for ($i = 0; $i < count($value); $i++) {
            
			$p = $this->makePAndLabel($label[$i], $this->getId());

	        $input = $this->form->createElement('input');
	        $domAttributeType = $this->form->createAttribute('type');
	        $domAttributeType->value = $type;
	        $domAttributeName = $this->form->createAttribute('name');
	        $domAttributeName->value = (count($name) == 1)? $name[0] : $name[$i];
	        $domAttributeValue = $this->form->createAttribute('value');
	        $domAttributeValue->value = $value[$i];
	        $domAttributeId = $this->form->createAttribute('id');
	        $domAttributeId->value = $this->getAndUpdateId();
	        
	        $input->appendChild($domAttributeType);
	        $input->appendChild($domAttributeName);
	        $input->appendChild($domAttributeValue);
            $input->appendChild($domAttributeId);
            
            $p->appendChild($input);

            $this->form->appendChild($p);
    
        }

        return 1;
        
    }

    /**
     * Add Button
     * 
     * @param string $value Value for button
     * @param string $type Type for button (default submit)
     */
    public function addButton($value = 'Submit', $type = 'submit') { // Add name and id
        $button = $this->form->createElement('button', $value);
        $domAttributeType = $this->form->createAttribute('type');
        $domAttributeType->value = $type;
        $button->appendChild($domAttributeType);
        $this->form->appendChild($button);

        return 1;
    }

    /**
     * Add textarea
     * 
     * @param string $label Label for textarea
     * @param string $name Name for textarea
     * @param string $value Value in textarea
     * @param string $id Id for label and textarea
     */
    public function addTextarea($label, $name, $value, $id) {

        $p = $this->makePAndLabel($label, $this->getId());

        $textareaDom = $this->form->createElement('textarea', $value);
        $domAttributeName = $this->form->createAttribute('name');
        $domAttributeName->value = $name;
        $domAttributeId = $this->form->createAttribute('id');
        $domAttributeId->value = $this->getAndUpdateId();

        $textareaDom->appendChild($domAttributeName);
        $textareaDom->appendChild($domAttributeId);

        $p->appendChild($textareaDom);

        $this->form->appendChild($p);

        return 1;
    }

    /**
     * Add select
     * 
     * @param string $label Label for select
     * @param string $name Name for select
     * @param string $value Value for select (space with ; )
     * @param string $id Id for label and select
     */
    public function addSelect($label, $name, $value, $text, $id) {

        $p = $this->makePAndLabel($label, $this->getId());

        $value = explode(';', $value);
        $text = explode(';', $text);

        $selectDom = $this->form->createElement('select');
        $domAttributeName = $this->form->createAttribute('name');
        $domAttributeName->value = $name;
        $domAttributeId = $this->form->createAttribute('id');
        $domAttributeId->value = $this->getAndUpdateId();

        $selectDom->appendChild($domAttributeName);
        $selectDom->appendChild($domAttributeId);

        for ($i = 0; $i < count($text); $i++) {
            $optionDom = $this->form->createElement('option', $text[$i]);

            $domAttributeValue = $this->form->createAttribute('value');
            $domAttributeValue->value = $value[$i];

            $optionDom->appendChild($domAttributeValue);
            $selectDom->appendChild($optionDom);
        }

        $p->appendChild($selectDom);

        $this->form->appendChild($p);
    }



    /**
     * Add p html element and label
     * @param string $label Text for label
     * @param string $id Id for label
     * 
     */
    private function makePAndLabel($label, $id) {

        $p = $this->form->createElement('p'); // p or div ?

        if ($id == '')
            $id = $this->getId();

        if ($label != '') {
            $labelDom = $this->form->createElement('label', $label);
            $domAttributeFor = $this->form->createAttribute('for');
            $domAttributeFor->value = $id;
            $labelDom->appendChild($domAttributeFor);
            $p->appendChild($labelDom);
        }

        

        return $p;
    }

    public function getForm() {
        return $this->form;
    }

    public function renderHtml() {
        return $this->form->saveHTML();
    }
}



// Create form
$form = new Form('/be_code/poo/', 'GET');
// Add Input
$form->addInput('Nom', 'firstname', '', '', 'text');
$form->addInput('Prénom', 'secondname', '', '', 'test');
$form->addTextarea('Explications', 'explain', '', '');
// Add Radio
$form->addInput('Voiture;Moto;Bus', 'r1', 'r1;r2;r3', '', 'radio');
// Or this
$form->addInput('Test1', 'r2', 'r1', '', 'radio');
$form->addInput('Test2', 'r2', 'r2', '', 'radio');
$form->addInput('Test3', 'r2', 'r3', '', 'radio');
// Add Checkbox (or ligne by line too)
$form->addInput('Insciption à la news letter;Validation des conditions géneral', 'c1;c2', 'c1;c2', '', 'checkbox');
// Add Select
$form->addSelect('La taille de je ne sais pas quoi', 'sizeChoice', 't1;t2;t3', 'Taille 1;Taille 2; Taille 3', '');
// Add Button
$form->addButton('Envois');
// Render html (form)
//print $form->renderHtml();

$html = new Html('fr');

// Two possible for style
$html->addStyle('style.php?px=0'); // (and why not with php-css ;) sass it's not php power)
$html->addStyle('label{padding-right:12px;}'); 
$html->addStyle('a{padding-right:12px;}'); 
// Two possible for style
$html->addJS('js.php', 0); // (and why not with php-js lol)
$html->addJS('console.log("test")');

// Add meta
$html->addMeta('description', 'PHP Sample class');
$html->addMeta('author', 'TheDoudou');
$html->addMeta('viewport', 'width=device-width, initial-scale=1.0');

$html->addLink('index.php', 'Retour', 0);

$html->addLink('#', 'Target Self', 0);
$html->addLink('#', 'Target Blank');


$html->addImg('image.jpg',
 'Photo Becode', 300, 200);
//$html->addDom($form->getForm());




//print $html->RenderHtml();
//print $form->renderHtml();



/*
<p>Php Sample with Form class :<br>
// Create form<br>
$test = new Form('/be_code/poo/', 'GET');<br>
// Add Style<br>
$test->addStyle('label{padding-right:12px;}');<br>
// Add Input<br>
$test->addInput('Nom', 'firstname', '', '', 'text');<br>
$test->addInput('Prénom', 'secondname', '', '', 'test');<br>
$test->addTextarea('Explications', 'explain', '', '');<br>
// Add radio<br>
$test->addInput('Voiture;Moto;Bus', 'r1', 'r1;r2;r3', '', 'radio');<br>
// Or this<br>
$test->addInput('Test1', 'r2', 'r1', '', 'radio');<br>
$test->addInput('Test2', 'r2', 'r2', '', 'radio');<br>
$test->addInput('Test3', 'r2', 'r3', '', 'radio');<br>
// Add checkbox (or ligne by line too)<br>
$test->addInput('Insciption à la news letter;Validation des conditions géneral', 'c1;c2', 'c1;c2', '', 'checkbox');<br>
//$test->addSelect('');<br>
$form->addSelect('La taille de je ne sais pas quoi', 'sizeChoice', 't1;t2;t3', 'Taille 1;Taille 2; Taille 3', '');<br>
// Add button<br>
$test->addButton('Envois');<br>
// Render html (form)<br>
//$test->RenderHtml();<br>
<br>
// Create html (with head/body)<br>
$html = new Html('fr');<br>
<br>
// Two possible for style<br>
$html->addStyle('style.php?px=0'); // (and why not with php-css ;) sass it's not php power)<br>
$html->addStyle('label{padding-right:12px;}');<br>
<br>
// Try something ...<br>
//$html->addDom($test->getForm());<br>
<br>
print $html->RenderHtml();<br>
print $test->renderHtml();<br>

*/








// Simple version

class HtmlSimple {

    private $html = [];

    function __construct($lang, $desc, $title) {
        array_push($this->html, '<html lang="'.$lang.'">');
        array_push($this->html, '<head>');
        array_push($this->html, '<title>'.$title.'</title>');
        array_push($this->html, '<meta description="'.$desc.'">');
    }

    function __destruct() {
        echo ("\n</body>\n</html>");
        
    }

    public function render() {
        return implode("\n", $this->html);
    }

    public function closeHead() {
        array_push($this->html, '</head>');
        array_push($this->html, '<body>');
    }

    public function addStyle($style) {

        $getCheck = explode('?', $style);

        if (is_file($getCheck[0]))
            array_push($this->html, '<link type="text/css" rel="stylesheet" href="'.$style.'">');
        else
            array_push($this->html, '<style type="text/css">'.$style.'</style>');
    }

    /**
     * 
     * Add Javascript 
     * 
     * @param string $js Script js or js file
     * 
     */ 
    public function addJS($js) {

        $getCheck = explode('?', $js);

        if (is_file($getCheck[0]))
            array_push($this->html, '<script src="'.$script.'" type="text/javascript"></script>');
        else
            array_push($this->html, '<script type="text/javascript">'.$script.'</script>');
    
    }

    public function addMeta($name, $content) {

        array_push($this->html, '<meta name="'.$name.'" content="'.$content.'">');
    
    }

    public function addLink($href, $text, $target = 1) {

        $target = ($target)? '_blank' : '_self';

        array_push($this->html, '<a href="'.$href.'" target="'.$target.'">'.$text.'</a>');
    
    }

    public function addImg($src, $alt, $width = 0, $height = 0) {

        array_push($this->html, '<img src="'.$src.'" alt="'.$alt.'" width="'.$width.'" height="'.$height.'">');
    
    }

    public function addHtml($html) {

        array_push($this->html, $html);
    
    }

    public function addN($howMany = 1) {
        array_push($this->html, '');
    }

    public function addBr($howMany = 1) {
        array_push($this->html, '<br>');
    }
}

class FormSimple {
/**
     * Formulary save
     */
    private $form = [];
    private $id = 0;

    /**
     * @param string $action Action url for form (default / )
     * @param string $method Method for form (default post)
     * @param string $style Style for html
     */
    function __construct($action  = '/', $method = 'POST', $style = '') {
        
        array_push($this->form, '<form action="'.$action.' method="'.$method.'">');
        
    }

    /**
     * Add Input
     * 
     * Add multi label/name/value/id with ; for type checkbox or radio
     * 
     * @param string $label Label for input (if empty no label)
     * @param string $name Name input (random unique id if empty (need change by ID))
     * @param string $value Value for input
     * @param string $id (deprecated) Id for input (and label) (take name and add random unique id if empty (need change by ID))
     * @param string $type Type for input (default text)
     * 
     * @return boolean If true input is added to form
     */
    public function addInput($label, $name, $value, $id, $type = 'text') {

        $label = explode(';', $label);
        $name = explode(';', $name);
        $value = explode(';', $value);
        //$id = explode(';', $id);

        for ($i = 0; $i < count($label); $i++) {
            
			$this->openPAndLabel($label[$i], $this->getId());

	        array_push($this->form, '<input type="'.$type.'" name="'.((count($name) == 1)? $name[0] : $name[$i]).'" id="'.$this->getAndUpdateId().'" value="'.$value[$i].'">');
    
            $this->closeP();
        }

        return 1;
        
    }

    /**
     * Add Button
     * 
     * @param string $value Value for button
     * @param string $type Type for button (default submit)
     */
    public function addButton($value = 'Submit', $type = 'submit') { // Add name and id

        array_push($this->form, '<button type="'.$type.'">'.$value.'</button>');

        return 1;
    }

    /**
     * Add textarea
     * 
     * @param string $label Label for textarea
     * @param string $name Name for textarea
     * @param string $value Value in textarea
     * @param string $id Id for label and textarea
     */
    public function addTextarea($label, $name, $value, $id) {

        $this->openPAndLabel($label, $this->getId());

        array_push($this->form, '<textarea name="'.$name.'" id="'.$this->getAndUpdateId().'">'.$value.'</textarea>');

        $this->closeP();

        return 1;
    }

    /**
     * Add select
     * 
     * @param string $label Label for select
     * @param string $name Name for select
     * @param string $value Value for select (space with ; )
     * @param string $id Id for label and select
     */
    public function addSelect($label, $name, $value, $text, $id) {

        $this->openPAndLabel($label, $this->getId());

        $value = explode(';', $value);
        $text = explode(';', $text);

        array_push($this->form, '<select name="'.$name.'" id="'.$this->getAndUpdateId().'">');

        for ($i = 0; $i < count($text); $i++) {

            array_push($this->form, '<option value="'.$value[$i].'">'.$text[$i].'</option>');

        }

        array_push($this->form, '</select>');

        $this->closeP();

        return 1;
    }



    /**
     * Add p html element and label
     * @param string $label Text for label
     * @param string $id Id for label
     * 
     */
    private function openPAndLabel($label, $id) {

        array_push($this->form, '<p>'); // p or div ?

        if ($id == '')
            $id = $this->getId();

        if ($label != '') {
            array_push($this->form, '<label for="'.$id.'">'.$label.'</label>');
        }

    }

    public function closeForm() {
        array_push($this->form, '</form>');
    }
    private function closeP() {
        array_push($this->form, '</p>');
    }
    private function getId() {
        return $this->id;
    }
    private function updateId() {
        $this->id++;
    }
    private function getAndUpdateId() {
        $id = $this->id;
        $this->id++;
        return $id;
    }

    public function getForm() {
        return $this->form;
    }

    public function render() {
        return implode("\n", $this->form);
    }
}

class Validator {
    
    public function validate() {
        
    }
}

$html = new HtmlSimple('fr', 'test', 'Exo1');
$html->addStyle('style.php?px=0'); // (and why not with php-css ;) sass it's not php power)
$html->addStyle('label{padding-right:12px;}'); 
$html->addStyle('a{padding-right:12px;}'); 
// Two possible for style
$html->addJS('js.php'); // (and why not with php-js lol)
$html->addJS('console.log("test")');
// Add meta
$html->addMeta('description', 'PHP Sample class');
$html->addMeta('author', 'TheDoudou');
$html->addMeta('viewport', 'width=device-width, initial-scale=1.0');

$html->closeHead();

$html->addLink('index.php', 'Retour', 0);
$html->addLink('ex2.php', 'Exercice 2', 0);
$html->addLink('https://github.com/TheDoudou/poo', 'Source', 1);
$html->addN();
$html->addBr();
$html->addBr();
$html->addImg('image.jpg',
 'Photo Becode', 300, 200);
$html->addN();
$html->addBr();
$html->addLink('#', 'Target Self', 0);
$html->addN();
$html->addLink('#', 'Target Blank');
$html->addN();
$html->addBr();
$html->addBr();

// Create form
$form = new FormSimple('/be_code/poo/', 'GET');
// Add Input
$form->addInput('Nom', 'firstname', '', '', 'text');
$form->addInput('Prénom', 'secondname', '', '', 'test');
$form->addTextarea('Explications', 'explain', '', '');
// Add Radio
$form->addInput('Voiture;Moto;Bus', 'r1', 'r1;r2;r3', '', 'radio');
// Or this
$form->addInput('Test1', 'r2', 'r1', '', 'radio');
$form->addInput('Test2', 'r2', 'r2', '', 'radio');
$form->addInput('Test3', 'r2', 'r3', '', 'radio');
// Add Checkbox (or ligne by line too)
$form->addInput('Insciption à la news letter;Validation des conditions géneral', 'c1;c2', 'c1;c2', '', 'checkbox');
// Add Select
$form->addSelect('La taille de je ne sais pas quoi', 'sizeChoice', 't1;t2;t3', 'Taille 1;Taille 2; Taille 3', '');
// Add Button
$form->addButton('Envois');
$form->closeForm();
$html->addHtml($form->render());

// Render html
echo ($html->render());























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
