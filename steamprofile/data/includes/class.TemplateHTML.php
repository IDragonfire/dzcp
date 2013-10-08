<?php
require_once "class.Template.php";

/**
 * Easy Template optional HTML class that replaces tags like <!--TagName --> and actual HTML tags with any value
 *   These methods were placed into an extended class for performance because they require the regex engine
 *   The separation of code also makes the class more maintainable and easier to use for smaller projects.
 *
 * Mar 13, 2007 - Initial Release
 * Mar 22, 2007 - Special thanks to Todd Morrow for regex code
 * Mar 27, 2007 - Separated HTML Support in Extended Class
 *
 * @version 2.5
 * @author Jeff L. Williams
 */
class TemplateHTML extends Template {

    /**
     * Returns a complete HTML form element from the template
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @return string HTML form element with opening and closing tags included
     */
    public function getHTMLElement($elementTag, $elementName) {
        return $this->getHTMLElementFromString($elementTag, $elementName, $this->page);
    }

    /**
     * Returns a complete HTML form element from an HTML file
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $url The URL location of the page to extract from
     * @return string HTML form element with opening and closing tags included
     */
    public function getHTMLElementFromFile($elementTag, $elementName, $url) {
        if (!file_exists($fileName)) {
            die('File does not exists: ' . $fileName);
        } else {
            $content = join("", file($fileName));
            return $this->getHTMLElementFromString($elementTag, $elementName, $content);
        }
    }

    /**
     * Returns a complete HTML form element from a string containing HTML
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $html A string containing HTML
     * @return string HTML form element with opening and closing tags included
     */
    public function getHTMLElementFromString($elementTag, $elementName, $html) {
        // Grab the opening HTML tag using regular expressions
        $pattern = '/<' . $elementTag . '[^>]*?name\\s*=\\s*("|\')?' .
            $elementName . '("|\')?[^>]*?>(.*?)<\/' . $elementTag . '>/si';
        $result = '';

        // If we find a match... (store the opening HTML tag)
        if (preg_match ($pattern, $html, $result)) {

            // Success
            return $result;

        } else { // We couldn't find the table

            // No table found by that name
            return false;
        }
    }

    /**
     * Returns a complete HTML form element from a live URL
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $url The URL location of the page to extract from
     * @return string HTML form element with opening and closing tags included
     */
    public function getHTMLElementFromURL($elementTag, $elementName, $url) {
        $content = file_get_contents($url);

        if (!$content) {
            die('Cannot find site: ' . $url);
        } else {
            return $this->getHTMLElementFromString($elementTag, $elementName, $content);
        }
    }

    /**
     * Returns an HTML form element tag from the template
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @return string HTML form element tag
     */
    public function getHTMLElementTag($elementTag, $elementName) {
        return $this->getHTMLElementTagFromString($elementTag, $elementName, $this->getPage());
    }

    /**
     * Returns an HTML form element tag from an HTML file
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $url The URL location of the page to extract from
     * @return string HTML form element tag
     */
    public function getHTMLElementTagFromFile($elementTag, $elementName, $url) {
        if (!file_exists($fileName)) {
            die('File does not exists: ' . $fileName);
        } else {
            $content = join("", file($fileName));
            return $this->getHTMLElementTagFromString($elementTag, $elementName, $content);
        }
    }

    /**
     * Returns an HTML form element tag from a string containing HTML
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $html A string containing HTML
     * @return string HTML form element tag
     */
    public function getHTMLElementTagFromString($elementTag, $elementName, $html) {
        // Grab the opening HTML tag using regular expressions
        $pattern = '/<' . $elementTag . '[^>]*?name\\s*=\\s*("|\')?' .
            $elementName . '("|\')?[^>]*?>/si';
        $result = '';

        // If we find a match... (store the opening HTML tag)
        if (preg_match ($pattern, $html, $result)) {

            // Success
            return $result;

        } else { // We couldn't find the tag

            // No tag found by that name
            return false;
        }
    }

    /**
     * Returns an HTML form element tag from a live URL
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $url The URL location of the page to extract from
     * @return string HTML form element tag
     */
    public function getHTMLElementTagFromURL($elementTag, $elementName, $url) {
        $content = file_get_contents($url);

        if (!$content) {
            die('Cannot find site: ' . $url);
        } else {
            return $this->getHTMLElementTagFromString($elementTag, $elementName, $content);
        }
    }

    /**
     * Checks to see if an HTML tag element exists
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @return TRUE if exists, FALSE if not
     */
    public function hasHTMLTag($elementTag, $elementName) {
        // Grab the opening HTML tag using regular expressions
        $pattern = '/<' . $elementTag . '[^>]*?name\\s*=\\s*("|\')?' .
            $elementName . '("|\')?[^>]*?>/si';

        // If we find a match... (store the opening HTML tag)
        if (preg_match ($pattern, $this->$page)) {

            // Success
            return true;

        } else { // We couldn't find the tag

            // No tag found by that name
            return false;
        }
    }

    /**
     * Removes all comments from the document
     *
     */
    public function removeAllComments()
    {
        // Remove all HTML comments using a regular expression
        $this->removeAllTags();
    }

    /**
     * Removes an HTML form element
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     */
    public function removeHTMLElement($elementTag, $elementName) {
        $this->replaceHTMLElement($elementTag, $elementName, '');
    }

    /**
     * Removes data between an HTML from element opening and closing tag
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     */
    public function removeHTMLElementData($elementTag, $elementName) {
        $this->replaceHTMLElementData($elementTag, $elementName, '');
    }

    /**
     * Removes an HTML form element tag
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     */
    public function removeHTMLElementTag($elementTag, $elementName) {
        $this->replaceHTMLElementTag($elementTag, $elementName, '');
    }

    /**
     * Replaces a template HTML form element with a string
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $newText The replacement text
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     */
    public function replaceHTMLElement($elementTag, $elementName, $newText, $fixHTML = false, $stripSlashes = false) {
        if ($stripSlashes) {
            $newText = stripslashes($newText);
        }
        if ($fixHTML) {
            $newText = htmlentities($newText);
        }

        // Replace the table in the template and keep the opening HTML tag
        $replacepattern = '/<' . $elementTag . '[^>]*?name\\s*=\\s*("|\')?' .
            $elementName . '("|\')?[^>]*?>(.*?)<\/' . $elementTag . '>/si';
        $this->setPage(preg_replace($replacepattern, $newText, $this->getPage()));
    }

    /**
     * Replaces template data between an opening and closing HTML form tag with a string
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $newText The replacement text
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     * @return boolean TRUE if success, FALSE if failure
     */
    public function replaceHTMLElementData($elementTag, $elementName, $newText, $fixHTML = false, $stripSlashes = false) {
        // Get the opening HTML tag
        $openingtag = $this->getHTMLElementTag($elementTag, $elementName);

        // If we find a match... (store the opening HTML tag)
        if ($openingtag) {

            $this->replaceHTMLElement($elementTag, $elementName,
                $openingtag[0] . $newText . "</" . $elementTag . ">", $fixHTML, $stripSlashes);

            // Success
            return true;

        } else { // We couldn't find the table

            // No table found by that name
            return false;
        }
    }

    /**
     * Replaces template data between an opening and closing HTML form tag with data from a file
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $fileName The name of the file containing the data
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     */
    public function replaceHTMLElementDataFromFile($elementTag, $elementName, $fileName, $fixHTML = false, $stripSlashes = false) {
        if (!file_exists($fileName)) {
            die('File does not exists: ' . $fileName);
        } else {
            $content = join("", file($fileName));
            $this->replaceHTMLElementData($elementTag, $elementName, $content, $fixHTML, $stripSlashes);
        }
    }

    /**
     * Replaces template data between an opening and closing HTML form tag with data from a live URL
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $url The URL location of the page to extract from
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     */
    public function replaceHTMLElementDataFromWeb($elementTag, $elementName, $url, $fixHTML = false, $stripSlashes = false) {
        $content = file_get_contents($url);

        if (!$content) {
            die('Cannot find site: ' . $url);
        } else {
            $this->replaceHTMLElementData($elementTag, $elementName, $content, $fixHTML, $stripSlashes);
        }
    }

    /**
     * Replaces a template HTML form element with data from a file
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $fileName The name of the file containing the data
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     */
    public function replaceHTMLElementFromFile($elementTag, $elementName, $fileName, $fixHTML = false, $stripSlashes = false) {
        if (!file_exists($fileName)) {
            die('File does not exists: ' . $fileName);
        } else {
            $content = join("", file($fileName));
            $this->replaceHTMLElement($elementTag, $elementName, $content, $fixHTML, $stripSlashes);
        }
    }

    /**
     * Replaces a template HTML form element with data from a live URL
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $url The URL location of the page to extract from
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     */
    public function replaceHTMLElementFromWeb($elementTag, $elementName, $url, $fixHTML = false, $stripSlashes = false) {
        $content = file_get_contents($url);

        if (!$content) {
            die('Cannot find site: ' . $url);
        } else {
            $this->replaceHTMLElement($elementTag, $elementName, $content, $fixHTML, $stripSlashes);
        }
    }

    /**
     * Replaces a template HTML form element opening tag with a string
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $newText The replacement text
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     */
    public function replaceHTMLElementTag($elementTag, $elementName, $newText, $fixHTML = false, $stripSlashes = false) {
        if ($stripSlashes) {
            $newText = stripslashes($newText);
        }
        if ($fixHTML) {
            $newText = htmlentities($newText);
        }

        // Replace the table in the template and keep the opening HTML tag
        $replacepattern = '/<' . $elementTag . '[^>]*?name\\s*=\\s*("|\')?' .
            $elementName . '("|\')?[^>]*?>/si';

        $replace = $newText;
        $this->page = preg_replace($replacepattern, $replace, $this->getPage());
    }

    /**
     * Replaces a template HTML form element opening tag with data from a file
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $fileName The name of the file containing the data
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     */
    public function replaceHTMLElementTagFromFile($elementTag, $elementName, $fileName, $fixHTML = false, $stripSlashes = false) {
        if (!file_exists($fileName)) {
            die('File does not exists: ' . $fileName);
        } else {
            $content = join("", file($fileName));
            $this->replaceHTMLElementTag($elementTag, $elementName, $content, $fixHTML, $stripSlashes);
        }
    }

    /**
     * Replaces a template HTML form element opening tag with data from a live URL
     *
     * @param string  $elementTag The type of element (i.e. table, input or div)
     * @param string  $elementName The name of the element (name= attribute)
     * @param string  $url The URL location of the page to extract from
     * @param boolean $fixHTML Determines if the content should be formatted
     * @param boolean $stripSlashes Encodes the contect
     */
    public function replaceHTMLElementTagFromWeb($elementTag, $elementName, $url, $fixHTML = false, $stripSlashes = false) {
        $content = file_get_contents($url);

        if (!$content) {
            die('Cannot find site: ' . $url);
        } else {
            $this->replaceHTMLElementTag($elementTag, $elementName, $content, $fixHTML, $stripSlashes);
        }
    }

    /**
     * Replaces the list data in an existing HTML SELECT within the template
     *
     * @param string  $htmlSelectName The name of the SELECT element
     * @param array   $array An array containing numbered or associated arrays (rows)
     * @param string/integer $displayColumn The column in the arrays that contains the displayed text
     * @param string  $valueColumn The column in the arrays that contains the form return values
     * @param string  $selectedValue The default selected item in the list
     * @param boolean $caseSensitive TRUE if the selected item is chosen using case sensitivity
     * @param integer $indent The number of tabs to indent
     * @return boolean TRUE if success, FALSE if failure
     */
    public function replaceHTMLSelectData($htmlSelectName, $array, $displayColumn, $valueColumn = '',
        $selectedValue = '', $caseSensitive = false, $indent = 0) {

        return $this->replaceHTMLElementData("select", $htmlSelectName,
            self::createHTMLSelectData($array, $displayColumn, $valueColumn,
                $selectedValue, $caseSensitive, $indent));
    }

    /**
     * Replaces the table data in an existing HTML table within the template
     *
     * @param string  $htmlTableName The name of the table (i.e. name=)
     * @param array   $array An array containing numbered or associated arrays (rows)
     * @param boolean $showAssocArrayColumnTitles TRUE if you would like to display heading titles.
     *                NOTE: This only works with associated arrays that contain key values
     * @param integer $indent The number of tabs to indent
     * @param string  $optionalRowHeadingTags Tag attributes for row headings (i.e. style or class)
     * @param string  $optionalCellHeadingTags Tag attributes for cells (i.e. style or class)
     * @param string  $optionalRowTags Tag attributes for rows (i.e. style or class)
     * @param string  $optionalCellTags Tag attributes for cells (i.e. style or class)
     * @param string  $optionalRowTags2 Tag attributes for alternating rows (i.e. style or class)
     * @param string  $optionalCellTags2 Tag attributes for alternating row cells (i.e. style or class)
     * @return boolean TRUE if success, FALSE if failure
     */
    public function replaceHTMLTableData(
        $htmlTableName, $array, $showAssocArrayColumnTitles = true, $indent = 0,
        $optionalRowHeadingTags= '', $optionalCellHeadingTags= '',
        $optionalRowTags= '', $optionalCellTags= '',
        $optionalRowTags2= '', $optionalCellTags2= '') {

        return $this->replaceHTMLElementData("table", $htmlTableName,
            self::createHTMLTableData($array, $showAssocArrayColumnTitles, $indent,
                                      $optionalRowHeadingTags, $optionalCellHeadingTags,
                                      $optionalRowTags, $optionalCellTags,
                                      $optionalRowTags2, $optionalCellTags2));
    }
}
?>